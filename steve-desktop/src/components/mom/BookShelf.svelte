<script lang="ts">
  /**
   * Which books an assignment belongs to.
   *
   * Membership is a list, not a single choice: the same probability homework is reasonable in more
   * than one course, and duplicating the file to achieve that would leave two things to keep in
   * step. Toggling here rewrites only the `books` key in the manifest.
   */
  import type { MomBook } from '../../integrations/mom/loader';
  import { saveBookMembership } from '../../integrations/mom/book-membership';

  let { book, allBooks = [], root, onSaved = () => {} } = $props<{
    book: MomBook;
    allBooks?: string[];
    root: string;
    onSaved?: () => void | Promise<void>;
  }>();

  let open = $state(false);
  let busy = $state(false);
  let err = $state<string | null>(null);
  let newBook = $state('');

  /** `applied-finite-math` -> `Applied Finite Math`. */
  function title(slug: string): string {
    return slug.split(/[-_/]/).filter(Boolean).map((w) => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
  }

  // Known books plus whatever this assignment already claims, so a membership pointing at a book
  // no one else uses still shows a ticked box rather than vanishing from the list.
  const choices = $derived([...new Set([...allBooks, ...book.books])].filter(Boolean).sort());

  async function apply(next: string[]) {
    if (busy) return;
    busy = true;
    err = null;
    try {
      await saveBookMembership(root, book.path, next);
      await onSaved();
    } catch (e) {
      err = e instanceof Error ? e.message : String(e);
    } finally {
      busy = false;
    }
  }

  function toggle(slug: string) {
    const has = book.books.includes(slug);
    // Refuse to remove the last one: an assignment in no book cannot be found again from this UI.
    if (has && book.books.length === 1) {
      err = 'An assignment has to stay in at least one book.';
      return;
    }
    apply(has ? book.books.filter((b: string) => b !== slug) : [...book.books, slug]);
  }

  function addNew() {
    const slug = newBook.trim().toLowerCase().replace(/\s+/g, '-');
    if (!slug) return;
    newBook = '';
    apply([...book.books, slug]);
  }
</script>

<div class="shelf">
  <div class="row">
    <span class="label">Books</span>
    {#each book.books as b (b)}<span class="chip">{title(b)}</span>{/each}
    {#if book.books.length === 0}<span class="chip none">none</span>{/if}
    <button class="edit" onclick={() => (open = !open)} disabled={busy}>
      {open ? 'Done' : 'Change'}
    </button>
  </div>

  {#if open}
    <div class="editor">
      {#each choices as slug (slug)}
        <label class="opt">
          <input
            type="checkbox"
            checked={book.books.includes(slug)}
            disabled={busy}
            onchange={() => toggle(slug)}
          />
          {title(slug)}
        </label>
      {/each}
      <div class="add">
        <input
          type="text"
          placeholder="New book, e.g. Integrated Math 1"
          bind:value={newBook}
          disabled={busy}
          onkeydown={(e) => e.key === 'Enter' && addNew()}
        />
        <button onclick={addNew} disabled={busy || !newBook.trim()}>Add</button>
      </div>
      {#if busy}<p class="note">Saving…</p>{/if}
      {#if err}<p class="note err">{err}</p>{/if}
    </div>
  {/if}
</div>

<style>
  .shelf { margin: 6px 0 10px; }
  .row { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; }
  .label { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; opacity: .55; }
  .chip { font-size: 12px; padding: 1px 8px; border-radius: 10px; background: rgba(59,130,246,.16); }
  .chip.none { background: rgba(128,128,128,.16); opacity: .7; }
  .edit { font-size: 12px; padding: 1px 9px; border-radius: 10px; border: 1px solid rgba(128,128,128,.35);
          background: transparent; color: inherit; cursor: pointer; }
  .edit:disabled { opacity: .5; cursor: default; }
  .editor { margin-top: 8px; padding: 8px 10px; border: 1px solid rgba(128,128,128,.25); border-radius: 8px;
            display: flex; flex-direction: column; gap: 5px; }
  .opt { display: flex; align-items: center; gap: 6px; font-size: 13px; }
  .add { display: flex; gap: 6px; margin-top: 4px; }
  .add input { flex: 1; font: inherit; font-size: 12px; padding: 3px 7px; border-radius: 6px;
               border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; }
  .add button { font-size: 12px; padding: 3px 10px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3);
                background: transparent; color: inherit; cursor: pointer; }
  .add button:disabled { opacity: .45; cursor: default; }
  .note { margin: 2px 0 0; font-size: 12px; opacity: .7; }
  .note.err { color: #b91c1c; opacity: 1; }
</style>
