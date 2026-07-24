# Group Test: Weeks 12-14 (Expected Value, Chi-Square, Linear Regression)

**Status:** transferred 2026-05-12 — aid 22551860
**Course:** MATH 105 Spring26 (cid 314128)
**Block:** Quizzes & Tests (path 0-1)
**Target size:** 17 questions (14 auto-graded + 3 FRQs), ~75 min estimated work, 88-min in-class window
**Assessment URL:** https://www.myopenmath.com/course/addassessment2.php?id=22551860&cid=314128

## Coverage

All FRQs are grouped at the end of the assessment (slots 15-17) so students hit the auto-graded items first.

| Topic | Auto slots | FRQ slot | Sources |
|---|---|---|---|
| Wk 12 Expected Value | 1-4 | 15 | 1 reuse (`q22`), 3 auto-graded drafts, 1 FRQ draft |
| Wk 13 Chi-Square | 5-8 | 16 | 4 auto-graded drafts, 1 FRQ reuse |
| Wk 14 Linear Regression | 9-14 | 17 | 6 auto-graded reuses, 1 FRQ reuse |

## Drafts to write (8 files)

### Chi-Square (`questions/stats-tests/chi-square/`)
1. `q1-when-gof-vs-independence.php` — choices, conceptual MC: pick the right chi-square test for a scenario
2. `q2-gof-expected-counts.php` — multipart number: compute E_i = n·p_i for k categories
3. `q3-gof-test-statistic.php` — multipart: compute χ² = Σ(O-E)²/E, df, decision at α
4. `q4-independence-expected.php` — multipart: compute expected counts from row/column totals in a 2×2 table

### Expected Value (`questions/probability/expected-value/`)
1. `q1-ev-game-of-chance.php` — multipart: probability table, E(X), decide if game is fair (compare to 0 or cost)
2. `q2-ev-insurance.php` — number: net EV of an insurance policy given payout and probability
3. `q3-ev-fill-missing-prob.php` — multipart: find missing P from a partial table, then compute E(X)

### EV FRQ (`questions/frq/probability/`)
1. `q1-expected-value-decision.php` — essay: compute and interpret EV, recommend a decision in context

## Reuses (9 files)

| Slot | File | Why |
|---|---|---|
| 1 | `questions/probability/q22-expected-value-of-discrete-rv.php` | Solid EV anchor: sum-to-1 check + E(X) + E(X²) |
| 9 | `questions/regression/residuals-correlation/q9-correlation-properties.php` | Correlation conceptual anchor |
| 10 | `questions/regression/intro/q12-slope-intercept-context.php` | Slope/intercept interpretation |
| 11 | `questions/regression/intro/q7-prediction-and-interpret.php` | Prediction computation |
| 12 | `questions/regression/residuals-correlation/q1-compute-residual.php` | Residual computation |
| 13 | `questions/regression/least-squares/q7-r-squared-interpretation.php` | R² conceptual |
| 14 | `questions/regression/slope-inference/q1-ci-for-slope.php` | CI for slope computation |
| 16 | `questions/frq/inference-for-proportions/q3-chi-square-goodness-of-fit.php` | GoF FRQ already drafted; matches Wk13 |
| 17 | `questions/frq/regression/q10-interpret-p-value-for-slope-test.php` | Inference FRQ |

## Final qid map (after transfer)

| Slot | Topic | Title | qid | Notes |
|---|---|---|---|---|
| 1 | EV | Confirm probs sum to 1, E(X), E(X²) | 1823685 | reuse |
| 2 | EV | EV of a game — fair-game decision | 1825138 | new |
| 3 | EV | EV of insurance | 1825140 | new |
| 4 | EV | Fill missing P, compute E(X) | 1825141 | new |
| 5 | Chi | Which test (GoF vs Independence) | 1825133 | new |
| 6 | Chi | GoF expected counts | 1825134 | new |
| 7 | Chi | GoF test statistic + decision | 1825135 | new |
| 8 | Chi | Independence 2×2 expected | 1825137 | new |
| 9 | Reg | Correlation properties | 1825147 | new (first MOM push) |
| 10 | Reg | Slope/intercept context | 1825185 | re-upload (orig 1804882 not searchable) |
| 11 | Reg | Prediction + interpret | 1825188 | re-upload (orig 1804702 not searchable) |
| 12 | Reg | Compute residual | 1807060 | reuse |
| 13 | Reg | R-squared interpretation | 1825201 | re-upload (orig 1809002 not searchable) |
| 14 | Reg | CI for slope | 1825202 | re-upload (orig 1815974 not searchable) |
| 15 | EV FRQ | Explain EV-based decision | 1825142 | new |
| 16 | Chi FRQ | GoF reasoning (essay) | 1825150 | new (first MOM push) |
| 17 | Reg FRQ | Interpret p-value for slope test | 1825204 | re-upload (orig 1816295 not searchable) |

## Manual teacher follow-up

1. **Re-order questions** in MOM via the position dropdowns. Current MOM order is insertion order, not manifest slot order. Specifically: positions 10-12 have residual / slope-intercept / prediction (manifest order is slope-intercept / prediction / residual), and positions 13-16 have EV-FRQ / Chi-FRQ / R² / CI (manifest order is R² / CI / EV-FRQ / Chi-FRQ).
2. **Set `available` and `due` dates** for the in-class 88-min window.
3. **Paste the group-work protocol text** into the Intro / Instructions field.
4. **Unhide** the assessment when ready (currently `avail=0` = Hide).
5. **Distribute group rosters / structures outside MOM** (cards, slips, paper roster). MOM has no native group mode — the protocol lives entirely in the intro text.

## Reminders

- Group rosters/structures live outside MOM (cards, slips, paper roster).
- Subtype = **Quiz**, not Homework.
- `defattempts: 3`, `versions_per_question: 1`, `penalty.type: "none"` — groups can re-submit the same item without losing points.
- `displaymethod: "All questions on one page"` so groups can see/discuss everything.
- `gbcategory: "Tests"` (graded category).
