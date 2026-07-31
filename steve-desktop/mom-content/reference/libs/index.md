# IMathAS Library Index

## How to Load

```php
loadlibrary("stats")                        // single library
loadlibrary("stats,matrix,fractions")       // multiple libraries (comma-separated)
```

Place `loadlibrary()` at the **beginning** of the Common Control section, before any macros from those libraries are used.

## Core vs Extension

**Core libraries** (randomizers, graph/table, format, string, array, general, math) are always available in every question — no `loadlibrary()` call needed. See [core.md](core.md) for details.

**Extension libraries** must be explicitly loaded with `loadlibrary("name")`. There are 44 extension libraries covering statistics, algebra, calculus, geometry, discrete math, and more.

## Docs URL Pattern

```
https://raw.githubusercontent.com/drlippman/IMathAS/master/assessment/libs/{name}.html
```

Example for `stats`:
```
https://raw.githubusercontent.com/drlippman/IMathAS/master/assessment/libs/stats.html
```

Browse all installed libs (requires MOM login): `https://www.myopenmath.com/assessment/libs/libhelp.php`

## Categories

See [categories.md](categories.md) for the full category → library mapping (12 categories, 44 libraries).

## Extension Libraries (44)

Docs for each library follow the URL pattern above: replace `{name}` with the loadlibrary key.

| Name | loadlibrary key | Description | Category |
|------|-----------------|-------------|----------|
| JSXG | `JSXG` | JSXGraph interactive geometry (newer version) | interactive-geometry |
| acct | `acct` | Accounting macros — journal entries, T-charts, trial balances, financial statements | business |
| biology | `biology` | Biology macros — Punnett squares, genetics | science |
| calculus | `calculus` | Calculus macros — derivatives, integrals, Riemann sums | calculus |
| chemistry | `chemistry` | Chemistry macros — chemical equations, molecular formulas, balancing | science |
| chgbase | `chgbase` | Change of base macros — number base conversions | number-theory |
| complex | `complex` | Complex number macros — operations, polar form, roots | algebra |
| construct2 | `construct2` | Compass and straightedge construction macros | geometry |
| conversion | `conversion` | Unit conversion macros — comprehensive unit conversion system | applied-math |
| crypto | `crypto` | Cryptography macros — Caesar cipher, substitution ciphers | discrete-math |
| dates | `dates` | Date macros — date arithmetic, day of week calculations | applied-math |
| diffeq | `diffeq` | Differential equations macros — slope fields, Euler's method | calculus |
| finance | `finance` | Finance macros — simple/compound interest, annuities, TVM | business |
| finance2 | `finance2` | Finance macros v2 — extended financial calculations, amortization | business |
| finderiv | `finderiv` | Finite derivatives and difference quotients | calculus |
| fractions | `fractions` | Fraction macros — display, operations, simplification | algebra |
| functioneval | `functioneval` | Function evaluation macros | algebra |
| geogebra | `geogebra` | GeoGebra integration macros | interactive-geometry |
| graphtheory | `graphtheory` | Graph theory macros — vertices, edges, paths, adjacency matrices | discrete-math |
| ineq | `ineq` | Inequality macros — graphing, solving, interval notation | algebra |
| interval | `interval` | Interval notation macros — union, intersection, display | algebra |
| interval_ext | `interval_ext` | Extended interval notation macros | algebra |
| jsxgraph | `jsxgraph` | JSXGraph interactive geometry (original version) | interactive-geometry |
| lineutil | `lineutil` | Line utility macros — slope, intercept, parallel, perpendicular | algebra |
| logic | `logic` | Logic macros — truth tables, propositions, logical connectives | discrete-math |
| logistic | `logistic` | Logistic growth macros — logistic functions, carrying capacity | applied-math |
| matrix | `matrix` | Matrix macros — operations, determinants, row reduction, eigenvalues | linear-algebra |
| normalcurve | `normalcurve` | Normal curve display macros — shaded regions, z-scores | statistics |
| plot3d | `plot3d` | 3D plotting macros — surfaces, parametric curves in 3D | calculus |
| poly3 | `poly3` | Polynomial macros v3 — advanced polynomial operations | algebra |
| polys | `polys` | Polynomial macros — factoring, roots, display | algebra |
| polys2 | `polys2` | Polynomial macros v2 — extended polynomial operations | algebra |
| primes | `primes` | Prime number macros — primality testing, factorization, GCD/LCM | number-theory |
| radicals | `radicals` | Radical expression macros — simplification, display | algebra |
| sagecell | `sagecell` | SageMath cell integration macros | interactive-tools |
| setexp | `setexp` | Set theory macros — set operations, Venn diagrams, expressions | discrete-math |
| shapes | `shapes` | Geometric shapes macros — drawing, measurements, area, volume | geometry |
| simplex | `simplex` | Simplex method macros — linear programming, optimization | applied-math |
| socchoice | `socchoice` | Social choice / voting theory macros — preference schedules, voting methods | discrete-math |
| solvers | `solvers` | Equation solver macros — numerical solvers | algebra |
| stats | `stats` | Statistics macros — descriptive stats, distributions, regression, ANOVA, hypothesis tests | statistics — see [stats.md](stats.md) |
| units | `units` | Unit handling macros — dimensional analysis, unit parsing | applied-math |
| vector | `vector` | Vector macros — dot product, cross product, magnitude, direction | linear-algebra |
| virtmanip | `virtmanip` | Virtual manipulatives macros — interactive math tools | interactive-tools |

## Appendix A: PHP-Only Libraries

These have `.php` source files but no `.html` docs and are **not listed** on the libhelp.php page. Availability in MOM is not guaranteed.

| Name | Note | Source |
|------|------|--------|
| geom | Has .php but no .html docs — NOT listed on libhelp.php page | `/assessment/libs/geom.php` |
| timedate | Has .php but no .html docs — NOT listed on libhelp.php page | `/assessment/libs/timedate.php` |

## Appendix B: Supplemental Files

| File | Note |
|------|------|
| moleculehelper.html | Supplemental docs referenced by chemistry library |
| sagecellframe.html | Frame page for SageMath cell embedding |
| help.css | Shared stylesheet for library help pages |
