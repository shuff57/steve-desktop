---
name: mom-question
description: Write, repair or review a MyOpenMath (IMathAS) question in Steve's question bank. Use when authoring a new question, fixing one that renders wrong or scores wrong, or filing a question into a book assignment. Covers the restricted PHP dialect, the render-and-verify loop, and where the bank lives.
---

# Writing a MyOpenMath question

MOM questions are **restricted PHP**, not PHP. The parser rejects things real PHP allows, and it
usually fails without saying clearly why — a broken question renders as an empty box or a
plausible-looking question with a silently empty variable. So the rule here is:

**Never judge your own output. Render it.**

## Where things live

The bank is `mom-content/` in the `steve-desktop` repo.

| Path | What |
|---|---|
| `mom-content/questions/<family>/[<subtopic>/]*.php` | The questions. One question per file. |
| `mom-content/books/<book>/<kind>/<assignment>.json` | Assignment manifests — which questions, in what order, worth what. |
| `mom-content/books/_books.json` | The books (courses) that exist. |
| `mom-content/reference/index.md` | Authoring docs: macros, answer types, libraries, styles. **Read the relevant page before using anything you are unsure of.** |
| `mom-content/reference/dialect-rules.md` | The rules below, as a file. |
| `mom-content/reference/learned-rules.md` | Rules the writer taught itself from real failures. Read these too. |

## The shape of a question file

Five markers, in this order. All five, always:

```
// === NAME - DESCRIPTION: <what the question assesses> ===
// === SET QUESTION TYPE TO: <number|choices|multipart> ===
// === COMMON CONTROL ===
// === QUESTION TEXT ===
// === ANSWER ===
```

The name is how the question gets found in a bank of hundreds, so name it for the skill it
assesses — "Two-Sample Hypothesis Test", not "Problem 7". Naming the scenario as well is fine when
it separates two similar questions ("Fitness Center Key Terms"); naming *only* the scenario is not.
The filename follows the same rule: no `qNN-` prefix on new questions.

## The dialect rules

Read `mom-content/reference/dialect-rules.md` — it is the authoritative copy and it is kept in step
with the app's own prompts by a test. Also read `learned-rules.md` beside it.

The traps that cost the most time, in short:

- **No trailing semicolons** in the control block. One statement per line.
- **Math is AsciiMath in backticks**, never TeX `$…$` — `$` means currency here.
- **The ANSWER section is CODE, not markup.** Put HTML in `$solutionguide` in the control block.
- **Braces evaluate.** `{$x}` in question text is parsed as an expression, so set-builder notation
  breaks the question. Use `&#123;` / `&#125;`.
- **`&&` and `||`**, never the words `and` / `or`.
- **Question text substitutes scalars only** — no nested array indexing. Precompute a scalar.
- **A `choices` answer is an INDEX** into `$questions[i]`, not the option text.
- **A rendered-twice diff proves nothing about randomization.** The render sandbox seeds
  deterministically — a properly randomized question returns byte-identical HTML on every POST.
  Verify randomization by reading the control block, and grep the combined pattern
  `diffrands|randsfrom|randfrom|jointrandfrom|rand\(` — a bare `rand\(` misses `diffrands(` and
  reports a correctly-randomized question as static. (full detail: `learned-rules.md`)

## The loop — this is the part that matters

Writing the file is one step of five. Do not stop after writing.

```
  write the .php
        │
        ▼
  render it ──────▶ POST the file's contents to https://mom.huffpalmer.fyi/
        │            (stateless IMathAS; returns a full HTML page)
        ▼
  read the render ──▶ diagnostics are printed INTO the page body, and the
        │             endpoint still answers HTTP 200 — a 200 proves nothing
        │
   clean │ broken
        │     │
        │     └──▶ read the docs for whatever the error names,
        │          fix, and render AGAIN (up to 5 repair rounds)
        ▼
  file it into the assignment manifest
```

Two things that look like success and are not:

- **HTTP 200 is not a working question.** IMathAS prints its complaints into the body.
- **A clean render is not preserved content.** A question truncated mid-sentence renders perfectly —
  a fragment is valid. After any scripted edit, diff the prose and the variable names against the
  original.

Engine noise to ignore: running stateless with no session, IMathAS emits
`Undefined global variable $myrights … in parsers.php` on perfectly healthy questions. A question's
*own* errors say "of Common Control" instead.

## Start from the section's problem set, not from the topic

A set is built against the numbered problems the section actually poses. Read them first:

```bash
cd mom-content && python reference/problem-set.py <section.html> --solutions
```

Save the bookSHelf section page's HTML locally, then run that against it. `--solutions` prints each
problem's worked steps, and those matter: a question taken from a numbered problem repackages
**that problem's own solution** into its `$solutionguide`, because that is the explanation the
student was taught from.

Then map every problem to a slot before writing a line of PHP. Three outcomes per problem — covered
by a question the bank already has, covered by one that needs its scenario changed, or not covered.
That map is the plan; write only the third column. Say in the brief which problems each question
covers, so a gap is visible rather than assumed.

Two shapes of problem recur and neither can be graded as posed:

- **"Construct a graph."** MyOpenMath cannot grade a drawing. Generate the display and ask about it
  instead, or hand the construction to a tool the student can use — raSHio does histograms, box
  plots, scatter with regression, normal curves and five-number summaries, but has no stem-and-leaf,
  line graph or bar graph. When the student builds it, fix the answer with the data and state the
  bin width and boundary rule, so what they build cannot differ from what you graded.
- **"Explain in complete sentences."** Homework carries no free response. Turn it into a choice
  between the explanation and the plausible wrong ones, which is what the problem was testing.

## Before writing anything, check what the bank already has

The bank is 500+ questions and a section's problems routinely repeat what an earlier section
already covers — 2.2's first five problems are frequency-table work that 1.3 covers outright.
Writing a fresh question for one of those is wasted effort twice over: once writing it, and again
every time it needs a repair that its twin already had.

```bash
cd mom-content && python reference/build-question-index.py   # regenerate, then search it
```

`reference/question-index.json` holds one entry per question — its `NAME - DESCRIPTION`, its
qtype, the assignments already using it, and its `qsetid` if it has been filed. Search it by
topic word before planning a set, and again before writing each question.

Three outcomes, in order of preference:

- **Attach it unchanged.** A question can serve any number of assignments; attaching costs one
  GET and copies nothing, so a later fix improves every section at once. Two questions covering
  the same ground is the thing to avoid, not one question in two places.
- **Copy and change the scenario.** When the topic fits but the context is wrong for the section,
  copy the file, change the context strings and the numbers, and leave the structure alone. The
  structure is what took the work and what has already been proven.
- **Write a new one.** Only when nothing covers it. Say in the brief what you searched for and
  what came back, so the gap is on the record.

Rebuild the index after adding questions, so the next run sees them.

**Check the `health` field before attaching anything.** It carries the result of the last full
replay: `ok`, `warns`, `BROKEN`, or `unchecked`. Nineteen questions in the bank do not render at
all — mostly a `SET QUESTION TYPE TO: multiple_choice` that MOM rejects, where the valid type is
`choices`. They are left unfixed deliberately; repair one **when a section picks it up**, not as a
sweep. Attaching a `BROKEN` question without reading that field puts a hard error in front of
students.

```bash
bun reference/regress.ts            # replay the bank, diff against the baseline
bun reference/regress.ts --write    # accept current results as the new baseline
```

Run the replay after changing a rule that affects how questions are written, and after any scripted
edit across many files. It is the only thing that checks whether a new rule broke a question written
six sections ago; rendering just the question in front of you never will.

## How many questions a set should hold

**Between 10 and 15, aiming at 15.** These are practice assignments, so the count is a teaching
decision — a section with eight numbered exercises does not mean eight is enough drilling. Keep every
numbered exercise the section has, then invent the difference. Past 15 a single sitting becomes a
slog; keep the ones that cover the most ground and say which you dropped.

What you invent has to earn its slot. Two kinds do:

- a **composite** that makes the student carry one concept into another in one question — build the
  frequency table, then decide which display is honest for it;
- another angle on whatever the section teaches that **students most reliably get wrong**.

Say in the brief which kind it is, and for the second, name the mistake it targets. Re-running an
exercise with new numbers adds length, not practice.

## Filing it into an assignment

Only file a question that renders clean — a broken question must never enter a book. Append to the
manifest's `questions` array, continuing the `slot` numbering. Edit the JSON in place: these files
are hand-formatted and a full round-trip rewrites every line.

Do not invent a `target.cid` or `mom_settings`. Those describe a live MyOpenMath assignment; writing
plausible values puts a lie in the file.

## Things not to do

- Do not edit a question you were not asked to edit. The bank is live teaching content.
- Do not copy an existing question's wording or numbers. Randomize, so each student sees a different
  version.
- Do not delete a question from disk to "fix" it in MyOpenMath. Deleting the file does not remove
  the live question — its `qid` must be deleted in the course itself.

## In the app

Steve's desktop app does all of this from the MOM tool's Write rail: it plans a set from a bookSHelf
section, writes each question, renders and repairs it, files it, and then reflects on any failures
to append a new rule to `learned-rules.md`. The app builds its own prompts from
`src/integrations/mom/`, not from this file; this skill is the same knowledge for any agent, in or
out of the app.

---

**This file is installed and maintained by S.T.E.V.E Desktop.** It is compiled into the app and
written to `~/.claude/skills/mom-question/` on launch, so it arrives with the app and updates with
it. Edits here are replaced on the next launch — change the copy in the repo at
`steve-desktop/skills/mom-question/SKILL.md` instead. The rules it points at
(`dialect-rules.md`, `learned-rules.md`) are ordinary files and are yours to edit freely.
