# Binomial Distribution Questions

**Parent:** `../../AGENTS.md`
**Files:** chapter 4.3 binomial questions (built 2026-08-11 for the -SH book's 4-3 hw)

## OVERVIEW

Binomial questions test the three conditions, the notation `X ~ B(n, p)`, the probability formula
`P(X = x) = C(n,x) p^x q^(n-x)`, the shortcuts `mu = np` and `sigma = sqrt(npq)`, translating
English inequalities into probability statements, and recognizing when an experiment is NOT
binomial (sampling without replacement).

## QUESTION TYPES

| File | Parts | Description |
|------|-------|-------------|
| `q1-binomial-conditions.php` | 3 | Which of the three conditions hold/fail for a described experiment |
| `q2-binomial-notation.php` | 3 | State X~B(n,p), the values of X, and q |
| `q3-binomial-exact-prob.php` | 1 | P(X = k) by the formula (precomputed) |
| `q4-binomial-at-most.php` | 1 | P(X <= k) cumulative (precomputed) |
| `q5-binomial-at-least.php` | 1 | P(X >= k) via the complement (precomputed) |
| `q6-binomial-mean-sd.php` | 2 | mu = np and sigma = sqrt(npq) |
| `q7-binomial-translate.php` | 3 | choices - which inequality the English asks for |
| `q8-not-binomial.php` | 2 | choices - without replacement breaks independence |
| `q9-binomial-compare-outcomes.php` | 2 | which of two k values is more likely, justified numerically |

## CONVENTIONS

1. **Precompute every binomial probability** in the scenario arrays (`P = C(n,k) p^k q^(n-k)`).
   Never call `binomcdf`/`binompdf` — the family rule is precomputed answers, and a wrong macro
   call renders an empty box with no error.
2. `loadlibrary("stats")` at the top of Common Control (harmless, matches the family).
3. Small `n` (4-20) with clean `p` (0.25, 0.3, 0.4, 0.5, 0.6) so probabilities are hand-checkable
   and precomputable to 4 decimals.
4. All numeric answers `numfunc` with `abstolerance = 0.005`; every `choices`/`select` part
   carries `$noshuffle[N] = "all"`.
5. White-card UI + blue-chip part labels, same as the probability family.

## GOTCHAS

- `C(n,k)` is written `binom(n,k)` in IMathAS but NEVER needed in the answer — precompute.
- The complement `P(X >= k) = 1 - P(X <= k-1)` is the "at least" route; teach it in the guide.
- Without replacement = NOT binomial; the tell is "does not go back / not replaced".
- "At most" = `<=`; "at least" = `>=`; "more than" = `>`; "less than" = `<`.
