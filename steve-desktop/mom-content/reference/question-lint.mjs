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

  // $answers is NOT always a typo. It is the documented variable for the types below:
  //   draw / essay-file  reference/question-types/essay-file.md  "$answers -- string or array of
  //                      strings describing points/curves"
  //   choices / multans  reference/question-types/choice.md      "$answers -- list of correct indices"
  //   matching           reference/question-types/choice.md      "$answers -- array of answers"
  // Flagging those was noise: 5 of 11 hits on questions pulled live from MOM in Aug 2026 were draw
  // questions that score correctly in front of students. The original bug (1.2, 2.1) was a
  // single-answer question, so the rule is kept for everything else.
  const qtypeLine = lines.find((l) => l.includes('=== SET QUESTION TYPE TO:')) || '';
  const qtype = (qtypeLine.split('SET QUESTION TYPE TO:')[1] || '').replace(/=+/g, '').trim();
  const answersIsValid = ['draw', 'essay', 'file', 'choices', 'multans', 'matching'].includes(qtype);

  // 4. The splitter matches the marker text anywhere, including inside a comment, so a comment that
  // QUOTES a marker cuts the file in the wrong place. (Marker COUNT is deliberately not checked:
  // essay FRQs and older questions legitimately carry three or four, and a rule that fires on fifty
  // valid files is noise that gets ignored.)
  lines.forEach((line, i) => {
    if (/^\s*\/\/ === [A-Z]/.test(line)) return;
    if (/=== (ANSWER|QUESTION TEXT|COMMON CONTROL|NAME - DESCRIPTION|SET QUESTION TYPE TO) ===/.test(line)) {
      findings.push({ kind: 'marker-count', file: rel, n: i + 1, detail: 'marker text inside a non-marker line' });
    }
  });

  // 5. Every $answerbox[N] needs a matching $answer[N]. This is the check that subsumes the two
  // key bugs above and catches any other route to a keyless part -- a box with no key renders and
  // saves and simply cannot score.
  // Hand-graded part types legitimately have no key, so they are exempt. Without this the check
  // fires on every essay FRQ in the bank and becomes noise.
  const HAND_GRADED = new Set(['essay', 'file', 'draw']);
  const atMatch = /\$anstypes\s*=\s*array\(([^)]*)\)/.exec(txt);
  const anstypes = atMatch ? atMatch[1].split(',').map((s) => s.trim().replace(/^["']|["']$/g, '')) : [];

  const boxes = new Set([...txt.matchAll(/\$answerbox\[(\d+)\]/g)].map((m) => m[1]));
  const keys = new Set([...txt.matchAll(/^\s*\$answer\[(\d+)\]\s*=/gm)].map((m) => m[1]));
  // A one-part question keys with a SCALAR `$answer = ...` and IMathAS accepts it against
  // $answerbox[0]. Verified live: q2-probability-card-draw grades correctly on 3.1 with this form.
  if (/^\s*\$answer\s*=/m.test(txt)) keys.add('0');
  // Keys assigned through a variable index (`$answer[$i] = ...`, usually in a loop) cannot be
  // resolved without running the question, so this file is skipped rather than reported. Saying
  // nothing is correct here; guessing would make the check untrustworthy on the files it CAN judge.
  const dynamicKeys = /\$answer\[\s*\$/.test(txt);
  const missing = [...boxes]
    .filter((b) => !keys.has(b))
    .filter((b) => !HAND_GRADED.has(anstypes[Number(b)]))
    .sort((x, y) => Number(x) - Number(y));
  if (missing.length && !dynamicKeys) {
    findings.push({ kind: 'box-without-key', file: rel, n: 0, detail: `answerbox ${missing.join(', ')} has no $answer[]` });
  }

  lines.forEach((line, i) => {
    const n = i + 1;

    const art = /(?:^|[^A-Za-z])(an?) ' \. \$([A-Za-z_]+)/.exec(line);
    if (art) findings.push({ kind: 'article', file: rel, n, detail: `${art[1]} ' . $${art[2]}` });

    if (!answersIsValid && /\$answers\s*\[/.test(line)) findings.push({ kind: 'answers-plural', file: rel, n, detail: line.trim().slice(0, 60) });

    if (qtextAt >= 0 && i > qtextAt && /^\s*\$answer\s*\[/.test(line)) {
      findings.push({ kind: 'key-after-qtext', file: rel, n, detail: line.trim().slice(0, 60) });
    }
  });
}

walk(root);

const groups = {
  'box-without-key': 'ANSWER BOX WITH NO KEY — that part cannot score',
  'marker-count': 'MARKER PROBLEM — the splitter will cut this file in the wrong place',
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
