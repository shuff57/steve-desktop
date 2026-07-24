# Cumulative Test: Weeks 1-14 (Data -> Regression) Huff

- **Book:** introduction-to-stats
- **Kind:** ind
- **Chapter:** cumulative-w1-14
- **Course:** cid=314128, block=0-1, aid=null (not yet transferred)
- **Questions:** 10 (8 auto-graded + 2 FRQ)
- **Fresh / reuse:** 6 freshly authored (slots 2, 3, 5, 7, 9, 10) + 4 reused (slots 1, 4, 6, 8)
- **Est. time:** ~59 min / 60 min budget
- **Settings:** Quiz, 3 attempts, 1 version, 33% after-full-score penalty, All questions on one page, solutions after last attempt, 10 pts each (100 total), hidden
- **Status:** planned (manifest + .php only — not transferred)

## Source
Drawn from the two final group quiz manifests:
- `books/introduction-to-stats/college/group/wk1-5-group.json` (aid 22618261)
- `books/introduction-to-stats/college/group/wk7-14-group.json` (aid 22620729)

One high-impact full-multipart per major section across both halves + 2 broad FRQs. Per teacher request, ~half the test is freshly authored: the 4 fresh auto-graded items reuse the same skill/structure with new contexts and values, and the 2 fresh FRQs are new general conceptual prompts (easy, broad understanding, not topic-specific). Regression appears as auto-graded slot 8.

## Date Window
- **Available:** null (hidden — teacher sets when ready)
- **Due:** null (provide before transfer; `mom-transfer` requires the window)

## Questions

| Slot | File | Parts | Topic | Half | Source | src qid |
|------|------|-------|-------|------|--------|---------|
| 1 | `data-sampling/q4-population-parameter-statistic.php` | 3 | Population/sample, parameter/statistic | 1 | reuse | 1829213 |
| 2 | `displays/q4-boxplot-outliers-context.php` | 4 | Boxplot: IQR, fences, outliers | 1 | **fresh** | — |
| 3 | `normal-distribution/q5-normal-probability-context.php` | 3 | Normal: P(X<a), P(X>b), P(a<X<b) | 1 | **fresh** | — |
| 4 | `stats-tests/confidence-intervals/q6-ci-mean-t-story.php` | 3 | CI for one mean (t) + interpret | 2 | reuse | 1829339 |
| 5 | `stats-tests/hypothesis-testing/q17-full-ht-one-mean-fresh-2.php` | 3 | Full HT one mean (t, p, decision) | 2 | **fresh** | — |
| 6 | `stats-tests/two-sample-inference/q17-ci-two-means-compute.php` | 4 | CI for difference of two means | 2 | reuse | 1829348 |
| 7 | `stats-tests/chi-square/q9-gof-full-with-conclusion-2.php` | 4 | χ² goodness-of-fit + conclusion | 2 | **fresh** | — |
| 8 | `regression/least-squares/q19-data-r-r2-pvalue.php` | 4 | From data: r, r², two-tailed p-value, interpret r² | 2 | **fresh** | — |
| 9 | `frq/descriptive-statistics/q12-reading-a-study-frq.php` | essay | FRQ: reading a study (obs/exp, bias, conclusion) | 1 | **fresh** | — |
| 10 | `frq/inference-for-means/q13-interpreting-inference-frq.php` | essay | FRQ: what inference really says (interpret + misinterpretation) | 2 | **fresh** | — |

## Section coverage
Sampling · Displays · Normal · Confidence intervals · Hypothesis testing · Two-sample · Chi-square · Regression (auto) + general data literacy · general inference literacy (FRQ).

**Not represented** (trimmed to ~10): Expected Value, CI↔HT connection, Empirical Rule, and the single-step drills (variable type, sampling-method ID, choose-display).

## Notes
- **Fresh auto-graded (slots 2, 3, 5, 7):** new `.php` files mirroring the group-quiz items' structure with brand-new contexts/values, so students who saw the group quizzes get genuinely new items. Single-line ternaries only, no blocked functions — but **unverified against `testquestion2.php`** until mom-transfer uploads them.
- **Fresh FRQs (slots 9, 10):** two new general conceptual prompts; easy, broad understanding, human-graded (rubric + model response collapsibles).
- **Reuse (slots 1, 4, 6, 8):** existing repo `.php` already live in cid 314128 via the group quizzes; `src qid` is the group-quiz qid for reference.
- Block `0-1` (Quizzes & Tests), matching the group quizzes.

## Reminders before release
- Provide `available` / `due` before running `mom-transfer` — a graded test without dates opens immediately.
- On transfer, verify the 6 fresh questions render Correct via `testquestion2.php` (the 4 new auto-graded especially — check randomized cases and answer keys).
- Sanity-check the ~60 min budget against the actual class/exam slot.
- After transfer, preview as a student and confirm the after-full-score 33% penalty and "solutions after last attempt" before unhiding.
