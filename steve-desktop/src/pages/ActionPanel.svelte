<script lang="ts">
  /* biome-ignore-all lint/correctness/noUnusedImports: Svelte template uses imported components */
  import AutomateRunner from '../components/grading/AutomateRunner.svelte';
  import SkillRunner from '../components/grading/SkillRunner.svelte';
  import SiteMapper from '../components/grading/SiteMapper.svelte';
  import TeachMode from '../components/grading/TeachMode.svelte';
  import OgreGrader from '../components/grading/OgreGrader.svelte';
  import ActionShell from '../components/shell/ActionShell.svelte';

  let {
    isCollapsed = $bindable(false),
    width = $bindable(400),
    pageUrl = '',
    tabId = '',
    // With one panel mounted per browser tab, only the active one may handle
    // global keyboard shortcuts — otherwise Ctrl+B toggles once per instance.
    active = true,
  } = $props();

  // `teach` is deliberately absent: recording a skill is one job with one front door, and that
  // door is the Skills page ("Record Skill" → nav 'teach-record'). The mode still renders below —
  // recording has to happen beside the browser it watches — it just is not a tab you pick.
  const MODES = [
    { id: 'agent', icon: '🤖', label: 'Agent' },
    { id: 'discovery', icon: '🔍', label: 'Discovery' },
    { id: 'skills', icon: '▶', label: 'Skills' },
    { id: 'ogre', icon: '📝', label: 'Grading' },
  ];

  let activeMode = $state('agent');

  // The OGRE sidebar entry opens grading here rather than on its own route: "load students
  // from page" means the page on screen, so the controls have to sit beside it.
  //
  // Two paths because the drawer may have been closed when the request was made, in which
  // case no panel existed to hear the event: the live event covers an open drawer, the
  // sessionStorage handoff covers one opening for the first time. Read once and clear, so
  // it steers only the navigation that set it.
  const pendingMode = typeof sessionStorage !== 'undefined' ? sessionStorage.getItem('steve:panel-mode') : null;
  if (pendingMode) {
    activeMode = pendingMode;
    sessionStorage.removeItem('steve:panel-mode');
  }

  $effect(() => {
    const open = (e: Event) => {
      if (!active) return;
      const mode = (e as CustomEvent<{ mode?: string }>).detail?.mode;
      if (mode) {
        activeMode = mode;
        sessionStorage.removeItem('steve:panel-mode');
      }
    };
    window.addEventListener('steve:action-panel', open);
    return () => window.removeEventListener('steve:action-panel', open);
  });

  // The shell owns the engine selection; held here only to pass down to each mode.
  let activeProvider = $state('');
  let activeModel = $state('');

  // Status pill for the shell header — the child running a task binds these. There is
  // deliberately no activity feed here: the runner renders its own, and mirroring it into the
  // shell put the same run log on screen twice.
  let agentStatus = $state('idle');
  let agentStatusText = $state('');
</script>

<ActionShell
  modes={MODES}
  bind:activeMode
  bind:isCollapsed
  bind:width
  bind:provider={activeProvider}
  bind:model={activeModel}
  bind:agentStatus
  bind:agentStatusText
  {active}
>
  {#if activeMode === 'agent'}
    <AutomateRunner
      provider={activeProvider}
      model={activeModel}
      bind:agentStatus
      bind:agentStatusText
        />
  {:else if activeMode === 'skills'}
    <SkillRunner provider={activeProvider} model={activeModel} />
  {:else if activeMode === 'teach'}
    <TeachMode {pageUrl} provider={activeProvider} model={activeModel} />
  {:else if activeMode === 'ogre'}
    <OgreGrader {pageUrl} provider={activeProvider} model={activeModel} />
  {:else}
    <SiteMapper {pageUrl} provider={activeProvider} model={activeModel} />
  {/if}
</ActionShell>
