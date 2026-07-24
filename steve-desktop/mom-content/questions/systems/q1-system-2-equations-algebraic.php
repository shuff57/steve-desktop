// === NAME - DESCRIPTION: Solve a 2-Equation Linear System - Elimination method to find integer solution (x, y) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "number")

/* ---------- 1. Randomization ---------- */
// Each scenario is a system with a known integer solution.
// Stored as [a1, b1, c1, a2, b2, c2, sol_x, sol_y]
// where a1*x + b1*y = c1 and a2*x + b2*y = c2.
// Systems constructed from solution first; all coefficients are small integers.
//
// System 1: solution (2, -3)   =>  x + y = -1,  2x - y = 7
// System 2: solution (-1, 4)   =>  x + y = 3,   3x + y = 1
// System 3: solution (5, 1)    =>  x + y = 6,   2x - y = 9
// System 4: solution (3, -2)   =>  x + y = 1,   x - 2y = 7
// System 5: solution (-2, 5)   =>  x + y = 3,   2x + y = 1
// System 6: solution (4, -1)   =>  x + y = 3,   3x - y = 13
// System 7: solution (1, 3)    =>  2x + y = 5,  x - y = -2
// System 8: solution (-3, 2)   =>  x + y = -1,  2x + 3y = 0

$sc_a1   = array(1,  1,  1,  1,  1,  1,  2,  1)
$sc_b1   = array(1,  1,  1,  1,  1,  1,  1,  1)
$sc_c1   = array(-1, 3,  6,  1,  3,  3,  5, -1)
$sc_a2   = array(2,  3,  2,  1,  2,  3,  1,  2)
$sc_b2   = array(-1, 1, -1, -2,  1, -1, -1,  3)
$sc_c2   = array(7,  1,  9,  7,  1, 13, -2,  0)
$sc_solx = array(2, -1,  5,  3, -2,  4,  1, -3)
$sc_soly = array(-3, 4,  1, -2,  5, -1,  3,  2)

$picked = jointrandfrom($sc_a1, $sc_b1, $sc_c1, $sc_a2, $sc_b2, $sc_c2, $sc_solx, $sc_soly)
$a1   = $picked[0]
$b1   = $picked[1]
$c1   = $picked[2]
$a2   = $picked[3]
$b2   = $picked[4]
$c2   = $picked[5]
$solx = $picked[6]
$soly = $picked[7]

$answer[0] = $solx
$answer[1] = $soly
$abstolerance[0] = 0.01
$abstolerance[1] = 0.01

/* ---------- 2. Display helpers ---------- */
// Format a coefficient nicely: skip "1x" -> "x", "-1x" -> "-x"
$a1x = $a1 . "x"
if ($a1 == 1) { $a1x = "x" }
if ($a1 == -1) { $a1x = "-x" }

$a2x = $a2 . "x"
if ($a2 == 1) { $a2x = "x" }
if ($a2 == -1) { $a2x = "-x" }

// Format "+ b1*y" part
$b1y_disp = " + " . $b1 . "y"
if ($b1 == 1) { $b1y_disp = " + y" }
if ($b1 == -1) { $b1y_disp = " - y" }
if ($b1 < -1) { $b1y_disp = " - " . abs($b1) . "y" }

$b2y_disp = " + " . $b2 . "y"
if ($b2 == 1) { $b2y_disp = " + y" }
if ($b2 == -1) { $b2y_disp = " - y" }
if ($b2 < -1) { $b2y_disp = " - " . abs($b2) . "y" }

$eq1_disp = $a1x . $b1y_disp . " = " . $c1
$eq2_disp = $a2x . $b2y_disp . " = " . $c2

/* ---------- 3. Solution guide ---------- */
// Multiply eq1 by a2, eq2 by a1, then subtract to eliminate x.
$mult1 = $a2
$mult2 = $a1
$new_b1 = $b1 * $mult1
$new_c1 = $c1 * $mult1
$new_b2 = $b2 * $mult2
$new_c2 = $c2 * $mult2
$elim_b = $new_b1 - $new_b2
$elim_c = $new_c1 - $new_c2
// y solution from elimination
$soly_check = $elim_c / $elim_b

// Back-substitute to find x from eq1
$solx_check = ($c1 - $b1 * $soly) / $a1

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>System:</b></p>
      <p style="margin-left:1.5em;">(1) &nbsp; `' . $eq1_disp . '`</p>
      <p style="margin-left:1.5em;">(2) &nbsp; `' . $eq2_disp . '`</p>
      <p><b>Step 1 (Elimination):</b> Multiply equation (1) by ' . $mult1 . ' and equation (2) by ' . $mult2 . ' to make the `x`-coefficients equal, then subtract.</p>
      <p style="margin-left:1.5em;">Multiply (1) by ' . $mult1 . ': &nbsp; `' . ($a1*$mult1) . 'x + (' . $new_b1 . ')y = ' . $new_c1 . '`</p>
      <p style="margin-left:1.5em;">Multiply (2) by ' . $mult2 . ': &nbsp; `' . ($a2*$mult2) . 'x + (' . $new_b2 . ')y = ' . $new_c2 . '`</p>
      <p style="margin-left:1.5em;">Subtract: &nbsp; `(' . $elim_b . ')y = ' . $elim_c . '` &nbsp;&rArr;&nbsp; `y = ' . $soly . '`</p>
      <p><b>Step 2 (Back-substitute):</b> Plug `y = ' . $soly . '` into equation (1):</p>
      <p style="margin-left:1.5em;">`' . $a1x . ($b1 >= 0 ? " + " : " + (") . $b1 . ($b1 >= 0 ? "" : ")") . '(' . $soly . ') = ' . $c1 . '` &nbsp;&rArr;&nbsp; `' . $a1x . ($b1*$soly >= 0 ? " + " . ($b1*$soly) : " + (" . ($b1*$soly) . ")") . ' = ' . $c1 . '` &nbsp;&rArr;&nbsp; `x = ' . $solx . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Solution: &nbsp; <b>`x = ' . $solx . '`</b>, &nbsp; <b>`y = ' . $soly . '`</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 6px 0;">Solve the following system of equations algebraically using elimination or substitution.</p>
<p style="margin:4px 0; text-align:center;">`$eq1_disp`</p>
<p style="margin:4px 0; text-align:center;">`$eq2_disp`</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the value of `x`? <span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the value of `y`? <span style="margin-left:8px;">$answerbox[1]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
