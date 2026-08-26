// === NAME - DESCRIPTION: Find the Mistake in a Linear Inequality - A worked solution contains one error (the sign flip); identify the step, give the corrected solution, and name the habit ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("choices", "choices", "choices")
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"

/* ---------- 1. Randomization ---------- */
// Each scenario: [a, b, c, d, sign, boundary]
// Student's work (shown to the student):
//   Step 1:  ax + b <op> cx + d            (setup, correct)
//   Step 2:  (a-c)x <op> (d-b)           (correct: terms moved)
//   Step 3:  x <op> boundary             (WRONG: division by a negative without flipping)
// Every scenario has (a - c) < 0, so dividing by (a - c) must flip the direction.
// Boundary = (d - b) / (a - c), guaranteed clean integer.
//
// S0: 2x - 7 <= 5x - 1   =>  -3x <= 6   =>  x >= -2   (student wrote x <= -2)
// S1: 3x + 2 <= 5x - 4   =>  -2x <= -6  =>  x >= 3    (student wrote x <= 3)
// S2: 4x + 1 <= 6x - 5   =>  -2x <= -6  =>  x >= 3    (student wrote x <= 3)
// S3: 5x + 2 <= 8x - 4   =>  -3x <= -6  =>  x >= 2    (student wrote x <= 2)
// S4: x + 10 <= 3x - 2   =>  -2x <= -12 =>  x >= 6    (student wrote x <= 6)
// S5: 3x - 4 >= 5x       =>  -2x >= 4   =>  x <= -2   (student wrote x >= -2)

$sc_a    = array(2, 3, 4, 5, 1, 3)
$sc_b    = array(-7, 2, 1, 2, 10, -4)
$sc_c    = array(5, 5, 6, 8, 3, 5)
$sc_d    = array(-1, -4, -5, -4, -2, 0)
$sc_sign = array(0, 0, 0, 0, 0, 1)        // 0 = "<=", 1 = ">="
$sc_bnd  = array(-2, 3, 3, 2, 6, -2)

$picked = jointrandfrom($sc_a, $sc_b, $sc_c, $sc_d, $sc_sign, $sc_bnd)
$a = $picked[0]
$b = $picked[1]
$c = $picked[2]
$d = $picked[3]
$sign = $picked[4]
$boundary = $picked[5]

// Display helpers (sign-aware, no "+ -")
$lhs = $a . "x"
if ($b >= 0) { $lhs = $lhs . " + " . $b } else { $lhs = $lhs . " - " . abs($b) }
$rhs = $c . "x"
if ($d >= 0) { $rhs = $rhs . " + " . $d } else { $rhs = $rhs . " - " . abs($d) }

$c1 = $a - $c                                   // coefficient after moving cx left (always negative)
$c2 = $d - $b                                   // constant after moving b right
$step2_lhs = $c1 . "x"
$step2_rhs = $c2
if ($c2 < 0) { $step2_rhs = "-" . abs($c2) }

// The student's (wrong) Step 3 keeps the same direction; the corrected one flips.
$op_sym = "&le;"
$opp_op = "&ge;"
$op_txt = "<="
$opp_txt = ">="
if ($sign == 1) { $op_sym = "&ge;"; $opp_op = "&le;"; $op_txt = ">="; $opp_txt = "<=" }

$answer[0] = 2                                  // Step 3 is the wrong step
$answer[1] = 1                                  // the flipped direction is correct
$answer[2] = 0                                  // the sign-flip habit

$questions[0] = array("Step 1", "Step 2", "Step 3")
$questions[1] = array("x " . $op_txt . " " . $boundary, "x " . $opp_txt . " " . $boundary)
$questions[2] = array(
  "Dividing both sides by a negative number without reversing the inequality sign",
  "Subtracting a term from the wrong side of the inequality",
  "Adding to one side but subtracting from the other",
  "Writing the boundary value on the wrong side of the inequality sign"
)

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
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">The error.</span> Step 3 is wrong: the student divided both sides by a negative number but did not reverse the inequality sign.</p>
      <p><b>Step 1:</b> &nbsp; `$lhs $op_txt $rhs`</p>
      <p><b>Step 2:</b> &nbsp; `$step2_lhs $op_txt $step2_rhs`</p>
      <p><b>Step 3 (the fix):</b> divide both sides by `$c1`, which is negative, so the direction flips:</p>
      <p style="margin-left:1.5em;"><b>`x $opp_txt $boundary`</b></p>
      <p style="margin:10px 0; padding:0.6em 1em; background:#fff3cd; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;"><b>Sign flip:</b> dividing both sides of an inequality by a negative number reverses the direction. The boundary value is <b>' . $boundary . '</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 8px 0;"><b>Student work.</b> A student solved the inequality `$lhs $op_txt $rhs` as shown below. Exactly one step contains an error.</p>
<table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
  <tr><td style="border:1px solid #21242c; padding:6px 16px; text-align:left;">Step 1</td><td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">`$lhs $op_txt $rhs`</td></tr>
  <tr><td style="border:1px solid #21242c; padding:6px 16px; text-align:left;">Step 2</td><td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">`$step2_lhs $op_txt $step2_rhs`</td></tr>
  <tr><td style="border:1px solid #21242c; padding:6px 16px; text-align:left;">Step 3</td><td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">`x $op_txt $boundary`</td></tr>
</table>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which step contains the error? <span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which inequality correctly describes the solution? <span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which habit most likely caused this error? <span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>

// === ANSWER ===

$solutionguide
