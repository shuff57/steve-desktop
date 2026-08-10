# SPEC — the six remaining questions for 3.5 Tree and Venn Diagrams

Write six IMathAS question files into `mom-content/questions/probability/`. Four of the section's ten
already exist and are the pattern to copy — **read `q31`, `q32`, `q33` and `q34` in that directory
before starting.** They establish the file shape, the inline-SVG approach and the solution-guide
voice. Match them.

Do not push anything. Do not touch any manifest. Write files only.

## Self-check before you report

```bash
node mom-content/reference/question-lint.mjs mom-content/questions
```

Exit 0 with no new findings for your six files. It checks: every `$answerbox[N]` has a matching
`$answer[N]`; no `$answers[` (plural — it silently leaves a part with no key); no `$answer[]` after
the QUESTION TEXT marker (that section is the SOLUTION field, not the key); no article in front of an
interpolated noun; no marker text quoted inside a comment.

**Also seed-sweep each one yourself** with a throwaway node script: loop every combination your
`rand()` calls can produce and assert the invariant named per question below. One render proves one
seed; the sweep is what proves the rest. Report the number of combinations checked.

## The non-negotiable dialect rules

- **No semicolons** at the end of statements.
- **`$answer[...]` goes in COMMON CONTROL**, never in the ANSWER section. The five marker sections
  map to description / qtype / control / qtext / solution. The last one is the solution guide.
- A one-part question may use scalar `$answer = ...`; multipart uses `$answer[0]`, `$answer[1]`, …
- Strings concatenate with `.` — `'text ' . $var . ' more'`. In raw HTML, `$var` and `{$var}`
  interpolate; **`${var}` does not**.
- Escape single quotes inside single-quoted strings as `\'`.
- `&#36;` for a literal dollar sign, `&mdash;` for an em dash, `prettyint()` for thousands.
- Never put an article directly before an interpolated noun (`'a ' . $who`) — it is right for one
  randomised context and wrong for the next. Reword, or carry a singular array.
- Inline SVG is built by string concatenation, opens with `role="img"` and an `aria-label`, and must
  have exactly one `<svg` and one `</svg>`.

## The six

Each is `multipart`, auto-graded, no `essay` parts. Contexts come in threes, chosen by
`$i = rand(0, 2)`, as in the existing four.

### q35-read-a-given-tree.php — 4 parts
A two-stage tree drawn complete, with **unequal** branch probabilities (a screening-test shape: a
rare first stage, then two very different second stages). Parts: multiply along each of two named
paths; total the probability of the second-stage event across both paths; confirm all four leaves
sum to 1. **Invariant to sweep: the four leaf probabilities sum to exactly 1 on every seed.**

### q36-conditional-from-a-venn.php — 4 parts
A Venn with four region counts. Ask for a conditional both directions, restricting to one circle so
the denominator is a **region total, not the grand total**. Make the two directions come out
different. **Invariant: the two conditionals differ on every seed, and each denominator is the
correct circle total.**

### q37-three-draws-without-replacement.php — 4 parts
Three draws from a small collection, no replacement. This is the point where drawing the tree stops
being worth it — say so in the solution guide — and the path product has to carry the reasoning.
Parts: all three the same; at least one of the other kind; the third-draw conditional given the first
two. **Invariant: every denominator steps down 1 each draw and no numerator ever goes negative.**

### q38-set-operations-on-a-venn.php — 4 parts
Venn with counts. Ask for `A and not B`, `not A and not B`, `A or B`, and the complement of the
union. **`A and not B` is the one with no formula** — it has to be read off the picture, and the
solution guide should say that plainly. **Invariant: the four regions sum to the total.**

### q39-at-least-one-two-ways.php — 3 parts
Two or three trials. Part (a): add every path that satisfies "at least one". Part (b): the same
number via `1 - P(none)`. Part (c) is `choices` — why the complement is the route worth learning.
The two numbers **must** agree; that is the whole question. **Invariant: (a) equals (b) exactly on
every seed.**

### pre-frq-grade-a-diagram-setup.php — 3 parts
Follow `mom-content/reference/pre-frq-template.md` exactly, and copy the structure of
`pre-frq-grade-a-conditional-reading.php`.

- `$anstypes = array("choices", "multans", "choices")`, `$scoremethod[1] = "allornothing"`.
- Rubric, 10 points: **Draw the Structure (3)** / **Fill the Branches or Regions (4)** /
  **Answer the Question Asked (3)**.
- **Dropped category: DRAW THE STRUCTURE.** Students produce a plausible-looking product without
  ever committing to a tree or a Venn, so nothing catches a mis-stated second-stage probability.
- **Category purity is the hard requirement.** Each of the three sentences must earn its own rubric
  line and no other. Build the four responses by concatenating the three sentences and dropping one.
  Then read every response against **every** rubric line and ask whether a fair grader could award a
  category it is supposed to be missing. If yes, trim until they cannot. This defect shipped twice
  before it was caught, and it is invisible to every automated check.
- Part (b) must grade a **different** response than part (a) names.

## Report

Per file: the invariant you swept and how many combinations, the lint output, and anything you could
not check. State plainly which of the six you did not finish, if any — an honest short list beats six
files where two were rushed.
