# The pre-FRQ pattern

A pre-FRQ is the **auto-graded mirror of a free-response question**. It reuses an FRQ's scenario and
its grading checklist, then replaces the writing with grading: the student marks four sample
responses against the rubric instead of producing one.

That is what lets it live on a homework at all. Homework is fully auto-graded (see `mom-transfer`,
"Homework carries no free response"), and a pre-FRQ teaches the FRQ's standard while still marking
itself.

## The rule

**One to three per assignment, never zero.** Steve's call, 2026-08-10. The count inside that range
is the author's, and the default is one — take two only when the section carries two genuinely
different FRQ-able skills, three only for a chapter capstone.

**"No FRQ to mirror" is not an exemption.** If nothing in `questions/frq/<family>/` covers the
section, the pre-FRQ gets written anyway, and it defines the scenario and rubric a later FRQ should
match. Say in the manifest `_note` which FRQ each pre-FRQ mirrors, or that one had to be authored.

## Placement and naming

- **Last slot of the assignment, 12 points.** True of all seven originals.
- `questions/<family>/pre-frq-<verb>-a-<thing>.php` — `pre-frq-grade-a-display-choice`,
  `pre-frq-judge-a-distribution-comparison`.
- Description line: `Pre-FRQ <Title> - The scenario and grading checklist of the <topic> FRQ, but the
  student grades four sample responses against the rubric instead of writing one`.

## The invariant structure

Verified across all ten. Deviating from any of this is a defect, not a style choice:

```
$anstypes = array("choices", "multans", "choices")     // three parts, always
$answer[0]  = <index of the full-credit response>
$questions[1] = array(<category (N pts)>, ...)          // the rubric, verbatim
$answer[1]  = "<comma-separated indices>"
$scoremethod[1] = "allornothing"                        // partial ticking must fail
$questions[2] = array(<4 options>)                      // why the dropped category is separate
$answer[2]  = 0
```

- **Part (a)** — which response earns full credit on every category.
- **Part (b)** — grade one specific response against the checklist. `allornothing` is what stops it
  being passed by ticking everything.
- **Part (c)** — the concept: why the dropped category is a *separate* judgement rather than
  something implied by the others. Four options, one right, and the three distractors should be the
  reasoning a student would actually use, not filler.

Build the four responses by concatenating one sentence per rubric category, then dropping one:

```
$rFull      = $sCat1 . ' ' . $sCat2 . ' ' . $sCat3
$rDropped   = <the two that remain>      <- this is the one part (b) grades
$rOther     = <a different category missing>
$rMinimal   = <one category, plus a sentence that sounds like closure>
```

**Take those sentences from the mirror FRQ's own `$r_*` target strings** where they exist. Then a
student studying the pre-FRQ is reading the exact prose the FRQ rewards, rather than a paraphrase
that drifts from it.

## The dropped category

Each pre-FRQ targets **one** rubric line that students habitually skip — the one that feels already
covered once the visible work is done. Part (c) exists to name that habit.

**Do not reuse a dropped category across assignments.** Two pre-FRQs teaching the same lesson waste
a slot. Used so far:

| Assignment | Mirror | Dropped category |
|---|---|---|
| 2.4 | q8 five-number-summary | Contextual Interpretation |
| 2.5 | q9 measure-of-center | Outlier Impact |
| 2.6 | q5 bimodal | Further Investigation |
| 2.7 | q7 means-and-SDs | Practical Conclusion |

## Two legitimate variants

Both are in use and both are fine. What is **not** fine is a key that does not match its prompt.

**1. Part (a) options.** Either the four labels `"Response A".."Response D"` with a `$pos` rotating
which letter is full credit, or the four response *texts* directly with `$answer[0] = 0`. The label
form needs `$pos` or the answer is always "Response A"; the text form does not, because MOM shuffles
the display and there is no label to memorise.

**2. Part (b) polarity.** Either *"select every category it earns"* or *"select every category it
fails to earn"*. Eight use earns, two use fails. Fails is the better item where only one category is
missing, because "select the one it earns" would be trivially short; earns is better where most
categories are missing. **The key must be the complement of the other reading** — write the prompt
first, then derive the indices from it.

## Traps

- **`$answer[1]`, not `$answers[1]`.** The plural silently does nothing: the part gets a
  `$scoremethod` and no key at all, and the question still renders and still saves. Found live on
  `pre-frq-grade-a-sampling-critique` (1.2) and `pre-frq-judge-a-distribution-comparison` (2.1) on
  2026-08-10, both already pushed. Grep for `$answers[` before filing anything.
- **Quote arithmetic that is true for the seed.** Every number inside a sample response has to be
  generated from the same variables the scenario uses, or some seeds show a response whose figures
  contradict the data above it.
- **Part (b) must grade a different response than part (a) names.** Otherwise the second part is
  answered by the first.
- **Scope the CSS** with a per-question wrapper class (`.qscope8`, `.qscope9`), as several of these
  can share one assignment page.
