import { invoke } from '@tauri-apps/api/core';
import { cliModelArg, engineForProvider, extractCliText } from './agent-cli';
import { getActiveProvider } from './db';

/**
 * One prose turn against the local agent CLI.
 *
 * Runs `claude`/`opencode` headlessly through the Rust side rather than calling a
 * provider's HTTP API: it reuses the CLI's own auth, so no API key is stored here.
 *
 * This file used to carry a browser action loop as well — `sendAgentRequest`, which put a
 * dehydrated DOM in the prompt, demanded a JSON action back, resumed a session per step and
 * ran the redaction boundary over it. Nothing drove with it. AutomateRunner spawns a
 * full-shell CLI that speaks CDP itself, and in-app browser driving is the page-agent loop;
 * the action loop's only caller was this file's own `sendAgentMessage`, which asks for prose.
 *
 * That mismatch was doing damage, not just sitting there: every skill-authoring message went
 * out wrapped as `TASK: … / CURRENT PAGE: (no elements captured) / Respond with exactly one
 * JSON object and nothing else`, which is the opposite of the markdown the caller wants.
 */

interface SendAgentMessageRequest {
  message: string;
  systemPrompt?: string;
  provider?: string;
  model?: string;
}

interface SendAgentMessageHandlers {
  onStatus?: (data: { status: string }) => void;
  onMessage?: (data: { content: string }) => void;
  onDone?: () => void;
  onError?: (data: { message: string }) => void;
}

export async function sendAgentMessage(
  request: SendAgentMessageRequest,
  handlers: SendAgentMessageHandlers = {},
): Promise<void> {
  handlers.onStatus?.({ status: 'thinking' });

  try {
    const providerId = request.provider ?? (await getActiveProvider().catch(() => null))?.id;
    const engine = engineForProvider(providerId);

    const stdout = await invoke<string>('run_agent_cli', {
      engine,
      prompt: request.message,
      sessionId: crypto.randomUUID(),
      resume: false,
      model: cliModelArg(engine, request.model),
      systemPrompt: request.systemPrompt ?? null,
      // Required by the command, not optional — omitting it fails the whole call with
      // "missing required key bypassPermissions" before the CLI is ever spawned. False is
      // also the right value: this path asks for prose and has no business with a shell.
      bypassPermissions: false,
    });

    handlers.onMessage?.({ content: extractCliText(engine, stdout) });
    handlers.onDone?.();
  } catch (error: unknown) {
    const message = error instanceof Error ? error.message : String(error);
    handlers.onError?.({ message });
    throw error;
  }
}
