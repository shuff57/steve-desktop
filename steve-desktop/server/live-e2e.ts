// Live end-to-end test of the production data path:
//   Redactor (app side) -> sidecar (:3457) -> real HTTP -> provider adapter -> reply
// Uses a local stand-in model (mimics Ollama's /api/chat) instead of a cloud
// vendor (no credential available), and a throwaway DB so the real app DB is
// untouched. Asserts NO known identifier crosses the wire to the model.

import { Database } from 'bun:sqlite';
import { join } from 'node:path';
import { tmpdir } from 'node:os';
import { mkdtempSync } from 'node:fs';
import { Redactor } from '../src/lib/redact';

const MODEL_PORT = 8999;
const SIDECAR_PORT = 3457;
const base = (p: number) => `http://localhost:${p}`;

// 1) Stand-in model: captures exactly what it receives, replies referencing a token.
const seen: string[] = [];
const model = Bun.serve({
  port: MODEL_PORT,
  async fetch(req) {
    const raw = await req.text();
    seen.push(raw);
    return Response.json({ message: { content: 'Plan: fill the form for ⟦S1⟧ and submit.' } });
  },
});

// 2) Throwaway DB with an Ollama provider pointed at the stand-in model.
const dir = mkdtempSync(join(tmpdir(), 'steve-e2e-'));
const dbPath = join(dir, 'steve.db');
const db = new Database(dbPath);
db.run(`CREATE TABLE provider_configs (id TEXT PRIMARY KEY, api_url TEXT, api_key TEXT, model TEXT, is_active INTEGER)`);
db.run(`INSERT INTO provider_configs VALUES ('ollama', ?, 'test-key', 'stand-in-model', 1)`, [base(MODEL_PORT)]);
db.close();

// 3) Start the real sidecar against the throwaway DB on a test port.
const sidecar = Bun.spawn(['bun', 'run', join(import.meta.dir, 'agent-server.ts')], {
  env: { ...process.env, STEVE_PORT: String(SIDECAR_PORT), STEVE_DB_PATH: dbPath },
  stdout: 'pipe',
  stderr: 'pipe',
});
for (let i = 0; i < 40; i++) {
  try { if ((await fetch(`${base(SIDECAR_PORT)}/health`)).ok) break; } catch {}
  await Bun.sleep(100);
}

// 4) App side: redact a payload containing real PII, then send the redacted text.
const redactor = new Redactor(['Jane Doe', '4471']);
const rawMessage = 'Student Jane Doe (ID 4471) is missing assignment 3. What reminder should I send?';
const payload = redactor.redact(rawMessage);

const res = await fetch(`${base(SIDECAR_PORT)}/api/agent`, {
  method: 'POST',
  headers: { 'content-type': 'application/json' },
  body: JSON.stringify({
    messages: [
      { role: 'system', content: 'You are a teacher assistant.' },
      { role: 'user', content: payload.text },
    ],
    provider: 'ollama',
  }),
});
const body = (await res.json()) as { content?: string; error?: string };

// 5) Assertions — the real safety + plumbing guarantees.
const sentToModel = seen.join('\n');
const leakedName = sentToModel.includes('Jane Doe');
const leakedId = sentToModel.includes('4471');
const replyRehydrated = redactor.rehydrate(body.content ?? '');

const checks: [string, boolean][] = [
  ['sidecar reachable + returned 200', res.ok],
  ['model received the request (real HTTP round trip)', seen.length === 1],
  ['NO name reached the model', !leakedName],
  ['NO id reached the model', !leakedId],
  ['model saw the token instead', sentToModel.includes('⟦S1⟧') || sentToModel.includes('⟦S2⟧')],
  ['reply rehydrates to the real identity locally', replyRehydrated.includes('Jane Doe')],
];

console.log('\n── LIVE END-TO-END (redactor → sidecar → HTTP → model → rehydrate) ──');
console.log('sent to model :', sentToModel.slice(0, 140).replace(/\s+/g, ' '), '…');
console.log('sidecar reply :', body.content ?? body.error);
console.log('rehydrated    :', replyRehydrated, '\n');
let ok = true;
for (const [label, pass] of checks) {
  console.log(`  ${pass ? '✓' : '✗'} ${label}`);
  ok = ok && pass;
}
console.log(`\n${ok ? '✅ PASS — full path works, no PII crossed the wire' : '❌ FAIL'}\n`);

sidecar.kill();
model.stop();
process.exit(ok ? 0 : 1);
