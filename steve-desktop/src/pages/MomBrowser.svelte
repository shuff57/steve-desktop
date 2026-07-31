<script lang="ts">
  /**
   * mom-island browser — read-only question browser.
   * Questions view is TWO panes: one list that drills families -> questions in place (a laptop has
   * limited horizontal room, and the preview is the pane that needs it), plus the preview on the
   * right — PHP source, or the sandbox-rendered question with an optional answer key.
   * Books view drills the same way: book -> assignments -> questions, each level replacing the
   * last, plus the shared preview.
   * MOM_ROOT is read from a Tauri setting; on first run the user picks a folder.
   *
   * Phase 3: "New question" per family opens a modal where the user picks a
   * template, sees the draft, and pastes it into MyOpenMath via CDP.
   */
  import { onMount } from 'svelte';
  import { getSetting, setSetting } from '../lib/db';
  import { momIsland, type MOMFamily, type MOMQuestion, type MomQuestionDetail, type MomBook, type MomBookEntry, getTemplates, type MomTemplate, findTemplate } from '../integrations/mom';
  import type { PlanView } from '../integrations/mom/author';
  import MomDraft from './MomDraft.svelte';
  import { withAnswerKey } from '../integrations/mom/answer-key';
  import { prepareRenderHtml } from '../integrations/mom/render-html';
  import { questionHealth } from '../integrations/mom/health';
  import { withCheckData, injectChecker, checkableParts } from '../integrations/mom/answer-check';
  import BookShelf from '../components/mom/BookShelf.svelte';
  import MomAuthor from '../components/mom/MomAuthor.svelte';
  import ActionShell from '../components/shell/ActionShell.svelte';

  const ROOT_SETTING = 'mom_root';
  const DRAFTS_DIR_SETTING = 'mom_drafts_dir';
  // Stateless IMathAS render sandbox (self-hosted, no login). POST question PHP -> full HTML page.
  // ponytail: one hardcoded URL; lift to a setting if we ever need per-env sandboxes.
  const SANDBOX_URL = 'https://mom.huffpalmer.fyi/';

  let momRoot = $state<string | null>(null);
  let rootInput = $state('');
  let savingRoot = $state(false);
  let loading = $state(false);
  let err = $state<string | null>(null);

  let families = $state<MOMFamily[]>([]);
  let selectedFamily = $state<string | null>(null);
  let selectedQuestion = $state<MomQuestionDetail | null>(null);
  let loadingQuestion = $state(false);
  let questionErr = $state<string | null>(null);

  // Books = the assignment manifests (the organizing spine). A top-level view toggle
  // switches the left/center panes between the question bank and the book/assignment list.
  let view = $state<'questions' | 'books'>('questions');
  let books = $state<MomBook[]>([]);

  /** `applied-finite-math` -> `Applied Finite Math`. Works for any future book slug. */
  function bookTitle(slug: string): string {
    return slug
      .split(/[-_/]/)
      .filter(Boolean)
      .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
      .join(' ');
  }

  /** Which book the Books view has drilled into; null = showing the book list. */
  let selectedBookSlug = $state<string | null>(null);
  /** Declared books, so a course with no assignments yet is still listed and selectable. */
  let bookRegistry = $state<MomBookEntry[]>([]);

  // Group by book; within a book order by kind then name, so homework, group work and practice
  // stay together instead of interleaving alphabetically.
  const KIND_ORDER = ['hw', 'practice', 'group', 'ind'];
  const booksByBook = $derived.by(() => {
    // An assignment appears under EVERY book it belongs to, so sharing one across courses shows
    // up in both rather than forcing a duplicate file.
    const groups = new Map<string, MomBook[]>();
    for (const b of books) {
      for (const key of b.books.length ? b.books : ['']) {
        if (!groups.has(key)) groups.set(key, []);
        groups.get(key)!.push(b);
      }
    }
    // Declared-but-empty books still list, or a new course could never be filled — it would be
    // invisible until it already had an assignment.
    for (const entry of bookRegistry) {
      if (!groups.has(entry.slug)) groups.set(entry.slug, []);
    }

    const declared = new Map(bookRegistry.map((e) => [e.slug, e]));
    return [...groups.entries()]
      .map(([slug, items]) => ({
        slug,
        title: declared.get(slug)?.title ?? (slug ? bookTitle(slug) : 'Ungrouped'),
        archived: declared.get(slug)?.archived === true,
        items: items.sort((x, y) => {
          const k = KIND_ORDER.indexOf(x.kind ?? '') - KIND_ORDER.indexOf(y.kind ?? '');
          return k !== 0 ? k : x.name.localeCompare(y.name);
        }),
      }))
      // Archived courses sort last: they are kept for reference, not taught from.
      .sort((a, b) => Number(a.archived) - Number(b.archived) || a.title.localeCompare(b.title));
  });

  const currentBook = $derived(booksByBook.find((g) => g.slug === selectedBookSlug) ?? null);

  function selectBookGroup(slug: string) {
    selectedBookSlug = slug;
    selectedBook = null;
  }
  function backToBooks() {
    selectedBookSlug = null;
    selectedBook = null;
  }
  function backToAssignments() {
    selectedBook = null;
  }
  let selectedBook = $state<MomBook | null>(null);

  // Phase 3: draft modal state. When `draftingFamily` is set, the modal opens
  // for that family. draftsDir is the working dir the user has set.
  let draftingFamily = $state<string | null>(null);
  let draftsDir = $state<string | null>(null);
  let draftsDirInput = $state('');
  let savingDraftsDir = $state(false);
  const templates = getTemplates();

  const currentFamily = $derived<MOMFamily | null>(
    families.find((f) => f.name === selectedFamily) ?? null,
  );

  // Preview pane can show the raw PHP or the sandbox-rendered question ("as if in MyOpenMath").
  let renderMode = $state<'php' | 'rendered'>('php');
  // Preview-only: appends the computed answers + solution guide into the SAME render pass.
  let showKey = $state(false);
  // Type an answer in and be told whether it is right. Same one-pass rule as the key: the expected
  // values must come from the render you are typing into, or randomization makes them disagree.
  let checkAnswers = $state(false);
  /**
   * Recolour the rendered question for a dark app.
   *
   * Defaults to the app's own theme, but stays a toggle: MyOpenMath serves students a LIGHT page, so
   * "dark" is comfort and "light" is fidelity. Read once — the theme lives on <html>, and following
   * it live would fight a deliberate override.
   */
  let darkRender = $state(document.documentElement.getAttribute('data-theme') === 'dark');
  let renderedHtml = $state('');
  /** What the sandbox returned, before the checker is injected — what health is judged on. */
  let sandboxHtml = $state('');
  let rendering = $state(false);
  let renderErr = $state<string | null>(null);
  let lastRenderedPath = '';

  // Revision rail. Collapsed by default so it costs nothing until wanted.
  let railCollapsed = $state(true);
  let railWidth = $state(420);
  /**
   * Width and collapsed-ness outlive the visit, the way the browser drawer's already do — dragging
   * the rail back to a working width on every launch is the sort of thing you stop doing by just
   * leaving it collapsed. Guarded by `railLoaded` so the default 420 cannot save over the stored
   * value before the restore lands.
   */
  /**
   * The rail may not squeeze the preview below a working width. The plan list and the rendered
   * question both live in that pane, and a rail dragged wider than its neighbour was leaving the
   * plan 181px to show ten briefs in.
   */
  let panesWidth = $state(0);
  const BROWSE_W = 260;
  const PREVIEW_MIN = 340;
  const railMax = $derived(panesWidth ? Math.max(320, panesWidth - BROWSE_W - PREVIEW_MIN - 24) : null);
  const RAIL_SETTING = 'mom_rail_state';
  let railLoaded = false;
  let railSaveTimer: ReturnType<typeof setTimeout> | undefined;
  $effect(() => {
    void railWidth;
    void railCollapsed;
    if (!railLoaded) return;
    clearTimeout(railSaveTimer);
    railSaveTimer = setTimeout(() => {
      setSetting(RAIL_SETTING, JSON.stringify({ width: railWidth, collapsed: railCollapsed })).catch(() => {});
    }, 300);
  });
  /**
   * The file the writer is currently producing, mirrored here so you watch it appear in the preview
   * rather than reading tool-call chatter. `null` = no run in flight, `''` = started but nothing on
   * disk yet — a distinction the pane shows differently.
   */
  let authorDraft = $state<string | null>(null);
  /**
   * The writer's current plan, shown in the preview pane rather than the rail — ten slugs with
   * their briefs need the width, and truncating a brief to "Identify the population and…" is
   * exactly the part you need to read before ticking it.
   */
  let planView = $state<PlanView | null>(null);
  const showPlan = $derived(authorDraft === null && !!planView && !selectedQuestion);
  const planAllSelected = $derived(
    !!planView && planView.planned.length > 0 && planView.selected.length === planView.planned.length,
  );
  /** Which CLI the rail's agents run on. Restored and persisted by ProviderSelector itself. */
  let agentProvider = $state('');
  let agentModel = $state('');

  /** Re-read the edited file and force a re-render. The agent's summary is not evidence. */
  async function reloadSelected() {
    const q = selectedQuestion;
    if (!q || !momRoot || !selectedFamily) return;
    try {
      selectedQuestion = await momIsland.methods.getQuestion(selectedFamily, q.slug, momRoot);
      lastRenderedPath = '';
    } catch (e) {
      questionErr = e instanceof Error ? e.message : String(e);
    }
  }

  async function renderQuestion(contents: string) {
    rendering = true;
    renderErr = null;
    try {
      const res = await fetch(SANDBOX_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'text/plain' },
        body: contents,
      });
      if (!res.ok) throw new Error(`sandbox HTTP ${res.status}`);
      // The sandbox's MathJax config makes `(` and `$` math delimiters, which italicises ordinary
      // prose and currency. Repair it before the iframe runs MathJax.
      const prepared = prepareRenderHtml(await res.text(), darkRender);
      // Health judges what the SANDBOX returned, before our own additions. The checker script
      // mentions `$answerbox` in a comment, and scanning the injected page reported the question
      // as broken because of our own markup.
      sandboxHtml = prepared;
      renderedHtml = checkAnswers ? injectChecker(prepared) : prepared;
    } catch (e) {
      renderErr = e instanceof Error ? e.message : String(e);
      renderedHtml = '';
      sandboxHtml = '';
    } finally {
      rendering = false;
    }
  }

  // A broken question still renders: the sandbox answers 200 and prints its diagnostics into the
  // body, so `renderErr` stays null and the iframe looks fine. Of the 418 bank questions, 21 are
  // broken this way. Surface it above the frame instead of leaving it buried in the render.
  const health = $derived(
    sandboxHtml && selectedQuestion
      ? questionHealth(selectedQuestion.contents, sandboxHtml)
      : { errors: [], warnings: [] },
  );

  // Render lazily: only when the Rendered tab is showing, and only once per question. The key flag
  // is part of the cache token, so toggling it re-renders instead of showing the previous pass.
  // Re-rendering also re-randomizes, which is fine — question and key come from the SAME pass.
  $effect(() => {
    const q = selectedQuestion;
    const token = q
      ? `${q.path}|${showKey ? 'key' : 'plain'}|${checkAnswers ? 'chk' : ''}|${darkRender ? 'dark' : 'light'}`
      : '';
    if (renderMode === 'rendered' && q && token !== lastRenderedPath) {
      lastRenderedPath = token;
      let src = q.contents;
      if (showKey) src = withAnswerKey(src);
      if (checkAnswers) src = withCheckData(src);
      renderQuestion(src);
    }
  });

  // How many parts the checker can actually judge. A `numfunc` answer needs MyOpenMath to decide
  // whether two expressions are equivalent, so it is left unchecked rather than judged by string.
  const checkable = $derived(selectedQuestion ? checkableParts(selectedQuestion.contents) : 0);

  async function saveRoot() {
    if (savingRoot) return;
    const trimmed = rootInput.trim();
    if (!trimmed) return;
    savingRoot = true;
    err = null;
    try {
      await setSetting(ROOT_SETTING, trimmed);
      momRoot = trimmed;
      await loadIndex();
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      savingRoot = false;
    }
  }

  async function clearRoot() {
    await setSetting(ROOT_SETTING, '');
    momRoot = null;
    rootInput = '';
    families = [];
    selectedFamily = null;
    selectedQuestion = null;
  }

  /**
   * Re-read the bank.
   *
   * `background` skips the loading state, and that is not cosmetic: the panes are rendered inside
   * `{:else if loading}`, so raising the flag UNMOUNTS the whole pane tree — including the writer
   * rail mid-run. A refresh triggered by a finished write was destroying the component that
   * triggered it, taking its log and its pending reflection with it. The spinner belongs to the
   * first load, when there is genuinely nothing to show yet.
   */
  async function loadIndex(background = false) {
    if (!momRoot) return;
    if (!background) {
      loading = true;
      selectedFamily = null;
      selectedQuestion = null;
    }
    err = null;
    try {
      const [idx, bookList, registry] = await Promise.all([
        momIsland.methods.browse(momRoot),
        momIsland.methods.listBooks(momRoot).catch(() => [] as MomBook[]),
        momIsland.methods.listBookRegistry(momRoot).catch(() => [] as MomBookEntry[]),
      ]);
      families = idx.families;
      books = bookList;
      bookRegistry = registry;
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
      families = [];
      books = [];
    } finally {
      loading = false;
    }
  }

  /**
   * Re-read the manifests after a membership change and re-point `selectedBook` at the fresh
   * object — the old one is a stale copy, so the chips would keep showing the previous books.
   */
  async function reloadBooks() {
    if (!momRoot) return;
    const keep = selectedBook?.path;
    books = await momIsland.methods.listBooks(momRoot).catch(() => books);
    bookRegistry = await momIsland.methods.listBookRegistry(momRoot).catch(() => bookRegistry);
    selectedBook = keep ? (books.find((b) => b.path === keep) ?? null) : null;
  }

  /** After the author finishes a question, load it into the normal preview pane so it stays visible. */
  async function handleAuthorDone(path: string) {
    // Background: this fires FROM the writer rail, and a foreground reload would unmount it.
    await loadIndex(true);
    if (!momRoot) return;
    const rel = path.replace(/[\\/]+/g, '/').replace(`${momRoot.replace(/[\\/]+/g, '/')}/`, '');
    const m = rel.match(/^questions\/([^/]+)\/(.+)\.php$/i);
    if (!m) return;
    const [_, family, slug] = m;
    try {
      selectedFamily = family;
      selectedQuestion = await momIsland.methods.getQuestion(family, slug, momRoot);
      view = 'questions';
      renderMode = 'rendered';
      authorDraft = null; // hand preview back to the normal browser
    } catch (e) {
      questionErr = e instanceof Error ? e.message : String(e);
    }
  }

  /** Open a question referenced by a book entry. filePath is `questions/<family>/<slug>`. */
  async function openBookQuestion(filePath: string) {
    const rel = filePath.replace(/^questions\//, '');
    const slash = rel.indexOf('/');
    if (slash < 0 || !momRoot) return;
    const family = rel.slice(0, slash);
    const slug = rel.slice(slash + 1);
    loadingQuestion = true;
    questionErr = null;
    try {
      selectedQuestion = await momIsland.methods.getQuestion(family, slug, momRoot);
    } catch (e) {
      questionErr = e instanceof Error ? e.message : String(e);
      selectedQuestion = null;
    } finally {
      loadingQuestion = false;
    }
  }

  async function selectQuestion(q: MOMQuestion) {
    if (!momRoot || !selectedFamily) return;
    loadingQuestion = true;
    questionErr = null;
    try {
      selectedQuestion = await momIsland.methods.getQuestion(selectedFamily, q.slug, momRoot);
    } catch (e) {
      questionErr = e instanceof Error ? e.message : String(e);
      selectedQuestion = null;
    } finally {
      loadingQuestion = false;
    }
  }

  function selectFamily(name: string) {
    selectedFamily = name;
    selectedQuestion = null;
    questionErr = null;
  }

  /** Drill back out to the family list. Clears the selected question too, so the preview pane
   *  does not keep showing a question from a family that is no longer on screen. */
  function backToFamilies() {
    selectedFamily = null;
    selectedQuestion = null;
    questionErr = null;
  }

  onMount(async () => {
    let root = await getSetting(ROOT_SETTING).catch(() => null);
    // First run: default to the in-repo mom-content/ so the bank is there without a paste.
    if (!root) {
      const fallback = await momIsland.methods.getDefaultRoot().catch(() => '');
      if (fallback) {
        root = fallback;
        await setSetting(ROOT_SETTING, fallback).catch(() => {});
      }
    }
    if (root) {
      momRoot = root;
      rootInput = root;
      await loadIndex();
    }
    const drafts = await getSetting(DRAFTS_DIR_SETTING).catch(() => null);
    if (drafts) {
      draftsDir = drafts;
      draftsDirInput = drafts;
    }
    try {
      const raw = await getSetting(RAIL_SETTING).catch(() => null);
      if (raw) {
        const s = JSON.parse(raw) as { width?: number; collapsed?: boolean };
        if (typeof s.width === 'number') railWidth = s.width;
        if (typeof s.collapsed === 'boolean') railCollapsed = s.collapsed;
      }
    } finally {
      railLoaded = true;
    }
  });

  // Re-load if the user has manually edited the disk between visits. Not auto-polled.
  function refresh() {
    if (momRoot) loadIndex();
  }

  async function saveDraftsDir() {
    if (savingDraftsDir) return;
    const trimmed = draftsDirInput.trim();
    if (!trimmed) return;
    savingDraftsDir = true;
    err = null;
    try {
      await setSetting(DRAFTS_DIR_SETTING, trimmed);
      draftsDir = trimmed;
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      savingDraftsDir = false;
    }
  }

  function openDraftModal(family: string) {
    draftingFamily = family;
  }

  function closeDraftModal() {
    draftingFamily = null;
  }

  async function handleDraftCreated() {
    draftingFamily = null;
    // No re-browse: drafts live outside the source repo.
  }

  function templateFor(family: string): MomTemplate | null {
    return findTemplate(family);
  }
</script>

<div class="browser">
  <header>
    <div>
      <h1>MOM Question Browser</h1>
      <p class="sub">
        {#if momRoot}Browsing <code>{momRoot}</code>{:else}No root set — paste a path to start.{/if}
      </p>
    </div>
    <div class="header-actions">
      {#if momRoot}
        <div class="view-toggle">
          <button class:active={view === 'questions'} onclick={() => (view = 'questions')}>Questions</button>
          <button class:active={view === 'books'} onclick={() => (view = 'books')}>Books ({books.length})</button>
        </div>
        <button class="refresh" onclick={refresh} disabled={loading}>↻ Refresh</button>
        <button class="change" onclick={clearRoot}>Change root</button>
      {/if}
    </div>
  </header>

  {#if err}<p class="err">{err}</p>{/if}

  {#if !momRoot}
    <form class="root-form" onsubmit={(e) => { e.preventDefault(); saveRoot(); }}>
      <label>
        MOM root folder
        <input
          type="text"
          bind:value={rootInput}
          placeholder="C:\Users\shuff\Documents\GitHub\mom"
          required
        />
      </label>
      <button type="submit" disabled={savingRoot || rootInput.trim().length === 0}>
        {savingRoot ? 'Saving…' : 'Save'}
      </button>
    </form>
    <p class="empty">MOM_ROOT is unset. Paste the path to the folder that contains <code>questions/&lt;family&gt;/</code>.</p>
  {:else if loading}
    <p class="empty">Loading…</p>
  {:else if families.length === 0}
    <p class="empty">No families found under <code>{momRoot}/questions</code>. Is this the mom repo root?</p>
  {:else}
    <div class="panes" bind:clientWidth={panesWidth}>
      {#if view === 'questions'}
        <!-- One column, drilled: families REPLACE themselves with their questions rather than
             opening a second sidebar. A laptop only has so much horizontal room, and the preview
             pane is the one that actually needs it. -->
        <section class="browse">
          {#if !currentFamily}
            <h2>Families</h2>
            <ul>
              {#each families as f (f.name)}
                <li>
                  <button class="fam" onclick={() => selectFamily(f.name)}>
                    <span class="fam-name">{f.name}</span>
                    <span class="fam-count">{f.count}</span>
                  </button>
                  {#if templateFor(f.name)}
                    <button
                      class="new-q"
                      title="New question in {f.name}"
                      onclick={() => openDraftModal(f.name)}
                    >+ New</button>
                  {/if}
                </li>
              {/each}
            </ul>
          {:else}
            <div class="drill-head">
              <button class="back" onclick={backToFamilies}>← Families</button>
              {#if templateFor(currentFamily.name)}
                <button
                  class="new-q"
                  title="New question in {currentFamily.name}"
                  onclick={() => openDraftModal(currentFamily.name)}
                >+ New</button>
              {/if}
            </div>
            <h2>{currentFamily.name} · {currentFamily.count}</h2>
            {#if currentFamily.questions.length === 0}
              <p class="empty">No questions in this family yet.</p>
            {:else}
              <ul>
                {#each currentFamily.questions as q (q.slug)}
                  <li>
                    <button
                      class="q"
                      class:active={selectedQuestion?.slug === q.slug}
                      onclick={() => selectQuestion(q)}
                    >
                      <span class="q-slug">{q.slug}</span>
                      {#if q.hasManifest}<span class="badge">manifest</span>{/if}
                    </button>
                  </li>
                {/each}
              </ul>
            {/if}
          {/if}
        </section>
      {:else}
        <!-- Same one-column drill as Families: book -> assignments -> questions, each level
             REPLACING the last. Two panes instead of three, so the preview keeps the width. -->
        <section class="browse">
          {#if books.length === 0}
            <h2>Books</h2>
            <p class="empty">No assignment manifests under <code>books/</code>.</p>
          {:else if !currentBook}
            <h2>Books</h2>
            <ul>
              {#each booksByBook as group (group.slug)}
                <li>
                  <button class="fam" onclick={() => selectBookGroup(group.slug)}>
                    <span class="fam-name">{group.title}</span>
                    {#if group.archived}<span class="badge archived">archived</span>{/if}
                    <span class="fam-count">{group.items.length}</span>
                  </button>
                </li>
              {/each}
            </ul>
          {:else if !selectedBook}
            <div class="drill-head">
              <button class="back" onclick={backToBooks}>← Books</button>
            </div>
            <h2>{currentBook.title} · {currentBook.items.length}</h2>
            {#if currentBook.items.length === 0}
              <p class="empty">
                No assignments in this book yet. Open one under another book and use
                <strong>Change</strong> to add it here.
              </p>
            {/if}
            <ul>
              {#each currentBook.items as b (b.path)}
                <li>
                  <button class="fam" onclick={() => (selectedBook = b)}>
                    <span class="fam-name">{b.name}</span>
                    <span class="fam-count">{b.questions.length}</span>
                  </button>
                </li>
              {/each}
            </ul>
          {:else}
            <div class="drill-head">
              <button class="back" onclick={backToAssignments}>← {currentBook.title}</button>
            </div>
            <h2>{selectedBook.name}</h2>
            <p class="stats muted">
              {selectedBook.kind ?? ''}{selectedBook.chapterSection ? ` · §${selectedBook.chapterSection}` : ''}{selectedBook.cid ? ` · cid ${selectedBook.cid}` : ''}
            </p>
            <BookShelf
              book={selectedBook}
              allBooks={booksByBook.map((g) => g.slug)}
              root={momRoot ?? ''}
              onSaved={reloadBooks}
            />
            {#if selectedBook.questions.length === 0}
              <p class="empty">This assignment has no questions yet.</p>
            {:else}
              <ul>
                {#each selectedBook.questions as q (q.slot ?? q.filePath)}
                  <li>
                    <button class="q" class:active={selectedQuestion?.path?.replace(/\\/g, '/').endsWith(q.filePath.replace(/^questions\//, ''))} onclick={() => openBookQuestion(q.filePath)}>
                      <span class="q-slug">{q.slot ? `${q.slot}. ` : ''}{q.title ?? q.filePath}</span>
                      {#if q.qid}<span class="badge">qid {q.qid}</span>{/if}
                      {#if q.verifyStatus}<span class="badge status-{q.verifyStatus}">{q.verifyStatus}</span>{/if}
                    </button>
                  </li>
                {/each}
              </ul>
            {/if}
          {/if}
        </section>
      {/if}

      <section class="preview">
        <div class="preview-head">
          <h2>{authorDraft !== null ? 'Writing…' : showPlan ? 'Planned questions' : 'Preview'}</h2>
          {#if showPlan && planView}
            <div class="preview-toggles">
              <label class="key-toggle" title="Select or clear every question in the plan">
                <input
                  type="checkbox"
                  checked={planAllSelected}
                  indeterminate={planView.selected.length > 0 && !planAllSelected}
                  onclick={planView.toggleAll}
                />
                All
              </label>
              <span class="stats muted">{planView.selected.length}/{planView.planned.length} selected</span>
            </div>
          {:else if selectedQuestion && authorDraft === null}
            <div class="preview-toggles">
              {#if renderMode === 'rendered'}
                <label class="key-toggle" title="Append the computed answers and solution guide to this same render">
                  <input type="checkbox" bind:checked={showKey} />
                  Key
                </label>
                <label
                  class="key-toggle"
                  title={checkable > 0
                    ? 'Type an answer into a box and check it against this render'
                    : 'This question’s answer types need MyOpenMath to judge equivalence'}
                >
                  <input type="checkbox" bind:checked={checkAnswers} disabled={checkable === 0} />
                  Check
                </label>
                <label class="key-toggle" title="MyOpenMath serves students a light page — turn this off to see it as they do">
                  <input type="checkbox" bind:checked={darkRender} />
                  Dark
                </label>
              {/if}
              <div class="view-toggle">
                <button class:active={renderMode === 'php'} onclick={() => (renderMode = 'php')}>PHP</button>
                <button class:active={renderMode === 'rendered'} onclick={() => (renderMode = 'rendered')}>Rendered</button>
              </div>
            </div>
          {/if}
        </div>
        {#if authorDraft !== null}
          <!-- A run is in flight: the preview belongs to the file being written, not to whatever
               question happened to be selected before it started. -->
          {#if authorDraft === ''}
            <p class="empty">Waiting for the agent to write the file…</p>
          {:else}
            <pre class="live">{authorDraft}</pre>
          {/if}
        {:else if showPlan && planView}
          <!-- The plan, at the width its briefs need. Ticking here is the same selection the rail's
               "Write selected" acts on — the writer owns it, this only renders it. -->
          <ul class="plan">
            {#each planView.planned as p (p.slug)}
              {@const exists = planView.existing.includes(p.slug)}
              <li class="plan-row" class:exists>
                <label title={p.brief}>
                  <input
                    type="checkbox"
                    checked={planView.selected.includes(p.slug)}
                    onclick={() => planView?.toggleOne(p.slug)}
                  />
                  <!-- Slug over brief, not beside it: a non-shrinking monospace slug next to the
                       brief left the brief a few characters wide and wrapping one letter per line. -->
                  <span class="plan-text">
                    <span class="plan-slug">{p.slug}</span>
                    <span class="plan-brief">{p.brief}</span>
                  </span>
                </label>
                {#if exists}
                  <span class="badge exists-badge" title="A question with this slug already exists; writing will overwrite it.">exists</span>
                {/if}
              </li>
            {/each}
          </ul>
        {:else if loadingQuestion}
          <p class="empty">Loading…</p>
        {:else if questionErr}
          <p class="err">{questionErr}</p>
        {:else if !selectedQuestion}
          <p class="empty">Select a question to preview.</p>
        {:else if renderMode === 'rendered'}
          {#if rendering}
            <p class="empty">Rendering in sandbox…</p>
          {:else if renderErr}
            <p class="err">Sandbox unreachable: {renderErr}</p>
            <p class="muted small">Renders at <code>{SANDBOX_URL}</code> — verified reachable 2026-07-30, so check the tunnel/origin rather than DNS.</p>
          {:else}
            {#if health.errors.length || health.warnings.length}
              <div class="health" class:broken={health.errors.length > 0}>
                <strong>{health.errors.length ? 'This question is broken' : 'Check this question'}</strong>
                <ul>
                  {#each health.errors as e (e)}<li>{e}</li>{/each}
                  {#each health.warnings as w (w)}<li class="warn">{w}</li>{/each}
                </ul>
              </div>
            {:else}
              <div class="health clean">
                <strong>Rendered clean</strong>
              </div>
            {/if}
            <iframe class="render-frame" class:dark={darkRender} title="Rendered question" srcdoc={renderedHtml} sandbox="allow-scripts"></iframe>
          {/if}
        {:else}
          {#if selectedQuestion.manifest.total > 0}
            <p class="stats">
              Manifest: {selectedQuestion.manifest.completed}/{selectedQuestion.manifest.total} completed
              {#if selectedQuestion.manifest.pending}· {selectedQuestion.manifest.pending} pending{/if}
            </p>
          {:else}
            <p class="stats muted">No manifest in this folder.</p>
          {/if}
          <pre>{selectedQuestion.contents}</pre>
        {/if}
      </section>

      <!-- Same shell as the embedded browser's action panel, in its 'column' variant: one set of
           chrome (tabs, engine picker, collapse, resize, session/context footer) instead of a
           second hand-built copy that drifts from it. -->
      <ActionShell
        variant="column"
        title="Question rail"
        bind:isCollapsed={railCollapsed}
        bind:width={railWidth}
        bind:provider={agentProvider}
        bind:model={agentModel}
        providerDisabled={authorDraft !== null}
        maxWidth={railMax}
      >
        <MomAuthor
          root={momRoot ?? ''}
          sandboxUrl={SANDBOX_URL}
          families={families.map((f) => f.name)}
          placements={booksByBook.map((g) => ({
            slug: g.slug,
            title: g.title,
            items: g.items.map((b) => ({ path: b.path, name: b.name })),
          }))}
          provider={agentProvider}
          model={agentModel}
          onDone={handleAuthorDone}
          onDraft={(c) => (authorDraft = c)}
          onBooksChanged={reloadBooks}
          selectedPath={selectedQuestion?.path ?? null}
          selectedLabel={selectedQuestion ? `${selectedFamily ?? ''}/${selectedQuestion.slug}` : null}
          selectedContents={selectedQuestion?.contents ?? ''}
          onRevised={reloadSelected}
          onClearSelection={() => (selectedQuestion = null)}
          onPlan={(v) => (planView = v)}
        />
      </ActionShell>
    </div>

    <footer class="drafts-config">
      {#if draftsDir}
        <span class="muted">Drafts dir: <code>{draftsDir}</code></span>
        <button class="change" onclick={() => { draftsDir = null; draftsDirInput = ''; }}>Change</button>
      {:else}
        <form class="drafts-form" onsubmit={(e) => { e.preventDefault(); saveDraftsDir(); }}>
          <label>
            Drafts working dir
            <input
              type="text"
              bind:value={draftsDirInput}
              placeholder="C:\Users\shuff\AppData\Roaming\steve-desktop\mom-drafts"
              required
            />
          </label>
          <button type="submit" disabled={savingDraftsDir || draftsDirInput.trim().length === 0}>
            {savingDraftsDir ? 'Saving…' : 'Save'}
          </button>
        </form>
        <p class="muted small">Required to enable "New question" — drafts live outside the source repo.</p>
      {/if}
    </footer>
  {/if}
</div>

{#if draftingFamily && momRoot && draftsDir}
  <MomDraft
    family={draftingFamily}
    momRoot={momRoot}
    draftsDir={draftsDir}
    onclose={closeDraftModal}
    oncreated={handleDraftCreated}
  />
{/if}

<style>
  .browser { padding: 24px; height: 100%; box-sizing: border-box; display: flex; flex-direction: column; overflow: hidden; }
  header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-shrink: 0; }
  h1 { margin: 0 0 4px; font-size: 22px; }
  .sub { margin: 0; opacity: .7; font-size: 13px; }
  .sub code { font-size: 12px; }
  .header-actions { display: flex; gap: 8px; align-items: center; }
  .view-toggle { display: inline-flex; border: 1px solid var(--color-border, #3333); border-radius: 6px; overflow: hidden; }
  .view-toggle button { background: none; border: none; padding: 4px 10px; font: inherit; font-size: 13px; color: var(--color-text-secondary, #888); cursor: pointer; }
  .view-toggle button.active { background: var(--color-primary-hover, #4a9eff); color: var(--color-primary-text, #fff); }
  .refresh, .change { padding: 6px 12px; border-radius: 6px; cursor: pointer; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; font-size: 13px; }
  .refresh:disabled, .change:disabled { opacity: .5; cursor: default; }
  .change { opacity: .7; }
  .root-form { display: flex; gap: 8px; align-items: end; margin-top: 16px; }
  .root-form label { display: flex; flex-direction: column; gap: 4px; flex: 1; font-size: 12px; opacity: .8; }
  .root-form input { padding: 7px 10px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; font-size: 13px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
  .root-form button { padding: 7px 14px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; cursor: pointer; font-size: 13px; }
  .root-form button:disabled { opacity: .5; cursor: default; }
  .err { color: #b91c1c; font-size: 13px; }
  .empty { opacity: .6; text-align: center; padding: 40px 16px; }
  .empty code { font-size: 12px; }

  /* Three panes in a row; the rail is ActionShell, which sets its own width and owns its
     drag handle, so nothing here sizes it. */
  .panes { display: flex; gap: 12px; flex: 1; min-height: 0; margin-top: 16px; }
  .panes > section.browse { flex: 0 0 260px; min-width: 0; }
  .panes > section.preview { flex: 1 1 auto; min-width: 0; }
  .drill-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px; flex-shrink: 0; }
  .preview-toggles { display: flex; align-items: center; gap: 10px; }
  .key-toggle { display: flex; align-items: center; gap: 5px; font-size: 12px; opacity: .8; cursor: pointer; user-select: none; }
  .key-toggle input { cursor: pointer; }
  .back { padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; cursor: pointer; font-size: 12px; }
  .back:hover { background: rgba(128,128,128,.12); }
  section { background: rgba(128,128,128,.06); border-radius: 8px; padding: 12px; overflow: hidden; display: flex; flex-direction: column; }
  h2 { margin: 0 0 8px; font-size: 13px; opacity: .7; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; }
  ul { list-style: none; margin: 0; padding: 0; overflow-y: auto; display: flex; flex-direction: column; gap: 4px; }
  .fam, .q { display: flex; width: 100%; justify-content: space-between; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 6px; border: 1px solid transparent; background: transparent; color: inherit; cursor: pointer; text-align: left; font-size: 13px; }
  .fam:hover, .q:hover { background: rgba(128,128,128,.12); }
  .fam.active, .q.active { background: rgba(59,130,246,.18); border-color: rgba(59,130,246,.5); }
  .fam-count { font-size: 11px; opacity: .6; }
  .q-slug { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .badge { font-size: 10px; padding: 1px 6px; border-radius: 999px; background: rgba(34,197,94,.18); color: #22c55e; flex-shrink: 0; }
  /* verify_status colouring: in-mom = live/green (default badge), pending/other = amber. */
  .badge.status-pending, .badge.status-draft { background: rgba(245,158,11,.18); color: #f59e0b; }

  .preview-head { display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
  .preview-head h2 { margin: 0; }
  /* The frame's own background shows during load and behind a short page, so it has to match the
     mode the page inside is rendered in — otherwise a dark render sits on a white sheet. */
  .render-frame { flex: 1; width: 100%; border: none; border-radius: 6px; background: #fff; }
  .render-frame.dark { background: #141220; }
  /* Amber = renders but is suspect; red = the sandbox refused it. Both sit ABOVE the frame,
     because the whole point is that the render itself looks convincing. */
  .health { flex-shrink: 0; margin-bottom: 8px; padding: 8px 10px; border-radius: 6px; font-size: 12px;
            background: rgba(217,119,6,.12); border: 1px solid rgba(217,119,6,.45); color: #b45309; }
  .health.broken { background: rgba(185,28,28,.12); border-color: rgba(185,28,28,.5); color: #b91c1c; }
  .health strong { display: block; margin-bottom: 4px; font-size: 12px; }
  .health ul { margin: 0; padding-left: 16px; }
  .health li { margin: 2px 0; line-height: 1.4; overflow-wrap: anywhere; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
  .health li.warn { font-family: inherit; }
  .health.clean { background: rgba(34,197,94,.12); border-color: rgba(34,197,94,.45); color: #15803d; }
  .preview .muted.small { font-size: 11px; opacity: .6; margin: 6px 0 0; }
  .preview pre { flex: 1; overflow: auto; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 12.5px; line-height: 1.5; padding: 12px; border-radius: 6px; background: rgba(0,0,0,.25); margin: 0; white-space: pre; }
  /* The file as it is being written. Accent rather than a spinner: the content itself is the
     progress indicator, and it grows as the agent works. */
  .preview pre.live { border-left: 2px solid rgba(59,130,246,.7); }
  /* The plan list. Briefs wrap instead of ellipsing — reading the brief is the whole reason it
     moved out of the rail. */
  .plan { flex: 1; overflow-y: auto; list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 4px; }
  .plan-row { display: flex; align-items: flex-start; gap: 8px; padding: 8px 10px; border-radius: 6px; background: rgba(128,128,128,.08); }
  .plan-row.exists { background: rgba(217,119,6,.1); }
  .plan-row label { display: flex; align-items: flex-start; gap: 8px; flex: 1; min-width: 0; cursor: pointer; font-size: 13px; line-height: 1.45; }
  .plan-row input { margin-top: 3px; flex-shrink: 0; cursor: pointer; }
  .plan-text { display: flex; flex-direction: column; gap: 2px; flex: 1; min-width: 0; }
  .plan-slug { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 12px; opacity: .9; overflow-wrap: anywhere; }
  /* The planner writes a paragraph per question — enough to write it from without the section.
     Three lines is enough to tell them apart and tick the right ones; the rest is on hover, and
     all of it goes to the writer regardless. Ten unclamped briefs was a 4000px wall. */
  .plan-brief { opacity: .75; overflow-wrap: anywhere; display: -webkit-box; -webkit-box-orient: vertical;
                -webkit-line-clamp: 3; line-clamp: 3; overflow: hidden; }
  .exists-badge { background: rgba(217,119,6,.18); color: #b45309; align-self: flex-start; }

  .stats { margin: 0 0 8px; font-size: 12px; opacity: .85; }
  .stats.muted { opacity: .5; }

  .new-q { padding: 0 8px; font-size: 11px; border-radius: 6px; border: 1px dashed rgba(128,128,128,.4); background: transparent; color: inherit; cursor: pointer; opacity: .7; }
  .new-q:hover { opacity: 1; border-color: rgba(59,130,246,.5); color: #3b82f6; }

  .drafts-config { margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(128,128,128,.15); display: flex; flex-direction: column; gap: 6px; flex-shrink: 0; }
  .drafts-config .muted { font-size: 12px; opacity: .7; }
  .drafts-config .muted.small { font-size: 11px; }
  .drafts-config code { font-size: 11px; }
  .drafts-form { display: flex; gap: 8px; align-items: end; }
  .drafts-form label { display: flex; flex-direction: column; gap: 4px; flex: 1; font-size: 12px; opacity: .8; }
  .drafts-form input { padding: 6px 10px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; font-size: 12px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
  .drafts-form button { padding: 6px 12px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; cursor: pointer; font-size: 12px; }
  .drafts-form button:disabled { opacity: .5; cursor: default; }
  .drafts-config .change { padding: 4px 10px; font-size: 11px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; cursor: pointer; }
</style>
