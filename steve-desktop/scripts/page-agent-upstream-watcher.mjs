#!/usr/bin/env node
/**
 * page-agent-upstream-watcher.mjs
 *
 * Monitors the Alibaba page-agent repo for changes to the files we vendored.
 * Fetches the latest versions from GitHub, diffs them against our copies, and
 * writes a report showing what changed and whether we need to sync.
 *
 * Usage:
 *   node scripts/page-agent-upstream-watcher.mjs              # check latest main
 *   node scripts/page-agent-upstream-watcher.mjs --tag v1.12.0 # check specific tag
 *   node scripts/page-agent-upstream-watcher.mjs --update      # update vendored files
 *
 * The watcher tracks three source files:
 *   - packages/core/src/prompts/system_prompt.md  → src/lib/page-agent-prompt.ts
 *   - packages/core/src/tools/index.ts           → src/lib/page-agent-tools.ts
 *   - packages/core/src/PageAgentCore.ts         → src/lib/page-agent-loop.ts (concept)
 *
 * It reports:
 *   - whether upstream has changed since last check
 *   - a unified diff of each file
 *   - an impact assessment (prompt tweak vs breaking tool schema change)
 *
 * Output: scripts/page-agent-upstream-report.md
 */

import { writeFileSync, readFileSync, existsSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const REPO_ROOT = join(__dirname, '..');
const REPORT_PATH = join(__dirname, 'page-agent-upstream-report.md');
const STATE_PATH = join(__dirname, 'page-agent-upstream-state.json');

const UPSTREAM_REPO = 'alibaba/page-agent';
const UPSTREAM_BRANCH = 'main';

// The three files we vendor, mapped: upstream path → our file + description
const VENDORED_FILES = [
  {
    upstream: 'packages/core/src/prompts/system_prompt.md',
    ours: 'src/lib/page-agent-prompt.ts',
    label: 'System Prompt',
    impact: 'prompt', // prompt changes are usually safe to cherry-pick
  },
  {
    upstream: 'packages/core/src/tools/index.ts',
    ours: 'src/lib/page-agent-tools.ts',
    label: 'Tool Schemas',
    impact: 'tools', // tool changes may need loop + config updates
  },
  {
    upstream: 'packages/core/src/PageAgentCore.ts',
    ours: 'src/lib/page-agent-loop.ts',
    label: 'Re-Act Loop',
    impact: 'loop', // loop changes are structural — manual review needed
  },
];

async function fetchUpstreamFile(path, ref) {
  const url = `https://raw.githubusercontent.com/${UPSTREAM_REPO}/${ref}/${path}`;
  const resp = await fetch(url);
  if (!resp.ok) throw new Error(`Failed to fetch ${path}: ${resp.status}`);
  return await resp.text();
}

async function getLatestRef(tag) {
  if (tag) return tag;
  // Use the latest commit on main
  const resp = await fetch(`https://api.github.com/repos/${UPSTREAM_REPO}/commits/${UPSTREAM_BRANCH}`);
  if (!resp.ok) throw new Error(`Failed to get latest commit: ${resp.status}`);
  const data = await resp.json();
  return data.sha;
}

function loadState() {
  if (!existsSync(STATE_PATH)) return { lastRef: null, lastCheck: null };
  try {
    return JSON.parse(readFileSync(STATE_PATH, 'utf-8'));
  } catch {
    return { lastRef: null, lastCheck: null };
  }
}

function saveState(state) {
  writeFileSync(STATE_PATH, JSON.stringify(state, null, 2));
}

// Simple unified diff — not a full implementation, just enough to show what changed.
// For a real diff, use `git diff --no-index` which is available everywhere.
function generateDiff(oldText, newText, label) {
  const oldLines = oldText.split('\n');
  const newLines = newText.split('\n');
  const maxLen = Math.max(oldLines.length, newLines.length);
  const diffs = [];
  for (let i = 0; i < maxLen; i++) {
    const old = oldLines[i] ?? '';
    const news = newLines[i] ?? '';
    if (old !== news) {
      if (old && !news) diffs.push(`- ${old}`);
      else if (!old && news) diffs.push(`+ ${news}`);
      else {
        diffs.push(`- ${old}`);
        diffs.push(`+ ${news}`);
      }
    }
  }
  return diffs;
}

function impactAssessment(impact, diffLines) {
  if (diffLines.length === 0) return 'No change.';
  const added = diffLines.filter((l) => l.startsWith('+ ')).length;
  const removed = diffLines.filter((l) => l.startsWith('- ')).length;
  switch (impact) {
    case 'prompt':
      return `Prompt tweak: +${added} -${removed} lines. Usually safe to cherry-pick the new prompt string into page-agent-prompt.ts. Check if new sections (like <browser_rules>) were added or existing ones modified.`;
    case 'tools':
      return `Tool schema change: +${added} -${removed} lines. Check if tool names/params changed — our loop calls these by name. New tools may need adding to DEFAULT_TOOLS. Removed tools should be dropped from our set.`;
    case 'loop':
      return `Loop logic change: +${added} -${removed} lines. Structural — manual review needed. Our loop is a CDP reimplementation, not a copy, so upstream changes are inspirational, not directly applicable.`;
    default:
      return `Unknown change: +${added} -${removed} lines.`;
  }
}

async function main() {
  const args = process.argv.slice(2);
  const doUpdate = args.includes('--update');
  const tagArg = args.find((a) => a === '--tag');
  const tagIdx = args.indexOf('--tag');
  const tag = tagIdx >= 0 && tagIdx + 1 < args.length ? args[tagIdx + 1] : null;

  const state = loadState();
  const ref = await getLatestRef(tag);

  console.log(`Checking page-agent upstream @ ${ref.slice(0, 8)}...`);
  console.log(`Last check: ${state.lastRef?.slice(0, 8) ?? 'never'} @ ${state.lastCheck ?? 'never'}\n`);

  const reportLines = [];
  reportLines.push(`# page-agent upstream watcher report`);
  reportLines.push('');
  reportLines.push(`- **Checked:** ${new Date().toISOString()}`);
  reportLines.push(`- **Upstream ref:** ${ref}`);
  reportLines.push(`- **Last check:** ${state.lastRef ?? 'never'} @ ${state.lastCheck ?? 'never'}`);
  reportLines.push(`- **Repo:** https://github.com/${UPSTREAM_REPO}`);
  reportLines.push('');

  let anyChanged = false;

  for (const file of VENDORED_FILES) {
    console.log(`Fetching ${file.label}: ${file.upstream}`);
    let upstreamContent;
    try {
      upstreamContent = await fetchUpstreamFile(file.upstream, ref);
    } catch (e) {
      reportLines.push(`## ${file.label} — FETCH FAILED`);
      reportLines.push(`Error: ${e.message}\n`);
      continue;
    }

    const ourPath = join(REPO_ROOT, file.ours);
    const ourContent = existsSync(ourPath) ? readFileSync(ourPath, 'utf-8') : '';

    // For the prompt: upstream is .md, ours is .ts with the prompt as a string.
    // We extract the prompt string from our file and compare to upstream .md.
    let ourComparable = ourContent;
    if (file.impact === 'prompt') {
      // Extract the template literal content from our TS file
      const match = ourContent.match(/export const PAGE_AGENT_SYSTEM_PROMPT = `([\s\S]*?)`;/);
      ourComparable = match ? match[1] : ourContent;
    }

    // For tools: upstream is .ts with zod, ours is .ts without zod.
    // We can't do a clean text diff, but we can detect if the tool set changed.
    if (file.impact === 'tools') {
      // Extract tool names from both files
      const upstreamToolNames = [...upstreamContent.matchAll(/tools\.set\(\s*['"]([^'"]+)['"]/g)].map((m) => m[1]);
      const ourToolNames = [...ourContent.matchAll(/name:\s*['"]([^'"]+)['"]/g)].map((m) => m[1]);
      const added = upstreamToolNames.filter((n) => !ourToolNames.includes(n));
      const removed = ourToolNames.filter((n) => !upstreamToolNames.includes(n));
      reportLines.push(`## ${file.label} (${file.ours})`);
      reportLines.push(`Source: ${file.upstream}`);
      reportLines.push('');
      if (added.length === 0 && removed.length === 0) {
        reportLines.push(`No tool set changes. Tool names match.`);
      } else {
        anyChanged = true;
        if (added.length > 0) reportLines.push(`**New upstream tools (consider adding):** ${added.join(', ')}`);
        if (removed.length > 0) reportLines.push(`**Our tools not in upstream (may be custom or removed):** ${removed.join(', ')}`);
      }
      reportLines.push('');
      continue;
    }

    const diff = generateDiff(ourComparable, upstreamContent, file.label);

    reportLines.push(`## ${file.label} (${file.ours})`);
    reportLines.push(`Source: ${file.upstream}`);
    reportLines.push('');

    if (diff.length === 0) {
      reportLines.push(`No change since our vendored copy.`);
    } else {
      anyChanged = true;
      reportLines.push(`**${diff.length} lines changed.**`);
      reportLines.push('');
      reportLines.push('```diff');
      // Show first 50 diff lines to keep the report readable
      reportLines.push(...diff.slice(0, 50));
      if (diff.length > 50) reportLines.push(`... (${diff.length - 50} more lines)`);
      reportLines.push('```');
      reportLines.push('');
      reportLines.push(`**Impact:** ${impactAssessment(file.impact, diff)}`);

      if (doUpdate && file.impact === 'prompt') {
        // Auto-update: only for the prompt (safest to sync)
        const updatedContent = ourContent.replace(
          /export const PAGE_AGENT_SYSTEM_PROMPT = `[\s\S]*?`;/,
          `export const PAGE_AGENT_SYSTEM_PROMPT = \`${upstreamContent}\`;`,
        );
        if (updatedContent !== ourContent) {
          writeFileSync(ourPath, updatedContent);
          reportLines.push(`\n**✅ Updated ${file.ours}** (--update flag was set)`);
        }
      }
    }
    reportLines.push('');
  }

  reportLines.push('---');
  reportLines.push('');
  if (anyChanged) {
    reportLines.push(`**⚠️ Upstream has changes.** Review the diffs above and sync manually.`);
    reportLines.push(`- Prompt: cherry-pick the new string into page-agent-prompt.ts`);
    reportLines.push(`- Tools: add/remove tools in page-agent-tools.ts to match upstream`);
    reportLines.push(`- Loop: read the PageAgentCore changes for inspiration, but our loop is a CDP reimplementation`);
    if (!doUpdate) {
      reportLines.push(`- Run with --update to auto-sync the prompt (tools + loop need manual review)`);
    }
  } else {
    reportLines.push(`**✅ All vendored files are up to date.**`);
  }

  const report = reportLines.join('\n');
  writeFileSync(REPORT_PATH, report);
  console.log(`\nReport written to ${REPORT_PATH}`);
  console.log(report.split('\n').slice(0, 30).join('\n'));

  saveState({ lastRef: ref, lastCheck: new Date().toISOString() });
}

main().catch((e) => {
  console.error('Watcher failed:', e);
  process.exit(1);
});