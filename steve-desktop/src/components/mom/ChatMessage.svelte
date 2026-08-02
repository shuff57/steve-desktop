<script lang="ts">
  /**
   * One chat bubble. Used by both the Revise rail and the Write tab so the two agent jobs read as
   * the same kind of conversation.
   */
  let {
    role,
    text,
  } = $props<{
    role: 'user' | 'agent' | 'step' | 'ok' | 'error';
    text: string;
  }>();

  const label: Record<string, string> = {
    user: 'You',
    agent: 'Agent',
    step: '',
    ok: 'Sandbox',
    error: 'Error',
  };
</script>

<div class="wrap" class:user={role === 'user'} class:agent={role === 'agent' || role === 'error' || role === 'ok'} class:system={role === 'step'}>
  {#if role !== 'step'}
    <span class="meta">{label[role]}</span>
  {/if}
  <div class="bubble {role}">{text}</div>
</div>

<style>
  .wrap { display: flex; flex-direction: column; gap: 2px; max-width: 92%; }
  .wrap.user { align-self: flex-end; align-items: flex-end; }
  .wrap.agent { align-self: flex-start; align-items: flex-start; }
  .wrap.system { align-self: center; align-items: center; max-width: 100%; }
  .meta { font-size: 11px; opacity: .55; padding: 0 2px; }
  .bubble { font-size: 13px; line-height: 1.45; padding: 8px 11px; border-radius: 12px; white-space: pre-wrap; overflow-wrap: anywhere; }
  .bubble.user { background: rgba(59,130,246,.18); border-bottom-right-radius: 4px; }
  .bubble.agent { background: rgba(128,128,128,.14); border-bottom-left-radius: 4px; }
  .bubble.error { background: rgba(185,28,28,.14); color: #b91c1c; border-bottom-left-radius: 4px; }
  .bubble.ok { background: rgba(27,94,32,.14); color: #1b5e20; border-bottom-left-radius: 4px; }
  .bubble.step { padding: 0; background: none; font-size: 12px; opacity: .55; }
</style>
