// === NAME - DESCRIPTION: Solve a Linear Inequality - Solve ax + b <= cx + d for x, with sign-flip explanation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "choices")
$displayformat[1] = "select"
$noshuffle[1] = "all"

/* ---------- 1. Randomization ---------- */
// Each scenario: [a, b, c, d, boundary, direction_index]
// Boundary = (d - b) / (a - c), guaranteed clean integer.
// direction_index: 0 = "x <= boundary" (no sign flip), 1 = "x >= boundary" (sign flip when a - c < 0)
// Scenarios constructed so (a - c) != 0 and boundary is an integer.
//
// Scenario 1: 3x + 1 <= x + 7   => 2x <= 6 => x <= 3   (positive coeff, no flip)
// Scenario 2: 2x + 5 <= 5x - 4  => -3x <= -9 => x >= 3  (negative coeff, flip)
// Scenario 3: 4x - 3 <= x + 9   => 3x <= 12 => x <= 4   (positive coeff, no flip)
// Scenario 4: x + 10 <= 4x - 2  => -3x <= -12 => x >= 4  (negative coeff, flip)
// Scenario 5: 5x + 2 <= 2x + 11 => 3x <= 9 => x <= 3    (positive coeff, no flip)
// Scenario 6: x - 1 <= 3x - 9   => -2x <= -8 => x >= 4  (negative coeff, flip)

$sc_a   = array(3, 2, 4, 1, 5, 1)
$sc_b   = array(1, 5, -3, 10, 2, -1)
$sc_c   = array(1, 5, 1, 4, 2, 3)
$sc_d   = array(7, -4, 9, -2, 11, -9)
$sc_bnd = array(3, 3, 4, 4, 3, 4)
$sc_dir = array(0, 1, 0, 1, 0, 1)

$picked = jointrandfrom($sc_a, $sc_b, $sc_c, $sc_d, $sc_bnd, $sc_dir)
$a = $picked[0]
$b = $picked[1]
$c = $picked[2]
$d = $picked[3]
$boundary = $picked[4]
$dir_idx  = $picked[5]

$answer[0] = $boundary
$abstolerance[0] = 0.01

// choices for direction
$dir_choices = array("x <= " . $boundary, "x >= " . $boundary)
$answer[1] = $dir_idx

// Display coefficients nicely
$a_str = $a
$b_str = $b
$c_str = $c
$d_str = $d

// left side display: ax + b
$lhs = $a_str . "x"
if ($b >= 0) {
  $lhs = $lhs . " + " . $b
} else {
  $lhs = $lhs . " - " . abs($b)
}

// right side display: cx + d
$rhs = $c_str . "x"
if ($d >= 0) {
  $rhs = $rhs . " + " . $d
} else {
  $rhs = $rhs . " - " . abs($d)
}

// Algebra display values
$coeff_diff = $a - $c       // coefficient of x after moving cx to left
$const_diff = $d - $b       // constant after moving b to right

// Step display: coeff_diff * x <= const_diff
$step1_lhs = $coeff_diff . "x"
if ($const_diff >= 0) {
  $step1_rhs = $const_diff
} else {
  $step1_rhs = $const_diff
}

// Sign flip note
$flip_note = ""
if ($coeff_diff < 0) {
  $flip_note = '<p style="margin:6px 0; padding:6px 12px; background:#fff3cd; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0; font-size:15px;"><b>Sign flip:</b> Dividing both sides by a negative number (' . $coeff_diff . ') reverses the inequality direction from &le; to &ge;.</p>'
}

// Final answer display
$final_ineq = "x &le; " . $boundary
if ($dir_idx == 1) {
  $final_ineq = "x &ge; " . $boundary
}

/* ---------- 2. Solution guide ---------- */
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
      <p><b>Start:</b> &nbsp; `' . $lhs . ' <= ' . $rhs . '`</p>
      <p><b>Step 1:</b> Subtract `' . $c_str . 'x` from both sides and subtract `' . $b . '` from both sides:</p>
      <p style="margin-left:1.5em;">`' . $step1_lhs . ' <= ' . $step1_rhs . '`</p>
      <p><b>Step 2:</b> Divide both sides by `' . $coeff_diff . '`.</p>
      ' . $flip_note . '
      <p><b>Result:</b> &nbsp; <b>' . $final_ineq . '</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Boundary value: <b>' . $boundary . '</b> &nbsp;&bull;&nbsp; Solution: <b>' . $final_ineq . '</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 6px 0;">Solve the inequality for `x`:</p>
<p style="margin:0; text-align:center; font-size:18px;">`$lhs <= $rhs`</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the boundary value of `x`? <span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which inequality correctly describes the solution? <span style="margin-left:8px;">$answerbox[1]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
