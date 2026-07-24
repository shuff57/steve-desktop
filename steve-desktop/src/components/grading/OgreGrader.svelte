<script lang="ts">
  /**
   * OGRE batch grading, as a drawer tab beside the page being graded.
   *
   * This is the flow O.G.R.E had and a full-page route loses: the MyOpenMath grading
   * page stays on screen while you pick a rubric, pull the students off it, and read the
   * scores back. "Load students from page" means the page you are looking at, so putting
   * the controls anywhere else makes the target ambiguous.
   *
   * Engine comes from the panel's own ProviderSelector — the same one the Agent, Teach
   * and Skills tabs use — so switching tabs never silently changes which model grades.
   *
   * Nothing is written back to MyOpenMath. Scores and feedback are shown for review only;
   * the write-back path does not exist yet, and adding one silently would be the worst
   * possible surprise in a gradebook.
   */
  import { onMount } from 'svelte';
  import { ogreIsland } from '../../integrations/ogre';
  import { describeLeniency, restoreCategoryWeights, rewriteRubric } from '../../integrations/ogre/leniency';
  import { detectOutliers } from '../../integrations/ogre/outliers';
  import { generateScoringAnchors } from '../../integrations/ogre/batch';
  import { anchorsToText, withCalibration } from '../../integrations/ogre/anchors';
  import {
    autoFillWeights,
    clearWeights,
    equalizeWeights,
    hasWeights,
    validateWeights,
    weightFieldsFor,
  } from '../../integrations/ogre/weights';
  import { evalScript, isConnected } from '../../lib/cdp-actions';
  import type { ExtractedStudent, ExtractionProfile } from '../../integrations/ogre/load-students';
  import type { RubricChecklistItem } from '../../integrations/ogre/grading';
  import type { BatchResult } from '../../integrations/ogre/batch';
  import type { Rubric } from '../../integrations/ogre/grading';
  import type { Skill } from '../../integrations/ogre/types';

  let { pageUrl = '', provider = '', model = '' } = $props();

  let rubrics = $state<Skill[]>([]);
  let rubricId = $state<string | null>(null);
  let leniency = $state(50);
  let chunkSize = $state(20);
  let instructions = $state('');

  // Only these students get graded when set. O.G.R.E's "single student mode" — the escape
  // hatch for re-grading one person after a dispute without touching the rest of the class.
  let onlyStudent = $state('');

  let reviewing = $state(false);
  let reviewMsg = $state<string | null>(null);

  // Stop between chunks, not mid-call: a chunk IS one model call, so there is nothing to
  // interrupt inside one. O.G.R.E's pause/resume guarded its per-student write-back loop;
  // with nothing written back, the only thing worth interrupting is spending more calls.
  let stopRequested = $state(false);
  let stopped = $state(false);

  // Anchors: generated examples, then edited. Held as text because that is what the
  // teacher edits and what ends up in the rubric's calibration block.
  let anchorText = $state('');
  let anchorBusy = $state(false);
  let anchorError = $state<string | null>(null);

  // Weights live here rather than in the stored rubric: they are a property of THIS run,
  // and writing them back would silently re-weight every future use of the rubric.
  let weighted = $state<RubricChecklistItem[] | null>(null);

  let profiles = $state<ExtractionProfile[]>([]);
  let profileId = $state('auto');

  let students = $state<ExtractedStudent[]>([]);
  let includeGraded = $state(false);
  let loadingStudents = $state(false);
  let loadError = $state<string | null>(null);

  let importing = $state(false);
  let importMsg = $state<string | null>(null);

  let grading = $state(false);
  let progress = $state<{ chunk: number; of: number } | null>(null);
  let results = $state<BatchResult[]>([]);
  let gradeError = $state<string | null>(null);
  let expanded = $state<number | null>(null);

  const selectedRubric = $derived(rubrics.find((r) => r.id === rubricId) ?? null);

  const parsedRubric = $derived.by((): Rubric | null => {
    if (!selectedRubric?.content) return null;
    try {
      return JSON.parse(selectedRubric.content) as Rubric;
    } catch {
      return null;
    }
  });

  /**
   * Leniency rewrites the criteria text, so it has to be applied to the rubric actually
   * handed to the grader — not appended as an instruction.
   */
  /** Categories as currently weighted, falling back to the rubric's own. */
  const weightItems = $derived(weighted ?? parsedRubric?.checklistItems ?? []);
  const weightCheck = $derived(hasWeights(weightItems) ? validateWeights(weightItems) : null);

  const effectiveRubric = $derived.by((): Rubric | null => {
    if (!parsedRubric) return null;

    // Order matters: calibration anchors must be folded in as the SCORING CALIBRATION
    // block with the plain instructions after, which is what extractCustomInstructions
    // splits back apart at the far end.
    let base = withCalibration(parsedRubric, anchorText, instructions);

    // Weights are a run-level override of the stored categories.
    if (weighted) base = { ...base, checklistItems: weighted };
    base = { ...base, ...weightFieldsFor(base) };

    if (leniency === 50) return base;
    const items = (base.checklistItems ?? []).map((c) => ({
      ...c,
      items: (c.items ?? []).map((line) => restoreCategoryWeights(rewriteRubric(line, leniency), line)),
    }));
    return { ...base, checklistItems: items };
  });

  /**
   * The calibration references the model is told to score against. Shown because they are
   * derived from maxScore rather than written by anyone — seeing "Excellent = 19/20" is
   * how you catch a rubric whose maxScore is wrong before a whole class is graded to it.
   */
  const anchors = $derived(effectiveRubric ? generateScoringAnchors(effectiveRubric) : null);

  /** Roster the run will actually cover — narrowed by single-student mode. */
  const targetStudents = $derived.by(() => {
    const q = onlyStudent.trim().toLowerCase();
    if (!q) return students;
    return students.filter((s) => (s.name ?? '').toLowerCase().includes(q));
  });

  const outliers = $derived(results.length ? detectOutliers(results) : null);
  /**
   * Results index into the roster that was graded, not the roster that was loaded — those
   * differ under single-student mode, and using the wrong one puts a name on the wrong
   * grade. Captured at grade time so later edits to the filter cannot retarget it.
   */
  let gradedRoster = $state<ExtractedStudent[]>([]);
  const nameFor = (i: number) => gradedRoster[i]?.name ?? `Student ${i}`;

  /** One evaluator for both reads — throws rather than returning a silent undefined. */
  async function readPage(expression: string): Promise<unknown> {
    if (!isConnected()) throw new Error('Not connected to the browser. Open the page in a tab first.');
    const res = await evalScript(expression);
    if (!res.success) throw new Error(res.error ?? 'Page evaluation failed');
    return res.data;
  }

  async function loadRubrics() {
    try {
      rubrics = await ogreIsland.methods.listRubrics();
      if (!rubricId && rubrics.length) rubricId = rubrics[0]!.id;
    } catch (e) {
      loadError = e instanceof Error ? e.message : String(e);
    }
  }

  /** Pull the rubric off a question page — the other half of the same browsing session. */
  async function importRubric() {
    importing = true;
    importMsg = null;
    try {
      const r = await ogreIsland.methods.importRubricFromPage(readPage);
      const id = await ogreIsland.methods.saveImportedRubric(r);
      await loadRubrics();
      rubricId = id;
      importMsg = `Imported “${r.name}”.`;
    } catch (e) {
      importMsg = e instanceof Error ? e.message : String(e);
    } finally {
      importing = false;
    }
  }

  /** Auto-detect resolves against the current page; an explicit pick always wins. */
  const activeProfile = $derived.by((): ExtractionProfile | null => {
    if (profileId !== 'auto') return profiles.find((p) => p.id === profileId) ?? null;
    return ogreIsland.methods.matchProfile(pageUrl, profiles);
  });

  async function loadProfiles() {
    try {
      profiles = await ogreIsland.methods.listExtractionProfiles();
    } catch {
      profiles = []; // extraction still works on the built-in MyOpenMath selectors
    }
  }

  /** Generate worked examples of each score level for this question. */
  async function generateAnchors() {
    if (!parsedRubric) return;
    anchorBusy = true;
    anchorError = null;
    try {
      const examples = await ogreIsland.methods.generateAnchorExamples(
        // Anchors describe the criteria as they will be graded, so leniency applies —
        // but calibration must not be fed back in while generating it.
        { ...parsedRubric, checklistItems: effectiveRubric?.checklistItems ?? parsedRubric.checklistItems },
        { id: provider, model },
        { leniency },
      );
      anchorText = anchorsToText(examples);
    } catch (e) {
      anchorError = e instanceof Error ? e.message : String(e);
    } finally {
      anchorBusy = false;
    }
  }

  // True once a load has run, so "found no students" can be told apart from "not tried yet".
  let loadAttempted = $state(false);

  async function loadFromPage() {
    loadingStudents = true;
    loadError = null;
    results = [];
    try {
      students = await ogreIsland.methods.loadStudents(readPage, {
        includeGraded,
        selectors: activeProfile?.selectors,
      });
      loadAttempted = true;
    } catch (e) {
      students = [];
      loadError = e instanceof Error ? e.message : String(e);
    } finally {
      loadingStudents = false;
    }
  }

  async function runGrading() {
    if (!effectiveRubric || targetStudents.length === 0) return;
    grading = true;
    gradeError = null;
    reviewMsg = null;
    results = [];
    progress = null;
    stopRequested = false;
    stopped = false;
    const roster = targetStudents;
    gradedRoster = roster;
    try {
      const toGrade = ogreIsland.methods.toGradingStudents(roster);
      const gen = ogreIsland.methods.gradeBatch(toGrade, effectiveRubric, { id: provider, model }, { chunkSize });
      for await (const ev of gen) {
        if (ev.type === 'chunk-start') progress = { chunk: ev.chunkIndex + 1, of: ev.chunkCount };
        // Keep each chunk as it lands. A later chunk that fails — or a stop — then leaves
        // the finished work on screen instead of discarding grades already paid for.
        if (ev.type === 'chunk-done') results = [...results, ...ev.results];
        if (ev.type === 'done') results = ev.results;
        if (stopRequested) {
          stopped = true;
          break; // breaking a for-await calls gen.return(), so no further chunk runs
        }
      }
      // Record the run only once it actually produced grades.
      if (results.length) {
        const scores = results.map((r) => r.score);
        await ogreIsland.methods.addGradingSession({
          provider_id: provider || null,
          model: model || null,
          student_count: results.length,
          mean_score: scores.reduce((a, b) => a + b, 0) / scores.length,
          min_score: Math.min(...scores),
          max_score: Math.max(...scores),
          max_possible_score: Number(effectiveRubric.maxScore ?? 10),
          page_url: pageUrl || null,
          custom_instructions: instructions.trim() || null,
        });
      }
    } catch (e) {
      // assertGraded throws here when the model returned nothing parseable — surface it
      // rather than showing a table of zeros.
      gradeError = e instanceof Error ? e.message : String(e);
    } finally {
      grading = false;
      progress = null;
    }
  }

  /**
   * Send the flagged students back for a second look. Detection alone only says the scores
   * look wrong; this is the pass that re-reads them against similarly-scored peers.
   *
   * Explicitly a button, not an automatic step: it costs another model call and it changes
   * grades, so it should be a decision rather than something that happens on its own.
   */
  async function runReview() {
    if (!effectiveRubric || !results.length) return;
    reviewing = true;
    reviewMsg = null;
    gradeError = null;
    try {
      const out = await ogreIsland.methods.reviewOutliers(
        ogreIsland.methods.toGradingStudents(gradedRoster),
        results,
        effectiveRubric,
        { id: provider, model },
      );
      results = out.results;
      reviewMsg = out.changed.length
        ? `Reviewed ${out.reviewed}, revised ${out.changed.length}: ${out.changed.map(nameFor).join(', ')}.`
        : `Reviewed ${out.reviewed}; every original score held.`;
    } catch (e) {
      gradeError = e instanceof Error ? e.message : String(e);
    } finally {
      reviewing = false;
    }
  }

  onMount(() => {
    void loadRubrics();
    void loadProfiles();
  });

  /**
   * Re-read the rubric list every time the sidebar asks for this tab.
   *
   * onMount alone is not enough: ActionPanel only mounts the tab you switch TO, so a
   * rubric added on the Rubrics page while this tab was already the active one never
   * showed up — the list looked empty long after it wasn't.
   */
  $effect(() => {
    const reload = () => void loadRubrics();
    window.addEventListener('steve:action-panel', reload);
    return () => window.removeEventListener('steve:action-panel', reload);
  });
</script>

<div class="grader">
  <!-- Rubric -->
  <details class="section" open>
    <summary class="section-summary"><span>Rubric</span><svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></summary>
    <div class="section-content">
      <div class="dropdown-row">
        <select class="fld" bind:value={rubricId} disabled={grading} title="Rubric">
          {#each rubrics as r (r.id)}<option value={r.id}>{r.name}</option>{/each}
          {#if rubrics.length === 0}<option value={null}>No rubrics yet</option>{/if}
        </select>
        <button class="btn-secondary small" onclick={importRubric} disabled={importing || grading}
          title="Import the grading checklist off the question on screen">
          {importing ? '…' : 'Import'}
        </button>
      </div>
      {#if importMsg}<span class="status-hint">{importMsg}</span>{/if}

      {#if !parsedRubric}
        <span class="status-hint">Pick a rubric, or Import one off the question open in the browser.</span>
      {:else}
        <div class="leniency-row">
          <div class="leniency-header">
            <span class="leniency-label">Rubric leniency</span>
            <span class="leniency-status" class:changed={leniency !== 50}>{describeLeniency(leniency)}</span>
          </div>
          <div class="leniency-slider-wrap">
            <input class="leniency-slider" type="range" min="0" max="100" step="5" bind:value={leniency} disabled={grading} />
            <div class="leniency-labels"><span>Lenient</span><span>Original</span><span>Strict</span></div>
          </div>
        </div>

        {#if anchors}
          <div class="sub-card">
            <div class="sub-head">
              <span class="sub-title">Scoring anchors</span>
              {#if anchorText}<span class="pill">edited</span>{/if}
            </div>
            <div class="anchor-scores">
              <span>Excellent <b>{anchors.excellent.score}</b></span>
              <span>Adequate <b>{anchors.adequate.score}</b></span>
              <span>Below avg <b>{anchors.belowAverage.score}</b></span>
              <span>Minimal <b>{anchors.minimal.score}</b></span>
            </div>
            <p class="hint">
              Score levels out of {effectiveRubric?.maxScore ?? 10} — if these read wrong, the max score is wrong.
              Generate examples to show what an actual answer looks like at each level; edit them freely, they go to the grader verbatim.
            </p>
            <button class="btn-secondary small" onclick={generateAnchors} disabled={anchorBusy || grading || !parsedRubric}>
              {anchorBusy ? 'Writing examples…' : anchorText ? 'Regenerate examples' : 'Generate examples'}
            </button>
            {#if anchorError}<span class="hint err-text">{anchorError}</span>{/if}
            {#if anchorText}
              <textarea class="fld mono" rows="6" bind:value={anchorText} disabled={anchorBusy || grading}></textarea>
              <button class="text-btn" onclick={() => (anchorText = '')} disabled={anchorBusy || grading}>Clear examples</button>
            {/if}
          </div>
        {/if}

        {#if weightItems.length > 1}
          <div class="sub-card">
            <div class="sub-head">
              <span class="sub-title">Category weights</span>
              {#if weightCheck}<span class="pill" class:bad={!weightCheck.valid}>{weightCheck.sum}%</span>{/if}
            </div>
            <p class="hint">Each category is scored 0-10 on its own merits; the weight is its share of the final grade. Off = grade on the rubric's points as-is.</p>
            <div class="btn-row">
              <button class="btn-secondary small" onclick={() => (weighted = autoFillWeights(weightItems))} disabled={grading}>From points</button>
              <button class="btn-secondary small" onclick={() => (weighted = equalizeWeights(weightItems))} disabled={grading}>Equal</button>
              {#if hasWeights(weightItems)}
                <button class="btn-secondary small" onclick={() => (weighted = clearWeights(weightItems))} disabled={grading}>Off</button>
              {/if}
            </div>
            {#if hasWeights(weightItems)}
              <table class="weight-table">
                <tbody>
                  {#each weightItems as c, i (c.category + i)}
                    <tr>
                      <td class="w-cat">{c.category || 'General'}</td>
                      <td class="w-val">
                        <input class="num-input" type="number" min="0" max="100" step="0.1" disabled={grading}
                          value={c.categoryWeight ?? 0}
                          oninput={(e) => {
                            const v = parseFloat((e.currentTarget).value) || 0;
                            weighted = weightItems.map((x, j) => (j === i ? { ...x, categoryWeight: v } : x));
                          }} />
                        <span class="w-pct">%</span>
                      </td>
                    </tr>
                  {/each}
                </tbody>
              </table>
              {#if weightCheck && !weightCheck.valid}
                <div class="weight-error">{weightCheck.error} Grading is blocked until it totals 100%.</div>
              {/if}
            {/if}
          </div>
        {/if}
      {/if}
    </div>
  </details>

  <!-- Page & students -->
  <details class="section" open>
    <summary class="section-summary"><span>Page &amp; students</span><svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></summary>
    <div class="section-content">
      <!-- The page profile is auto-detected from the URL; when a saved one matches it is
           named here, otherwise the built-in MyOpenMath selectors are used silently. No
           picker — the teacher shouldn't have to know which profile a page needs. -->
      {#if activeProfile}
        <span class="status-hint">Reading with the “{activeProfile.name}” profile.</span>
      {/if}

      <label class="fld-label">
        Extra instructions <span class="opt">optional</span>
        <textarea class="fld" rows="2" placeholder="e.g. accept informal notation for the fence calculation"
          bind:value={instructions} disabled={grading}></textarea>
      </label>

      <div class="two-col">
        <label class="fld-label">
          Students / context
          <input class="fld" type="number" min="1" max="50" bind:value={chunkSize} disabled={grading} />
        </label>
        <label class="fld-label">
          Only this student <span class="opt">optional</span>
          <input class="fld" type="text" placeholder="name fragment" bind:value={onlyStudent} disabled={grading} />
        </label>
      </div>

      <label class="check">
        <input type="checkbox" bind:checked={includeGraded} disabled={grading} />
        Include already-graded students
      </label>

      <button class="btn-secondary full-width" onclick={loadFromPage} disabled={loadingStudents || grading}>
        {loadingStudents ? 'Reading page…' : 'Load students from page'}
      </button>

      {#if students.length > 0 && results.length === 0 && !grading && !onlyStudent.trim()}
        <span class="status-hint">{students.length} student{students.length === 1 ? '' : 's'} loaded.</span>
      {:else if loadAttempted && students.length === 0 && !loadError}
        <!-- No profile fit this page. This is where the site-mapping flow will hook in:
             the teacher confirms it's gradeable, mapping derives the selectors, and the
             run comes back here. Not wired yet — see the design note. -->
        <span class="status-hint warn">
          No students found on this page. Its layout isn't one the grader recognises yet.
        </span>
      {/if}
      {#if onlyStudent.trim() && students.length > 0}
        <span class="status-hint" class:warn={targetStudents.length === 0}>
          {targetStudents.length === 0
            ? `No loaded student matches “${onlyStudent.trim()}”.`
            : `${targetStudents.length} of ${students.length} match: ${targetStudents.map((s) => s.name).join(', ')}`}
        </span>
      {/if}
    </div>
  </details>

  <!-- Errors -->
  {#if loadError}
    <div class="batch-error"><small><strong>Could not load students.</strong> {loadError}</small></div>
  {/if}
  {#if gradeError}
    <div class="batch-error">
      <small><strong>Grading failed.</strong> {gradeError}{#if results.length} The {results.length} graded before the failure are kept below.{/if}</small>
    </div>
  {/if}

  <!-- Status & progress -->
  {#if grading || results.length > 0}
    <div class="batch-status">
      <div class="status-row">
        <div class="status-left">
          <span class="label">Status:</span>
          {#if grading && stopRequested}
            <span class="value paused">Stopping…</span>
          {:else if grading}
            <span class="spinner" aria-hidden="true"></span><span class="value running">Grading</span>
          {:else if stopped}
            <span class="value paused">Stopped early</span>
          {:else}
            <span class="value complete">Complete</span>
          {/if}
        </div>
        {#if progress}<span class="elapsed-timer">chunk {progress.chunk}/{progress.of}</span>{/if}
      </div>
      <div class="progress-bar">
        <div class="progress" style="width: {Math.round((results.length / (gradedRoster.length || targetStudents.length || 1)) * 100)}%"></div>
      </div>
      <div class="stats-row">
        <small>{results.length} / {gradedRoster.length || targetStudents.length} students</small>
      </div>
      {#if outliers && results.length > 0}
        <div class="agg-stats">
          <span>mean <b>{outliers.mean}</b></span>
          <span>σ <b>{outliers.stdDev}</b></span>
          <span class:flag={outliers.outliers.length > 0}>{outliers.outliers.length} outlier{outliers.outliers.length === 1 ? '' : 's'}</span>
        </div>
      {/if}
    </div>
  {/if}

  <!-- Results -->
  {#if results.length > 0}
    <div class="batch-log-card">
      <div class="log-header">
        <span class="log-title">Results ({results.length})</span>
        {#if outliers && outliers.outliers.length > 0}
          <button class="btn-secondary small" onclick={runReview} disabled={reviewing || grading}>
            {reviewing ? 'Re-reading…' : `Review ${outliers.outliers.length} outlier${outliers.outliers.length === 1 ? '' : 's'}`}
          </button>
        {/if}
      </div>
      {#if reviewMsg}<div class="log-note">{reviewMsg}</div>{/if}
      <div class="log-container">
        {#each results as r (r.studentIndex)}
          {@const isOutlier = outliers?.outliers.some((o) => o.studentIndex === r.studentIndex)}
          <div class="log-entry" class:outlier={isOutlier}>
            <button class="log-row" onclick={() => (expanded = expanded === r.studentIndex ? null : r.studentIndex)}>
              <span class="log-icon-col" class:err={isOutlier}>{isOutlier ? '⚠' : '✓'}</span>
              <span class="log-name">{nameFor(r.studentIndex)}</span>
              <span class="log-score">{r.score}</span>
            </button>
            {#if expanded === r.studentIndex}
              <div class="log-fb">{@html r.feedback}</div>
            {/if}
          </div>
        {/each}
      </div>
    </div>
  {/if}

  <!-- Action footer -->
  <div class="action-bar">
    {#if grading}
      <button class="btn-danger full-width" onclick={() => (stopRequested = true)} disabled={stopRequested}>
        {stopRequested ? 'Stopping…' : 'Stop grading'}
      </button>
    {:else}
      <button class="btn-primary full-width" onclick={runGrading}
        disabled={!targetStudents.length || !effectiveRubric || weightCheck?.valid === false}
        title={weightCheck?.valid === false ? weightCheck.error : ''}>
        Grade {targetStudents.length || ''} student{targetStudents.length === 1 ? '' : 's'}
      </button>
    {/if}
    {#if grading}
      <span class="action-hint">Stopping finishes the current chunk and keeps everything graded so far.</span>
    {/if}
  </div>
</div>

<style>
  /*
   * Adopts O.G.R.E's grading-panel layout: collapsible section cards, the gradient
   * leniency slider, sub-cards for anchors and weights, a status card with a progress
   * bar, a results log, and a sticky action footer. Styled with steve's own design
   * tokens (the same set O.G.R.E used) so a wrong token renders wrong rather than
   * silently falling back to a stray hex.
   */
  .grader {
    display: flex; flex-direction: column; gap: var(--spacing-3);
    padding: var(--spacing-2); color: var(--color-text-primary);
  }

  /* Collapsible section cards */
  .section { border: 1px solid var(--color-border); border-radius: var(--radius-md); overflow: clip; }
  .section-summary {
    display: flex; align-items: center; justify-content: space-between;
    padding: var(--spacing-2) var(--spacing-3); cursor: pointer;
    font-size: 0.85rem; font-weight: 600; color: var(--color-text-primary);
    background: var(--color-bg-main); user-select: none; list-style: none;
  }
  .section-summary::-webkit-details-marker { display: none; }
  .section-summary .chevron { color: var(--color-text-secondary); transition: transform 0.2s ease; }
  .section[open] > .section-summary .chevron { transform: rotate(180deg); }
  .section-content { padding: var(--spacing-3); display: flex; flex-direction: column; gap: var(--spacing-2); }

  /* Fields */
  .fld {
    width: 100%; box-sizing: border-box; background: var(--color-bg-main);
    border: 1px solid var(--color-border); color: var(--color-text-primary);
    border-radius: var(--radius-md); padding: var(--spacing-2);
    font-family: var(--font-body); font-size: 0.85rem;
  }
  .fld:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 2px var(--color-primary-bg); }
  .fld:disabled { opacity: 0.6; }
  textarea.fld { resize: vertical; line-height: 1.45; }
  .mono { font-family: var(--font-mono); font-size: 0.8rem; }
  .fld-label { display: flex; flex-direction: column; gap: 4px; font-size: 0.82rem; font-weight: 600; color: var(--color-text-secondary); }
  .fld-label .opt { font-weight: 400; color: var(--color-text-muted); }
  .two-col { display: flex; gap: var(--spacing-2); }
  .two-col .fld-label { flex: 1; min-width: 0; }
  .dropdown-row { display: flex; gap: var(--spacing-2); align-items: center; }
  .dropdown-row .fld { flex: 1; min-width: 0; }
  .check { display: flex; align-items: center; gap: var(--spacing-2); font-size: 0.82rem; color: var(--color-text-secondary); }
  .status-hint { font-size: 0.8rem; color: var(--color-text-secondary); font-style: italic; }
  .status-hint.warn { color: var(--color-warning-text); font-style: normal; }
  .hint { font-size: 0.78rem; color: var(--color-text-secondary); margin: 0; line-height: 1.45; }
  .err-text { color: var(--color-warning-text); }

  /* Buttons */
  .btn-primary, .btn-secondary, .btn-danger {
    font-family: var(--font-body); font-size: 0.9rem; cursor: pointer;
    border-radius: var(--radius-md); padding: var(--spacing-2) var(--spacing-3);
    transition: all 0.15s; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
  }
  .btn-primary { background: var(--color-primary-hover); border: 1px solid var(--color-primary-hover); color: var(--color-primary-text); font-weight: 600; }
  .btn-primary:hover:not(:disabled) { filter: brightness(1.08); }
  .btn-secondary { background: var(--color-bg-card); border: 1px solid var(--color-border); color: var(--color-text-primary); }
  .btn-secondary:hover:not(:disabled) { background: var(--color-bg-card-hover); border-color: var(--color-border-hover); }
  .btn-danger { background: var(--color-danger, #ef4444); border: 1px solid var(--color-danger, #ef4444); color: #fff; font-weight: 600; }
  .btn-danger:hover:not(:disabled) { filter: brightness(1.08); }
  .btn-primary:disabled, .btn-secondary:disabled, .btn-danger:disabled { opacity: 0.5; cursor: not-allowed; }
  .btn-secondary.small { padding: var(--spacing-1) var(--spacing-2); font-size: 0.8rem; }
  .full-width { width: 100%; }
  .btn-row { display: flex; gap: var(--spacing-2); flex-wrap: wrap; }
  .text-btn { align-self: flex-start; background: none; border: none; color: var(--color-primary); font-size: 0.8rem; cursor: pointer; padding: 0; font-family: var(--font-body); }
  .text-btn:hover { text-decoration: underline; }

  /* Leniency slider */
  .leniency-row {
    display: flex; flex-direction: column; gap: 4px; padding: var(--spacing-2);
    border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-bg-main);
  }
  .leniency-header { display: flex; align-items: center; justify-content: space-between; }
  .leniency-label { font-size: 0.82rem; font-weight: 600; color: var(--color-text-primary); }
  .leniency-status { font-size: 0.75rem; color: var(--color-text-secondary); }
  .leniency-status.changed { color: var(--color-primary); font-weight: 600; }
  .leniency-slider-wrap { display: flex; flex-direction: column; gap: 2px; }
  .leniency-slider {
    width: 100%; height: 6px; -webkit-appearance: none; appearance: none;
    background: linear-gradient(to right, #22c55e, var(--color-border) 45%, var(--color-border) 55%, #ef4444);
    border-radius: 3px; outline: none; cursor: pointer;
  }
  .leniency-slider::-webkit-slider-thumb {
    -webkit-appearance: none; appearance: none; width: 16px; height: 16px; border-radius: 50%;
    background: var(--color-primary); cursor: pointer; border: 2px solid var(--color-bg-main); box-shadow: 0 1px 3px rgba(0,0,0,0.3);
  }
  .leniency-slider:disabled { opacity: 0.4; cursor: not-allowed; }
  .leniency-labels { display: flex; justify-content: space-between; font-size: 0.68rem; color: var(--color-text-secondary); opacity: 0.8; }

  /* Sub-cards */
  .sub-card {
    display: flex; flex-direction: column; gap: var(--spacing-2); padding: var(--spacing-3);
    border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-bg-main);
  }
  .sub-head { display: flex; align-items: center; gap: var(--spacing-2); }
  .sub-title { font-size: 0.85rem; font-weight: 600; color: var(--color-text-primary); }
  .pill { margin-left: auto; font-size: 0.68rem; font-weight: 600; padding: 1px var(--spacing-2); border-radius: var(--radius-full); background: var(--color-primary-bg); color: var(--color-primary); }
  .pill.bad { background: var(--color-warning-bg); color: var(--color-warning-text); }
  .anchor-scores { display: flex; flex-wrap: wrap; gap: var(--spacing-1) var(--spacing-3); font-size: 0.8rem; color: var(--color-text-secondary); }
  .anchor-scores b { color: var(--color-text-primary); font-variant-numeric: tabular-nums; }

  /* Weight table */
  .weight-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
  .weight-table td { padding: 3px 0; border-bottom: 1px solid var(--color-border); }
  .w-cat { color: var(--color-text-secondary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 0; width: 100%; }
  .w-val { text-align: right; white-space: nowrap; }
  .num-input {
    width: 4rem; text-align: right; background: var(--color-bg-card); color: var(--color-text-primary);
    border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 2px 4px; font-size: 0.82rem;
  }
  .num-input:focus { outline: none; border-color: var(--color-primary); }
  .w-pct { color: var(--color-text-muted); margin-left: 3px; }
  .weight-error { font-size: 0.78rem; color: var(--color-warning-text); background: var(--color-warning-bg); padding: var(--spacing-2); border-radius: var(--radius-sm); }

  /* Status card */
  .batch-status {
    padding: var(--spacing-3); background: var(--color-bg-main);
    border: 1px solid var(--color-border); border-radius: var(--radius-md);
    display: flex; flex-direction: column; gap: var(--spacing-2);
  }
  .status-row { display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; }
  .status-left { display: flex; align-items: center; gap: 6px; }
  .status-left .label { color: var(--color-text-secondary); }
  .value.running { color: var(--color-primary); font-weight: 600; }
  .value.paused { color: var(--color-warning-text); font-weight: 600; }
  .value.complete { color: var(--color-success); font-weight: 600; }
  .elapsed-timer { font-size: 0.82rem; font-weight: 600; color: var(--color-text-secondary); font-variant-numeric: tabular-nums; }
  .progress-bar { height: 6px; background: var(--color-border); border-radius: var(--radius-full); overflow: hidden; }
  .progress { height: 100%; background: var(--color-success); transition: width 0.3s ease; }
  .stats-row { text-align: right; color: var(--color-text-secondary); }
  .agg-stats { display: flex; gap: var(--spacing-3); font-size: 0.8rem; color: var(--color-text-secondary); }
  .agg-stats b { color: var(--color-text-primary); font-variant-numeric: tabular-nums; }
  .agg-stats .flag { color: var(--color-warning-text); }

  /* Spinner */
  .spinner { display: inline-block; width: 12px; height: 12px; border: 2px solid currentColor; border-top-color: transparent; border-radius: 50%; animation: spin 0.8s linear infinite; opacity: 0.8; flex-shrink: 0; }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* Results log */
  .batch-log-card { background: var(--color-bg-main); border: 1px solid var(--color-border); border-radius: var(--radius-md); overflow: hidden; }
  .log-header { display: flex; align-items: center; justify-content: space-between; gap: var(--spacing-2); padding: var(--spacing-2) var(--spacing-3); font-size: 0.85rem; font-weight: 600; color: var(--color-text-secondary); }
  .log-note { padding: 0 var(--spacing-3) var(--spacing-2); font-size: 0.78rem; color: var(--color-text-secondary); }
  .log-container { max-height: 320px; overflow-y: auto; border-top: 1px solid var(--color-border); }
  .log-entry { border-bottom: 1px solid var(--color-border); }
  .log-entry:last-child { border-bottom: none; }
  .log-entry.outlier { background: var(--color-warning-bg); }
  .log-row {
    width: 100%; display: flex; align-items: center; gap: var(--spacing-2);
    padding: var(--spacing-2) var(--spacing-3); background: none; border: none;
    color: var(--color-text-primary); cursor: pointer; text-align: left; font-size: 0.82rem;
  }
  .log-row:hover { background: var(--color-bg-card-hover); }
  .log-icon-col { flex-shrink: 0; width: 16px; text-align: center; color: var(--color-success); font-weight: bold; }
  .log-icon-col.err { color: var(--color-warning-text); }
  .log-name { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500; }
  .log-score { font-weight: 600; font-variant-numeric: tabular-nums; }
  .log-fb { padding: 0 var(--spacing-3) var(--spacing-2) calc(var(--spacing-3) + 24px); font-size: 0.8rem; line-height: 1.5; color: var(--color-text-secondary); }
  .log-fb :global(strong) { color: var(--color-text-primary); }
  .log-fb :global(blockquote) { margin: var(--spacing-2) 0; padding-left: var(--spacing-3); border-left: 2px solid var(--color-border); color: var(--color-text-muted); }

  /* Error banner */
  .batch-error { display: flex; gap: var(--spacing-2); padding: var(--spacing-2) var(--spacing-3); background: var(--color-danger-bg); border: 1px solid var(--color-danger-border); border-radius: var(--radius-md); color: var(--color-text-primary); }
  .batch-error small { line-height: 1.45; }

  /* Sticky action footer */
  .action-bar {
    position: sticky; bottom: calc(-1 * var(--spacing-2)); z-index: 2;
    display: flex; flex-direction: column; gap: var(--spacing-1);
    margin: 0 calc(-1 * var(--spacing-2)) calc(-1 * var(--spacing-2));
    padding: var(--spacing-3) var(--spacing-2);
    background: var(--color-bg-sidebar); border-top: 1px solid var(--color-border);
  }
  .action-hint { font-size: 0.75rem; color: var(--color-text-secondary); text-align: center; }
</style>
