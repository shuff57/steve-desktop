// === NAME - DESCRIPTION: Slope and Equation of a Flat or Vertical Line - From two points or a change in x and y, give the slope (0 or undefined), the equation y = k or x = h, and say if it is a function ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.3 (2.3.2). Each draw is a horizontal line or a vertical line, chosen by $vert, and it is
// described either by two points or by a start point with a change in x and a change in y. The constant
// k is never 0, so the equation is never y = 0 or x = 0. The two points always differ, so the line is
// well defined. Box 1 picks the variable and box 2 is the constant.
$anstypes = array("choices", "choices", "number", "choices")

$vert = rand(0, 1)
$form = rand(0, 1)
$k = nonzerorand(-9, 9)
$u = diffrands(-9, 9, 2)
$u1 = $u[0]
$u2 = $u[1]

$x1 = $u1
$y1 = $k
$x2 = $u2
$y2 = $k
if ($vert == 1) {
  $x1 = $k
  $y1 = $u1
  $x2 = $k
  $y2 = $u2
}
$dx = $x2 - $x1
$sx1 = $x1
if ($x1 < 0) {
  $sx1 = "(" . $x1 . ")"
}
$sy1 = $y1
if ($y1 < 0) {
  $sy1 = "(" . $y1 . ")"
}
$sdx = $dx
if ($dx < 0) {
  $sdx = "(" . $dx . ")"
}
$dy = $y2 - $y1

$desc = "A line passes through the points `(" . $x1 . ", " . $y1 . ")` and `(" . $x2 . ", " . $y2 . ")`."
if ($form == 1) {
  $desc = "A line starts at the point `(" . $x1 . ", " . $y1 . ")`. It has `Delta y = " . $dy . "` when `Delta x = " . $dx . "`."
}

$questions[0] = array("0", "undefined", "positive", "negative")
$questions[1] = array("x", "y")
$questions[3] = array("Yes, it is a function", "No, it is not a function")
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[3] = "select"

$slopeIdx = 0
$varIdx = 1
$funcIdx = 0
$varName = "y"
$slopeWord = "0"
$why = "Every point on a horizontal line has the same y-value, " . $k . ". Each input x has exactly one output, so it is a function."
$workSlope = "The change in y is `" . $y2 . " - " . $sy1 . " = 0`, so the slope is `0/" . $sdx . " = 0`."
if ($vert == 1) {
  $slopeIdx = 1
  $varIdx = 0
  $funcIdx = 1
  $varName = "x"
  $slopeWord = "undefined"
  $why = "Every point on a vertical line has the same x-value, " . $k . ". The input " . $k . " has more than one output, so it is not a function."
  $workSlope = "The change in x is `" . $x2 . " - " . $sx1 . " = 0`, and dividing by 0 cannot be done."
}

$answer[0] = $slopeIdx
$answer[1] = $varIdx
$answer[2] = $k
$answer[3] = $funcIdx
$answerboxsize[2] = 6
$abstolerance[2] = 0.001

$sol = '
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
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">The two points.</span> The line goes through `(' . $x1 . ', ' . $y1 . ')` and `(' . $x2 . ', ' . $y2 . ')`. The change in x is `' . $dx . '` and the change in y is `' . $dy . '`.</p>
      <p><span class="term-label">Slope.</span> ' . $workSlope . ' The slope is ' . $slopeWord . '.</p>
      <p><span class="term-label">Equation.</span> ' . $why . ' The equation is `' . $varName . ' = ' . $k . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$desc</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The slope of the line is $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The equation of the line is $answerbox[1] = $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is the line a function? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
