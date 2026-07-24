// === NAME - DESCRIPTION: Graphical LP Corner Evaluation - Smoothie shop maximizes profit by evaluating Z at every feasible corner ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Hardcoded scenario: Smoothie Bliss makes fruit smoothies (x1) and green smoothies (x2).
// Profit: Z = 4x1 + 5x2
// Constraints:
//   Blender time:  2x1 + 3x2 <= 24   (hours per week)
//   Ingredient budget: x1 + x2 <= 10  (batches per week)
//   x1, x2 >= 0
//
// Corner points of feasible region:
//   O = (0, 0):  Z = 4(0) + 5(0) = 0
//   A = (10, 0): Z = 4(10) + 5(0) = 40   [x1+x2=10 meets x2=0]
//   B = (6, 4):  Z = 4(6) + 5(4) = 24+20 = 44  [intersection of 2x1+3x2=24 and x1+x2=10]
//   C = (0, 8):  Z = 4(0) + 5(8) = 40   [2x1+3x2=24 meets x1=0]
//
// Intersection of 2x1+3x2=24 and x1+x2=10:
//   From x1+x2=10 -> x1 = 10-x2
//   2(10-x2)+3x2 = 24 -> 20+x2 = 24 -> x2=4, x1=6. Corner B=(6,4). Z=44. Optimal.
//
// Optimal: x1=6, x2=4, Z=44.

$ans_x1 = 6
$ans_x2 = 4
$ans_Z  = 44

// Part d: which constraint is binding at the optimal corner (both are binding at B=(6,4))
// Options (0-indexed):
//   0. 2x1+3x2<=24 only
//   1. x1+x2<=10 only
//   2. Both 2x1+3x2<=24 and x1+x2<=10  <-- correct
//   3. Neither constraint is binding

$anstypes = array("numfunc", "numfunc", "numfunc", "choices")
$answer[0] = $ans_x1
$answer[1] = $ans_x2
$answer[2] = $ans_Z
$choices[3] = array(
  "2x₁ + 3x₂ ≤ 24 only",
  "x₁ + x₂ ≤ 10 only",
  "Both 2x₁ + 3x₂ ≤ 24 and x₁ + x₂ ≤ 10",
  "Neither constraint is binding at the optimal corner"
)
$answer[3] = 2
$displayformat[3] = "select"
$noshuffle[3] = "all"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .corner-table { border-collapse:collapse; width:100%; margin:8px 0; font-size:14px; }
  .corner-table th { background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:6px 12px; text-align:center; }
  .corner-table td { padding:6px 12px; border:1px solid #dee1e3; text-align:center; }
  .corner-opt { background:#e8f5e9; font-weight:700; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>LP Formulation:</b></p>
      <p>Maximize Z = 4x<sub>1</sub> + 5x<sub>2</sub></p>
      <p>Subject to:<br>
        &nbsp;&nbsp;2x<sub>1</sub> + 3x<sub>2</sub> &le; 24 &nbsp;(blender time)<br>
        &nbsp;&nbsp;x<sub>1</sub> + x<sub>2</sub> &le; 10 &nbsp;(ingredient budget)<br>
        &nbsp;&nbsp;x<sub>1</sub>, x<sub>2</sub> &ge; 0
      </p>
      <p><b>Identify corner points of the feasible region:</b></p>
      <table class="corner-table">
        <tr>
          <th>Corner</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>Z = 4x<sub>1</sub> + 5x<sub>2</sub></th>
        </tr>
        <tr><td>O (origin)</td><td>0</td><td>0</td><td>0</td></tr>
        <tr><td>A (budget, x<sub>2</sub>=0)</td><td>10</td><td>0</td><td>40</td></tr>
        <tr class="corner-opt"><td>B (both constraints active) &#9733;</td><td>6</td><td>4</td><td>44</td></tr>
        <tr><td>C (blender, x<sub>1</sub>=0)</td><td>0</td><td>8</td><td>40</td></tr>
      </table>
      <p><b>Finding corner B:</b> Set 2x<sub>1</sub>+3x<sub>2</sub>=24 and x<sub>1</sub>+x<sub>2</sub>=10 simultaneously.<br>
      Substituting x<sub>1</sub> = 10 &minus; x<sub>2</sub>: 2(10 &minus; x<sub>2</sub>) + 3x<sub>2</sub> = 24 &rarr; x<sub>2</sub> = 4, x<sub>1</sub> = 6.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Optimal:</b> x<sub>1</sub> = 6 fruit smoothies, x<sub>2</sub> = 4 green smoothies, Maximum Z = $44.<br>
        Both constraints are binding at B (both hold with equality).
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 6px 0;"><b>Smoothie Bliss</b> produces two drinks each week: <b>fruit smoothies</b> (x<sub>1</sub>) and <b>green smoothies</b> (x<sub>2</sub>). Each fruit smoothie earns $4 profit; each green smoothie earns $5 profit.</p>
<p style="margin:0 0 4px 0;">Production is limited by two constraints:</p>
<table style="border-collapse:collapse; width:100%; margin:4px 0;">
<tr><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:left;">Constraint</th><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Fruit (x<sub>1</sub>)</th><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Green (x<sub>2</sub>)</th><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Limit</th></tr>
<tr><td style="padding:6px 12px; border:1px solid #dee1e3;">Blender time (hrs/week)</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">2</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">3</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">24</td></tr>
<tr><td style="padding:6px 12px; border:1px solid #dee1e3;">Ingredient budget (batches/week)</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">1</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">1</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">10</td></tr>
</table>
<p style="margin:6px 0 0 0;">Graph the feasible region, identify all corner points, evaluate Z = 4x<sub>1</sub> + 5x<sub>2</sub> at each corner, and find the maximum.</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a</span>Optimal x<sub>1</sub> (fruit smoothies): <span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b</span>Optimal x<sub>2</sub> (green smoothies): <span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c</span>Maximum value of Z ($): <span style="margin-left:8px;">$answerbox[2]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d</span>Which constraint(s) are binding (active) at the optimal corner? <span style="margin-left:8px;">$answerbox[3]</span>
</div>
</div>

///

$solutionguide
