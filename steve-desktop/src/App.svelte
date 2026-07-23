<script lang="ts">
  /* biome-ignore-all lint/correctness/noUnusedImports: Svelte template uses these imports */
  /* biome-ignore-all lint/correctness/noUnusedVariables: Svelte template uses script bindings */
  /* biome-ignore assist/source/organizeImports: Keep explicit grouped imports for readability */
  import { onMount, onDestroy } from 'svelte';
  import Dashboard from './pages/Dashboard.svelte';
  import Browser from './pages/Browser.svelte';
  import Skills from './pages/Skills.svelte';
  import Artifacts from './pages/Artifacts.svelte';
  import SiteProfiles from './pages/SiteProfiles.svelte';
  import Settings from './pages/settings/Settings.svelte';
  import SetupWizard from './pages/SetupWizard.svelte';
  import MomBrowser from './pages/MomBrowser.svelte';
  import OgreGrading from './pages/OgreGrading.svelte';
  import OgreRubrics from './pages/OgreRubrics.svelte';
  import OgreHistory from './pages/OgreHistory.svelte';
  import NavSection from './components/NavSection.svelte';
  import {
    CollapseIcon,
    DashboardIcon,
    BrowserIcon,
    SkillsIcon,
    ArtifactsIcon,
    SiteProfilesIcon,
    SettingsIcon,
    MomIcon,
    OgreIcon,
  } from './components/icons/index';
  import { Sun, Moon } from 'lucide-svelte';
  import { getSetting } from './lib/db';

  let currentPage = $state('dashboard');
  let sidebarCollapsed = $state(false);
  let setupComplete = $state(false);
  let loading = $state(true);
  
  let theme = $state(localStorage.getItem('steve-theme') || 'dark');

  $effect(() => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('steve-theme', theme);
  });

  function toggleTheme() {
    theme = theme === 'dark' ? 'light' : 'dark';
  }

  $effect(() => {
    // If we have a browser page with native webview, we'd handle it here
    if (currentPage === 'browser') {
      window.dispatchEvent(new CustomEvent('steve:sidebar-changed'));
    }
  });

  onMount(async () => {
    try {
      const setting = await getSetting('setup_complete');
      setupComplete = setting === 'true';
    } catch {
      setupComplete = false;
    } finally {
      loading = false;
    }

    window.addEventListener('steve:navigate', handleNavigateEvent as EventListener);
  });

  onDestroy(() => {
    window.removeEventListener('steve:navigate', handleNavigateEvent as EventListener);
  });

  function handleNavigateEvent(e: CustomEvent<string>) {
    if (e.detail) navigate(e.detail);
  }

  function navigate(page: string) {
    currentPage = page;
    if (page === 'browser') {
      sidebarCollapsed = true;
      window.dispatchEvent(new CustomEvent('steve:sidebar-changed'));
    } else {
      sidebarCollapsed = false;
    }
  }

  function toggleSidebar() {
    sidebarCollapsed = !sidebarCollapsed;

    if (currentPage === 'browser') {
      window.dispatchEvent(new CustomEvent('steve:sidebar-changed'));
    }
  }

  function handleSetupComplete() {
    setupComplete = true;
    navigate('dashboard');
  }
</script>

{#if loading}
  <div class="loading-screen">
    <div class="spinner"></div>
    <p>Loading S.T.E.V.E...</p>
  </div>
{:else if !setupComplete}
  <SetupWizard oncomplete={handleSetupComplete} />
{:else}
  <div class="app-container">
    <aside class="sidebar" class:collapsed={sidebarCollapsed}>
      <div class="sidebar-header">
        <div class="brand" class:hidden={sidebarCollapsed}>S.T.E.V.E</div>
        <button class="toggle-btn" onclick={toggleSidebar} aria-label="Toggle Sidebar">
          <CollapseIcon collapsed={sidebarCollapsed} />
        </button>
      </div>
      <nav>
        <!-- Dashboard – standalone -->
        <button class="nav-item" class:active={currentPage === 'dashboard'} onclick={() => navigate('dashboard')} title="Dashboard">
          <span class="icon"><DashboardIcon /></span>
          <span class="label">Dashboard</span>
        </button>

        <!-- Primary group -->
        <div class="nav-group">
          <span class="nav-group-label">Primary</span>
          <button class="nav-item" class:active={currentPage === 'browser'} onclick={() => navigate('browser')} title="Browse">
            <span class="icon"><BrowserIcon /></span>
            <span class="label">Browse</span>
          </button>
        </div>

        <!-- Tools group -->
        <div class="nav-group">
          <span class="nav-group-label">Tools</span>
          <button class="nav-item" class:active={currentPage === 'skills'} onclick={() => navigate('skills')} title="AI Skills">
            <span class="icon"><SkillsIcon /></span>
            <span class="label">AI Skills</span>
          </button>
          <button class="nav-item" class:active={currentPage === 'artifacts'} onclick={() => navigate('artifacts')} title="Artifacts">
            <span class="icon"><ArtifactsIcon /></span>
            <span class="label">Artifacts</span>
          </button>
          <button class="nav-item" class:active={currentPage === 'profiles'} onclick={() => navigate('profiles')} title="Site Profiles">
            <span class="icon"><SiteProfilesIcon /></span>
            <span class="label">Site Profiles</span>
          </button>
          <!-- MOM stays flat: its Draft view is a modal that needs a selected family,
               so there is no second destination to nest. Give it a NavSection when it
               grows one. -->
          <button class="nav-item" class:active={currentPage === 'mom'} onclick={() => navigate('mom')} title="MOM Question Bank">
            <span class="icon"><MomIcon /></span>
            <span class="label">MOM</span>
          </button>
          <NavSection
            label="OGRE"
            icon={OgreIcon}
            collapsed={sidebarCollapsed}
            {currentPage}
            onnavigate={navigate}
            items={[
              { id: 'ogre-grading', label: 'Grading', title: 'Batch grading' },
              { id: 'ogre-rubrics', label: 'Rubrics', title: 'Rubric library' },
              { id: 'ogre-history', label: 'History', title: 'Past grading sessions' },
            ]}
          />
        </div>

        <!-- System group -->
        <div class="nav-group">
          <span class="nav-group-label">System</span>
          <button class="nav-item" class:active={currentPage === 'settings'} onclick={() => navigate('settings')} title="Settings">
            <span class="icon"><SettingsIcon /></span>
            <span class="label">Settings</span>
          </button>
        </div>
      </nav>
      
      <div class="spacer"></div>
      
      <div class="sidebar-footer">
        <button class="nav-item theme-toggle" onclick={toggleTheme} title="Toggle Theme">
          <span class="icon">{#if theme === 'dark'}<Sun />{:else}<Moon />{/if}</span>
          <span class="label">Theme</span>
        </button>
      </div>
    </aside>

    <main class="content">
      <!-- Browser stays MOUNTED across navigation: unmounting it fires onDestroy, which tears
           down every embedded webview and kills any running agent task. Hidden (not destroyed)
           when another page is showing; it hides its own native webview via the `active` prop. -->
      <div class="browser-holder" class:hidden={currentPage !== 'browser'}>
        <Browser active={currentPage === 'browser'} />
      </div>

      {#if currentPage === 'dashboard'}
        <Dashboard onnavigate={navigate} />
      {:else if currentPage === 'skills'}
        <Skills />
      {:else if currentPage === 'artifacts'}
        <Artifacts />
      {:else if currentPage === 'profiles'}
        <SiteProfiles />
      {:else if currentPage === 'mom'}
        <MomBrowser />
      {:else if currentPage === 'ogre-grading'}
        <OgreGrading />
      {:else if currentPage === 'ogre-rubrics'}
        <OgreRubrics />
      {:else if currentPage === 'ogre-history'}
        <OgreHistory />
      {:else if currentPage === 'settings'}
        <Settings />
      {:else if currentPage !== 'browser'}
        <!-- Placeholders for other pages for now -->
        <div style="padding: 2rem; color: var(--text-primary);">
          <h2>{currentPage}</h2>
          <p>Page coming soon...</p>
        </div>
      {/if}
    </main>
  </div>
{/if}

<style>
  /* Global styles for the app container */
  .loading-screen {
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: var(--bg-primary);
    color: var(--text-primary);
  }
  
  .spinner {
    border: 4px solid var(--border-color);
    border-radius: 50%;
    border-top: 4px solid var(--color-primary);
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .app-container {
    display: flex;
    height: 100vh;
    width: 100vw;
    overflow: hidden;
  }

  .sidebar {
    width: var(--sidebar-width-expanded, 250px);
    background: var(--bg-sidebar);
    color: var(--text-on-sidebar);
    display: flex;
    flex-direction: column;
    padding: 1rem;
    box-shadow: var(--shadow-md);
    transition: width var(--sidebar-transition, 0.3s);
    overflow: hidden;
  }

  .sidebar.collapsed {
    width: var(--sidebar-width-collapsed, 60px);
    padding: 1rem 0.5rem;
  }

  .sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    height: 40px;
  }

  .brand {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--text-on-sidebar);
    white-space: nowrap;
    overflow: hidden;
    transition: opacity 0.2s, width 0.2s;
  }

  .brand.hidden {
    opacity: 0;
    width: 0;
    display: none;
  }
  
  .toggle-btn {
    background: none;
    border: none;
    color: var(--sidebar-text);
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-sm);
    transition: all 0.2s;
    margin-left: auto;
  }

  .toggle-btn:hover {
    background-color: var(--sidebar-hover-bg);
    color: var(--sidebar-text-active);
  }
  
  .sidebar.collapsed .toggle-btn {
    margin: 0 auto;
  }

  nav {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    overflow-y: auto;
    overflow-x: hidden;
  }

  /* Nav group container */
  .nav-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-top: 0.5rem;
  }

  /* Section label above each group */
  .nav-group-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--sidebar-text-muted);
    padding: 0.25rem 1rem 0.1rem;
    white-space: nowrap;
    overflow: hidden;
    transition: opacity 0.2s;
  }

  .sidebar.collapsed .nav-group-label {
    opacity: 0;
    height: 0;
    padding: 0;
  }

  .nav-item {
    background: none;
    border: none;
    color: var(--sidebar-text);
    text-align: left;
    padding: 0.75rem 1rem;
    cursor: pointer;
    font-size: 1rem;
    border-radius: var(--radius-sm);
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    white-space: nowrap;
    overflow: hidden;
    width: 100%;
  }
  
  .sidebar.collapsed .nav-item {
    padding: 0.75rem;
    justify-content: center;
  }

  .icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .label {
    transition: opacity 0.2s;
  }
  
  .sidebar.collapsed .label {
    opacity: 0;
    width: 0;
    display: none;
  }

  .nav-item:hover {
    background: var(--sidebar-hover-bg);
    color: var(--sidebar-text-active);
  }

  .nav-item.active {
    background: var(--sidebar-active-bg);
    color: var(--sidebar-text-active);
    font-weight: 500;
  }
  
  .spacer {
    flex: 1;
  }
  
  .sidebar-footer {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid var(--sidebar-border);
  }

  .content {
    flex: 1;
    overflow-y: auto;
    background-color: var(--bg-primary);
  }

  /* display:contents makes the holder transparent to layout so Browser lays out exactly as a
     direct child of .content; hidden removes the whole subtree (Browser's own effect hides the
     native webview separately). */
  .browser-holder {
    display: contents;
  }
  .browser-holder.hidden {
    display: none;
  }
</style>
