// Phase 4 — measure, don't assert. Connects to the live page over CDP and reports how much
// of the merged DOM+AX capture actually carries durable role+name anchors. Run with the app
// open on a real page:  bun run server/measure-capture.ts  [cdpPort]
// For a real number on the actual target, open a logged-in SafeColleges page first.

import { CDPClient } from '../src/lib/cdp-client';
import { captureMergedTree, mergedToProfile } from '../src/lib/merged-tree';

const port = Number(process.argv[2]) || 9223;

const client = new CDPClient();
const ok = await client.connect(port);
if (!ok) {
  console.error(`Could not attach to a real page on CDP :${port}. Open a page in the app's browser first.`);
  process.exit(2);
}

try {
  const { merged, snapshot } = await captureMergedTree(client);
  // mergedToProfile needs a URL; pull it from the page.
  const ev = (await client.send('Runtime.evaluate', { expression: 'location.href', returnByValue: true })) as {
    result?: { value?: string };
  };
  const url = ev.result?.value ?? 'https://unknown/';
  const profile = mergedToProfile(merged, url);

  const total = merged.length;
  const withRole = merged.filter((n) => n.role).length;
  const withName = merged.filter((n) => n.name?.trim()).length;
  const interactive = profile.interactive.buttons.length + profile.interactive.links.length + profile.interactive.inputs.length;
  const interactiveEls = [...profile.interactive.buttons, ...profile.interactive.links, ...profile.interactive.inputs];
  const withRoleName = interactiveEls.filter((e) => e.candidates?.some((c) => c.type === 'role-name')).length;
  const frames = new Set(merged.map((n) => n.frameId)).size;
  const pct = (a: number, b: number) => (b ? `${Math.round((a / b) * 100)}%` : 'n/a');

  console.log(`\n── Merged capture coverage @ ${url} ──`);
  console.log(`frames captured        : ${frames}`);
  console.log(`DOM nodes (merged)     : ${total}`);
  console.log(`  with AX role         : ${withRole} (${pct(withRole, total)})`);
  console.log(`  with accessible name : ${withName} (${pct(withName, total)})`);
  console.log(`interactive elements   : ${interactive}`);
  console.log(`  with role+name anchor: ${withRoleName} (${pct(withRoleName, interactive)})  ← the durable, self-heal-friendly ones`);
  console.log(`snapshot chars         : ${snapshot.meta.charCount}`);
  console.log(`\nHigher role+name coverage = more elements that survive DOM churn (Phase 3 self-heal Tier 1).`);
} finally {
  await client.disconnect();
}
