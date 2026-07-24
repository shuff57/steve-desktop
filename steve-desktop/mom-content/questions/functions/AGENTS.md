# Functions Questions — Notation, Domain, and Rate of Change

**Parent:** `../../AGENTS.md`
**Files:** 3 autograded questions covering function evaluation, domain, and average rate of change

## OVERVIEW

Functions questions build the foundational language of functions used throughout the course. All three items are auto-graded multipart questions with randomized coefficients; no hardcoded scenario values appear in question text.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-function-notation-eval.php` | 3 | number, number, number | Evaluate f(x) = ax^2 + bx + c at three x values from 6 parallel-array scenarios |
| `q2-domain-radical-rational.php` | 3 | number (select), number, number | Domain of a polynomial (dropdown), rational (excluded value), and radical (lower bound) |
| `q3-average-rate-of-change.php` | 1 | number | AROC = (f(x2)-f(x1))/(x2-x1) for a randomized quadratic from 10 precomputed scenarios |

## CONVENTIONS

1. **Auto-graded only.** All three questions use `number` or `number` with `displayformat="select"`.
2. **Parallel-array randomization.** Answers are precomputed and stored alongside inputs so grading stays exact. Use `jointrandfrom(...)` for correlated picks.
3. **Coefficient display helpers.** Build sign strings (`$bsign`, `$csign`, `$b_abs`, etc.) in CC so question text never shows `"+ -"` artifacts.
4. **Solution guide.** Each file builds `$solutionguide` as an inline `<details>/<summary>` collapsible (`.sol-wrap` CSS class) with step-by-step arithmetic referencing actual randomized values.
5. **Part label chips.** Use the `display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px` chip style, not circles or plain bold letters.

## ADDING A NEW FUNCTIONS QUESTION

1. Copy the file closest to the answer shape (`q1` for pure number evals, `q2` for mixed number/select, `q3` for single-answer AROC).
2. Build all coefficient/x-value scenarios in parallel arrays; precompute answers by hand and verify before committing.
3. Add two numbered subsection comments in CC: `/* ---------- 1. Randomization ---------- */` and `/* ---------- 2. Solution guide ---------- */`.
4. Place `loadlibrary("stats")` and `$anstypes` immediately after `// === COMMON CONTROL ===`.
5. Set question type to **Multipart** in MOM.

## GOTCHAS

- `q2` uses `displayformat[0]="select"` with `$choices[0]` for the polynomial part (dropdown answer). The `$anstypes` entry for that part is `"number"` graded by index -- the correct choice index must match `$answer[0]`.
- When building sign strings for display, always derive `$b_abs = abs($b)` before concatenating to avoid `-3` appearing after `+`.
- `pow()` is blocked; use `^` for exponentiation in IMathAS (e.g., `2^$nc`).
