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

  async function loadFromPage() {
    loadingStudents = true;
    loadError = null;
    results = [];
    try {
      students = await ogreIsland.methods.loadStudents(readPage, {
        includeGraded,
        selectors: activeProfile?.selectors,
      });
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
  <div class="card">
    <div class="field">
      <span class="lbl">Rubric</span>
      <div class="row">
        <select bind:value={rubricId} disabled={grading} title="Rubric">
          {#each rubrics as r (r.id)}<option value={r.id}>{r.name}</option>{/each}
          {#if rubrics.length === 0}<option value={null}>No rubrics yet</option>{/if}
        </select>
        <button onclick={importRubric} disabled={importing || grading} title="Import the grading checklist off the question on screen">
          {importing ? '…' : 'Import'}
        </button>
      </div>
    </div>
    {#if importMsg}<p class="note">{importMsg}</p>{/if}

    <div class="field">
      <span class="lbl">Leniency <em class:changed={leniency !== 50}>{describeLeniency(leniency)}</em></span>
      <input type="range" min="0" max="100" step="5" bind:value={leniency} disabled={grading} />
    </div>
  </div>

  {#if anchors}
    <details class="anchors">
      <summary>Scoring anchors{#if anchorText}<span class="tag">edited</span>{/if}</summary>
      <p class="note">Derived from the rubric's max score ({effectiveRubric?.maxScore ?? 10}). If these read wrong, the max score is wrong.</p>
      <ul>
        <li><span>Excellent</span><b>{anchors.excellent.score}</b></li>
        <li><span>Adequate</span><b>{anchors.adequate.score}</b></li>
        <li><span>Below average</span><b>{anchors.belowAverage.score}</b></li>
        <li><span>Minimal</span><b>{anchors.minimal.score}</b></li>
      </ul>
      <p class="note">
        Those are score levels with generic wording. Generating examples writes what an
        actual answer to <em>this</em> question looks like at each level — edit them freely,
        they go to the grader verbatim.
      </p>
      <button onclick={generateAnchors} disabled={anchorBusy || grading || !parsedRubric}>
        {anchorBusy ? 'Writing examples…' : anchorText ? 'Regenerate examples' : 'Generate examples'}
      </button>
      {#if anchorError}<p class="note warn">{anchorError}</p>{/if}
      {#if anchorText}
        <textarea class="anchor-text" rows="8" bind:value={anchorText} disabled={anchorBusy || grading}></textarea>
        <button onclick={() => (anchorText = '')} disabled={anchorBusy || grading}>Clear examples</button>
      {/if}
    </details>

    {#if weightItems.length > 1}
      <details class="anchors">
        <summary>Category weights{#if weightCheck}<span class="tag" class:bad={!weightCheck.valid}>{weightCheck.sum}%</span>{/if}</summary>
        <p class="note">
          Each category is scored 0-10 on its own merits; the weight is the share of the final
          grade it carries. Leave these off to grade on the rubric's points as-is.
        </p>
        <div class="row">
          <button onclick={() => (weighted = autoFillWeights(weightItems))} disabled={grading}>From points</button>
          <button onclick={() => (weighted = equalizeWeights(weightItems))} disabled={grading}>Equal</button>
          {#if hasWeights(weightItems)}
            <button onclick={() => (weighted = clearWeights(weightItems))} disabled={grading}>Off</button>
          {/if}
        </div>
        {#if hasWeights(weightItems)}
          <ul class="weights">
            {#each weightItems as c, i (c.category + i)}
              <li>
                <span>{c.category || 'General'}</span>
                <input
                  type="number" min="0" max="100" step="0.1" disabled={grading}
                  value={c.categoryWeight ?? 0}
                  oninput={(e) => {
                    const v = parseFloat((e.currentTarget as HTMLInputElement).value) || 0;
                    weighted = weightItems.map((x, j) => (j === i ? { ...x, categoryWeight: v } : x));
                  }}
                />
              </li>
            {/each}
          </ul>
          {#if weightCheck && !weightCheck.valid}
            <p class="note warn">{weightCheck.error} Grading is blocked until they do.</p>
          {/if}
        {/if}
      </details>
    {/if}
  {/if}

  <div class="field">
    <span class="lbl">Page profile</span>
    <select bind:value={profileId} disabled={grading || loadingStudents}>
      <option value="auto">
        Auto-detect{activeProfile && profileId === 'auto' ? ` — ${activeProfile.name}` : ''}
      </option>
      {#each profiles as p (p.id)}<option value={p.id}>{p.name}</option>{/each}
    </select>
    {#if profileId === 'auto' && !activeProfile}
      <span class="note">No saved profile matches this page — reading with the built-in MyOpenMath selectors.</span>
    {/if}
  </div>

  <div class="field">
    <span class="lbl">Extra instructions</span>
    <textarea
      rows="2"
      placeholder="e.g. accept informal notation for the fence calculation"
      bind:value={instructions}
      disabled={grading}
    ></textarea>
  </div>

  <div class="field">
    <span class="lbl">Students per context</span>
    <input class="num" type="number" min="1" max="50" bind:value={chunkSize} disabled={grading} />
  </div>

  <div class="field">
    <span class="lbl">Only this student <em>optional</em></span>
    <input type="text" placeholder="name fragment — blank grades everyone" bind:value={onlyStudent} disabled={grading} />
  </div>

  <label class="check">
    <input type="checkbox" bind:checked={includeGraded} disabled={grading} />
    Include already-graded students
  </label>

  <div class="row">
    <button onclick={loadFromPage} disabled={loadingStudents || grading}>
      {loadingStudents ? 'Reading…' : 'Load students'}
    </button>
    {#if grading}
      <button class="primary" onclick={() => (stopRequested = true)} disabled={stopRequested}>
        {stopRequested ? 'Stopping…' : `Stop${progress ? ` (${progress.chunk}/${progress.of})` : ''}`}
      </button>
    {:else}
      <button
        class="primary"
        onclick={runGrading}
        disabled={!targetStudents.length || !effectiveRubric || weightCheck?.valid === false}
        title={weightCheck?.valid === false ? weightCheck.error : ''}
      >
        Grade {targetStudents.length || ''}
      </button>
    {/if}
  </div>
  {#if grading}
    <p class="note">
      {progress ? `Chunk ${progress.chunk} of ${progress.of}` : 'Starting…'} — stopping finishes the
      current chunk and keeps everything graded so far.
    </p>
  {/if}
  {#if stopped}
    <p class="note warn">Stopped early. {results.length} of {gradedRoster.length} students graded.</p>
  {/if}

  {#if onlyStudent.trim() && students.length > 0}
    <p class="note" class:warn={targetStudents.length === 0}>
      {targetStudents.length === 0
        ? `No loaded student matches “${onlyStudent.trim()}”.`
        : `Grading ${targetStudents.length} of ${students.length}: ${targetStudents.map((s) => s.name).join(', ')}`}
    </p>
  {/if}

  {#if loadError}<div class="err"><strong>Could not load students.</strong><p>{loadError}</p></div>{/if}
  {#if gradeError}
    <div class="err">
      <strong>Grading failed.</strong>
      <p>{gradeError}</p>
      {#if results.length}
        <p>The {results.length} student{results.length === 1 ? '' : 's'} graded before the failure are kept below.</p>
      {/if}
    </div>
  {/if}

  {#if students.length > 0 && results.length === 0 && !grading}
    <p class="note">{students.length} student{students.length === 1 ? '' : 's'} loaded.</p>
  {/if}

  {#if results.length > 0 && outliers}
    <div class="stats">
      <span>mean <strong>{outliers.mean}</strong></span>
      <span>σ <strong>{outliers.stdDev}</strong></span>
      <span class:flag={outliers.outliers.length > 0}><strong>{outliers.outliers.length}</strong> outlier{outliers.outliers.length === 1 ? '' : 's'}</span>
    </div>
    {#if outliers.outliers.length > 0}
      <button onclick={runReview} disabled={reviewing || grading}>
        {reviewing ? 'Re-reading…' : `Review ${outliers.outliers.length} outlier${outliers.outliers.length === 1 ? '' : 's'}`}
      </button>
    {/if}
    {#if reviewMsg}<p class="note">{reviewMsg}</p>{/if}
  {/if}

  {#each results as r (r.studentIndex)}
    {@const isOutlier = outliers?.outliers.some((o) => o.studentIndex === r.studentIndex)}
    <div class="result" class:outlier={isOutlier}>
      <button class="result-head" onclick={() => (expanded = expanded === r.studentIndex ? null : r.studentIndex)}>
        <span class="who">{nameFor(r.studentIndex)}</span>
        {#if isOutlier}<span class="badge">outlier</span>{/if}
        <span class="score">{r.score}</span>
      </button>
      {#if expanded === r.studentIndex}
        <div class="fb">{@html r.feedback}</div>
      {/if}
    </div>
  {/each}
</div>

<style>
  /*
   * Uses the app's own design tokens (app.css) — the same set O.G.R.E's panel used, since
   * steve inherited it. The first cut invented names (--text-muted, --surface, --accent)
   * that exist in neither, so every rule silently fell through to a hardcoded hex and the
   * panel ignored the theme entirely. If a token here is wrong, it renders wrong rather
   * than looking almost right, which is the point.
   */
  .grader {
    display: flex; flex-direction: column;
    gap: var(--spacing-3); padding: var(--spacing-2);
    color: var(--color-text-primary);
  }

  .row { display: flex; gap: var(--spacing-2); }
  .row select { flex: 1; min-width: 0; }

  .field { display: flex; flex-direction: column; gap: var(--spacing-1); }
  .lbl { font-size: 0.78rem; font-weight: 600; color: var(--color-text-secondary); }
  .lbl em { font-style: normal; font-weight: 400; color: var(--color-text-muted); }
  .lbl em.changed { color: var(--color-primary); font-weight: 600; }

  select, input[type='number'], input[type='text'], textarea {
    padding: var(--spacing-2); border-radius: var(--radius-md);
    border: 1px solid var(--color-border); background: var(--color-bg-main);
    color: var(--color-text-primary); font-family: var(--font-body);
    font-size: 0.85rem; width: 100%; box-sizing: border-box;
  }
  select:focus, input:focus, textarea:focus {
    outline: none; border-color: var(--color-primary);
  }
  textarea { resize: vertical; line-height: 1.45; }
  .num { width: 5rem; }
  input[type='range'] { width: 100%; accent-color: var(--color-primary); }

  .check {
    display: flex; align-items: center; gap: var(--spacing-2);
    font-size: 0.8rem; color: var(--color-text-secondary);
  }
  .check input { width: auto; }

  button {
    padding: var(--spacing-2) var(--spacing-3); border-radius: var(--radius-md);
    border: 1px solid var(--color-border); background: var(--color-bg-card);
    color: var(--color-text-primary); font-family: var(--font-body);
    font-size: 0.85rem; cursor: pointer; transition: all var(--transition-fast);
  }
  button:hover:not(:disabled) {
    background: var(--color-bg-card-hover); border-color: var(--color-border-hover);
  }
  button.primary {
    flex: 1; background: var(--color-primary-hover);
    border-color: var(--color-primary-hover); color: var(--color-primary-text); font-weight: 600;
  }
  button:disabled { opacity: 0.6; cursor: not-allowed; }

  .note { font-size: 0.78rem; color: var(--color-text-muted); margin: 0; line-height: 1.45; }
  .note.warn { color: var(--color-warning-text); }

  .err {
    border: 1px solid var(--color-danger-border); background: var(--color-danger-bg);
    color: var(--color-text-primary); padding: var(--spacing-2) var(--spacing-3);
    border-radius: var(--radius-md); font-size: 0.8rem;
  }
  .err p { margin: var(--spacing-1) 0 0; color: var(--color-text-secondary); }

  /* ── Cards ── */
  .card {
    border: 1px solid var(--color-border); border-radius: var(--radius-md);
    background: var(--color-bg-card); padding: var(--spacing-3);
    display: flex; flex-direction: column; gap: var(--spacing-3);
  }

  /* ── Collapsible cards: anchors, weights ── */
  .anchors {
    border: 1px solid var(--color-border); border-radius: var(--radius-md);
    background: var(--color-bg-card); padding: var(--spacing-2); font-size: 0.8rem;
  }
  .anchors summary {
    cursor: pointer; font-weight: 600; color: var(--color-text-secondary);
    list-style: none; display: flex; align-items: center;
  }
  .anchors summary::-webkit-details-marker { display: none; }
  .anchors summary::before {
    content: '▸'; margin-right: var(--spacing-2); color: var(--color-text-muted);
    transition: transform var(--transition-fast);
  }
  .anchors[open] summary::before { content: '▾'; }
  .anchors[open] summary { margin-bottom: var(--spacing-2); }
  .anchors > * + * { margin-top: var(--spacing-2); }
  .anchors ul { list-style: none; margin: 0; padding: 0; }
  .anchors ul li {
    display: flex; justify-content: space-between;
    padding: var(--spacing-1) 0; color: var(--color-text-secondary);
  }
  .anchors ul li b { color: var(--color-text-primary); font-variant-numeric: tabular-nums; }
  .anchor-text { font-size: 0.78rem; font-family: var(--font-mono); }

  .weights li { gap: var(--spacing-2); align-items: center; }
  .weights li span {
    flex: 1; min-width: 0; overflow: hidden;
    text-overflow: ellipsis; white-space: nowrap;
  }
  .weights input { width: 4.5rem; flex: none; }

  .tag {
    font-size: 0.68rem; font-weight: 600; margin-left: auto;
    padding: 1px var(--spacing-2); border-radius: var(--radius-full);
    background: var(--color-primary-bg); color: var(--color-primary);
  }
  .tag.bad { background: var(--color-warning-bg); color: var(--color-warning-text); }

  /* ── Results ── */
  .stats {
    display: flex; gap: var(--spacing-3); font-size: 0.78rem;
    color: var(--color-text-muted); padding: var(--spacing-2);
    background: var(--color-bg-card); border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
  }
  .stats strong { color: var(--color-text-primary); font-variant-numeric: tabular-nums; }
  .stats .flag { color: var(--color-warning-text); }

  .result {
    border: 1px solid var(--color-border); border-radius: var(--radius-md);
    background: var(--color-bg-card); overflow: hidden;
  }
  .result + .result { margin-top: calc(var(--spacing-1) * -1 + var(--spacing-2)); }
  .result.outlier { border-color: var(--color-warning-border); background: var(--color-warning-bg); }
  .result-head {
    width: 100%; display: flex; align-items: center; gap: var(--spacing-2);
    background: none; border: none; border-radius: 0; text-align: left;
    padding: var(--spacing-2) var(--spacing-3);
  }
  .result-head:hover:not(:disabled) { background: var(--color-bg-card-hover); }
  .who { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .score { font-weight: 600; font-variant-numeric: tabular-nums; }
  .badge {
    font-size: 0.65rem; font-weight: 600; padding: 1px var(--spacing-2);
    border-radius: var(--radius-full);
    background: var(--color-warning-bg); color: var(--color-warning-text);
  }
  .fb {
    font-size: 0.8rem; line-height: 1.5; color: var(--color-text-secondary);
    padding: 0 var(--spacing-3) var(--spacing-3);
    border-top: 1px solid var(--color-border);
  }
  .fb :global(strong) { color: var(--color-text-primary); }
  .fb :global(blockquote) {
    margin: var(--spacing-2) 0; padding-left: var(--spacing-3);
    border-left: 2px solid var(--color-border); color: var(--color-text-muted);
  }
</style>
