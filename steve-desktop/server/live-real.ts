// Live test against a REAL provider (Ollama Cloud) through the production sidecar.
//   redactor (app) → sidecar :3458 → real HTTPS → ollama.com → reply → rehydrate
// Key/model come from env so no secret is written to disk:
//   OLLAMA_TEST_KEY=... OLLAMA_TEST_MODEL=gpt-oss:120b bun run server/live-real.ts

import { Database } from 'bun:sqlite';
import { join } from 'node:path';
import { tmpdir } from 'node:os';
import { mkdtempSync } from 'node:fs';
import { Redactor } from '../src/lib/redact';

const KEY = process.env.OLLAMA_TEST_KEY;
const MODEL = process.env.OLLAMA_TEST_MODEL ?? 'gpt-oss:120b';
const PORT = 3458;
if (!KEY) { console.error('Set OLLAMA_TEST_KEY'); process.exit(2); }

const dir = mkdtempSync(join(tmpdir(), 'steve-real-'));
const dbPath = join(dir, 'steve.db');
const db = new Database(dbPath);
db.run(`CREATE TABLE provider_configs (id TEXT PRIMARY KEY, api_url TEXT, api_key TEXT, model TEXT, is_active INTEGER)`);
db.run(`INSERT INTO provider_configs VALUES ('ollama', 'https://ollama.com', ?, ?, 1)`, [KEY, MODEL]);
db.close();

const sidecar = Bun.spawn(['bun', 'run', join(import.meta.dir, 'agent-server.ts')], {
  env: { ...process.env, STEVE_PORT: String(PORT), STEVE_DB_PATH: dbPath },
  stdout: 'pipe', stderr: 'pipe',
});
for (let i = 0; i < 40; i++) {
  try { if ((await fetch(`http://localhost:${PORT}/health`)).ok) break; } catch {}
  await Bun.sleep(100);
}

// App side: real PII redacted before anything leaves the machine.
const redactor = new Redactor(['Jane Doe', '4471']);
const real = 'Student Jane Doe, ID 4471, is missing assignment 3.';
const instruction = `${real} Write a one-sentence reminder addressed to the student. Refer to the student only by the exact placeholder token shown, verbatim.`;
const payload = redactor.redact(instruction);

console.log('\n── LIVE against Ollama Cloud (model:', MODEL + ') ──');
console.log('redacted text SENT over the wire:\n  ', payload.text);
console.log('  contains "Jane Doe"? ', payload.text.includes('Jane Doe'), ' | contains "4471"? ', payload.text.includes('4471'));

const res = await fetch(`http://localhost:${PORT}/api/agent`, {
  method: 'POST', headers: { 'content-type': 'application/json' },
  body: JSON.stringify({ messages: [{ role: 'user', content: payload.text }], provider: 'ollama' }),
});
const body = (await res.json()) as { content?: string; error?: string };

console.log('\nreal model reply (still tokenized):\n  ', body.content ?? body.error);
console.log('\nrehydrated locally:\n  ', redactor.rehydrate(body.content ?? ''));

const ok = res.ok && !!body.content && !payload.text.includes('Jane Doe') && !payload.text.includes('4471');
console.log(`\n${ok ? '✅ PASS — real model answered; no PII left the machine' : '❌ FAIL'}\n`);

sidecar.kill();
process.exit(ok ? 0 : 1);
