// Local model sidecar for S.T.E.V.E. Listens on :3456 and answers POST /api/agent.
// It reads the provider the user configured in the app's SQLite DB, builds the
// right auth (API key, or an OAuth token fetched from the `ant` CLI), forwards
// the agent's already-redacted request to the provider, and returns the reply.
//
// Run:  bun run server          (from steve-desktop/steve-desktop)
// Mock: STEVE_MODEL_MOCK=1 bun run server   (no network; canned reply — for smoke tests)
//
// Runs under Bun (uses bun:sqlite + spawn). Pure mapping lives in
// src/lib/model-providers.ts (unit-tested); auth headers in src/lib/anthropic-auth.ts.

import { Database } from 'bun:sqlite';
import { join } from 'node:path';
import {
  buildAnthropicBody,
  parseAnthropicResponse,
  buildChatBody,
  parseChatResponse,
  type AgentRequestBody,
} from '../src/lib/model-providers';
import { anthropicAuthHeaders, getAnthropicAccessToken } from '../src/lib/anthropic-auth';

const PORT = Number(process.env.STEVE_PORT) || 3456;
const MOCK = process.env.STEVE_MODEL_MOCK === '1';

const DB_PATH =
  process.env.STEVE_DB_PATH ??
  join(process.env.APPDATA ?? join(process.env.HOME ?? '.', 'AppData', 'Roaming'), 'com.steve.desktop', 'steve.db');

interface ProviderConfig {
  id: string;
  api_url: string | null;
  api_key: string | null;
  model: string | null;
  is_active: number;
}

function loadProvider(requested?: string): ProviderConfig | null {
  let db: Database;
  try {
    db = new Database(DB_PATH, { readonly: true });
  } catch {
    return null; // DB not created yet
  }
  try {
    if (requested) {
      const row = db.query('SELECT * FROM provider_configs WHERE id = ?').get(requested) as ProviderConfig | null;
      if (row) return row;
    }
    return db.query('SELECT * FROM provider_configs WHERE is_active = 1 ORDER BY id LIMIT 1').get() as ProviderConfig | null;
  } finally {
    db.close();
  }
}

function anthropicSignedIn(): boolean {
  try {
    const db = new Database(DB_PATH, { readonly: true });
    try {
      const row = db.query("SELECT access_token FROM oauth_tokens WHERE provider = 'anthropic'").get() as
        | { access_token: string }
        | null;
      return !!row?.access_token;
    } finally {
      db.close();
    }
  } catch {
    return false;
  }
}

async function antAccessToken(): Promise<string> {
  return getAnthropicAccessToken(async () => {
    const proc = Bun.spawn(['ant', 'auth', 'print-credentials', '--access-token'], { stdout: 'pipe', stderr: 'pipe' });
    const out = await new Response(proc.stdout).text();
    await proc.exited;
    if (proc.exitCode !== 0) throw new Error('ant exited non-zero');
    return out;
  });
}

async function callModel(body: AgentRequestBody): Promise<{ content: string }> {
  if (MOCK) {
    return { content: JSON.stringify({ action: 'done', params: { message: 'mock model reply', success: true } }) };
  }

  const cfg = loadProvider(body.provider);
  if (!cfg) throw new Error('No active provider configured. Add one in Settings → Providers.');
  const model = body.model || cfg.model || '';
  if (!model) throw new Error(`No model set for provider "${cfg.id}".`);

  if (cfg.id === 'anthropic') {
    const headers: Record<string, string> = { 'content-type': 'application/json' };
    const auth = cfg.api_key
      ? anthropicAuthHeaders({ mode: 'apikey', apiKey: cfg.api_key })
      : anthropicSignedIn()
        ? anthropicAuthHeaders({ mode: 'oauth', accessToken: await antAccessToken() })
        : (() => {
            throw new Error('Anthropic not configured: add an API key or sign in.');
          })();
    Object.assign(headers, auth);
    const res = await fetch('https://api.anthropic.com/v1/messages', {
      method: 'POST',
      headers,
      body: JSON.stringify(buildAnthropicBody(body, model)),
    });
    if (!res.ok) throw new Error(`Anthropic ${res.status}: ${await res.text()}`);
    return parseAnthropicResponse(await res.json());
  }

  // openai + ollama: chat-completions shape, Bearer auth
  const base = cfg.id === 'openai' ? 'https://api.openai.com/v1' : (cfg.api_url || 'https://ollama.com').replace(/\/$/, '');
  const url = cfg.id === 'openai' ? `${base}/chat/completions` : `${base}/api/chat`;
  const headers: Record<string, string> = { 'content-type': 'application/json' };
  if (cfg.api_key) headers['Authorization'] = `Bearer ${cfg.api_key}`;
  const payload: Record<string, unknown> = buildChatBody(body, model);
  if (cfg.id !== 'openai') payload.stream = false; // ollama
  const res = await fetch(url, { method: 'POST', headers, body: JSON.stringify(payload) });
  if (!res.ok) throw new Error(`${cfg.id} ${res.status}: ${await res.text()}`);
  return parseChatResponse(await res.json());
}

const CORS = {
  'Access-Control-Allow-Origin': '*',
  'Access-Control-Allow-Methods': 'POST, OPTIONS',
  'Access-Control-Allow-Headers': 'content-type',
};

Bun.serve({
  port: PORT,
  async fetch(req) {
    const { pathname } = new URL(req.url);
    if (req.method === 'OPTIONS') return new Response(null, { headers: CORS });
    if (req.method === 'GET' && pathname === '/health') {
      return Response.json({ ok: true, mock: MOCK, db: DB_PATH }, { headers: CORS });
    }
    if (req.method === 'POST' && pathname === '/api/agent') {
      try {
        const body = (await req.json()) as AgentRequestBody;
        const result = await callModel(body);
        return Response.json(result, { headers: CORS });
      } catch (e) {
        return Response.json({ error: e instanceof Error ? e.message : String(e) }, { status: 500, headers: CORS });
      }
    }
    return new Response('Not found', { status: 404, headers: CORS });
  },
});

console.log(`[steve] model sidecar on http://localhost:${PORT}  (mock=${MOCK}, db=${DB_PATH})`);
