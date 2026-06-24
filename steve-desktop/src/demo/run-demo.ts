import { join } from 'node:path';
import { runBoringClicksDemo } from './boring-clicks';

// On-demand demo: `bun run src/demo/run-demo.ts` (from steve-desktop/steve-desktop).
// Writes the site-profile + skill to the repo's .agents/ and prints the run.

const rootDir = join(process.cwd(), '..'); // repo root holds .agents/

const r = await runBoringClicksDemo({
  rootDir,
  renameOnReplay: { '#studentName': '#student_name' }, // force a self-heal
});

const line = '─'.repeat(64);
console.log(`\n${line}\n  BORING-CLICKS ROBOT — open → train → replay\n${line}`);

console.log('\n[1] OPEN     embedded browser on https://sis.example.edu/grades');

console.log('\n[2] TRAIN');
console.log(`    discovery → site-profile saved:`);
console.log(`      ${r.profilePath}`);
const idFields = r.profile.interactive.inputs.filter((i) => i.identifier).map((i) => i.label);
console.log(`    identifier fields flagged: ${idFields.join(', ') || '(none)'}`);
console.log(`    workflow captured → skill saved:`);
console.log(`      ${r.skillPath}`);

console.log('\n[3] MODEL CALL (redactor gate)');
console.log(`    sent to model (de-identified):`);
console.log(`      ${r.outboundCapture[0]?.slice(0, 110)}…`);
console.log(`    known identifiers in outbound payload: ${r.leaked ? 'LEAKED ❌' : 'none ✅'}`);
console.log(`    rehydrated plan (local only): ${r.modelPlan}`);

console.log('\n[4] REPLAY  (skill reloaded cold from disk)');
for (const step of r.summary.results) {
  const mark = step.status === 'skipped' ? '⚠' : '✓';
  console.log(`    ${mark} ${step.step.action.padEnd(5)} ${step.status.padEnd(9)} ${step.detail}`);
}
console.log(`\n    workflow "${r.summary.workflow}" completed: ${r.summary.completed ? 'yes ✅' : 'no ⚠'}`);
console.log(`${line}\n`);

if (r.leaked) process.exit(1);
