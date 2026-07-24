# Finance Questions — Interest, Payments, Annuities

**Parent:** `../../AGENTS.md`
**Files:** 9 autograded finance questions across 4 sub-topics

## OVERVIEW

Covers the standard consumer-finance toolkit: simple interest + discount/maturity, compound interest, installment payments / amortization, and annuities (sinking funds, future value of annuity due). Every question randomizes principal, rate, and time; solutions use `$contexts` arrays for scenario variety.

## QUESTION TYPES

### Simple Interest (`simple-interest/`)

| File | Description |
|------|-------------|
| `q3-simple-interest-discount.php` | I = Prt, plus maturity / discount in scenario context |
| `q4-solve-rate-maturity-value.php` | Solve for the rate given maturity value |

### Compound Interest (`compound-interest/`)

| File | Description |
|------|-------------|
| `q5-compound-interest.php` | A = P(1 + r/n)^{nt} |
| `q6-solve-rate-compound.php` | Solve for r given A, P, n, t |
| `q7-comparing-future-values.php` | Compare two compounding strategies |

### Installment Payment (`installment-payment/`)

| File | Description |
|------|-------------|
| `q1-installment-payment-payout-annuity.php` | Loan payout annuity — monthly payment |
| `q2-amortization-schedule.php` | Read / build an amortization row |

### Annuities (`annuities/`)

| File | Description |
|------|-------------|
| `q8-annuities-sinking-funds.php` | Sinking fund periodic deposit |
| `q9-annuity-due-future-value.php` | Future value, annuity due |

## CONVENTIONS

1. `$anstypes` is typically `array("number", "number", ...)` for dollar / rate outputs, plus `choices` where pedagogy wants a concept check.
2. Scenario randomization via `$contexts = array(array(verb, account_type, goal), ...)` + `$ci = rand(0, count($contexts)-1)`. Pull `$ctx_verb`, `$ctx_type`, `$ctx_goal` out to scalars.
3. Principal drawn in $100 increments: `$P = rand(5, 50) * 100` — keeps numbers realistic and divisible.
4. Rate randomized from a fixed percent list: `$rate_pcts = array(3,4,5,6,7,8,9,10,12,15)` then `$r = $rate_pct / 100`.
5. Time from a list of clean fractional years: `$time_choices = array(1, 1.5, 2, 2.5, 3, 4, 5)`.
6. Round monetary outputs with `round($value, 2)`; never use PHP's `number_format`.
7. Precompute every derived value (`$I`, `$A`) so the student-facing prompt shows the same values the answer key uses.

## ADDING A NEW FINANCE QUESTION

1. Copy the closest existing file in the matching sub-topic.
2. Replace scenario, principal, rate, and time ranges; keep the same structure (`$contexts` + `$P` + `$r` + `$t` + derived).
3. Precompute the answer in Common Control and assign to `$answer` / `$answer[N]`.
4. Set `$reltolerance = 0.01` (or `$abstolerance = 0.01`) on monetary parts so rounding doesn't reject correct answers.
5. Verify in MOM preview; test across a few seeds to make sure the randomization stays within sensible ranges.

## GOTCHAS

- `number_format` is blocked — use `round(...)` plus explicit string concatenation if you need thousands separators.
- For amortization questions, make sure the payment formula matches the account type: payout annuity vs. standard amortization vs. annuity due differ in the `(1 + r/n)^{nt}` placement.
- Do not hardcode the dollar amount in Detailed Solution; use the same `$P` etc. so the worked solution always matches the randomized problem.
