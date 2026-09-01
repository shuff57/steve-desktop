# Review needed

Things found while writing question sets from bookSHelf that Steve needs to decide on or fix.
Appended as they are found; nothing here is fixed unless it says so.

Three kinds of entry:

- **SOURCE** — a defect on the bookSHelf page itself. The question set can't be right until the page is.
- **BLOCKED** — a problem-set exercise I could not turn into a gradable MyOpenMath question.
- **TOOLING** — a bug or limit in the app / dialect that cost a real failure.

---

## SOURCE

### 3.3 Problem Set — the shared setup is mathematically impossible (inherited from OpenStax)

`chapter-3-probability-topics/3.3_two_basic_rules_of_probability`, problems 1–10.

**Not a bookSHelf defect.** Verified against the source: this is OpenStax *Introductory Statistics 2e*,
section 3.3 Practice, exercises 44–53, transcribed word for word, same `Z` and `S`. The erratum is
upstream.

**The section itself is complete and correct.** Every formula problems 1–10 need is present —
the multiplication rule in both orders (Def 3.3.1), the conditional rearrangement, the independence
shortcut and test (Def 3.3.2), the addition rule (Def 3.3.3), the mutually-exclusive shortcut
(Def 3.3.4), and the complement (Try It Now 3.3.7). The section even pre-warns about the trap in
problem 5. Every worked example in the section is internally consistent — Try It Now 3.3.5
(0.40 / 0.30 / 0.5) and Example 3.3.4 (0.2 / 0.65 / 0.25) both check out. Only the imported
practice set does not.

The setup gives `P(Z) = 0.87`, `P(S) = 0.32`, `P(S|Z) = 0.55`. Those three cannot coexist:

- Problem 5's answer is `P(Z AND S) = (0.87)(0.55) = 0.4785`. But `Z ∩ S ⊆ S`, so `P(Z AND S)` can never exceed `P(S) = 0.32`.
- Problem 8's answer is `P(Z OR S) = 0.87 + 0.32 − 0.4785 = 0.7115`. But `Z ⊆ Z ∪ S`, so `P(Z OR S)` can never be below `P(Z) = 0.87`.

Both of the section's headline answers are unreachable. The *methods* being taught are right; only the numbers are impossible.

**Status:** worked around in the question set, not fixed on the page. My versions randomize under two constraints that keep the scenario consistent — `P(A)·P(B|A) ≤ P(B)` and `P(A) + P(B) − P(A AND B) ≤ 1` — so students get numbers that actually work while every solution step stays identical to the book's. **The page still shows the impossible version.**

**Your call, since this is upstream:** leave the page matching OpenStax and let the MOM questions
quietly use consistent numbers (what I've done), or correct the page's numbers and diverge from the
source. Changing `P(S)` from 32% to anything ≥ 48% fixes it with the smallest possible edit and
leaves all ten solutions' wording intact.

### 2.2 Problem 10 — the histogram it depends on is not on the page

Separate from, and worse than, the misplaced sentence below. Problem 10 asks for "the percentage of
people who own at most three T-shirts" and the page's own solution says outright:

> "Note that this exercise refers to a histogram published with the original survey which is not
> reproduced here; the method above is what the exercise is testing."

The page carries six images and none of them is that histogram, so the bar heights the question
needs do not exist anywhere on the page. **The published answer of 41 cannot be derived by any
student.** Moving the setup sentence fixes Problem 11; it does not fix this.

**Reconstructed data, if you want to draw the histogram rather than rewrite the problem.** The page
states three figures — 41 (at most three), 21 (at most two, as a distractor) and 59 (four or more,
as a distractor) — and those three pin the distribution down. These counts over 111 shoppers
reproduce all three exactly:

| T-shirts owned | Shoppers | Relative frequency |
|---|---|---|
| 1 | 10 | 0.090 |
| 2 | 13 | 0.117 |
| 3 | 23 | 0.207 |
| 4 | 40 | 0.360 |
| 5 | 25 | 0.225 |
| **Total** | **111** | **1.000** |

At most three = 46/111 = 41.4% &rarr; 41. At most two = 23/111 = 20.7% &rarr; 21. Four or more =
65/111 = 58.6% &rarr; 59. These are *not* the original survey's numbers — they are the simplest
whole-shopper counts consistent with every figure the page already prints.

### 2.1 — multi-problem instruction glued to the wrong problem

`chapter-2-descriptive-statistics/2.1_...`. The line "For the next three exercises, use the data to construct a line graph." sits at the end of **Problem 4**'s prompt, immediately before its Solution. Problem 4 is a stemplot problem; that instruction introduces Problems 5–7.

### 2.2 — same defect, and it destroys the following problems

`chapter-2-descriptive-statistics/2.2_...`. "For the next two exercises: suppose 111 people who shopped in a special T-shirt store were asked the number of T-shirts they own costing more than $19 each. Their answers were displayed in a relative frequency histogram with bars for 1, 2, 3, 4, and 5 shirts." is buried at the end of the preceding histogram problem.

That sentence is the **only** place the 111 shoppers and the bar setup appear, so the two problems it feeds have no stated context at all.

Swept all 28 built sections for this pattern — only 2.1 and 2.2 have it. 4.5's "For the following exercises, draw the picture." is correctly placed between problems, and 2.3's are Try It Now prompts, not orphans.

---

## BLOCKED

*(none — all 14 of 3.3's problems became gradable questions)*

---

## COVERAGE AUDIT — chapter 1 (2026-08-08)

Counts alone do not prove coverage, so each section was checked problem by problem against what
its manifest actually files.

| Section | Problems | Filed | Verdict |
|---|---|---|---|
| 1.1 | 11 | 10 → **11** | was missing Problem 1; **fixed** |
| 1.2 | 42 | 15 | **short — see below** |
| 1.3 | 8 | 15 | complete (all 8 covered, 7 extra) |
| 1.4 | 7 | 17 | complete (all 7 covered, 10 extra) |
| 1.5 | — | — | no problem set; nothing owed |

### 1.1 — fixed, and the fix was a filing error not a writing one

`q01-fitness-center-key-terms.php` (Problem 1) was **already written and rendering clean**, but was
referenced by no manifest in the repo. It is now filed as slot 11.

Appended rather than inserted at problem order: 1.1 carries live MOM `qid`s, so renumbering existing
slots would break the mapping to questions already pushed to the course.

A sweep for the same failure across the whole bank found **7 other written-but-unfiled questions**:
`descriptive-stats/q1-descriptive-vs-inferential.php`, and in `probability/` — `addition-rule.php`,
`basic-probability.php`, `conditional-probability.php`, `expected-value.php`,
`q25-prob-set-ops-dice.php`, `q26-independence-multiplication-rule.php`. Worth deciding whether each
belongs in a section or should be deleted; they are invisible to every assignment right now.

### 1.2 — the real gap

The 15 filed questions are organised **thematically, not one per problem**. Three of them
(pie-chart validity, choose-the-right-display, sampling with/without replacement) match no 1.2
problem at all — they look like chapter-2 material.

Blocks with **no question covering them**:

| Problems | What they ask |
|---|---|
| P5 | "The population is ______" for the neighbourhood-park setup |
| P6 | Earthquake-deaths table over 13 years |
| P11–P12 | Complete two researchers' frequency tables; identify the key term |
| P20–P23 | Sample-size adequacy for two different populations; conclusions from differing data; whether a gift-card incentive announced in advance biases the data |
| P29–P33 | Reliability judgments — 1,000 of 5,000; 500 volunteers of 2,500; a leading survey question; 2 of 5; whether two well-run experiments can differ |

Partial coverage only on **P13–P19** (two researchers, differing data) and **P24–P28** (the two
software studies — funding, causation, confounding).

Bringing 1.2 to 1:1 is roughly **13 new questions minimum**, more if P13–P19 and P24–P28 are split
out rather than kept as multipart blocks.

---

## DEVIATIONS — where a question does not mirror its source exercise

### 3.3 Problem 11 — context generalised away from a real named survey

The source attaches its four figures to a real, named event: a 2015 survey of California
registered voters on same-sex marriage and Proposition 8. Randomizing those percentages —
which is what you asked for — would put invented statistics in the mouth of a real survey
about a real ruling.

So the question keeps the **probability structure exactly**: two unconditional rates, one
conditional on an age band, one conditional on support, and all ten parts a–j in the same
order. Only the subject changes, to a randomized local ballot measure. Every solution step
matches the original.

Say the word if you would rather have the original context with the original fixed numbers
and no randomization — that is the honest alternative, and it is a one-file change.

### 3.3 Problems 13 & 14 — the wheel is fixed, so randomization moved

A roulette wheel has 38 pockets and always will, so the usual "randomize the numbers" move is
not available. Instead:

- **Problem 13** randomizes the colour asked about, and the range — between a dozen (12 pockets,
  6 even) and a half (18 pockets, 9 even). That changes the answers to parts c and g, not just
  the wording.
- **Problem 14** rotates which of the seven bets appears as part (a), (b), … by a random offset,
  so every student answers all seven bets but each part is a different bet with a different
  answer. Rotation rather than a shuffle because `shuffle()` is rejected by the parser.

---

## FROM THE 1.4 PUSH (2026-08-08)

### FIXED — three questions carried free-response parts

Per your instruction that homework must not need hand-grading, the `essay` part b in
`misleading-axis-scale-comparison`, `raw-count-vs-rate-airline-complaints` and
`lurking-variable-observational-study-critique` is now four-option multiple choice, with the
distractors drawn from the plausible wrong fixes (resize the pictures, add a legend, give each graph
its own axis; count aircraft or years operating; survey more people, or just mention the confounders).

Two of the three had been scoring on `$scoremethod = "takeanything"`, which awards full marks for
*any* text typed — so they were never actually assessing anything. The third had no `$answer` at all
and was silently costing students half the question. Both problems are gone with the conversion.

**No homework question in 1.1–1.4 now has a free-response part.** I swept the bank: the only
remaining `essay` questions are the 75 files under `questions/frq/`, which are meant to be
hand-graded tests, not homework.

### FIXED — two questions had answer keys no student could match

`repeated-measures-design-identification` and `randomized-block-design-application` store several
accepted phrasings in one string separated by the literal `" or "`. Where an accepted phrasing
itself contained the word "or", MOM split it into fragments — so
`"order effects, such as practice or fatigue, ... or which session came first, so that practice or
fatigue ..."` became four sentence fragments and neither intended answer was accepted. One part was
completely unanswerable; another accepted only one of its two intended phrasings. Rewritten to use
"and" internally. Also removed `&ndash;` from inside an answer, which would have had to be typed as
the literal entity.

### Not fixed — your call

- **`design-experiment-full-anatomy` (slot 1) asks six free-text questions.** They are auto-graded,
  so they do not create marking work, but each one needs near-exact phrasing — "the individual
  volunteers enrolled in the sleep study" and nothing else. A student who understands the material
  perfectly will still lose most of these. Same issue, smaller, in
  `audit-frequency-table-errors` part (e) in 1.3. Converting them to multiple choice is the same
  move I just made on the three essay parts; say the word.
- **12 of 1.4's 17 descriptions were over MOM's 254-character limit** and I shortened them, syncing
  the manifest titles to match. 17 over-length descriptions remain elsewhere in the bank.

---

## FROM THE 1.3 PUSH (2026-08-08)

Three of 1.3's fifteen questions were **broken and are now fixed**. All three passed the render
check and `questionHealth` cleanly; only filling the questions in and submitting them found the
problems. They are recorded here because of what they say about the rest of the bank, not because
anything is outstanding.

### FIXED — `classify-measurement-scale` had a wrong answer key

`$pool_answers[6]` said **Nominal** for "Political outlook: extreme left, left-of-center,
right-of-center, extreme right". Those categories are ordered along a spectrum, so it is **Ordinal** —
and the item's own `$pool_reasons` entry described an ordinal scale, contradicting the key beside it.
A student answering correctly was marked wrong.

The wider point: this question draws **12 items from a pool of 20**, so eight keys are invisible on
any given run. I checked all twenty by hand; only that one was wrong. Any pool-based question hides
most of its answer key from any single test, so pools need auditing as a whole, not by sampling.

### FIXED — `audit-frequency-table-errors` was unanswerable on two draws in three

`$vals` (the table's "Years in US" column) is fixed at `0, 2, 4, 5, 7, 10, 12, 15, 20`, but two of
the three `$rawDataSets` used completely different values (`1; 1; 1; 3; 3; 4; ...`) and frequencies
that matched neither. A student who actually tallied the data could not reconcile it with the table
they were asked to correct, and the questions asked about values that were not in the data at all.
Only `$ci = 0` was coherent.

Rebuilt both broken sets so each raw list is exactly the tally of its own frequency row over the
same `$vals`, and left a comment saying that changing one without the other silently breaks it again.

### FIXED — `grouped-frequency-table-and-graphs` leaked PHP into the question

QUESTION TEXT contained `' . $total . '` style concatenation, which is only valid inside CONTROL
block strings. Students saw literal `' . 60 . '` and `' . . '` on the page. It rendered without any
error and `questionHealth` passed it.

**Four more files still have this and I have not touched them, because they are live teaching
content outside this assignment — your call:** `trig/sine-transformation-anchor-table.php` (4
occurrences), `trig/sine-sign-by-quadrant.php` (3), `trig/sine-graph-key-points.php` (1),
`trig/match-sine-equation-to-graph.php` (1).

### Not fixed — things worth a decision

- **29 more descriptions exceed MOM's silent 254-character limit.** I shortened the five that 1.3
  needed. The rest are stored truncated mid-word in MOM, which hurts findability in the library.
  Worst offenders: `survey-percent-and-extrapolation` (392), `control-group-placebo-blinding-matching`
  (373), `randomized-block-design-application` (357). Note `q20-do-the-percents-allow-a-pie-chart`
  and `q21-percent-versus-count-comparison` are already filed truncated from the 1.2 push.
- **`audit-frequency-table-errors` part (e) is free text matched against four fixed sentences.** A
  student would have to phrase their explanation almost word for word to get credit. It is worth
  either dropping the part or making it multiple choice.
- **`grouped-frequency-table-and-graphs` part (a) is ambiguous.** "The frequency for CEO ages
  between 54 and 65" — the key counts every interval that *overlaps* 54–65 (50–54, 55–59, 60–64,
  65–69 = 46), but the natural reading is the intervals fully inside it (55–59, 60–64 = 27). Either
  answer is defensible from the wording; only one is accepted.
- **Cosmetic:** in the same question the table header row has cell borders and the body rows do not,
  because `$tableRows` is built without the border styling.
- **Cosmetic:** `diagnose-cumulative-vs-individual-percent-claim` renders "exactly 1 loyalty
  purchases" (plural after 1) and starts a sentence lowercase with "the store's marketing report".

---

## FROM THE 1.2 PUSH (2026-08-08) — your call, none of these blocked the push

### 1.2 is 28 questions, roughly double the guidance

`mom-question`'s SKILL says a set should be 10–15. 1.2 now files 28, because you asked for one
question per uncovered problem and 1.2's problem set has 42 problems. It pushed clean and totals 100
points, but it is a long sitting for a student. The honest options are: leave it as one long
assignment, or split it into 1.2a (data types and sampling methods, slots 1–15) and 1.2b (judging a
study, slots 16–28), which is where the material naturally divides anyway. Say the word and the split
is a manifest edit plus one more assessment.

### `complete-grouped-frequency-table-survival` can draw a trivial part (c)

Part c asks for the **cumulative** relative frequency through a named interval. When the randomizer
names the LAST interval — which it did on this run — the answer is 1.0000 by definition, and the
student can write it without reading the table. Worth constraining the draw to exclude the final
interval so part c always requires the running total.

### 1.1's assessment in the question class has no Book link

`showextrefs` is ticked on 1.1 in course 334437 but there are zero resource rows, so the Book link
was never added. 1.2 now has its row pointing at the 1.2 bookSHelf page. 1.1 needs the same, one
edit on its settings form.

---

## TOOLING

### `$la` is a reserved variable name and kills the whole control block

Assigning `$la` anywhere in COMMON CONTROL produces `Eeek.. unallowed var $la!` and the variable renders as the literal text `Invalid variable`. It fails in plain question text, not only inside backticks.

The damage is not local: the control block **aborts at that line**, so every variable assigned after it renders as empty string. The symptom is a question with every number missing, which reads as a randomization bug rather than a naming bug.

Tested 16 candidate names — `la` is the only one rejected. `lb`, `ln`, `ev`, `evA`, `eventA`, `aa`, `ab`, `ca`, `cb`, `na`, `nb`, `pa`, `pb`, `sa`, `sb` all pass. No pattern; treat it as a one-off reserved word.

**Belongs in `reference/learned-rules.md`.**

### `questionHealth` reports a fully dead control block as clean

The question above — every variable blank, `Eeek.. unallowed var` printed into the body — came back from `questionHealth` as `{errors: [], warnings: []}`. HTTP 200, no diagnostic matched.

`renderProblems` has no pattern for `Eeek.. unallowed var`, and `undefinedVars` is static so it sees the control block assigning `$la` and considers it defined. Nothing in the app's gate catches this class.

**Suggested fix:** add `/Eeek\.\.[^<\n]{0,120}/` and `/Invalid variable/` to `renderProblems` in `src/integrations/mom/health.ts`.

### `health.ts` reports engine noise as a question error

`src/integrations/mom/health.ts:70-72` pushes `Caught warning in the (question code|common control)…` unconditionally, without the `isEngineNoise` guard it applies on line 76.

Running stateless, the sandbox emits `Caught warning in the question code: Undefined global variable $myrights on line 486 in parsers.php` on perfectly healthy questions. Confirmed against a known-good bank question (`descriptive-stats/classify-measurement-scale.php`): one reported error, entirely engine noise.

One-line fix; not applied, since it's app code and outside what was asked for.

### The render sandbox cannot show a picture — only its text alternative

Every graph macro returns its accessible text alternative rather than an image when rendered stateless, including for the trig questions that have shipped for months. So this is a sandbox artifact, not a defect, but it means **no automated check can see what a graph actually looks like.** Graph questions are verified by reading the alt text, which is exact (coordinates and frequencies), and by your eye in the app.

### `showplot` alt text leaks the answer on read-the-shape questions

`connectthedots` + `showplot` emits a full coordinate dump — every plotted point, listed. A student on text-alternative display mode reading "which statement describes this graph's shape" is handed all the underlying values.

`replacealttext($plot, "...")` is the fix, and it should be standing practice for any read-the-display question. The stats-library macros (`fdbargraph`, `histogram`, `stem_plot`, `boxplot`) are better behaved — their alt text is the data table, which is what the picture shows anyway.

## `block-pattern-4x-k-find-number-of-blocks-*` renders only ONE of its three answer boxes

Found 2026-08-31 while assembling IM1 1.2. Both copies (`1439793`, `1440396`) show all three parts
of the prompt but only **one** input, and `` `#$n[0]` `` / `` `#$n[1]` `` render as a bare `#`.
The source looks structurally correct: `$anstypes` has 3 entries, `$answer[0..2]` and
`$answerbox[0..2]` are all present, and there are 2 `///` separators.

Precomputing the scalars (`$structA = $n[0]`) did **not** fix it, so the array-indexing-in-question-
text rule is not the whole story — `$n` itself appears not to resolve, and
`$n = diffrands(40,100,2,'inc') where (...)` is the prime suspect. Parts 2 and 3 are unanswerable,
which means the question silently awards at most a third of its marks.

**Not used in 1.2.** Replaced by `questions/im1/adding-pattern-figure-to-table.php` (qsetid
`1893443`), authored on the template that does work. Both block-pattern copies are still attached to
whatever used them before — check `2.1.1` and anything else before assuming this is harmless.
