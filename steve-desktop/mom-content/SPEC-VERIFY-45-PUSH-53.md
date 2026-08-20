# SPEC — verify 4.5 actually grades, then push 5.3

**Book:** introduction-to-stats-sh
**Skill:** mom-transfer

Two tasks. Do **Task A first and report**, then Task B.

Drive Playwright over CDP `http://127.0.0.1:9223` via
`C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/_push/mom.mjs`.
Not browser-harness. **Do not kill the Chrome process.**

Repo root for relative paths: `C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/mom-content/`

**If any path or id does not resolve, STOP and say so rather than guessing.**

---

## Task A — 4.5 Continuous Probability Functions, aid 23444255

**It has never been answered.** Visual review 2026-08-16 found the Teacher Preview reading
`Score: 0/100, Answered: 0/10` — every box empty, every radio unselected. Its manifest is the only
pushed assignment in the book with no `_pushed` note. The questions are filed, attached and
correctly pointed (verified), and their sources were repaired today, but **nothing has ever
confirmed they grade.**

Rendering is not testing. A question can render perfectly, throw nothing, and still be broken: a
dropdown that never populates, an answer box that rejects the right value, a `multans` whose key
does not line up with its options.

Do this: open `/assess2/?cid=334437&aid=23444255`, click **Teacher Preview**, and for **every** one
of the ten questions — enter the answer, **submit it**, and confirm it comes back correct.

The answers are deterministic from each question's own source under
`questions/probability/uniform/`. Read the source to get them; do not guess from the rendered text.
Note the assignment is a uniform-distribution set, so most answers are `(d-c)/(b-a)` style
arithmetic that you can verify independently.

Finish condition: header reads `Answered: 10/10` and the score is 100 or 102 (102 is the 2%
early-finish bonus on a 100-point total and is expected).

**Report any question that does not reach full marks, with the answer you entered and what it said.**
Do not "fix" a question by changing what you enter until it passes — if a correct answer is marked
wrong, that is the defect and I want to know.

Then write the `_pushed` note onto
`books/introduction-to-stats-sh/hw/4-5-continuous-probability-functions.json`, in the shape used by
`hw/4-3-binomial-distribution.json`, recording what you verified. **Patch it as TEXT** — these
manifests are hand-formatted one-question-per-line and a JSON round-trip rewrites every line.

STOP and report before Task B.

---

## Task B — push 5.3, aid 23444259

`lab/5-3-normal-distribution-lap-times.json`, 10 questions, 100 points, kind `lab`.

**The assessment already exists and is EMPTY — do not create one.** aid `23444259` is already on the
manifest as `target.aid`. Do not touch its settings; the ch1-12 scaffold was built with them.

Full flow per `~/.claude/skills/mom-transfer/SKILL.md`, minus creation:
file → attach → points → Teacher Preview → write back → sync-index.

### Traps, every one of which has bitten in this course today

1. **`reference/question-library.json` outranks a manifest `qid`.** Check all 10 source paths
   against it before filing. A question already in the index gets attached, never re-filed.
2. **The five `// === ... ===` marker lines are DELIMITERS, not content.** Strip the marker line and
   any blank line after it from each section before filing. A push earlier today filed them and
   `// === QUESTION TEXT ===` rendered as visible text on all ten questions of 5.2.
3. **CodeMirror saves empty, silently.** `control`/`qtext`/`solution` are CM instances — use
   `cm.CodeMirror.setValue()`. Set all three, then click the **single visible**
   `<button type="button">Save</button>` **once**; clicking repeatedly races and the solution field
   intermittently fails to commit.
4. **`qtype` defaults to `number` and a wrong qtype renders perfectly clean.** Click the `#qtypedd`
   entry and assert the hidden `[name=qtype]` changed before saving.
5. **Points must total exactly 100**, set per instance on
   `modquestion2.php?id=<instanceid>`, then read every value back.
6. **assess2 is a Vue SPA** — the Teacher Preview button does not exist on load. Wait for it, click,
   then wait for widgets. Widgets are MathQuill spans and radios, so `input[type=text]` counts 0 on
   a healthy page. MathQuill here is `window.MathQuill.MathField(el)`, not `getGlobalMathQuill`.

### Verify

- Byte-exact read-back per field against the source, marker stripped. Normalise only CRLF→LF,
  em dash→`--`, ellipsis→`...`.
- qtype matches each source's `SET QUESTION TYPE TO:` marker.
- Rendered page: **zero** `=== QUESTION TEXT ===` / `=== COMMON CONTROL ===` / `=== ANSWER ===`,
  **zero** concat leaks (search `' . ` and ` . '` with an **unbounded** pattern — the leak can run
  50+ characters and a short pattern missed it once already), zero `Eeek!`.
- Teacher Preview: every question answered, submitted, graded correct, `Answered: 10/10`.
- Write back `qid` per slot, `target.aid`, a `_pushed` note — all as TEXT patches — plus
  `question-library.json` entries.
- Then: `bun mom-content/reference/sync-index.ts` and `--check` must exit 0.

## Report

Per question: slot, source, qsetid, filed-or-reused, qtype, points, byte-match y/n, graded y/n.
Per assignment: marker count, concat-leak count, Eeek count, points total, Answered n/n.
State any check you could not perform. If you have no image input, say so — the visual pass is
routed elsewhere.

`mom-content/questions/` is CLAIMED by claude: read those sources, do not write them. Report a bad
source rather than editing it.
