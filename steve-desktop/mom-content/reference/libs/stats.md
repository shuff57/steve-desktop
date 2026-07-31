# Stats Library

Back to [index.md](index.md)

## Overview

| Field | Value |
|-------|-------|
| **loadlibrary key** | `stats` |
| **Category** | statistics |
| **Description** | Statistics macros — descriptive stats, distributions, regression, ANOVA, hypothesis tests |
| **Docs URL** | https://raw.githubusercontent.com/drlippman/IMathAS/master/assessment/libs/stats.html |
| **Source** | https://raw.githubusercontent.com/drlippman/IMathAS/master/assessment/libs/stats.php |

## Load

```php
loadlibrary("stats");
```

The `stats` library is the only extension library in the JSON with a documented `functions` array. It is the primary library for statistics courses.

## Functions (50)

### Combinatorics
| Function | Purpose |
|----------|---------|
| `nCr` | Combinations — C(n,r) |
| `nPr` | Permutations — P(n,r) |

### Descriptive Statistics
| Function | Purpose |
|----------|---------|
| `mean` | Arithmetic mean |
| `variance` | Variance |
| `stdev` | Standard deviation |
| `absmeandev` | Mean absolute deviation |
| `median` | Median |
| `modes` | Mode(s) |
| `forceonemode` | Force single mode output |
| `percentile` | Percentile |
| `interppercentile` | Interpolated percentile |
| `Nplus1percentile` | (N+1) percentile method |
| `quartile` | Quartile |
| `TIquartile` | TI calculator quartile method |
| `Excelquartile` | Excel quartile method |
| `Excelquartileexc` | Excel QUARTILE.EXC method |
| `Nplus1quartile` | (N+1) quartile method |
| `allquartile` | All quartile methods |

### Frequency & Counting
| Function | Purpose |
|----------|---------|
| `freqdist` | Frequency distribution table |
| `frequency` | Frequency count |
| `countif` | Conditional count |

### Graphs & Plots
| Function | Purpose |
|----------|---------|
| `histogram` | Histogram |
| `fdhistogram` | Frequency distribution histogram |
| `stem_plot` | Stem-and-leaf plot |
| `fdbargraph` | Frequency distribution bar graph |
| `cluster_bargraph` | Clustered bar graph |
| `piechart` | Pie chart |
| `boxplot` | Box plot |
| `mosaicplot` | Mosaic plot |
| `dotplot` | Dot plot |
| `csvdownloadlink` | CSV download link for data |

### Random Variates
| Function | Purpose |
|----------|---------|
| `normrand` | Normal random variate |
| `expdistrand` | Exponential distribution random variate |
| `stats_randg` | Gamma distribution random variate |
| `stats_randF` | F distribution random variate |
| `stats_randt` | t distribution random variate |
| `stats_randchi2` | Chi-squared distribution random variate |
| `stats_randpoisson` | Poisson distribution random variate |

### Normal & t Distributions
| Function | Purpose |
|----------|---------|
| `normalcdf` | Normal CDF |
| `tcdf` | Student's t CDF |
| `invnormalcdf` | Inverse normal CDF |
| `invtcdf` | Inverse t CDF |

### Regression
| Function | Purpose |
|----------|---------|
| `linreg` | Linear regression |
| `expreg` | Exponential regression |
| `checklineagainstdata` | Check line fit against data |
| `checkdrawnlineagainstdata` | Check drawn line against data |

### Binomial Distribution
| Function | Purpose |
|----------|---------|
| `binomialpdf` | Binomial PDF |
| `binomialcdf` | Binomial CDF |

### Chi-Squared Distribution
| Function | Purpose |
|----------|---------|
| `chi2teststat` | Chi-squared test statistic |
| `chi2cdf` | Chi-squared CDF |
| `invchi2cdf` | Inverse chi-squared CDF |

### F Distribution
| Function | Purpose |
|----------|---------|
| `fcdf` | F distribution CDF |
| `invfcdf` | Inverse F CDF |

### Poisson Distribution
| Function | Purpose |
|----------|---------|
| `poissonpdf` | Poisson PDF |
| `poissoncdf` | Poisson CDF |

### Gamma & Beta Distributions
| Function | Purpose |
|----------|---------|
| `gamma_cdf` | Gamma CDF |
| `gamma_inv` | Inverse gamma CDF |
| `beta_cdf` | Beta CDF |
| `beta_inv` | Inverse beta CDF |

### ANOVA
| Function | Purpose |
|----------|---------|
| `anova1way` | One-way ANOVA |
| `anova1way_f` | One-way ANOVA F statistic |
| `anova2way` | Two-way ANOVA |
| `anova2way_f` | Two-way ANOVA F statistic |
| `anova_table` | ANOVA table display |

### Other
| Function | Purpose |
|----------|---------|
| `student_t` | Student t distribution utility |

## Usage Example

```php
loadlibrary("stats");
$mu = 100;
$sigma = 15;
$x = normrand($mu, $sigma);
$z = ($x - $mu) / $sigma;
$p = normalcdf(0, $z, 0, 1);
```
