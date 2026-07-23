<script lang="ts">
  /**
   * OGRE batch grading.
   *
   * Flow: pick a rubric → load the students off whatever MyOpenMath grading page the
   * embedded browser is on → grade them in one model context per chunk → review.
   *
   * Nothing is written back to MyOpenMath from this page. Scores and feedback are
   * displayed for review only; the write-back path does not exist yet, and adding one
   * silently would be the worst possible surprise in a gradebook.
   */
  import { onMount } from 'svelte';
  import { ogreIsland } from '../integrations/ogre';
  import { describeLeniency, restoreCategoryWeights, rewriteRubric } from '../integrations/ogre/leniency';
  import { detectOutliers } from '../integrations/ogre/outliers';
  import { evalScript, isConnected } from '../lib/cdp-actions';
  import type { ExtractedStudent } from '../integrations/ogre/load-students';
  import type { BatchResult } from '../integrations/ogre/batch';
  import type { Rubric } from '../integrations/ogre/grading';
  import type { Skill } from '../integrations/ogre/types';

  let rubrics = $state<Skill[]>([]);
  let rubricId = $state<string | null>(null);
  let leniency = $state(50);
  let chunkSize = $state(20);

  let students = $state<ExtractedStudent[]>([]);
  let includeGraded = $state(false);
  let loadingStudents = $state(false);
  let loadError = $state<string | null>(null);

  let grading = $state(false);
  let progress = $state<{ chunk: number; of: number } | null>(null);
  let results = $state<BatchResult[]>([]);
  let gradeError = $state<string | null>(null);

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
  const effectiveRubric = $derived.by((): Rubric | null => {
    if (!parsedRubric) return null;
    if (leniency === 50) return parsedRubric;
    const items = (parsedRubric.checklistItems ?? []).map((c) => ({
      ...c,
      items: (c.items ?? []).map((line) => restoreCategoryWeights(rewriteRubric(line, leniency), line)),
    }));
    return { ...parsedRubric, checklistItems: items };
  });

  const outliers = $derived(results.length ? detectOutliers(results) : null);
  const nameFor = (i: number) => students[i]?.name ?? `Student ${i}`;

  async function loadRubrics() {
    try {
      rubrics = await ogreIsland.methods.listRubrics();
      if (!rubricId && rubrics.length) rubricId = rubrics[0]!.id;
    } catch (e) {
      loadError = e instanceof Error ? e.message : String(e);
    }
  }

  async function loadFromPage() {
    loadingStudents = true;
    loadError = null;
    results = [];
    try {
      if (!isConnected()) throw new Error('Not connected to the browser. Open the grading page in Browse first.');
      students = await ogreIsland.methods.loadStudents(
        async (expression) => {
          const res = await evalScript(expression);
          if (!res.success) throw new Error(res.error ?? 'Page evaluation failed');
          return res.data;
        },
        { includeGraded },
      );
    } catch (e) {
      students = [];
      loadError = e instanceof Error ? e.message : String(e);
    } finally {
      loadingStudents = false;
    }
  }

  async function runGrading() {
    if (!effectiveRubric || students.length === 0) return;
    grading = true;
    gradeError = null;
    results = [];
    progress = null;
    try {
      const toGrade = ogreIsland.methods.toGradingStudents(students);
      const gen = ogreIsland.methods.gradeBatch(toGrade, effectiveRubric, {}, { chunkSize });
      for await (const ev of gen) {
        if (ev.type === 'chunk-start') progress = { chunk: ev.chunkIndex + 1, of: ev.chunkCount };
        if (ev.type === 'done') results = ev.results;
      }
      // Record the run only once it actually produced grades.
      if (results.length) {
        const scores = results.map((r) => r.score);
        await ogreIsland.methods.addGradingSession({
          model: null,
          student_count: results.length,
          mean_score: scores.reduce((a, b) => a + b, 0) / scores.length,
          min_score: Math.min(...scores),
          max_score: Math.max(...scores),
          max_possible_score: Number(effectiveRubric.maxScore ?? 10),
          page_url: null,
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

  onMount(loadRubrics);
</script>

<div class="page">
  <header>
    <h1>Grading</h1>
    <p class="sub">Grades are shown for review only — nothing is written back to MyOpenMath.</p>
  </header>

  <section class="controls">
    <div class="field">
      <label for="rubric">Rubric</label>
      <select id="rubric" bind:value={rubricId} disabled={grading}>
        {#each rubrics as r (r.id)}<option value={r.id}>{r.name}</option>{/each}
        {#if rubrics.length === 0}<option value={null}>No rubrics yet</option>{/if}
      </select>
    </div>

    <div class="field">
      <label for="chunk">Students per context</label>
      <input id="chunk" type="number" min="1" max="50" bind:value={chunkSize} disabled={grading} />
      <span class="hint">Larger keeps scores more comparable; too large and the model truncates.</span>
    </div>

    <div class="field wide">
      <label for="len">Leniency <span class="status" class:changed={leniency !== 50}>{describeLeniency(leniency)}</span></label>
      <input id="len" type="range" min="0" max="100" step="5" bind:value={leniency} disabled={grading} />
    </div>
  </section>

  <section class="actions">
    <button onclick={loadFromPage} disabled={loadingStudents || grading}>
      {loadingStudents ? 'Reading page…' : 'Load students from page'}
    </button>
    <label class="check">
      <input type="checkbox" bind:checked={includeGraded} disabled={grading} />
      Include already-graded students
    </label>
    <button class="primary" onclick={runGrading} disabled={grading || !students.length || !effectiveRubric}>
      {grading ? `Grading${progress ? ` — chunk ${progress.chunk}/${progress.of}` : '…'}` : `Grade ${students.length || ''} students`}
    </button>
  </section>

  {#if loadError}
    <div class="error"><strong>Could not load students.</strong><p>{loadError}</p></div>
  {/if}
  {#if gradeError}
    <div class="error"><strong>Grading failed — no grades were produced.</strong><p>{gradeError}</p></div>
  {/if}

  {#if students.length > 0 && results.length === 0 && !grading}
    <p class="muted">{students.length} student{students.length === 1 ? '' : 's'} ready to grade.</p>
  {/if}

  {#if results.length > 0}
    {#if outliers}
      <div class="stats">
        <span><strong>{outliers.mean}</strong> mean</span>
        <span><strong>{outliers.stdDev}</strong> std dev</span>
        <span class:flag={outliers.outliers.length > 0}>
          <strong>{outliers.outliers.length}</strong> outlier{outliers.outliers.length === 1 ? '' : 's'} beyond 2σ
        </span>
      </div>
    {/if}

    <table>
      <thead><tr><th>Student</th><th class="n">Score</th><th>Feedback</th></tr></thead>
      <tbody>
        {#each results as r (r.studentIndex)}
          {@const isOutlier = outliers?.outliers.some((o) => o.studentIndex === r.studentIndex)}
          <tr class:outlier={isOutlier}>
            <td>{nameFor(r.studentIndex)}{#if isOutlier}<span class="badge">outlier</span>{/if}</td>
            <td class="n strong">{r.score}</td>
            <td class="fb">{@html r.feedback}</td>
          </tr>
        {/each}
      </tbody>
    </table>
  {/if}
</div>

<style>
  .page { padding: 1.5rem; overflow-y: auto; height: 100%; }
  header { margin-bottom: 1.25rem; }
  h1 { margin: 0 0 0.25rem; font-size: 1.5rem; }
  .sub, .muted, .hint { color: var(--text-muted, #888); font-size: 0.85rem; margin: 0; }
  .controls { display: flex; gap: 1.25rem; flex-wrap: wrap; margin-bottom: 1rem; }
  .field { display: flex; flex-direction: column; gap: 0.25rem; min-width: 170px; }
  .field.wide { flex: 1; min-width: 240px; }
  label { font-size: 0.85rem; font-weight: 600; }
  .status { font-weight: 400; color: var(--text-muted, #888); }
  .status.changed { color: var(--accent, #4a9eff); font-weight: 600; }
  select, input[type='number'] { padding: 0.35rem 0.5rem; font: inherit; }
  .actions { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; margin-bottom: 1rem; }
  .check { display: flex; align-items: center; gap: 0.4rem; font-weight: 400; font-size: 0.85rem; }
  button { font: inherit; padding: 0.45rem 0.9rem; border-radius: 6px; cursor: pointer; }
  button.primary { background: var(--accent, #4a9eff); color: #fff; border: none; }
  button:disabled { opacity: 0.5; cursor: default; }
  .stats { display: flex; gap: 1.5rem; margin: 1rem 0 0.5rem; font-size: 0.9rem; }
  .stats .flag { color: #e67e22; }
  table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
  th, td { text-align: left; padding: 0.5rem 0.6rem; border-bottom: 1px solid var(--border, #3333); vertical-align: top; }
  th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-muted, #888); }
  .n { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }
  .strong { font-weight: 600; }
  tr.outlier { background: #e67e2211; }
  .badge { font-size: 0.68rem; background: #e67e22; color: #fff; padding: 0.05rem 0.35rem; border-radius: 4px; margin-left: 0.4rem; }
  .fb { line-height: 1.45; }
  .error { border: 1px solid #c0392b55; background: #c0392b11; padding: 0.9rem; border-radius: 8px; margin-bottom: 1rem; }
</style>
