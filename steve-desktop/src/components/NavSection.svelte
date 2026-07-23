<script lang="ts">
  /**
   * An expandable sidebar entry: a parent tool with its own sub-pages.
   *
   * Built for OGRE (Grading / Rubrics / History) and MOM (Question Bank / Draft), which
   * are the two islands with more than one page. The rest of the sidebar stays flat —
   * a group only earns nesting once it has somewhere to nest.
   *
   * Expansion is derived, not just toggled: a section holding the active page is always
   * open, so navigating from elsewhere (a Dashboard shortcut, a steve:navigate event)
   * can never leave the current page hidden inside a collapsed section.
   */
  interface NavChild {
    id: string;
    label: string;
    title?: string;
  }

  let {
    label,
    icon,
    items,
    currentPage,
    collapsed = false,
    onnavigate,
  }: {
    label: string;
    // ponytail: lucide-svelte icons are legacy class components, so Svelte 5's
    // `Component` type rejects them. Narrow this once the icon pack ships Svelte 5 types.
    // biome-ignore lint/suspicious/noExplicitAny: see above
    icon: any;
    items: NavChild[];
    currentPage: string;
    collapsed?: boolean;
    onnavigate: (id: string) => void;
  } = $props();

  const holdsActive = $derived(items.some((i) => i.id === currentPage));
  let userOpened = $state(false);
  const open = $derived(holdsActive || userOpened);

  const Icon = $derived(icon);

  function toggle() {
    // With the sidebar collapsed there is no room for children — go to the first page
    // instead of opening a submenu the user cannot read.
    if (collapsed) {
      onnavigate(items[0]!.id);
      return;
    }
    userOpened = !open;
  }
</script>

<button
  class="nav-item nav-parent"
  class:active={holdsActive}
  onclick={toggle}
  title={label}
  aria-expanded={collapsed ? undefined : open}
>
  <span class="icon"><Icon /></span>
  <span class="label">{label}</span>
  {#if !collapsed}
    <span class="chevron" class:open aria-hidden="true">›</span>
  {/if}
</button>

{#if open && !collapsed}
  <div class="nav-children">
    {#each items as item (item.id)}
      <button
        class="nav-item nav-child"
        class:active={currentPage === item.id}
        onclick={() => onnavigate(item.id)}
        title={item.title ?? item.label}
      >
        <span class="label">{item.label}</span>
      </button>
    {/each}
  </div>
{/if}

<style>
  .chevron {
    margin-left: auto;
    transition: transform 0.15s ease;
    opacity: 0.6;
    font-size: 1.1em;
    line-height: 1;
  }
  .chevron.open {
    transform: rotate(90deg);
  }
  .nav-children {
    display: flex;
    flex-direction: column;
  }
  /* Indent rail: makes the parent/child relationship readable without extra chrome. */
  .nav-child {
    padding-left: 2.6rem;
    font-size: 0.92em;
    position: relative;
  }
  .nav-child::before {
    content: '';
    position: absolute;
    left: 1.45rem;
    top: 0;
    bottom: 0;
    width: 1px;
    background: var(--border, currentColor);
    opacity: 0.25;
  }
</style>
