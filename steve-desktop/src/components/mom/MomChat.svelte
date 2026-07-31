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
  import { buildRevisePrompt, buildFollowUpPrompt } from '../../integrations/mom/revise';
  import { cliModelArg, extractCliText, engineForProvider, type AgentEngine } from '../../lib/agent-cli';
  import ChatMessage from './ChatMessage.svelte';

  let {
    open = $bindable(false),
    /** The rail now hosts tabs, so the parent draws the frame and the collapse control. */
    embedded = false,
    path = null,
    label = null,
    contents = '',
    provider = null,
    model = null,
    onRevised = () => {},
  } = $props<{
    open?: boolean;
    embedded?: boolean;
    path?: string | null;
    label?: string | null;
    contents?: string;
    provider?: string | null;
    model?: string | null;
    onRevised?: () => void | Promise<void>;
  }>();

  type Msg = { role: 'user' | 'agent' | 'error'; text: string };

  /**
   * One thread per question file. Revising is iterative — "now make part C harder" only means
   * anything next to what came before — and switching questions must not show another question's
   * conversation, so the log is keyed by path rather than reset.
   */
  let threads = $state<Record<string, Msg[]>>({});
  /**
   * The CLI session backing each thread. A follow-up resumes it, so the agent still has the file,
   * the rules and its own last edit in context instead of starting cold every turn.
   * Not $state: nothing renders from it.
   */
  const sessions = new Map<string, string>();
  let input = $state('');
  /** Which question is mid-run — switching away mid-turn should not lock the thread you moved to. */
  let busyPath = $state<string | null>(null);
  /** The session id of the turn currently running, so it can be killed. */
  let runningSession = $state<string | null>(null);

  const messages = $derived(path ? (threads[path] ?? []) : []);
  const busy = $derived(!!path && busyPath === path);
  const engine = $derived<AgentEngine>(engineForProvider(provider ?? undefined));
  const canSend = $derived(!!path && !busy && input.trim().length > 0);

  function push(key: string, m: Msg) {
    threads = { ...threads, [key]: [...(threads[key] ?? []), m] };
  }

  async function send() {
    if (!canSend || !path) return;
    const key: string = path;
    const instruction = input.trim();
    input = '';
    push(key, { role: 'user', text: instruction });
    busyPath = key;
    const prior = sessions.get(key);
    const sessionId = prior ?? crypto.randomUUID();
    runningSession = sessionId;
    try {
      const stdout = await invoke<string>('run_agent_cli', {
        engine,
        prompt: prior
          ? buildFollowUpPrompt(instruction)
          : buildRevisePrompt({ path: key, label: label ?? key, instruction, contents }),
        sessionId,
        resume: !!prior,
        model: cliModelArg(engine, model),
        systemPrompt: null,
        bypassPermissions: true, // it has to edit the file, which needs tools
        timeoutSecs: 600,
        stream: false,
      });
      // Only now is the session known to exist — resuming one the CLI failed to create just errors.
      sessions.set(key, sessionId);
      const reply = extractCliText(engine, stdout).trim();
      push(key, { role: 'agent', text: reply || '(no summary returned)' });
      // Re-read from disk and re-render: the agent's own account of the edit is not evidence.
      await onRevised();
    } catch (e) {
      push(key, { role: 'error', text: e instanceof Error ? e.message : String(e) });
      // A killed turn may have half-edited the file, so re-read either way rather than trusting
      // that stopping it left the question untouched.
      await onRevised().catch(() => {});
    } finally {
      if (busyPath === key) busyPath = null;
      if (runningSession === sessionId) runningSession = null;
    }
  }

  /**
   * Kill the running turn. The agent has already started editing by the time you want to stop it,
   * so this is "stop making it worse", not an undo — the file is left wherever it got to.
   */
  async function stop() {
    if (!runningSession) return;
    await invoke('stop_agent_cli', { sessionId: runningSession }).catch(() => {});
    // The session is now dead; a follow-up must start a fresh one rather than resume a corpse.
    if (busyPath) sessions.delete(busyPath);
  }

  function onKey(e: KeyboardEvent) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      send();
    }
  }
</script>

{#if open}
  <div class:rail={!embedded} class:embedded>
    {#if !embedded}
      <div class="rail-head">
        <h2>Revise</h2>
        <button class="collapse" title="Collapse" onclick={() => (open = false)}>›</button>
      </div>
    {/if}

    {#if label}
      <p class="ctx" title={path ?? ''}>{label}</p>
    {:else}
      <p class="ctx muted">Select a question to revise it.</p>
    {/if}

    <div class="chat-log">
      {#each messages as m, i (i)}
        <ChatMessage role={m.role} text={m.text} />
      {/each}
      {#if busy}<ChatMessage role="step" text="Working…" />{/if}
      {#if messages.length === 0 && !busy}
        <p class="hint">Ask for a fix — “part C reads awkwardly”, “make the distractors closer”, “round to cents”.</p>
      {/if}
    </div>

    <div class="composer">
      <textarea
        rows="1"
        placeholder={path ? 'Describe the change…' : 'No question selected'}
        bind:value={input}
        onkeydown={onKey}
        disabled={!path || busy}
      ></textarea>
      {#if busy}
        <button class="send stop" onclick={stop}>Stop</button>
      {:else}
        <button class="send" onclick={send} disabled={!canSend}>Send</button>
      {/if}
    </div>
  </div>
{:else if !embedded}
  <button class="strip" title="Open revision chat" onclick={() => (open = true)}>‹ Revise</button>
{/if}

<style>
  .rail { background: rgba(128,128,128,.06); border-radius: 8px; padding: 12px; display: flex; flex-direction: column; min-height: 0; overflow: hidden; }
  /* Inside the tabbed rail the parent supplies the frame; only the column layout is still needed. */
  .embedded { display: flex; flex-direction: column; flex: 1; min-height: 0; overflow: hidden; gap: 10px; }
  .rail-head { display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
  .rail-head h2 { margin: 0 0 8px; font-size: 13px; opacity: .7; text-transform: uppercase; letter-spacing: .05em; }
  .collapse { background: transparent; border: none; color: inherit; cursor: pointer; font-size: 16px; opacity: .6; padding: 0 4px; }
  .collapse:hover { opacity: 1; }
  .ctx { margin: 0 0 4px; font-size: 12px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; opacity: .8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex-shrink: 0; }
  .muted { opacity: .5; }
  .chat-log { flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; min-height: 0; padding-right: 4px; }
  .hint { font-size: 12px; opacity: .55; line-height: 1.5; margin: auto 8px; text-align: center; }
  .composer { display: flex; flex-direction: column; gap: 6px; flex-shrink: 0; }
  .composer textarea { resize: none; padding: 8px 10px; border-radius: 10px; border: 1px solid rgba(128,128,128,.3); background: rgba(128,128,128,.08); color: inherit; font: inherit; font-size: 13px; min-height: 40px; max-height: 160px; }
  .composer textarea:disabled { opacity: .5; }
  .send { padding: 7px 12px; border-radius: 8px; border: 1px solid rgba(128,128,128,.3); background: transparent; color: inherit; cursor: pointer; font-size: 13px; }
  .send:disabled { opacity: .45; cursor: default; }
  .stop { border-color: rgba(185,28,28,.5); color: #b91c1c; }
  .strip { align-self: start; writing-mode: vertical-rl; padding: 12px 6px; border-radius: 8px; border: 1px solid rgba(128,128,128,.25); background: rgba(128,128,128,.06); color: inherit; cursor: pointer; font-size: 12px; letter-spacing: .04em; opacity: .8; }
  .strip:hover { opacity: 1; background: rgba(128,128,128,.12); }
</style>
