# Expected Value Questions — Discrete Random Variables

**Parent:** `../AGENTS.md`
**Files:** 3 auto-graded expected-value questions covering games of chance, insurance, and filling missing probabilities. (The companion question `../q22-expected-value-of-discrete-rv.php` covers sum-to-1 + E(X) + E(X²) and is reused alongside this family.)

## OVERVIEW

These questions complement `q22-expected-value-of-discrete-rv.php` for the Expected Value section of an introductory stats course. They focus on the practical applications of `E(X)`: deciding whether to play a game, valuing an insurance policy, and constructing a complete distribution from a partial one.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-ev-game-of-chance.php` | 3 | numfunc, numfunc, choices | Compute `E(X)` for a game profit, the 100-play total, decide whether the game is fair (4 scenarios incl. one fair game) |
| `q2-ev-insurance.php` | 1 | numfunc | Customer's expected net value `E(X) = payout · p − premium` (5 scenarios; all negative — insurer perspective) |
| `q3-ev-fill-missing-prob.php` | 2 | numfunc × 2 | Use sum-to-1 to find the missing probability, then compute `E(X)` (5 scenarios) |

## CONVENTIONS

1. **Type picker is Multipart** in MOM, even for single-part questions (matches `../AGENTS.md`).
2. **Sign matters.** `q2-ev-insurance` uses the customer perspective so `E(X)` is negative; the question prompt makes "include the sign" explicit. Tolerances are loose enough (`abstolerance = 0.5`) that students can round to the nearest dollar.
3. **All probabilities are exact decimals or simple fractions.** `jointrandfrom` carries the precomputed `E(X)` so grading is exact.
4. **"Fair game" MC** in `q1` reads `Yes / No`. Only one of the 4 scenarios is genuinely fair (heart card draw, `E(X) = 0`).
5. **100-play expected total** in `q1` is `100 · E(X)` and is precomputed per scenario (`-150, 50, 25, 0`).

## ADDING A NEW EXPECTED-VALUE QUESTION

1. Copy `q1-ev-game-of-chance.php` for a profit/decision setup, `q2-ev-insurance.php` for a single-part computation, or `q3-ev-fill-missing-prob.php` for a missing-cell distribution.
2. Add a scenario by extending the parallel arrays. **Always precompute `E(X)`.** Never let MOM compute it via macros — IMathAS does not have a built-in `E(X)` function.
3. Pick clean decimals or simple fractions so the precomputed answer is exact. `abstolerance` should never be more than the smallest unit you expect students to round to.
4. For decision MCs, write `Yes / No` options where "Yes" maps to the favorable choice for the player and the option order matches the answer index.
5. Set the type picker to **Multipart** in MOM and verify on `testquestion2.php`.

## GOTCHAS

- `pow()` is blocked in IMathAS. Use `^` (e.g., `2^$n`) instead.
- `number_format()` is blocked. Use `round($x, 2)` and let MOM render the result.
- Negative numeric answers for `numfunc` type are accepted; the student just needs to enter the leading `-`. Make this explicit in the prompt for insurance-style problems.
- `numfunc` renders a MathQuill input — fractions display stacked, exponents as superscripts. Accepts decimals (`-50`), fractions (`-100/2`), and expressions (`200*0.30 - 70`).
- Do not concatenate `$payout` strings with commas inside backticks — MOM math rendering treats commas as separators. Show large numbers with `$$payout` outside backticks if a comma is desired, or pass them in plain.
