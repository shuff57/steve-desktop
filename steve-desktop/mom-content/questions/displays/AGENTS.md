# Graphical Displays

Auto-graded questions for Wk2 introductory-stats topics: choosing the right display, reading histograms (relative frequency + shape), and reading boxplots (5-number summary, IQR, 1.5*IQR outlier rule).

Created 2026-05-19 as part of the Wk1-5 Group Test batch (`books/introduction-to-stats/college/group/wk1-5-group.{json,md}`).

## Files

| File | Topic | Type | qid |
|---|---|---|---|
| `q1-choose-display.php` | Pick best display from {bar, histogram, pie, boxplot, time series, scatterplot} | multipart (choices x1) | TBD |
| `q2-histogram-relative-frequency.php` | Read bin counts, compute relative frequency, identify shape (left/right/symmetric) | multipart (numfunc, choices) | TBD |
| `q3-boxplot-five-number.php` | Compute IQR, lower fence, upper fence, decide outlier | multipart (numfunc x3, choices x1) | TBD |
| `q4-boxplot-outliers-context.php` | Same as q3 with fresh in-context five-number summaries (cumulative wk1-14 test) | multipart (numfunc x3, choices x1) | TBD |

## Conventions

- Display content uses inline HTML tables (no images); see `q2-histogram-relative-frequency.php` for the table-build pattern.
- Histogram shape codes: 0 = left-skewed, 1 = right-skewed, 2 = symmetric.
- Outlier rule: 1.5*IQR fences. Choice answer is 0 = outlier, 1 = not outlier.
