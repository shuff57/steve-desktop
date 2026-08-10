// Structural lint for MOM question sources. Every check here exists because the bug shipped.
//
//   1. article-before-interpolation  "a ' . $who" is right for one randomised context and wrong for
//                                    the next. Shipped as "a order", "a customers", "a students".
//   2. $answers[ (plural)            silently sets nothing, so the part has no key. Shipped live on
//                                    1.2 and 2.1, where part (b) could not score at all.
//   3. $answer[] after QUESTION TEXT the key belongs in COMMON CONTROL. The '=== ANSWER ===' section
//                                    is the SOLUTION field. A key written there is not a key, and
//                                    the question still saves and renders cleanly.
//
// None of these is caught by a byte-exact read-back, a qtype audit, or a render check.
//
//   node mom-content/reference/question-lint.mjs mom-content/questions
import fs from 'node:fs';
import path from 'node:path';

const root = process.argv[2] || '.';
const findings = [];

function walk(dir) {
  for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
    const p = path.join(dir, e.name);
    if (e.isDirectory()) walk(p);
    else if (e.name.endsWith('.php')) check(p);
  }
}

function check(file) {
  const txt = fs.readFileSync(file, 'utf8');
  const lines = txt.split('\n');
  const rel = file.replace(/\\/g, '/');

  const qtextAt = lines.findIndex((l) => l.includes('=== QUESTION TEXT'));

  lines.forEach((line, i) => {
    const n = i + 1;

    const art = /(?:^|[^A-Za-z])(an?) ' \. \$([A-Za-z_]+)/.exec(line);
    if (art) findings.push({ kind: 'article', file: rel, n, detail: `${art[1]} ' . $${art[2]}` });

    if (/\$answers\s*\[/.test(line)) findings.push({ kind: 'answers-plural', file: rel, n, detail: line.trim().slice(0, 60) });

    if (qtextAt >= 0 && i > qtextAt && /^\s*\$answer\s*\[/.test(line)) {
      findings.push({ kind: 'key-after-qtext', file: rel, n, detail: line.trim().slice(0, 60) });
    }
  });
}

walk(root);

const groups = {
  'answers-plural': 'ANSWER KEY MISSING — $answers[ is a typo for $answer[',
  'key-after-qtext': 'ANSWER KEY IN THE WRONG SECTION — must sit in COMMON CONTROL',
  article: 'ARTICLE BEFORE AN INTERPOLATED NOUN — check it is singular and consonant-initial',
};

let hard = 0;
for (const [kind, title] of Object.entries(groups)) {
  const hits = findings.filter((f) => f.kind === kind);
  if (!hits.length) continue;
  console.log(`\n${title}`);
  for (const h of hits) console.log(`  ${h.file}:${h.n}  ${h.detail}`);
  if (kind !== 'article') hard += hits.length;
}

console.log(`\n${findings.length} finding(s); ${hard} are defects, the rest need a human glance.`);
process.exit(hard > 0 ? 1 : 0);
