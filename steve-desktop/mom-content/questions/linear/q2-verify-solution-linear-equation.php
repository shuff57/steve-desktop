// === NAME - DESCRIPTION: Verify Solution Linear Equation - Determine which ordered pair satisfies Ax + By = C ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("choices")
$displayformat[0] = "select"
$noshuffle[0] = "all"

// Build an integer linear equation Ax + By = C with A, B nonzero
$A = nonzerorand(-5, 5)
$B = nonzerorand(-5, 5)

// Pick a true solution point with x0 != y0 so the (y0, x0) distractor never coincides.
$x0, $y0 = nonzerodiffrands(-4, 4, 2)
$C = $A * $x0 + $B * $y0

// Place the true candidate at a random slot and fill the other 3 with distinct distractors.
$true_idx = rand(0, 3)

$d1x = $x0 + 1
$d1y = $y0
$d2x = $x0 - 1
$d2y = $y0 + 1
$d3x = $y0
$d3y = $x0

$sx = array(0, 0, 0, 0)
$sy = array(0, 0, 0, 0)
$sx[$true_idx] = $x0
$sy[$true_idx] = $y0

if ($true_idx == 0) {
  $sx[1] = $d1x
  $sy[1] = $d1y
  $sx[2] = $d2x
  $sy[2] = $d2y
  $sx[3] = $d3x
  $sy[3] = $d3y
} elseif ($true_idx == 1) {
  $sx[0] = $d1x
  $sy[0] = $d1y
  $sx[2] = $d2x
  $sy[2] = $d2y
  $sx[3] = $d3x
  $sy[3] = $d3y
} elseif ($true_idx == 2) {
  $sx[0] = $d1x
  $sy[0] = $d1y
  $sx[1] = $d2x
  $sy[1] = $d2y
  $sx[3] = $d3x
  $sy[3] = $d3y
} else {
  $sx[0] = $d1x
  $sy[0] = $d1y
  $sx[1] = $d2x
  $sy[1] = $d2y
  $sx[2] = $d3x
  $sy[2] = $d3y
}

// Build display strings
$opt0 = "(" . $sx[0] . ", " . $sy[0] . ")"
$opt1 = "(" . $sx[1] . ", " . $sy[1] . ")"
$opt2 = "(" . $sx[2] . ", " . $sy[2] . ")"
$opt3 = "(" . $sx[3] . ", " . $sy[3] . ")"

$questions[0] = array($opt0, $opt1, $opt2, $opt3)
$answer[0] = $true_idx

// Build equation display: Ax + By = C
$Apart = $A . "x"
if ($A == 1) { $Apart = "x" }
elseif ($A == -1) { $Apart = "-x" }

if ($B > 0) {
  $Bvar = $B . "y"
  if ($B == 1) { $Bvar = "y" }
  $Bpart = " + " . $Bvar
} else {
  $Bvar = abs($B) . "y"
  if ($B == -1) { $Bvar = "y" }
  $Bpart = " - " . $Bvar
}
$eq_latex = $Apart . $Bpart . " = " . $C

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em 1em; background:#fafafa; }
  .sol-body p { margin: 0.6em 0; }
  .sol-body .step { font-weight:700; color:#1865f2; margin-top:1em; }
  .sol-body .calc { margin: 0.4em 0 0.4em 1.5em; font-size:17px; }
  .sol-body .answer-box { margin: 1em 0 0 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>An ordered pair `(x, y)` is a <b>solution</b> of a linear equation when substituting those values for `x` and `y` makes the equation a true statement. To find which choice is the solution, plug each pair into the left side and check whether it equals the right side.</p>

      <p class="step">Step 1. Write the equation and the candidate point.</p>
      <p>The equation is `' . $eq_latex . '`. The correct ordered pair we are checking is `(' . $x0 . ', ' . $y0 . ')`.</p>

      <p class="step">Step 2. Substitute and simplify.</p>
      <p>Replace `x` with ' . $x0 . ' and `y` with ' . $y0 . ' in the left side `' . $A . 'x + ' . $B . 'y`:</p>
      <p class="calc">`(' . $A . ')(' . $x0 . ') + (' . $B . ')(' . $y0 . ')`</p>
      <p>Multiply each term:</p>
      <p class="calc">`= ' . ($A * $x0) . ' + ' . ($B * $y0) . '`</p>
      <p>Add the results:</p>
      <p class="calc">`= ' . $C . '`</p>

      <p class="step">Step 3. Compare with the right side.</p>
      <p>The equation requires the left side to equal `' . $C . '`. We computed `' . $C . '`, so the substitution gives a TRUE statement. The point satisfies the equation.</p>

      <p class="step">Why the other choices fail.</p>
      <p>Each distractor changes the `x` or `y` value by a small amount, so plugging it in produces a number different from `' . $C . '`. You can verify by repeating Step 2 with any of the other ordered pairs: the totals will not equal `' . $C . '`.</p>

      <div class="answer-box">
        Solution: <b>(' . $x0 . ', ' . $y0 . ')</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 4px 0;">Which of the following ordered pairs is a solution to the linear equation</p>
<p style="margin:0; text-align:center; font-size:18px;">`$eq_latex`?</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Ordered pair: <span style="margin-left:8px;">$answerbox[0]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
