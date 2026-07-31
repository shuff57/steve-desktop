<script lang="ts">
  /**
   * Write one question from a problem-set link, a description, or a pasted example, then loop
   * until the sandbox renders it clean.
   *
   * The agent writes the file through the CLI's own tools; this component does the half the agent
   * cannot do honestly — render the result and judge it — and hands any failure back. The run is
   * streamed line by line because the point is watching it happen, not getting a summary at the end.
   */
  import { invoke } from '@tauri-apps/api/core';
  import { listen, type UnlistenFn } from '@tauri-apps/api/event';
import {
  buildAuthorPrompt,
  buildSetPlanPrompt,
  buildRepairPrompt,
  parseSetPlan,
  questionPath,
  shouldRetry,
  hasSource,
  MAX_ATTEMPTS,
  type AttemptResult,
  type PlannedQuestion,
  type SetPlanMode,
} from '../../integrations/mom/author';
  import { addQuestionToAssignment } from '../../integrations/mom/book-membership';
  import { createBook, createAssignment, assignmentPath, slugify } from '../../integrations/mom/create';
  import { momIsland } from '../../integrations/mom';
  import { questionHealth } from '../../integrations/mom/health';
  import { prepareRenderHtml } from '../../integrations/mom/render-html';
  import { cliModelArg, extractCliText, engineForProvider, summarizeCliLine, type AgentEngine } from '../../lib/agent-cli';
  import { getSetting, setSetting } from '../../lib/db';
  import ChatMessage from './ChatMessage.svelte';
  import {
    buildReflectPrompt,
    parseProposedRules,
    mergeLearnedRules,
    hasLessons,
    loadLearnedRules,
    saveLearnedRules,
  } from '../../integrations/mom/reflect';
  import { MOM_DIALECT_RULES } from '../../integrations/mom/revise';

  const PLAN_SETTING = 'mom_author_plan';
  /** Where finished questions get filed. Persisted for the same reason the plan is. */
  const DEST_SETTING = 'mom_author_dest';

  let {
    root,
    sandboxUrl,
    families = [],
    placements = [],
    provider = null,
    model = null,
    onDone = (_: string) => {},
    onDraft = (_: string | null) => {},
    onBooksChanged = () => {},
  } = $props<{
    root: string;
    sandboxUrl: string;
    /** Existing question families, for the picker. */
    families?: string[];
    /** Every book, with its assignments — including empty ones, which are still selectable. */
    placements?: { slug: string; title: string; items: { path: string; name: string }[] }[];
    provider?: string | null;
    model?: string | null;
    onDone?: (path: string) => void | Promise<void>;
    /** Live file contents while a run is in flight; null when there is nothing being written. */
    onDraft?: (contents: string | null) => void;
    /** A book or assignment was created here; the parent must re-read the manifests. */
    onBooksChanged?: () => void | Promise<void>;
  }>();

  const DEFAULT_SECTION = '1.1_definitions_of_statistics_probability_and_key_terms.html';

  let link = $state(DEFAULT_SECTION);
  let brief = $state('');
  /** Path on disk of the example image; set by pasting, or typed by hand. */
  let imagePath = $state('');
  /** Object URL for the pasted image, so the thumbnail shows what was actually captured. */
  let imagePreview = $state<string | null>(null);
  let pasteErr = $state<string | null>(null);
  let family = $state('descriptive-stats');
  /** Typing a new family instead of picking one. */
  let newFamily = $state(false);
  /** Where to file the finished question: pick a book, then one of its assignments. */
  let placeBook = $state('');
  let placeAssignment = $state('');
  /**
   * Inline creation of the containers a question gets filed into.
   *
   * A family needs none of this — it is just the directory the question is written to, so typing a
   * new one is enough. A book and an assignment are declared in files, and until they are declared
   * the pickers below have nothing to offer.
   */
  let creating = $state<null | 'book' | 'assignment'>(null);
  let newName = $state('');
  let newKind = $state('hw');
  let createErr = $state<string | null>(null);
  let createBusy = $state(false);
  /** Guards the save effect until the stored destination has been restored. */
  let destLoaded = $state(false);
  let slug = $state('q1-key-terms');
  let running = $state(false);
  /** Set by Stop. Ends the retry loop and marks a rejected turn as asked-for, not a crash. */
  let stopped = $state(false);
  /** Session id of the turn in flight, so Stop can kill that one run and nothing else. */
  let runningSession = $state<string | null>(null);
  /** Last contents read off disk while the agent works — mirrored into the preview pane. */
  let draft = $state('');
  /** What the loop has taught itself so far. Loaded per run, grown by `reflect`. */
  let learnedRules = $state<string[]>([]);
  /**
   * The run reads as a conversation, the same one Revise shows: what was asked, what the agent said
   * back, what the render made of it. Tool activity stays a thin muted line — it is progress, not
   * something anyone said — so the bubbles remain the parts worth reading.
   */
  type Line = { role: 'user' | 'agent' | 'step' | 'ok' | 'error'; text: string };
  let lines = $state<Line[]>([]);
  let attempts = $state<AttemptResult[]>([]);
  let finalPath = $state<string | null>(null);
  let fatal = $state<string | null>(null);
  /** Set-planning state. */
  let planned = $state<PlannedQuestion[] | null>(null);
  let selected = $state<Set<string>>(new Set());
  let existingSlugs = $state<Set<string>>(new Set());
  let setBusy = $state(false);
  let setError = $state<string | null>(null);
  let planMode = $state<'exercises' | 'invent'>('exercises');

  /** Persist the current plan so a refresh does not wipe it. */
  async function persistPlan() {
    if (!planned) {
      await setSetting(PLAN_SETTING, '');
      return;
    }
    const payload = JSON.stringify({
      mode: planMode,
      link: link.trim(),
      family: family.trim(),
      planned,
      selected: [...selected],
    });
    await setSetting(PLAN_SETTING, payload);
  }

  async function loadPlan() {
    const raw = await getSetting(PLAN_SETTING).catch(() => null);
    if (!raw) return;
    try {
      const parsed = JSON.parse(raw) as {
        mode: 'exercises' | 'invent';
        link: string;
        family: string;
        planned: PlannedQuestion[];
        selected: string[];
      };
      if (parsed.planned?.length && parsed.link && parsed.family) {
        planMode = parsed.mode ?? 'exercises';
        link = parsed.link;
        family = parsed.family;
        planned = parsed.planned;
        selected = new Set(parsed.selected ?? parsed.planned.map((p) => p.slug));
        await loadExistingSlugs();
      }
    } catch {
      await setSetting(PLAN_SETTING, '');
    }
  }

  async function clearPlan() {
    planned = null;
    selected = new Set();
    setError = null;
    await setSetting(PLAN_SETTING, '');
  }

  const engine = $derived<AgentEngine>(engineForProvider(provider ?? undefined));
  const target = $derived(root ? questionPath(root, family.trim(), slug.trim()) : '');
  const sourced = $derived(hasSource({ link, brief, imagePath }));
  const chosenBook = $derived(placements.find((p: { slug: string }) => p.slug === placeBook) ?? null);
  // Changing book must clear a stale assignment, or the question files into the previous book's.
  // Requires the book to be FOUND first: on restore the books have not loaded yet, and clearing
  // against an empty list wipes the destination we just restored.
  $effect(() => {
    if (placeBook && chosenBook && !chosenBook.items.some((i: { path: string }) => i.path === placeAssignment)) {
      placeAssignment = '';
    }
  });
  const canRun = $derived(!running && !!root && !!family.trim() && !!slug.trim() && sourced);
  const hasLink = $derived(!!link.trim());
  const canPlan = $derived(!running && !!root && !!family.trim() && hasLink);
  const canWriteSelected = $derived(!running && !!root && !!family.trim() && hasLink && selected.size > 0);
  const planModeChosen = $derived(!!planMode);
  const allSelected = $derived(planned ? selected.size === planned.length && planned.length > 0 : false);

  // Persist whenever the plan or selection changes.
  $effect(() => {
    if (planned) {
      // Reading the deps so Svelte tracks them.
      void planMode;
      void link;
      void family;
      void selected;
      void planned;
      persistPlan();
    }
  });

  // Load any saved plan when the component mounts and the root is known.
  $effect(() => {
    if (root) {
      loadPlan();
      loadDest();
    }
  });

  /**
   * Persist WHERE the question gets filed, not just what to write.
   *
   * The plan was already persisted; the destination was not, and that asymmetry is a data-loss bug:
   * any remount reset the pickers to "don't file it", so a later run would write the question, skip
   * filing, and still report success. Observed doing exactly that — the question landed on disk and
   * the assignment stayed empty.
   */
  async function persistDest() {
    await setSetting(DEST_SETTING, JSON.stringify({ book: placeBook, assignment: placeAssignment }));
  }

  /** Re-entry guard, separate from `destLoaded`: the restore must COMPLETE before saves resume. */
  let destLoading = false;

  async function loadDest() {
    if (destLoaded || destLoading) return;
    destLoading = true;
    try {
      const raw = await getSetting(DEST_SETTING).catch(() => null);
      if (raw) {
        const { book, assignment } = JSON.parse(raw) as { book: string; assignment: string };
        if (book) placeBook = book;
        if (assignment) placeAssignment = assignment;
      }
    } catch {
      await setSetting(DEST_SETTING, '').catch(() => {});
    } finally {
      // Only now may the save effect fire. Flipping this before the await let the empty initial
      // state save over the very destination being restored.
      destLoaded = true;
    }
  }

  // Save on change, but only after the restore has run — otherwise the empty initial state
  // overwrites the saved destination on first paint.
  $effect(() => {
    void placeBook;
    void placeAssignment;
    if (destLoaded) persistDest();
  });

  function log(text: string, role: Line['role'] = 'step') {
    lines = [...lines, { role, text }];
  }

  async function loadExistingSlugs() {
    if (!root || !family.trim()) return;
    try {
      const idx = await momIsland.methods.browse(root);
      const fam = idx.families.find((f: { name: string }) => f.name === family.trim());
      existingSlugs = new Set((fam?.questions ?? []).map((q: { slug: string }) => q.slug.replace(/\.php$/i, '')));
    } catch {
      existingSlugs = new Set();
    }
  }

  async function planQuestions() {
    if (!canPlan) return;
    stopped = false;
    setBusy = true;
    setError = null;
    planned = null;
    selected = new Set();
    lines = [];
    try {
      const reply = await turn(buildSetPlanPrompt({ link: link.trim(), family: family.trim(), root, mode: planMode }));
      if (reply) log(reply, 'agent');
    const list = parseSetPlan(reply || '');
    planned = list;
    selected = new Set(list.map((p) => p.slug));
    await loadExistingSlugs();
    log(`Planned ${list.length} question${list.length === 1 ? '' : 's'} for ${family.trim()} (${planMode})`, 'ok');
    } catch (e) {
      setError = e instanceof Error ? e.message : String(e);
      log(`Planning failed: ${setError}`, 'error');
    } finally {
      setBusy = false;
    }
  }

  function toggleAll() {
    if (!planned) return;
    selected = allSelected ? new Set() : new Set(planned.map((p) => p.slug));
  }

  function toggleOne(slug: string) {
    const next = new Set(selected);
    if (next.has(slug)) next.delete(slug);
    else next.add(slug);
    selected = next;
  }

  /**
   * Take an image off the clipboard and spill it to disk, because the agent opens a FILE.
   *
   * Bound to the whole panel rather than one input: Ctrl+V after a screenshot should just work,
   * without first having to find and focus the right box.
   */
  async function onPaste(e: ClipboardEvent) {
    if (running) return;
    const items = Array.from(e.clipboardData?.items ?? []);
    const item = items.find((i) => i.type.startsWith('image/'));
    const file = item?.getAsFile();
    if (!file) return; // a normal text paste — leave it to the focused field
    e.preventDefault();
    pasteErr = null;
    try {
      const bytes = [...new Uint8Array(await file.arrayBuffer())];
      imagePath = await invoke<string>('mom_save_pasted_image', { bytes });
      if (imagePreview) URL.revokeObjectURL(imagePreview);
      imagePreview = URL.createObjectURL(file);
    } catch (err) {
      pasteErr = err instanceof Error ? err.message : String(err);
    }
  }

  /**
   * Create the book or assignment named in the inline form, then select it.
   *
   * Selecting it afterwards is the point: you opened this to file the question you are about to
   * write, so leaving the picker on "don't file it" would waste the trip.
   */
  async function createContainer() {
    const name = newName.trim();
    if (!name || createBusy) return;
    createBusy = true;
    createErr = null;
    try {
      if (creating === 'book') {
        placeBook = await createBook(root, name);
        placeAssignment = '';
      } else {
        // `today` is passed in rather than read inside, so the manifest writer stays testable.
        const today = new Date().toISOString().slice(0, 10);
        await createAssignment(root, { book: placeBook, kind: newKind, name, today });
        placeAssignment = assignmentPath(placeBook, newKind, slugify(name));
      }
      await onBooksChanged();
      creating = null;
      newName = '';
    } catch (e) {
      createErr = e instanceof Error ? e.message : String(e);
    } finally {
      createBusy = false;
    }
  }

  function clearImage() {
    if (imagePreview) URL.revokeObjectURL(imagePreview);
    imagePreview = null;
    imagePath = '';
    pasteErr = null;
  }

  /** Run one agent turn, streaming its output into the log. */
  async function turn(prompt: string): Promise<string> {
    const sessionId = crypto.randomUUID();
    runningSession = sessionId;
    let unlisten: UnlistenFn | null = null;
    try {
      unlisten = await listen<{ sessionId: string; line: string }>('agent-cli-progress', (ev) => {
        if (ev.payload.sessionId !== sessionId) return; // other runs share this channel
        const summary = summarizeCliLine(ev.payload.line);
        if (summary) log(summary);
      });
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt,
        sessionId,
        resume: false,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: true, // it has to read the private repo and write the file
        timeoutSecs: 900,
        stream: true, // the whole point: watch it being written
      });
      return extractCliText(engine, stdout).trim();
    } finally {
      unlisten?.();
      if (runningSession === sessionId) runningSession = null;
    }
  }

  /**
   * Kill the run. `stopped` is what actually ends the loop — killing the CLI makes the current turn
   * reject, and without the flag the catch below would report that as a crash rather than as you
   * having asked it to stop.
   */
  async function stop() {
    stopped = true;
    if (runningSession) await invoke('stop_agent_cli', { sessionId: runningSession }).catch(() => {});
  }

  /**
   * Show the file being written, by re-reading it while the agent works.
   *
   * Polling the file beats parsing write payloads out of the CLI stream: the file on disk is the
   * thing that will actually be rendered, and it stays right even when the agent edits in several
   * passes or uses a tool whose output shape we do not know.
   *
   * Re-evaluates `slug` every loop so a batch can move from one planned question to the next without
   * restarting the watcher.
   */
  async function watchFile() {
    let last = '';
    while (running) {
      const detail = await momIsland.methods
        .getQuestion(family.trim(), `${slug.trim().replace(/\.php$/i, '')}.php`, root)
        .catch(() => null);
      const text = detail?.contents ?? '';
      if (text && text !== last) {
        last = text;
        draft = text;
        onDraft(text);
      }
      await new Promise((r) => setTimeout(r, 600));
    }
  }

  /**
   * Render the file the agent wrote and judge it. The agent's own account is not evidence.
   *
   * Reads through the island's existing question reader rather than a raw file read, so the path
   * is resolved and guarded the same way the browser does it.
   */
  async function verify(): Promise<string[]> {
    const detail = await momIsland.methods
      .getQuestion(family.trim(), `${slug.trim().replace(/\.php$/i, '')}.php`, root)
      .catch(() => null);
    const contents = detail?.contents ?? '';
    if (!contents.trim()) return ['The agent did not write the file, or wrote it empty.'];
    const res = await fetch(sandboxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'text/plain' },
      body: contents,
    });
    if (!res.ok) return [`sandbox HTTP ${res.status}`];
    const health = questionHealth(contents, prepareRenderHtml(await res.text()));
    return [...health.errors, ...health.warnings];
  }

  /**
   * Core write/repair loop for one question. Temporarily sets `slug` so the file helpers target the
   * right path; the caller restores UI state when done. `imageOverride` lets a batch skip a shared
   * example image while keeping the pasted preview intact.
   */
  /**
   * Turn what went wrong into a rule every future run will follow.
   *
   * Runs after the question is settled, and never throws: this is an improvement to the NEXT run,
   * and a question that renders must not be reported as a failure because the reflection step had
   * a bad day. Everything it decides is logged, including deciding nothing — a silent reflector is
   * indistinguishable from a broken one.
   */
  async function reflect(localAttempts: AttemptResult[], path: string) {
    if (!root || !hasLessons(localAttempts)) return;
    try {
      log('Reflecting on what went wrong…');
      const reply = await turn(
        buildReflectPrompt({
          attempts: localAttempts,
          existing: learnedRules,
          handWritten: MOM_DIALECT_RULES,
          targetPath: path,
        }),
      );
      const proposed = parseProposedRules(reply || '');
      if (!proposed.length) {
        log('Nothing new to learn — the existing rules already cover it.');
        return;
      }
      const { rules, added, rejected } = mergeLearnedRules(learnedRules, proposed);
      if (!added.length) {
        log(`Proposed ${proposed.length}, kept none (duplicate or out of bounds).`);
        return;
      }
      await saveLearnedRules(root, rules);
      learnedRules = rules;
      for (const r of added) log(`Learned: ${r}`, 'ok');
      if (rejected.length) log(`Discarded ${rejected.length} duplicate or malformed proposal(s).`);
    } catch (e) {
      log(`Could not reflect: ${e instanceof Error ? e.message : String(e)}`);
    }
  }

  async function writeSingleQuestion(
    plannedSlug: string,
    plannedBrief?: string,
    imageOverride?: string,
  ): Promise<{ ok: boolean; path: string }> {
    const savedSlug = slug;
    const savedBrief = brief;
    slug = plannedSlug;
    if (plannedBrief !== undefined) brief = plannedBrief;
    const path = target;
    try {
      log(`Writing ${family.trim()}/${slug.trim()}.php`);
      let reply = await turn(
        buildAuthorPrompt({
          link: link.trim() || undefined,
          brief: brief.trim() || undefined,
          imagePath: (imageOverride ?? imagePath).trim() || undefined,
          family: family.trim(),
          slug: slug.trim(),
          targetPath: path,
          root,
          learned: learnedRules,
        }),
      );
      if (reply) log(reply, 'agent');

      const localAttempts: AttemptResult[] = [];
      for (;;) {
        const errors = await verify();
        const n = localAttempts.length + 1;
        const record: AttemptResult = { attempt: n, errors, ok: errors.length === 0 };
        localAttempts.push(record);
        attempts = localAttempts;
        if (errors.length === 0) {
          log(`Attempt ${n}: renders clean.`, 'ok');
          finalPath = path;
          // File it only once it actually renders — a broken question must never enter a book.
          // A filing failure does not fail the run: the question is already written and good.
          if (placeAssignment) {
            try {
              await addQuestionToAssignment(
                root,
                placeAssignment,
                `questions/${family.trim()}/${slug.trim().replace(/\.php$/i, '')}.php`,
                reply || slug.trim(),
              );
              log(`Added to ${chosenBook?.items.find((i: { path: string }) => i.path === placeAssignment)?.name ?? placeAssignment}`);
              // Refresh the assignment list in the parent so the new question appears immediately.
              await onBooksChanged().catch(() => {});
            } catch (e) {
              log(`Could not file it: ${e instanceof Error ? e.message : String(e)}`, 'error');
            }
          } else {
            // Say so. A question written but filed nowhere used to end on "renders clean", which
            // reads as done — the assignment was still empty and nothing said otherwise.
            log('Not filed — no assignment selected. The question is on disk only.', 'error');
          }
          await onDone(path);
          // Reflect only on a question that actually fought back, and only after it is safely
          // filed — a reflection failure must never cost a question that already renders.
          await reflect(localAttempts, path);
          return { ok: true, path };
        }
        log(`Attempt ${n}: ${errors[0]}`, 'error');
        if (stopped) return { ok: false, path };
        if (!shouldRetry(localAttempts)) {
          log(`Stopping after ${MAX_ATTEMPTS} attempts — over to you.`, 'error');
          // Still worth reflecting: five failed repairs is the strongest evidence there is that
          // something about this dialect is not yet written down.
          await reflect(localAttempts, path);
          return { ok: false, path };
        }
        reply = await turn(buildRepairPrompt(path, errors, localAttempts.length + 1, root, learnedRules));
        if (reply) log(reply, 'agent');
      }
    } finally {
      slug = savedSlug;
      brief = savedBrief;
    }
  }

  async function run() {
    if (!canRun) return;
    running = true;
    stopped = false;
    lines = [];
    attempts = [];
    finalPath = null;
    fatal = null;
    draft = '';
    onDraft('');
    learnedRules = await loadLearnedRules(root);
    // Fire and forget: it stops itself when `running` clears, and its failures are cosmetic.
    watchFile();
    try {
      // Open with what was actually asked for, so the log is readable after the fact without
      // scrolling back up to the form.
      log(
        [brief.trim(), link.trim() && `from ${link.trim()}`, imagePath.trim() && 'with a pasted example']
          .filter(Boolean)
          .join('\n') || `Write ${family}/${slug}.php`,
        'user',
      );
      await writeSingleQuestion(slug, brief, imagePath);
    } catch (e) {
      // Killing the CLI makes the in-flight turn reject. That is the stop working, not a failure.
      if (stopped) log('Stopped. The file is left wherever the agent got to.', 'error');
      else fatal = e instanceof Error ? e.message : String(e);
    } finally {
      running = false;
      runningSession = null;
      // Hand the pane back: the finished file is reachable through the normal selection, and a
      // stuck "Writing…" would outlive the run.
      onDraft(null);
    }
  }

  async function writeSet() {
    if (!canWriteSelected) return;
    running = true;
    stopped = false;
    lines = [];
    attempts = [];
    finalPath = null;
    fatal = null;
    draft = '';
    onDraft('');
    learnedRules = await loadLearnedRules(root);
    const list = planned!.filter((p) => selected.has(p.slug));
    watchFile();
    try {
      log(`Write ${list.length} selected question${list.length === 1 ? '' : 's'} from ${link.trim()}`, 'user');
      // One question that cannot be repaired in three attempts must not cost the other nine. Each
      // is written to its own file and verified on its own, so a failure is already isolated —
      // carry on and name the casualties at the end rather than abandoning the set.
      const failed: string[] = [];
      for (const p of list) {
        const result = await writeSingleQuestion(p.slug, p.brief);
        if (stopped) {
          log(`Stopped after ${list.indexOf(p) + 1} of ${list.length}.`, 'error');
          return;
        }
        if (!result.ok) failed.push(p.slug);
      }
      if (!failed.length) log(`All ${list.length} questions render clean.`, 'ok');
      else
        log(
          `${list.length - failed.length} of ${list.length} render clean. Left for you: ${failed.join(', ')}.`,
          'error',
        );
    } catch (e) {
      if (stopped) log('Stopped. The file is left wherever the agent got to.', 'error');
      else fatal = e instanceof Error ? e.message : String(e);
    } finally {
      running = false;
      runningSession = null;
      onDraft(null);
    }
  }
</script>

<!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
<div class="author" onpaste={onPaste}>
  <div class="context">
    <div class="row">
      <label>Family
        {#if newFamily}
          <input bind:value={family} disabled={running} spellcheck="false" placeholder="new-family" />
        {:else}
          <select bind:value={family} disabled={running}>
            {#each families as f (f)}<option value={f}>{f}</option>{/each}
          </select>
        {/if}
      </label>
      <button class="plus" title={newFamily ? 'Pick an existing family' : 'New family'} disabled={running} onclick={() => (newFamily = !newFamily)}>
        {newFamily ? '↩' : '+'}
      </button>
      <label>Slug<input bind:value={slug} disabled={running} spellcheck="false" /></label>
    </div>

    <div class="row">
      <label>Add to book <span class="opt">optional</span>
        <select bind:value={placeBook} disabled={running}>
          <option value="">— don't file it —</option>
          {#each placements as p (p.slug)}
            <option value={p.slug}>{p.title} ({p.items.length})</option>
          {/each}
        </select>
      </label>
      <button
        class="plus"
        title="New book"
        disabled={running || createBusy}
        onclick={() => { creating = creating === 'book' ? null : 'book'; newName = ''; createErr = null; }}
      >{creating === 'book' ? '↩' : '+'}</button>
    </div>

    {#if placeBook}
      <div class="row">
        <label>Assignment
          {#if chosenBook && chosenBook.items.length}
            <select bind:value={placeAssignment} disabled={running}>
              <option value="">— choose —</option>
              {#each chosenBook.items as it (it.path)}
                <option value={it.path}>{it.name}</option>
              {/each}
            </select>
          {:else}
            <span class="hint">No assignments yet — use + to make one.</span>
          {/if}
        </label>
        <button
          class="plus"
          title="New assignment in this book"
          disabled={running || createBusy}
          onclick={() => { creating = creating === 'assignment' ? null : 'assignment'; newName = ''; createErr = null; }}
        >{creating === 'assignment' ? '↩' : '+'}</button>
      </div>
    {/if}

    {#if creating}
      <div class="create">
        <label>{creating === 'book' ? 'New book title' : 'New assignment name'}
          <input
            bind:value={newName}
            disabled={createBusy}
            spellcheck="false"
            placeholder={creating === 'book' ? 'Applied Finite Math' : 'Ch 9.1 Matrices'}
            onkeydown={(e) => { if (e.key === 'Enter') { e.preventDefault(); createContainer(); } }}
          />
        </label>
        {#if creating === 'assignment'}
          <label>Kind
            <select bind:value={newKind} disabled={createBusy}>
              <option value="hw">hw</option>
              <option value="practice">practice</option>
              <option value="group">group</option>
              <option value="ind">ind</option>
            </select>
          </label>
        {/if}
        <button class="go" onclick={createContainer} disabled={createBusy || !newName.trim()}>
          {createBusy ? 'Creating…' : 'Create'}
        </button>
        {#if createErr}<p class="bad">{createErr}</p>{/if}
      </div>
    {/if}
    <p class="target" title={target}>{target || 'Set the MOM root first.'}</p>
  </div>

  {#if planned}
    <div class="planned">
      <div class="planned-head">
        <label class="all-none">
          <input
            type="checkbox"
            checked={allSelected}
            indeterminate={selected.size > 0 && !allSelected}
            onclick={toggleAll}
          />
          All
        </label>
        <span class="planned-count">{selected.size}/{planned.length} selected</span>
      </div>
      <ul>
        {#each planned as p (p.slug)}
          <li class="planned-item" class:exists={existingSlugs.has(p.slug)}>
            <label>
              <input type="checkbox" checked={selected.has(p.slug)} onclick={() => toggleOne(p.slug)} />
              <span class="slug">{p.slug}</span>
              <span class="brief">{p.brief}</span>
            </label>
            {#if existingSlugs.has(p.slug)}
              <span class="warn-badge" title="A question with this slug already exists; writing will overwrite it.">exists</span>
            {/if}
          </li>
        {/each}
      </ul>
      {#if !running}
        <button class="go write-selected" onclick={writeSet} disabled={!canWriteSelected}>
          Write selected ({selected.size})
        </button>
      {/if}
      {#if setError}<p class="bad">{setError}</p>{/if}
    </div>
  {/if}

  <div class="chat-log">
    {#each lines as l, i (i)}
      <ChatMessage role={l.role} text={l.text} />
    {/each}
    {#if running}<ChatMessage role="step" text="Working…" />{/if}
    {#if lines.length === 0 && !running && !planned}
      <p class="hint">Describe the question you want, paste a screenshot, or give a problem-set link.</p>
    {/if}
  </div>

  <div class="composer">
    <input bind:value={link} disabled={running || setBusy} spellcheck="false" placeholder="Problem-set link or URL" />
    <textarea
      rows="2"
      bind:value={brief}
      disabled={running || setBusy}
      placeholder="Describe the question…"
    ></textarea>

    <div class="shot">
      {#if imagePreview}
        <div class="thumb">
          <img src={imagePreview} alt="Pasted example question" />
          <button class="x" title="Remove" disabled={running || setBusy} onclick={clearImage}>×</button>
        </div>
      {:else}
        <div class="dropzone" class:err={!!pasteErr}>
          {pasteErr ?? 'Paste a screenshot (Ctrl+V)'}
        </div>
      {/if}
    </div>

    {#if running}
      <button class="go stop" onclick={stop}>Stop</button>
    {:else}
      <div class="plan-mode">
        <label class="mode-option">
          <input type="radio" name="planMode" value="exercises" bind:group={planMode} disabled={setBusy} />
          Use the section’s problem set
        </label>
        <label class="mode-option">
          <input type="radio" name="planMode" value="invent" bind:group={planMode} disabled={setBusy} />
          Invent questions from section content
        </label>
      </div>
      <div class="actions">
        <button class="go" onclick={run} disabled={!canRun}>Write question</button>
        <button class="go" onclick={planQuestions} disabled={!canPlan || setBusy || !planModeChosen}>
          {setBusy ? 'Planning…' : 'Plan questions'}
        </button>
      </div>
    {/if}
    {#if !sourced && !running && !setBusy}
      <p class="hint">Give it a link, a description, or a pasted example.</p>
    {/if}
    {#if finalPath}
      <p class="ok">Question renders clean. Open it in the browser to preview and check answers.</p>
    {/if}
    {#if fatal}<p class="bad">{fatal}</p>{/if}
  </div>
</div>

<style>
  .author { display: flex; flex-direction: column; gap: 10px; min-height: 0; flex: 1; }
  .context { display: flex; flex-direction: column; gap: 6px; flex-shrink: 0; padding-bottom: 8px; border-bottom: 1px solid rgba(128,128,128,.15); }
  .row { display: flex; gap: 6px; align-items: flex-end; }
  .row label { flex: 1; min-width: 0; }
  label { display: flex; flex-direction: column; gap: 2px; font-size: 11px; text-transform: uppercase;
          letter-spacing: .05em; opacity: .6; }
  input { font: inherit; font-size: 12px; padding: 4px 7px; border-radius: 6px; text-transform: none;
          border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; }
  input:disabled, select:disabled, textarea:disabled { opacity: .55; }
  select, textarea { font: inherit; font-size: 12px; padding: 4px 7px; border-radius: 6px; text-transform: none;
                     border: 1px solid rgba(128,128,128,.3); background: var(--color-bg-card); color: var(--color-text-primary); }
  option { background: var(--color-bg-card); color: var(--color-text-primary); }
  textarea { resize: vertical; }
  .opt { text-transform: none; letter-spacing: 0; opacity: .6; font-weight: 400; }
  .plus { flex-shrink: 0; width: 26px; height: 26px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3);
          background: transparent; color: inherit; cursor: pointer; font-size: 13px; }
  .plus:disabled { opacity: .45; cursor: default; }
  .hint { margin: auto 8px; font-size: 12px; opacity: .55; line-height: 1.5; text-align: center; }
  /* Inset so it reads as belonging to the picker it was opened from, not as another top-level field. */
  .create { display: flex; flex-direction: column; gap: 6px; padding: 8px; border-radius: 6px;
            border: 1px dashed rgba(128,128,128,.4); background: rgba(128,128,128,.05); }
  .shot { display: flex; flex-direction: column; gap: 2px; }
  .dropzone { font-size: 11px; opacity: .55; padding: 6px; border-radius: 6px; text-align: center;
              border: 1px dashed rgba(128,128,128,.4); }
  .dropzone.err { color: #b91c1c; opacity: 1; border-color: rgba(185,28,28,.5); }
  .thumb { position: relative; }
  .thumb img { display: block; width: 100%; max-height: 100px; object-fit: contain;
               border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: rgba(128,128,128,.08); }
  .x { position: absolute; top: 3px; right: 3px; width: 20px; height: 20px; border-radius: 50%;
       border: none; background: rgba(0,0,0,.6); color: #fff; cursor: pointer; font-size: 13px; line-height: 1; }
  .target { margin: 0; font-size: 11px; opacity: .5; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .go { padding: 6px 12px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3);
        background: transparent; color: inherit; cursor: pointer; font-size: 13px; }
  .go:disabled { opacity: .45; cursor: default; }
  .go.stop { border-color: rgba(185,28,28,.5); color: #b91c1c; }
  .chat-log { flex: 1; overflow-y: auto; min-height: 0; display: flex; flex-direction: column; gap: 10px; padding-right: 4px; }
  .composer { display: flex; flex-direction: column; gap: 6px; flex-shrink: 0; padding-top: 8px; border-top: 1px solid rgba(128,128,128,.15); }
  .ok { margin: 0; font-size: 12px; color: #1b5e20; }
  .bad { margin: 0; font-size: 12px; color: #b91c1c; }
  .actions { display: flex; gap: 6px; }
  .plan-mode { display: flex; gap: 12px; font-size: 12px; }
  .mode-option { display: flex; align-items: center; gap: 4px; cursor: pointer; text-transform: none; letter-spacing: 0; opacity: .85; }
  .mode-option input { cursor: pointer; }
  .planned { display: flex; flex-direction: column; gap: 6px; padding: 8px; border-radius: 6px; border: 1px dashed rgba(128,128,128,.4); background: rgba(128,128,128,.05); flex-shrink: 0; max-height: 220px; }
  .planned-head { display: flex; justify-content: space-between; align-items: center; font-size: 12px; }
  .all-none { display: flex; align-items: center; gap: 4px; cursor: pointer; text-transform: none; letter-spacing: 0; font-size: 12px; opacity: .8; }
  .planned-count { opacity: .6; font-size: 11px; }
  .planned ul { list-style: none; margin: 0; padding: 0; overflow-y: auto; display: flex; flex-direction: column; gap: 4px; }
  .planned-item { display: flex; align-items: center; gap: 6px; padding: 4px 6px; border-radius: 4px; background: rgba(128,128,128,.08); }
  .planned-item.exists { background: rgba(217,119,6,.08); }
  .planned-item label { display: flex; align-items: center; gap: 6px; flex: 1; min-width: 0; cursor: pointer; font-size: 12px; text-transform: none; letter-spacing: 0; opacity: 1; }
  .planned-item .slug { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; opacity: .9; flex-shrink: 0; }
  .planned-item .brief { opacity: .7; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .warn-badge { font-size: 10px; padding: 1px 5px; border-radius: 999px; background: rgba(217,119,6,.18); color: #b45309; flex-shrink: 0; }
  .write-selected { align-self: flex-start; }
</style>
