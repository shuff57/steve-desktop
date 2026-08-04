<script lang="ts">
  import type { Skill } from '../../lib/db';
  import { renderSkillPreview } from '../../lib/skill-parser';
  import { isAgentTaskSkill } from '../../lib/agent-skill';

  let { skill, onDelete, onToggle, onRun } = $props<{
    skill: Skill;
    onDelete: (id: string) => void;
    onToggle: (id: string, isActive: number) => void;
    onRun?: (skill: Skill) => void;
  }>();

  let expanded = $state(false);
  const runnable = $derived(!!onRun && isAgentTaskSkill(skill.content));

  function getSourceLabel(skill: Skill): string {
    if (skill.source === 'local') return 'Local';
    if (!skill.source || skill.source === 'created') return 'Created';
    return 'Marketplace';
  }

  function getSourceClass(skill: Skill): string {
    if (skill.source === 'local') return 'badge-local';
    if (!skill.source || skill.source === 'created') return 'badge-created';
    return 'badge-marketplace';
  }

  function handleDelete(e: Event) {
    e.stopPropagation();
    onDelete(skill.id);
  }

  /** Two-step inline confirm — window.confirm() draws behind the WebView2 window and is unreachable. */
  let confirmingDelete = $state(false);
  function armDelete(e: Event) {
    e.stopPropagation();
    confirmingDelete = true;
  }
  function cancelDelete(e: Event) {
    e.stopPropagation();
    confirmingDelete = false;
  }

  function handleToggle(e: Event) {
    e.stopPropagation();
    // Toggle between 1 and 0
    const newValue = skill.is_active === 1 ? 0 : 1;
    onToggle(skill.id, newValue);
  }

  function toggleExpanded() {
    expanded = !expanded;
  }
</script>

<div class="skill-card card" onclick={toggleExpanded} role="button" tabindex="0" onkeydown={(e) => e.key === 'Enter' && toggleExpanded()}>
  <div class="card-header">
    <div class="title-section">
      <h3>{skill.name}</h3>
      <span class="badge {getSourceClass(skill)}">{getSourceLabel(skill)}</span>
      {#if skill.url_pattern}
        <span class="badge badge-url" title="Auto-injects when browsing this site">🌐 {skill.url_pattern.length > 30 ? skill.url_pattern.slice(0, 30) + '…' : skill.url_pattern}</span>
      {/if}
    </div>
    <div class="actions">
      {#if runnable}
        <button class="run-btn" onclick={(e) => { e.stopPropagation(); onRun?.(skill); }} title="Run this task in the browser Agent">▶ Run</button>
      {/if}
      <!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
      <label class="switch" onclick={(e) => e.stopPropagation()} onkeydown={(e) => e.key === 'Enter' && e.stopPropagation()}>
        <input 
          type="checkbox" 
          checked={skill.is_active === 1} 
          onchange={handleToggle}
        >
        <span class="slider round"></span>
      </label>
      {#if confirmingDelete}
        <button class="confirm-delete" onclick={handleDelete} aria-label="Confirm delete skill">Confirm</button>
        <button class="btn-icon" onclick={cancelDelete} aria-label="Cancel delete">✕</button>
      {:else}
        <button class="btn-icon delete-btn" onclick={armDelete} aria-label="Delete skill">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        </button>
      {/if}
    </div>
  </div>

  <div class="card-body">
    <p class="description">{skill.description}</p>
    
    {#if expanded}
      <!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
      <div class="preview" role="group" onclick={(e) => e.stopPropagation()} onkeydown={(e) => e.key === 'Enter' && e.stopPropagation()}>
        <div class="preview-header">Preview</div>
        {#await renderSkillPreview(skill.content)}
          <div class="loading">Loading preview...</div>
        {:then html}
          <div class="markdown-content">
            <!-- eslint-disable-next-line svelte/no-at-html-tags -->
            {@html html}
          </div>
        {:catch error}
          <div class="error">Error rendering preview: {error.message}</div>
        {/await}
      </div>
    {/if}
  </div>
</div>

<style>
  .skill-card {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid var(--border-color);
  }

  .skill-card:hover {
    border-color: var(--color-primary);
    transform: translateY(-2px);
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--spacing-2);
  }

  .title-section {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-1);
    flex: 1;
    min-width: 0; /* Enable truncation */
  }

  h3 {
    margin: 0;
    font-size: 1.1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
  }

  .badge {
    align-self: flex-start;
    font-size: 0.7rem;
    padding: 2px 8px;
    border-radius: var(--radius-full);
    border: 1px solid currentColor;
  }

  .badge-local {
    color: var(--text-secondary);
    background-color: var(--color-accent-bg);
  }

  .badge-created {
    color: var(--color-success);
    background-color: var(--color-success-bg);
  }

  .badge-marketplace {
    color: var(--color-primary);
    background-color: var(--color-primary-bg);
  }

  .badge-url {
    color: var(--text-secondary);
    background-color: var(--color-accent-bg);
    font-size: 0.65rem;
  }

  .actions {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);
    flex-shrink: 0;
  }

  .description {
    font-size: 0.9rem;
    color: var(--text-secondary);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
  }

  .preview {
    margin-top: var(--spacing-4);
    padding-top: var(--spacing-4);
    border-top: 1px dashed var(--border-color);
    cursor: default;
  }

  .preview-header {
    font-size: 0.8rem;
    text-transform: uppercase;
    color: var(--text-tertiary);
    margin-bottom: var(--spacing-2);
    font-weight: 600;
  }

  .markdown-content {
    font-size: 0.9rem;
    line-height: 1.5;
    color: var(--text-primary);
  }

  /* Switch Toggle */
  .switch {
    position: relative;
    display: inline-block;
    width: 36px;
    height: 20px;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--bg-primary);
    border: 1px solid var(--border-color);
    transition: .3s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 2px;
    bottom: 2px;
    background-color: var(--text-tertiary);
    transition: .3s;
    border-radius: 50%;
  }

  input:checked + .slider {
    background-color: var(--color-success-bg);
    border-color: var(--color-success);
  }

  input:checked + .slider:before {
    transform: translateX(16px);
    background-color: var(--color-success);
  }

  .slider.round {
    border-radius: 20px;
  }

  .run-btn {
    background: transparent;
    border: 1px solid var(--color-success, #30a46c);
    color: var(--color-success, #30a46c);
    border-radius: var(--radius-md);
    padding: 3px 10px;
    font-size: 0.78rem;
    cursor: pointer;
  }

  .delete-btn {
    background: transparent;
    border: none;
    color: var(--text-tertiary);
    padding: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-md);
    transition: all 0.2s;
  }

  .delete-btn:hover {
    color: var(--color-danger);
    background-color: var(--color-danger-bg);
  }

  .confirm-delete {
    background: var(--color-danger-bg);
    color: var(--color-danger);
    border: 1px solid var(--color-danger);
    padding: 3px 10px;
    font-size: 0.75rem;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
  }
  .confirm-delete:hover {
    background: var(--color-danger);
    color: #fff;
  }

  /* Markdown Content Styles */
  .markdown-content :global(h1), 
  .markdown-content :global(h2), 
  .markdown-content :global(h3) {
    margin-top: 1em;
    margin-bottom: 0.5em;
    font-size: 1.1em;
  }

  .markdown-content :global(p) {
    margin-bottom: 0.8em;
  }

  .markdown-content :global(ul), 
  .markdown-content :global(ol) {
    padding-left: 1.5em;
    margin-bottom: 0.8em;
  }

  .markdown-content :global(code) {
    background-color: var(--bg-primary);
    padding: 0.2em 0.4em;
    border-radius: 3px;
    font-family: var(--font-mono);
    font-size: 0.85em;
  }

  .markdown-content :global(pre) {
    background-color: var(--bg-primary);
    padding: 1em;
    border-radius: var(--radius-md);
    overflow-x: auto;
  }
</style>
