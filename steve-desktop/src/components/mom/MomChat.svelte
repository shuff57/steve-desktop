<script lang="ts">
  /**
   * Revision chat rail for the MOM question preview.
   *
   * Collapses to a thin strip so it costs no horizontal space until it is wanted — the same reason
   * the family list drills in place rather than opening a second column.
   *
   * The agent edits the .php on disk through the CLI's own tools (that is what `bypassPermissions`
   * buys), so after a turn the caller re-reads the file and re-renders. Question files are course
   * content, never student data, so the source goes to the model unredacted.
   */
  import { invoke } from '@tauri-apps/api/core';
  import { buildRevisePrompt } from '../../integrations/mom/revise';
  import { cliModelArg, extractCliText, engineForProvider, type AgentEngine } from '../../lib/agent-cli';

  let {
    open = $bindable(false),
    path = null,
    label = null,
    contents = '',
    provider = null,
    model = null,
    onRevised = () => {},
  } = $props<{
    open?: boolean;
    path?: string | null;
    label?: string | null;
    contents?: string;
    provider?: string | null;
    model?: string | null;
    onRevised?: () => void | Promise<void>;
  }>();

  type Msg = { role: 'user' | 'agent' | 'error'; text: string };
  let messages = $state<Msg[]>([]);
  let input = $state('');
  let busy = $state(false);

  const engine = $derived<AgentEngine>(engineForProvider(provider ?? undefined));
  const canSend = $derived(!!path && !busy && input.trim().length > 0);

  async function send() {
    if (!canSend || !path) return;
    const instruction = input.trim();
    input = '';
    messages = [...messages, { role: 'user', text: instruction }];
    busy = true;
    try {
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt: buildRevisePrompt({ path, label: label ?? path, instruction, contents }),
        sessionId: crypto.randomUUID(),
        resume: false,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: true, // it has to edit the file, which needs tools
        timeoutSecs: 600,
        stream: false,
      });
      const reply = extractCliText(engine, stdout).trim();
      messages = [...messages, { role: 'agent', text: reply || '(no summary returned)' }];
      // Re-read from disk and re-render: the agent's own account of the edit is not evidence.
      await onRevised();
    } catch (e) {
      messages = [...messages, { role: 'error', text: e instanceof Error ? e.message : String(e) }];
    } finally {
      busy = false;
    }
  }

  function onKey(e: KeyboardEvent) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      send();
    }
  }
</script>

{#if open}
  <aside class="rail">
    <div class="rail-head">
      <h2>Revise</h2>
      <button class="collapse" title="Collapse" onclick={() => (open = false)}>›</button>
    </div>

    {#if label}
      <p class="ctx" title={path ?? ''}>{label}</p>
    {:else}
      <p class="ctx muted">Select a question to revise it.</p>
    {/if}

    <div class="log">
      {#each messages as m, i (i)}
        <div class="msg {m.role}">{m.text}</div>
      {/each}
      {#if busy}<div class="msg agent pending">Working…</div>{/if}
      {#if messages.length === 0 && !busy}
        <p class="hint">Ask for a fix — “part C reads awkwardly”, “make the distractors closer”, “round to cents”.</p>
      {/if}
    </div>

    <div class="composer">
      <textarea
        rows="3"
        placeholder={path ? 'Describe the change…' : 'No question selected'}
        bind:value={input}
        onkeydown={onKey}
        disabled={!path || busy}
      ></textarea>
      <button class="send" onclick={send} disabled={!canSend}>
        {busy ? 'Working…' : 'Send'}
      </button>
    </div>
  </aside>
{:else}
  <button class="strip" title="Open revision chat" onclick={() => (open = true)}>‹ Revise</button>
{/if}

<style>
  .rail { background: rgba(128,128,128,.06); border-radius: 8px; padding: 12px; display: flex; flex-direction: column; min-height: 0; overflow: hidden; }
  .rail-head { display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
  .rail-head h2 { margin: 0 0 8px; font-size: 13px; opacity: .7; text-transform: uppercase; letter-spacing: .05em; }
  .collapse { background: transparent; border: none; color: inherit; cursor: pointer; font-size: 16px; opacity: .6; padding: 0 4px; }
  .collapse:hover { opacity: 1; }
  .ctx { margin: 0 0 8px; font-size: 12px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; opacity: .8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex-shrink: 0; }
  .muted { opacity: .5; }
  .log { flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; min-height: 0; }
  .msg { font-size: 13px; line-height: 1.45; padding: 7px 9px; border-radius: 6px; white-space: pre-wrap; overflow-wrap: anywhere; }
  .msg.user { background: rgba(59,130,246,.16); }
  .msg.agent { background: rgba(128,128,128,.12); }
  .msg.error { background: rgba(185,28,28,.14); color: #b91c1c; }
  .pending { opacity: .7; font-style: italic; }
  .hint { font-size: 12px; opacity: .55; line-height: 1.5; }
  .composer { display: flex; flex-direction: column; gap: 6px; margin-top: 10px; flex-shrink: 0; }
  .composer textarea { resize: vertical; padding: 7px 9px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; font: inherit; font-size: 13px; }
  .composer textarea:disabled { opacity: .5; }
  .send { padding: 7px 12px; border-radius: 6px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; cursor: pointer; font-size: 13px; }
  .send:disabled { opacity: .45; cursor: default; }
  .strip { align-self: start; writing-mode: vertical-rl; padding: 12px 6px; border-radius: 8px; border: 1px solid rgba(128,128,128,.25); background: rgba(128,128,128,.06); color: inherit; cursor: pointer; font-size: 12px; letter-spacing: .04em; opacity: .8; }
  .strip:hover { opacity: 1; background: rgba(128,128,128,.12); }
</style>
