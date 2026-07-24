# Annuity & Amortization Questions

**Parent:** `../../AGENTS.md`  
**Sections covered:** 6.4 (ordinary annuities, sinking funds, amortization, loan payments)

## OVERVIEW

Questions in this folder cover Section 6.4 of Applied Finite Math. All are auto-graded numeric questions (no FRQs). They use the IMathAS `finance` library (`loadlibrary("finance")`) for `futureValue()`, `presentValue()`, and `payment()` functions.

## FORMULAS IN SCOPE

| Concept | Formula | Library call |
|---------|---------|-------------|
| Annuity FV (ordinary) | FV = m × [(1+r/n)^(nt) − 1] / (r/n) | `futureValue(r_pct, N, n, -m, 0)` |
| Sinking fund payment | m = FV × (r/n) / [(1+r/n)^(nt) − 1] | `abs(payment(r_pct, N, n, 0, FV))` |
| Loan payment (amortization) | m = PV × (r/n) / [1 − (1+r/n)^(−nt)] | `abs(payment(r_pct, N, n, PV, 0))` |
| Annuity PV (present value) | PV = m × [1 − (1+r/n)^(−nt)] / (r/n) | `abs(presentValue(r_pct, N, n, m, 0))` |

**Convention:** `r_pct` is the nominal annual rate as a percentage (e.g., 6 for 6%), `N = n * t` is total periods.

## SECTION SCAFFOLD

```
1. loadlibrary("finance")
2. $contexts array + $ci = rand(0, count($contexts)-1) for scenario variability
3. Parallel arrays for numeric parameters; derive all answers from them
4. $anstypes = array("number") or array("number","number") — no file upload
5. $abstolerance = 0.01; $reqdecimals = "r2" for dollar answers
6. $css_block (copy verbatim from free-response-template.php)
7. $solutionguide with Step/Work table pattern
8. Question text uses <div style="font-family:Arial; font-size:medium; line-height:1.6;">
```

## FILE NAMING

`{slug}.php` (e.g., `ordinary-annuity-fv.php`, `loan-amortization-payment.php`)

## ANTI-PATTERNS

- Never omit `loadlibrary("finance")` — `futureValue()` etc. are not built-in
- Never hardcode a single scenario — always use `$contexts` arrays for replayability
- Never use `$answerformat = "integer"` for dollar amounts — students enter decimals
- Never mix annuity-due and ordinary annuity in the same question without clearly labeling each
- Never skip the solution guide — all questions must have a `$solutionguide` after `///`
