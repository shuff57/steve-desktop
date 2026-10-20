// === NAME - DESCRIPTION: Graph a Line from Slope-Intercept Form - Read the slope and y-intercept from y = mx + b, then plot the y-intercept and use the slope to draw the line ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.3 (2.3.4, Problem 15). The student reads m and b, then draws the line on a -12 to 12 grid.
// The slope is a nonzero integer from -5 to 5 and b is a nonzero integer from -7 to 7, so the y-intercept
// (0, b) and the point (1, b + m) are always inside the window (the largest |y| is 12). The equation is
// shown as y = mx + b on some draws and as y = b + mx on others, so the student has to look for the
// number multiplying x rather than the first number. Every lattice point of the line inside the window
// is listed as an optional dot, so a student who marks points before drawing is not penalized.
$anstypes = array("number", "number", "draw")

$m = randfrom(array(-5, -4, -3, -2, -1, 1, 2, 3, 4, 5))
$b = randfrom(array(-7, -6, -5, -4, -3, -2, -1, 1, 2, 3, 4, 5, 6, 7))
$form = rand(0, 1)

$mTxt = $m
if ($m == 1) {
  $mTxt = ""
}
if ($m == -1) {
  $mTxt = "-"
}
$mabsTxt = abs($m)
if (abs($m) == 1) {
  $mabsTxt = ""
}
$eq = "y = " . $mTxt . "x"
if ($b > 0) {
  $eq = $eq . " + " . $b
} else {
  $eq = $eq . " - " . abs($b)
}
if ($form == 1) {
  $eq = "y = " . $b
  if ($m > 0) {
    $eq = $eq . " + " . $mabsTxt . "x"
  } else {
    $eq = $eq . " - " . $mabsTxt . "x"
  }
}

$yNext = $b + $m
$yBack = $b - $m
$dir = "up"
$dirWhy = "positive, so the line climbs from left to right"
if ($m < 0) {
  $dir = "down"
  $dirWhy = "negative, so the line falls from left to right"
}
$mAbs = abs($m)

$ans = array("$m * x + $b")
$k = 1
for ($i = 0..24) {
  $xx = $i - 12
  $yy = $m * $xx + $b
  if (abs($yy) <= 12) {
    $ans[$k] = "optional,$xx,$yy"
    $k = $k + 1
  }
}

$answer[0] = $m
$answer[1] = $b
$answerboxsize[0] = 5
$answerboxsize[1] = 5
$answers[2] = $ans
$answerformat[2] = "twopoint,dot,line"
$grid[2] = "-12,12,-12,12,1,1,470,470"
$snaptogrid[2] = 1
$reltolerance[2] = 0.2

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
      <p><span class="term-label">(a) and (b) Read m and b.</span> In `y = mx + b`, m is the number multiplying x and b is the number added on its own. Here `m = ' . $m . '` and `b = ' . $b . '`. The y-intercept is the point `(0, ' . $b . ')`.</p>
      <p><span class="term-label">Plot the y-intercept.</span> The line crosses the y-axis at `(0, ' . $b . ')`. Plot that point first.</p>
      <p><span class="term-label">Use the slope.</span> The slope ' . $m . ' is ' . $dirWhy . '. The rise is ' . $m . ' for a run of 1. From `(0, ' . $b . ')`, go 1 to the right and ' . $mAbs . ' ' . $dir . '. That lands on `(1, ' . $yNext . ')`.</p>
      <p><span class="term-label">(c) Draw the line.</span> Draw the line through `(0, ' . $b . ')` and `(1, ' . $yNext . ')`. Check with a third point: going 1 to the left from the y-intercept lands on `(-1, ' . $yBack . ')`, and that point is on your line too.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A line has the equation `$eq`.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter whole numbers, with a minus sign if the number is negative.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The slope `m` is $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The y-intercept `b` is $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Graph the line `$eq`. Plot the y-intercept, use the slope to find a second point, and draw the line through them.
    <p style="margin:10px 0 0 0;">$answerbox[2]</p>
  </div>
</div>

// === ANSWER ===

$sol
