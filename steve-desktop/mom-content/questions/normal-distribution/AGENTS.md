# Normal Distribution

Auto-graded questions for Wk4-5 introductory-stats topics: z-scores, normal probabilities `P(X<a)` / `P(X>b)` / `P(a<X<b)`, inverse normal (percentiles and middle-c% endpoints), and the empirical rule.

Created 2026-05-19 as part of the Wk1-5 Group Test batch (`books/introduction-to-stats/college/group/wk1-5-group.{json,md}`).

## Files

| File | Topic | Type | qid |
|---|---|---|---|
| `q1-z-score-compute.php` | Compute z for two values; pick more unusual | multipart (numfunc x2, choices x1) | TBD |
| `q2-normal-probability.php` | P(X<a), P(X>b), P(a<X<b) with standardization | multipart (numfunc x3) | TBD |
| `q3-inverse-normal-percentile.php` | pth percentile + middle c% endpoints | multipart (numfunc x3) | TBD |
| `q4-empirical-rule.php` | 68-95-99.7 application for k=1, 2, 3 SDs | multipart (choices x3) | TBD |
| `q5-normal-probability-context.php` | Same as q2 with fresh in-context scenarios (cumulative wk1-14 test) | multipart (numfunc x3) | TBD |

## Conventions

- All probability / inverse-normal questions MUST start CC with `loadlibrary("stats")` — without it MOM rejects `normalcdf` / `invnormalcdf` macros.
- Standard form: standardize first, then call `normalcdf(z)` (the 1-arg standard-normal CDF). The 4-arg form `normalcdf(mean, sd, lower, upper)` is NOT accepted on this MOM instance.
- For inverse normal: use `invnormalcdf(p)` to get the z with area `p` to its left.
- Empirical rule percentages are hard-coded (68, 95, 99.7 within; 16, 2.5, 0.15 in one tail; 34, 47.5, 49.85 in one half).
