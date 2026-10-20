// === NAME - DESCRIPTION: Graph a Line from a Point and an Integer Slope - Draw the line through a given point with a whole-number slope, then write its equation in point-slope form ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.6 (2.6.3). The draw part is the main part. The slope is a whole number from -4 to 4 and
// never 0. The point is never on an axis. The window is -8 to 8 on both axes, the point stays within
// 6 of the origin, and every such draw leaves at least three lattice points of the line on the grid.
// The answer line is checked as y = mx + b, and every lattice point of the line on the grid is an
// optional dot, so a student who plots dots first is not marked wrong for them. Point-slope form is
// y - y1 = m(x - x1), and the three boxes take the numbers y1, m and x1 exactly as given.
$anstypes = array("draw", "number", "number", "number")

$m = nonzerorand(-4, 4)
$x1 = nonzerorand(-6, 6)
$y1 = nonzerorand(-6, 6)
$b = $y1 - $m * $x1

$answerformat[0] = "twopoint,dot,line"
$reltolerance[0] = 0.2
$grid[0] = "-8,8,-8,8,1,1,480,480"
$snaptogrid = 1

$lst = array("($m)*x+($b)")
for ($k = 0..16) {
  $xx = $k - 8
  $yy = $m * $xx + $b
  if ($yy >= -8 && $yy <= 8) {
    $lst[] = "optional,$xx,$yy"
  }
}
$answers[0] = $lst

$answer[1] = $y1
$answer[2] = $m
$answer[3] = $x1
$answerboxsize[1] = 4
$answerboxsize[2] = 4
$answerboxsize[3] = 4
$answeights = array(3, 1, 1, 1)

$sx1 = $x1
if ($x1 < 0) {
  $sx1 = "(" . $x1 . ")"
}
$sy1 = $y1
if ($y1 < 0) {
  $sy1 = "(" . $y1 . ")"
}
$flip = ""
if ($x1 < 0 || $y1 < 0) {
  $fy = "y - " . $sy1
  if ($y1 < 0) {
    $fy = "y + " . abs($y1)
  }
  $fx = "x - " . $sx1
  if ($x1 < 0) {
    $fx = "x + " . abs($x1)
  }
  $flip = '<p>Minus a negative number is plus, so this is the same line as `' . $fy . ' = ' . $m . '(' . $fx . ')`.</p>'
}
$x2 = $x1 + 1
$y2 = $y1 + $m
$pic = showplot(array("($m)*x+($b),blue", "dot,$x1,$y1,closed,red", "dot,$x2,$y2,closed,red"), -8, 8, -8, 8, 1, 1, 380, 380)

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
      <p><span class="term-label">(a) Graph.</span> Plot the point `(' . $x1 . ', ' . $y1 . ')`. The slope `' . $m . '` is `' . $m . '/1`, so move 1 to the right and ' . $m . ' up (a negative number means down). That gives a second point, `(' . $x2 . ', ' . $y2 . ')`. Draw the line through the two points.</p>
      ' . $pic . '
      <p><span class="term-label">(b) Point-slope form.</span> The form is `y - y_1 = m(x - x_1)`. Put in `y_1 = ' . $y1 . '`, `m = ' . $m . '` and `x_1 = ' . $x1 . '`: `y - ' . $sy1 . ' = ' . $m . '(x - ' . $sx1 . ')`.</p>
      ' . $flip . '
      <p><span class="term-label">Check.</span> Put the point back in. Both sides are 0 when `x = ' . $x1 . '` and `y = ' . $y1 . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A line passes through the point `($x1, $y1)` and has slope `m = $m`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Graph the line. Use the line tool and click two points on the line.</p>
    $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Write the equation in point-slope form, using the point given above. Type one number in each box.</p>
    <p style="margin:0;">`y -` $answerbox[1] `=` $answerbox[2] `(x -` $answerbox[3] `)`</p>
  </div>
</div>

// === ANSWER ===

$sol
