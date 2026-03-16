<script lang="ts">
  import { type ChatDiscoveryState, runChatDiscovery } from '../../lib/discovery-intent';

  let { 
    chatState = $bindable(), 
    onChatSubmit = () => {} 
  } = $props<{
    chatState: ChatDiscoveryState;
    onChatSubmit?: () => Promise<void>;
  }>();

  let chatInput = $state('');

  async function handleSubmit() {
    if (!chatInput.trim()) return;
    const msg = chatInput;
    chatInput = '';
    
    // Optimistic update
    chatState.messages = [...chatState.messages, { role: 'user', content: msg, timestamp: new Date().toISOString() }];

    await onChatSubmit();
  }
</script>

<div class="chat-mode-ui">
  <div class="chat-messages">
    {#each chatState.messages as msg}
      <div class="msg {msg.role}">
        {msg.content}
      </div>
    {/each}
    {#if chatState.messages.length === 0}
      <p class="placeholder">Describe the page discovery to help the AI...</p>
    {/if}
  </div>
  <div class="chat-input-area">
    <input 
      type="text" 
      bind:value={chatInput} 
      onkeydown={(e) => e.key === 'Enter' && handleSubmit()}
      placeholder="e.g. 'This is a YouTube video page with a play button'"
    >
    <button class="btn-sm" onclick={handleSubmit}>Send</button>
  </div>
</div>

<style>
  .chat-mode-ui {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-2);
  }
  
  .chat-messages {
    max-height: 150px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: var(--bg-secondary);
    padding: 8px;
    border-radius: var(--radius-sm);
  }

  .placeholder {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin: 0;
    font-style: italic;
    padding: 8px;
  }

  .msg {
    padding: 6px 10px;
    border-radius: 12px;
    font-size: 0.9rem;
    max-width: 85%;
  }

  .msg.user {
    align-self: flex-end;
    background: var(--color-primary);
    color: var(--color-primary-text);
  }

  .msg.assistant {
    align-self: flex-start;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
  }

  .chat-input-area {
    display: flex;
    gap: 4px;
  }

  .chat-input-area input {
    flex: 1;
    padding: 6px;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
  }

  .btn-sm {
    padding: 4px 12px;
    font-size: 0.85rem;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all 0.2s;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
  }

  .btn-sm:hover {
    background: var(--bg-hover);
  }
</style>
