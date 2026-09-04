// === NAME - DESCRIPTION: LP Application Formulation - T-shirt printing shop; identify objective function, constraints, and evaluate Z at the optimal corner ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Scenario: PrintRun makes two types of shirts:
//   x1 = custom printed shirts (profit $p1 each)
//   x2 = blank (stock) shirts (profit $p2 each)
//
// Constraints:
//   Labor: L1*x1 + L2*x2 <= H  (labor-hours per week)
//   Material: M1*x1 + M2*x2 <= F (fabric yards per week)
//   x1, x2 >= 0
//
// Randomize p1, p2 (profit), H (labor hours), F (fabric yards)
// within ranges that keep the optimal corner at clean integers.
//
// We fix the constraint matrix at [[2,1],[1,2]] (det=3) and
// let the RHS vary. This gives intersection:
//   2x1 + x2 = H  and  x1 + 2x2 = F
//   => x1 = (2H-F)/3,  x2 = (2F-H)/3
// For integer corners: need H,F such that (2H-F) and (2F-H) are divisible by 3.
// Choose H = 3*h, F = 3*f for integers h, f.
// Then x1 = (6h-3f)/3 = 2h-f, x2 = (6f-3h)/3 = 2f-h.
// Need x1>0 and x2>0: 2h>f and 2f>h, i.e., h/2 < f < 2h.
//
// Pick h in {4,5,6}, f in {h+1, h+2} to guarantee x1>0, x2>0.

$h = rand(4, 6)
$f_offset = rand(1, 2)
$f = $h + $f_offset
$H = 3 * $h
$F = 3 * $f

// Fixed constraint coefficients
$L1 = 2
$L2 = 1
$M1 = 1
$M2 = 2

// Optimal interior corner
$x1opt = 2*$h - $f
$x2opt = 2*$f - $h

// Profit coefficients: randomize moderately, p1 in {8,10,12}, p2 in {6,8,10}
// Use randfrom for clean values
$p1 = randfrom(array(8, 10, 12))
$p2 = randfrom(array(6, 8, 10))

$Zopt = $p1*$x1opt + $p2*$x2opt

// === Answer setup ===
// Part (a): objective function: choices, correct is Z = p1*x1 + p2*x2
// Part (b): labor constraint: choices, correct is L1*x1 + L2*x2 <= H
// Part (c): material constraint: choices, correct is M1*x1 + M2*x2 <= F
// Part (d): non-negativity: choices
// Part (e): Z at optimal corner (number)

$anstypes = array("choices", "choices", "choices", "choices", "number")
$answerformat[4] = "integer"
$answer[4] = $Zopt

// Part (a): objective function choices
// Distractors: swap coefficients, use constraint RHS as coefficient, wrong operation
$choices[0] = array(
  "Z = " . $p1 . "x₁ + " . $p2 . "x₂",
  "Z = " . $p2 . "x₁ + " . $p1 . "x₂",
  "Z = " . $L1 . "x₁ + " . $L2 . "x₂",
  "Z = " . $M1 . "x₁ + " . $M2 . "x₂"
)
$answer[0] = 0
$displayformat[0] = "select"
$noshuffle[0] = "all"

// Part (b): labor constraint choices
$choices[1] = array(
  "" . $L1 . "x₁ + " . $L2 . "x₂ ≤ " . $H,
  "" . $L1 . "x₁ + " . $L2 . "x₂ ≤ " . $F,
  "" . $M1 . "x₁ + " . $M2 . "x₂ ≤ " . $H,
  "" . $p1 . "x₁ + " . $p2 . "x₂ ≤ " . $H
)
$answer[1] = 0
$displayformat[1] = "select"
$noshuffle[1] = "all"

// Part (c): material constraint choices
$choices[2] = array(
  "" . $M1 . "x₁ + " . $M2 . "x₂ ≤ " . $F,
  "" . $M1 . "x₁ + " . $M2 . "x₂ ≤ " . $H,
  "" . $L1 . "x₁ + " . $L2 . "x₂ ≤ " . $F,
  "" . $p1 . "x₁ + " . $p2 . "x₂ ≤ " . $F
)
$answer[2] = 0
$displayformat[2] = "select"
$noshuffle[2] = "all"

// Part (d): non-negativity constraint choices
$choices[3] = array(
  "x₁ ≥ 0 and x₂ ≥ 0",
  "x₁ > 0 and x₂ > 0",
  "x₁ ≤ 0 and x₂ ≤ 0",
  "x₁ ≥ 0 or x₂ ≥ 0"
)
$answer[3] = 0
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
  .corner-table th { background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center; }
  .corner-table td { padding:6px 12px; border:1px solid #dee1e3; text-align:center; }
  .corner-opt { background:#e8f5e9; font-weight:700; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Solution Guide
    </summary>
    <div class="sol-body">
      <p><b>Complete LP Formulation:</b></p>
      <p>Let x<sub>1</sub> = custom shirts, x<sub>2</sub> = blank shirts.</p>
      <p>Maximize Z = ' . $p1 . 'x<sub>1</sub> + ' . $p2 . 'x<sub>2</sub></p>
      <p>Subject to:<br>
        &nbsp;&nbsp;2x<sub>1</sub> + x<sub>2</sub> &le; ' . $H . ' &nbsp;(labor-hours)<br>
        &nbsp;&nbsp;x<sub>1</sub> + 2x<sub>2</sub> &le; ' . $F . ' &nbsp;(fabric yards)<br>
        &nbsp;&nbsp;x<sub>1</sub>, x<sub>2</sub> &ge; 0
      </p>
      <p><b>Corner points and Z values:</b></p>
      <table class="corner-table">
        <tr>
          <th>Corner</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>Z = ' . $p1 . 'x<sub>1</sub> + ' . $p2 . 'x<sub>2</sub></th>
        </tr>
        <tr><td>O (0, 0)</td><td>0</td><td>0</td><td>0</td></tr>
        <tr><td>A (labor, x<sub>2</sub>=0)</td><td>' . ($H/2) . '</td><td>0</td><td>' . ($p1*($H/2)) . '</td></tr>
        <tr class="corner-opt"><td>B (both active) &#9733;</td><td>' . $x1opt . '</td><td>' . $x2opt . '</td><td>' . $Zopt . '</td></tr>
        <tr><td>C (fabric, x<sub>1</sub>=0)</td><td>0</td><td>' . ($F/2) . '</td><td>' . ($p2*($F/2)) . '</td></tr>
      </table>
      <p><b>Finding corner B:</b> Solve 2x<sub>1</sub>+x<sub>2</sub>=' . $H . ' and x<sub>1</sub>+2x<sub>2</sub>=' . $F . ' simultaneously.<br>
      Subtract: x<sub>1</sub> &minus; x<sub>2</sub> = ' . ($H-$F) . ', combined with x<sub>1</sub>+2x<sub>2</sub>=' . $F . ' gives x<sub>2</sub>=' . $x2opt . ', x<sub>1</sub>=' . $x1opt . '.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Optimal solution:</b> x<sub>1</sub> = ' . $x1opt . ', x<sub>2</sub> = ' . $x2opt . ', Maximum Z = $' . $Zopt . '
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 6px 0;"><b>PrintRun</b> is a t-shirt printing company that makes two products each week: <b>custom printed shirts</b> (x<sub>1</sub>) and <b>blank stock shirts</b> (x<sub>2</sub>). Each custom shirt earns <b>$$p1 profit</b>; each blank shirt earns <b>$$p2 profit</b>.</p>
<p style="margin:0 0 4px 0;">Two resources limit production each week:</p>
<table style="border-collapse:collapse; width:100%; margin:4px 0;">
<tr><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:left;">Resource</th><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Custom (x<sub>1</sub>)</th><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Blank (x<sub>2</sub>)</th><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Available</th></tr>
<tr><td style="padding:6px 12px; border:1px solid #dee1e3;">Labor (hours)</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">2</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">1</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$H</td></tr>
<tr><td style="padding:6px 12px; border:1px solid #dee1e3;">Fabric (yards)</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">1</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">2</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$F</td></tr>
</table>
<p style="margin:6px 0 0 0;">Identify the LP formulation. The optimal corner is at x<sub>1</sub> = $x1opt, x<sub>2</sub> = $x2opt.</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a</span>Select the correct objective function: <span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b</span>Select the correct labor constraint: <span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c</span>Select the correct fabric constraint: <span style="margin-left:8px;">$answerbox[2]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d</span>Select the non-negativity constraint: <span style="margin-left:8px;">$answerbox[3]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e</span>Evaluate Z at the optimal corner (x<sub>1</sub> = $x1opt, x<sub>2</sub> = $x2opt). Maximum Z = <span style="margin-left:8px;">$answerbox[4]</span>
</div>
</div>

///

$solutionguide
