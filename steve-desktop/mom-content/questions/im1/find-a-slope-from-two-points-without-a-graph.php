// === NAME - DESCRIPTION: Find a Slope from Two Points Without a Graph - Use the slope formula in a fixed order, reduce the fraction, then say if the line rises, falls or is flat ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.3 (2.3.3). Both x-values differ, so the slope always exists and the line is never
// vertical. About one draw in six has equal y-values, so the line is flat and the slope is 0. Rise and
// run are both asked as second point minus first point, so the order is fixed and every part has
// one answer. The slope is entered as a whole number or a fraction in lowest terms.
$anstypes = array("number", "number", "calculated", "choices")

$xs = diffrands(-9, 9, 2)
$x1 = $xs[0]
$x2 = $xs[1]
$ys = diffrands(-9, 9, 2)
$y1 = $ys[0]
$y2 = $ys[1]
$flat = rand(0, 5)
if ($flat == 0) {
  $y2 = $y1
}
$rise = $y2 - $y1
$sy1 = $y1
$sx1 = $x1
if ($y1 < 0) {
  $sy1 = "(" . $y1 . ")"
}
if ($x1 < 0) {
  $sx1 = "(" . $x1 . ")"
}
$run = $x2 - $x1
$m = $rise / $run

$sRise = $rise
$sRun = $run
if ($rise < 0) {
  $sRise = "(" . $rise . ")"
}
if ($run < 0) {
  $sRun = "(" . $run . ")"
}
$num = $rise
$den = $run
if ($rise != 0) {
  $gg = gcd(abs($rise), abs($run))
  $num = $rise / $gg
  $den = $run / $gg
  if ($den < 0) {
    $num = -$num
    $den = -$den
  }
} else {
  $num = 0
  $den = 1
}
$mTxt = $num
if ($den != 1) {
  $mTxt = $num . "/" . $den
}

$questions[3] = array("The line rises from left to right", "The line falls from left to right", "The line is flat", "The line is vertical")
$displayformat[3] = "select"
$moveX = "to the right"
if ($run < 0) {
  $moveX = "to the left"
}
$moveY = "stays level"
if ($rise > 0) {
  $moveY = "up"
}
if ($rise < 0) {
  $moveY = "down"
}
$dirIdx = 2
$dirWhy = "The slope is 0, so the line is flat."
if ($m > 0) {
  $dirIdx = 0
  $dirWhy = "The slope is positive, so the line rises from left to right."
}
if ($m < 0) {
  $dirIdx = 1
  $dirWhy = "The slope is negative, so the line falls from left to right."
}

$answer[0] = $rise
$answer[1] = $run
$answer[2] = $m
$answer[3] = $dirIdx
$answerformat[2] = "reducedfraction"
$showanswer[2] = $mTxt
$answerboxsize[0] = 6
$answerboxsize[1] = 6
$answerboxsize[2] = 8
$abstolerance[0] = 0.001
$abstolerance[1] = 0.001
$abstolerance[2] = 0.0001

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
      <p><span class="term-label">Name the points.</span> `(x_1, y_1) = (' . $x1 . ', ' . $y1 . ')` and `(x_2, y_2) = (' . $x2 . ', ' . $y2 . ')`.</p>
      <p><span class="term-label">(a) and (b).</span> Subtract in the same order on the top and the bottom. The change in y is `' . $y2 . ' - ' . $sy1 . ' = ' . $rise . '`. The change in x is `' . $x2 . ' - ' . $sx1 . ' = ' . $run . '`.</p>
      <p><span class="term-label">(c) Slope.</span> `m = (y_2 - y_1)/(x_2 - x_1) = ' . $sRise . '/' . $sRun . '`, which is ' . $mTxt . ' in lowest terms.</p>
      <p><span class="term-label">(d) Direction.</span> ' . $dirWhy . ' Sign check: going from the first point to the second, the line moves ' . $moveX . ' and ' . $moveY . '. That agrees with the slope.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A line passes through the points `(x_1, y_1) = ($x1, $y1)` and `(x_2, y_2) = ($x2, $y2)`. Use second point minus first point on the top and on the bottom.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter the slope as a whole number or a fraction in lowest terms, such as -3/2.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The change in y, `y_2 - y_1`, is $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The change in x, `x_2 - x_1`, is $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The slope `m` is $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What does the line do? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
