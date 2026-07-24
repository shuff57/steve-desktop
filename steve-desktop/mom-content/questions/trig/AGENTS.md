# Trig Questions — Unit Circle, Terminal Points & Sine

**Parent:** `../../AGENTS.md`
**Files:** 22 autograded trig questions across three topic groups

## OVERVIEW

Trig questions test unit circle skills: identifying quadrants, finding angles in degrees and radians, computing arc lengths, finding terminal points, coterminal angles, defining sine/cosine as coordinates, and graphing sine functions. All use standard unit circle angles with randomization and include collapsible step-by-step solution guides.

## QUESTION TYPES

### Unit Circle & Terminal Points (9 questions)

| File | Type | Answer Type | Description |
|------|------|-------------|-------------|
| `quadrant-from-terminal-point.php` | multipart(number) | Integer 1-4 | Identify quadrant from terminal point coordinates |
| `angle-degrees-and-radians-from-point.php` | multipart(number, calculated) | Degrees + radians | Find angle in both units given a point on the unit circle |
| `reference-angle-from-point.php` | multipart(number, calculated) | Degrees + radians | Find reference angle given a terminal point |
| `arc-length-from-terminal-point.php` | multipart(calculated) | Expression with pi | Arc length s = r*theta with variable radius (2-8) |
| `arc-length-to-angle-measure.php` | multipart(calculated, number) | Radians + degrees | Find radian and degree angle measure from arc length on a non-unit circle |
| `terminal-point-from-arc-length.php` | multipart(calcntuple) | Ordered pair | Find (cos(theta), sin(theta)) given arc length on unit circle |
| `terminal-point-non-unit-circle.php` | multipart(calculated) | Coordinates on radius r | Find terminal point coordinates on a circle of radius r |
| `coterminal-angles.php` | multipart(number, number) | Two integers | Find positive (theta+360) and negative (theta-360) coterminal angles |
| `define-sine-y-coordinate.php` | multipart(calculated, calculated, choices) | y-coord + x-coord + select | Definition of sine as y-coordinate on unit circle |

### Intro to Sine & Graphing Sine (11 questions)

| File | Type | Answer Type | Description |
|------|------|-------------|-------------|
| `sine-from-unit-circle.php` | multipart(calculated) | Exact radical | sin(θ) as y-coordinate on unit circle |
| `sine-of-special-angles.php` | multipart(calculated) | Exact radical | Exact values at standard angles (30°, 45°, 60°, etc.) |
| `sine-sign-by-quadrant.php` | multiple_choice | Quadrant selection | Where sine is positive/negative |
| `sine-reference-angle-values.php` | multipart(number, calculated, calculated) | Ref angle + ref sine + final sine | Use reference angles to find sine values |
| `degree-radian-sine-values.php` | multipart(calculated, calculated) | Radians + sine value | Convert degrees to radians and find sin(θ) |
| `key-features-of-sine-parent.php` | multipart(number, calculated, calcntuple) | Amp/period/midline + zeros + max/min | Key features of y = sin(x) parent function |
| `sine-graph-key-points.php` | multipart(calcntuple) | Ordered pair | Identify coordinates of max, zero, min on y = sin(x) |
| `sine-graph-period-amplitude.php` | multipart(number, calculated) | Integer + pi expression | Amplitude and period from y = A·sin(Bx) |
| `sine-graph-match-equation.php` | multiple_choice | Select equation | Match graph to equation from 4 choices |
| `sine-graph-from-equation.php` | multipart(number, calculated, calculated) | Amp + period + evaluation | Features and values from y = A·sin(Bx) |
| `graph-sine-find-values.php` | multipart(calculated) | Sine values | Given a sine graph, find sin(θ) for random angles |

### Sine Transformations y = a·sin(x − h) + k (2 questions, period = 2π)

| File | Type | Answer Type | Description |
|------|------|-------------|-------------|
| `match-sine-equation-to-graph.php` | multipart(choices × 3) | Graph panel + error identification | Pick the correct graph from 4 panels (3 distractors show wrong reflection, flipped phase shift, or negated vertical shift). |
| `sine-transformation-anchor-table.php` | multipart(number × 8) | y-values, max, min, midline, a, k, amplitude | Complete the 5-anchor-point table (parts a/b), then identify max, min, midline, a, k, \|a\|. |

> Note: the sine-transformation *graphing* task lives in `../draw/graph-sine-transformation.php` because its answer type is `draw`, not `multipart`.

## SHARED INFRASTRUCTURE

### Standard angle data (reused across all questions)

15 standard unit circle angles with parallel arrays for:
- Degree values: 30, 45, 60, 90, 120, 135, 150, 180, 210, 225, 240, 270, 300, 315, 330
- Radian numerators/denominators (e.g., 5pi/6 = numerator 5, denominator 6)
- x/y coordinate display strings using radical notation (`"sqrt(3)/2"`, `"-sqrt(2)/2"`, etc.)

Questions that exclude axial angles (90, 180, 270) use a 12-angle subset.

### Unit circle graph pattern

```php
$circle = showplot("[cos(t),sin(t)],blue,0,6.2832,,,2", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn,$yn,closed,red", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$radius = showplot("[$xn*t,$yn*t],gray,0,1,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $radius, $dot)
$graph = addlabel($graph, $xn, $yn, "P", "red", "right")
```

Arc length question scales the graph to radius `$r` with window `$nwin` to `$win`.

### Sine graph pattern

```php
$func_str = $A . "*sin(" . $B . "*x)"
$sine_curve = showplot($func_str . ",blue,-0.3," . $x_win . ",,,2", -0.5, $x_win, $ny_win, $y_win, "pi/2:1", "1:1", 400, 250)
$sine_graph = addfractionaxislabels($sine_curve, "pi/2")
```

- Window scales: y-range = `|A| + 1.5`, x-range varies by B (larger B = shorter visible window)
- `addfractionaxislabels` adds pi-fraction labels on x-axis
- Key points highlighted via `dot` and `addlabel`

### Pi fraction formatting

When the coefficient > 1, use parentheses to keep the constant and pi in the numerator:
```php
// Correct: renders as (7pi)/6
$rad_ans = "(" . $rn . "pi)/" . $rd

// Wrong: renders as 7*(pi/6)
$rad_ans = $rn . "pi/" . $rd
```

When the coefficient is 1, omit it: `"pi/" . $rd` (not `"1pi/..."`)

### Answer format

Use `$answerformat = "nodecimal"` for calculated answers containing pi. Do **not** use `"fraction"` — it rejects expressions with pi.

## SECTION SCAFFOLD

```
1. Define parallel arrays for standard angles, coordinates, radian fractions
2. $i = rand(0, N) to select angle
3. Compute numeric coordinates for plotting: cos($deg * pi / 180)
4. Build unit circle graph with showplot + mergeplots + addlabel
5. Set $anstypes, $answer[N], $answerformat[N], $ansprompt[N]
6. Copy $css_block verbatim from free-response-template.php
7. Build $solutionguide using $css_block + .rubric-container + .rubric-table pattern
```

Output block:
```
// === QUESTION TEXT ===
<div>question HTML with $graph</div>
(a) $answerbox[0]
(b) $answerbox[1]

// === ANSWER ===

$solutionguide
```

## SOLUTION GUIDE

Uses `$css_block` (copied verbatim from `free-response-template.php`) + `.rubric-container` + `.rubric-table` with Step/Work columns. Stored in `$solutionguide` variable, placed in the Answer section. Visibility controlled by MyOpenMath assessment settings ("Display with Show Answer" checkbox on each question, "show answers: after last try" in assessment settings).

Pattern: `$solutionguide = $css_block . '<div class="rubric-container"><details>...<table class="rubric-table">...</table></details></div>'`

- First row: **Given** (states knowns)
- Middle rows: **Step N** (computation)
- Last row: uses `.col-cat-bot` / `.col-check-bot` for border radius, contains green answer highlight
- See `reference/css-style-guide.md` for full class reference

## FILE NAMING

`{kebab-slug}.php` — descriptive content title, no question numbers.

## ANTI-PATTERNS

- Never use `$answerformat = "fraction"` for answers containing pi — use `"nodecimal"` instead
- Never format pi fractions as `$rn . "pi/" . $rd` when rn > 1 — wrap numerator: `"(" . $rn . "pi)/" . $rd`
- Never hardcode angles — always use parallel arrays with random index
- Never use inline styles for solution guides — use `$css_block` classes (`.rubric-container`, `.rubric-table`, etc.)
- All questions use `$anstypes` since question type is set to "Multipart" in MyOpenMath
- Use `$answer[0]` not `$answer` for multipart questions (even single-part ones)