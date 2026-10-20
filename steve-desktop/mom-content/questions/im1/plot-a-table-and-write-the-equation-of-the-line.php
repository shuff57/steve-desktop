// === NAME - DESCRIPTION: Plot a Table and Write the Equation of the Line - Plot four equally spaced rows, draw the line through them, then find the slope and y-intercept ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.7 (2.7.1). The draw part is the main part. Four rows with equal x steps (1, 2 or 3) from a
// linear relationship with a whole-number slope. The pairs of slope and step are limited so that the
// rise across all four rows is at most 12, and b is never 0. The window is -10 to 10 on both axes. Every
// row lies inside it (x from -9 to 9, y from -9 to 9), so the four rows are lattice points on the grid.
// The line is checked as y = mx + b and every lattice point on the grid is an optional dot, so plotting
// the rows first is allowed. The row at x = 0 is often missing, so b has to be found.
$anstypes = array("draw", "number", "number")

$ms = rand(0, 5)
$mlist = array(1, 2, 3, 1, 2, 1)
$slist = array(1, 1, 1, 2, 2, 3)
$sg = randfrom(array(-1, 1))
$m = $sg * $mlist[$ms]
$s = $slist[$ms]
$b = nonzerorand(-6, 6)
$x0 = rand(-9, 9 - 3 * $s) where (abs($m * $x0 + $b) <= 9 && abs($m * ($x0 + 3 * $s) + $b) <= 9)

$x1 = $x0 + $s
$x2 = $x0 + 2 * $s
$x3 = $x0 + 3 * $s
$y0 = $m * $x0 + $b
$y1 = $m * $x1 + $b
$y2 = $m * $x2 + $b
$y3 = $m * $x3 + $b

$answerformat[0] = "twopoint,dot,line"
$reltolerance[0] = 0.2
$grid[0] = "-10,10,-10,10,1,1,480,480"
$snaptogrid = 1

$lst = array("($m)*x+($b)")
for ($j = 0..20) {
  $xx = $j - 10
  $yy = $m * $xx + $b
  if ($yy >= -10 && $yy <= 10) {
    $lst[] = "optional,$xx,$yy"
  }
}
$answers[0] = $lst

$answer[1] = $m
$answer[2] = $b
$answerboxsize[1] = 4
$answerboxsize[2] = 4
$answeights = array(3, 1, 1)

$rise = $y1 - $y0
$bs = "+ " . $b
if ($b < 0) {
  $bs = "- " . abs($b)
}
$mt = $m
if ($m == 1) {
  $mt = ""
}
if ($m == -1) {
  $mt = "-"
}
$sy0 = $y0
if ($y0 < 0) {
  $sy0 = "(" . $y0 . ")"
}
$sx0 = $x0
if ($x0 < 0) {
  $sx0 = "(" . $x0 . ")"
}
$pic = showplot(array("($m)*x+($b),blue", "dot,$x0,$y0,closed,red", "dot,$x1,$y1,closed,red", "dot,$x2,$y2,closed,red", "dot,$x3,$y3,closed,red"), -10, 10, -10, 10, 1, 1, 400, 400)

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
      <p><span class="term-label">The slope.</span> Use any two rows and subtract in the same order on the top and the bottom: `(' . $y1 . ' - ' . $sy0 . ')/(' . $x1 . ' - ' . $sx0 . ') = ' . $rise . '/' . $s . ' = ' . $m . '`. The other neighboring rows give the same ' . $m . ', so the table is linear.</p>
      <p><span class="term-label">Find b.</span> Solve for b with one row, `(' . $x0 . ', ' . $y0 . ')`: `' . $y0 . ' = ' . $m . '(' . $x0 . ') + b`, so `' . $y0 . ' = ' . ($m * $x0) . ' + b` and `b = ' . $b . '`. The equation is `y = ' . $mt . 'x ' . $bs . '`. Check it on a row you did not use, `(' . $x3 . ', ' . $y3 . ')`: `' . $m . '(' . $x3 . ') ' . $bs . ' = ' . $y3 . '`.</p>
      <p><span class="term-label">(a) Graph.</span> Plot the four rows. They sit on one straight line.</p>
      ' . $pic . '
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">The table shows four points on a line.</p>
    <table style="border-collapse:collapse;">
      <tr><td style="border:1px solid #d1d5db; padding:6px 14px; font-weight:700;">x</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x0</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x3</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:6px 14px; font-weight:700;">y</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y0</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y3</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Plot the points and draw the line through them. Use the line tool and click two points on the line.</p>
    $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Write the equation of the line.</p>
    <p style="margin:0;">`y =` $answerbox[1] `x +` $answerbox[2]</p>
  </div>
</div>

// === ANSWER ===

$sol
