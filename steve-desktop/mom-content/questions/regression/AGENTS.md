# Regression Questions — Intro, Least-Squares, Residuals, Inference, Transformations

**Parent:** `../../AGENTS.md`
**Files:** 62 autograded linear-regression questions across 5 sub-topics

## OVERVIEW

Covers linear regression from scratch: identifying explanatory / response variables, interpreting slope and intercept in context, making predictions, the least-squares criterion, r² and r, residuals and residual plots, extrapolation warnings, leverage and influential points, causation versus association, correlation properties, slope inference (CI and hypothesis test), LINE conditions, regression output reading, and log/power transformations. Heavy use of `choices` for concept questions and `number` / `calculated` for computations.

## QUESTION TYPES

### Intro to Regression (`intro/`) — 20 questions

Conceptual foundations: explanatory / response variables, scatterplots, making predictions, interpreting slope and intercept in context, when a linear model is appropriate, ŷ notation.

Example answer-type mixes: `multipart(choices, choices)`, `number` with `$reltolerance`, `multipart(number, number)`.

### Least-Squares Regression (`least-squares/`) — 18 questions

The least-squares criterion, slope from correlation (`b = r·s_y/s_x`), intercept from means, r² interpretation, computing r² from r, extrapolation warnings, leverage vs. influential, association vs. causation, indicator variables, regression conditions.

### Residuals & Correlation (`residuals-correlation/`) — 9 questions

Computing residuals from a given line and (x, y), interpreting residual sign, residual plots for good fit vs. nonlinear fit, residual standard deviation, general residual concepts, interpreting r, estimating r from a scatterplot description, and correlation properties (unitless, swapping x/y, linear-only).

### Slope Inference (`slope-inference/`) — 12 questions

Confidence interval for the population slope, hypothesis test for the slope, CI interpretation (contains 0?), choosing between paired t and regression for pre/post designs, diagnosing LINE condition violations from residual plots, and a full end-to-end inference workflow (conditions, hypotheses, test statistic, CI, conclusion).

### Transformations (`transformations/`) — 3 questions

Log transform to fix right-skewed residuals, identifying power relationships from log-log plots, and interpreting the slope of a log-linear model as a multiplicative effect.

## CONVENTIONS

1. `$anstypes` mixes `"number"`, `"calculated"`, and `"choices"` inside `multipart`. Single-part questions use `number` directly with `$answer = ...`.
2. Numerical answers with real-world units need `$reltolerance = 0.01` (or tighter) so rounding doesn't flag correct answers.
3. For concept questions with a fixed pair of options (e.g. "Linear / Nonlinear"), set `$noshuffle[i] = "all"` so the option order stays predictable in the worked solution.
4. Randomization: pick an `$xval` from a realistic range, then derive `$yhat = intercept + slope * $xval`. Do NOT randomize the regression equation itself per-question — the slope and intercept anchor the worked solution.
5. Use `choices` + `$displayformat[i] = "select"` for dropdown selection; reserve the default (radio list) for longer text options.
6. Every question ships a collapsible `$solutionguide` `<details>` block with step-by-step algebra.

## ADDING A NEW REGRESSION QUESTION

1. Pick the right sub-folder: `intro/` for foundational concepts, `least-squares/` for the line-fitting math, `residuals-correlation/` for residuals and correlation, `slope-inference/` for CI/HT on the slope, `transformations/` for log/power transforms.
2. Copy the closest existing file (`q2-make-prediction.php` for a prediction-style question, `q13-linear-vs-nonlinear.php` for a concept multi-select).
3. Keep the regression equation fixed for the question and randomize only the input (`$xval`, sample means, etc.).
4. Set `$reltolerance` on every numeric part.
5. Update `$solutionguide` so the shown algebra uses the same randomized values the student sees.

## GOTCHAS

- Dropping a raw `$answer[i]` (a choice index) into prose renders `0` / `1`, not the option text. Precompute a scalar like `$decision_text = "Reject the Null" where ($pval < $alpha)` and interpolate that.
- Do not use nested array indexing (`$questions[3][$answer[3]]`) in Question Text or Detailed Solution — IMathAS interpolation emits the whole array and raises "Array to string conversion". Precompute in Common Control.
- Setting `$noshuffle[i]` is per-part in a multipart; forgetting one part causes option-order drift between the student prompt and the worked solution.
