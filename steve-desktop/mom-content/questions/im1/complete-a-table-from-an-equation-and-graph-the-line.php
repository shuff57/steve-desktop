// === NAME - DESCRIPTION: Complete a Table from an Equation and Graph the Line - Fill in four y-values for a slope-intercept equation, then draw its graph ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.7 (2.7.3). The draw part is the main part. The equation is y = (n/d)x + b with d from 1 to 4,
// n from 1 to 4 (gcd(n, d) = 1), either sign, and b never 0. The four x-values step by d and start at d
// times j with j from -2 to 0, kept so the last x is at most 10. Every x is a multiple of d, so every y
// is a whole number, and b is picked so every y is between -9 and 9. The window is -10 to 10 on both
// axes, so all four rows are lattice points on the grid. The line is checked as y = (n/d)x + b and every
// lattice point on the grid is an optional dot.
$anstypes = array("draw", "number", "number", "number", "number")

$d = randfrom(array(1, 2, 3, 4))
$n = rand(1, 4) where (gcd($n, $d) == 1)
$sg = randfrom(array(-1, 1))
$mn = $sg * $n
$j = rand(-2, 0) where ($d * ($j + 3) <= 10)
$b = nonzerorand(-5, 5) where (abs($mn * $j + $b) <= 9 && abs($mn * ($j + 1) + $b) <= 9 && abs($mn * ($j + 2) + $b) <= 9 && abs($mn * ($j + 3) + $b) <= 9)

$x0 = $d * $j
$x1 = $d * ($j + 1)
$x2 = $d * ($j + 2)
$x3 = $d * ($j + 3)
$y0 = $mn * $j + $b
$y1 = $mn * ($j + 1) + $b
$y2 = $mn * ($j + 2) + $b
$y3 = $mn * ($j + 3) + $b

$mTxt = $mn . "/" . $d
$mShow = $mTxt . " "
if ($d == 1) {
  $mTxt = $mn
  $mShow = $mn
  if ($mn == 1) {
    $mShow = ""
  }
  if ($mn == -1) {
    $mShow = "-"
  }
}
$bs = "+ " . $b
if ($b < 0) {
  $bs = "- " . abs($b)
}
$eq = "y = " . $mShow . "x " . $bs

$answerformat[0] = "twopoint,dot,line"
$reltolerance[0] = 0.2
$grid[0] = "-10,10,-10,10,1,1,480,480"
$snaptogrid = 1

$lst = array("($mn/$d)*x+($b)")
for ($i = 0..20) {
  $k = $i - 10
  $xx = $d * $k
  $yy = $mn * $k + $b
  if ($xx >= -10 && $xx <= 10 && $yy >= -10 && $yy <= 10) {
    $lst[] = "optional,$xx,$yy"
  }
}
$answers[0] = $lst

$answer[1] = $y0
$answer[2] = $y1
$answer[3] = $y2
$answer[4] = $y3
$answerboxsize[1] = 4
$answerboxsize[2] = 4
$answerboxsize[3] = 4
$answerboxsize[4] = 4
$answeights = array(4, 1, 1, 1, 1)

$cancel = ""
if ($d != 1) {
  $cancel = " The x-values are multiples of " . $d . ", so the fraction cancels."
}
$sx0 = "(" . $x0 . ")"
$sx1 = "(" . $x1 . ")"
$sx2 = "(" . $x2 . ")"
$sx3 = "(" . $x3 . ")"
$pic = showplot(array("($mn/$d)*x+($b),blue", "dot,$x0,$y0,closed,red", "dot,$x1,$y1,closed,red", "dot,$x2,$y2,closed,red", "dot,$x3,$y3,closed,red"), -10, 10, -10, 10, 1, 1, 400, 400)

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
      <p><span class="term-label">(a) The table.</span> Put each x in the equation `' . $eq . '`.' . $cancel . '</p>
      <p>`x = ' . $x0 . '`: `' . $mTxt . $sx0 . ' ' . $bs . ' = ' . $y0 . '`<br>
         `x = ' . $x1 . '`: `' . $mTxt . $sx1 . ' ' . $bs . ' = ' . $y1 . '`<br>
         `x = ' . $x2 . '`: `' . $mTxt . $sx2 . ' ' . $bs . ' = ' . $y2 . '`<br>
         `x = ' . $x3 . '`: `' . $mTxt . $sx3 . ' ' . $bs . ' = ' . $y3 . '`</p>
      <p>As a check, y changes by ' . $mn . ' each time x goes up by ' . $d . ', which is the slope `' . $mTxt . '`.</p>
      <p><span class="term-label">(b) Graph.</span> Plot the four rows. They are points on the line, and the line crosses the vertical axis at `(0, ' . $b . ')`.</p>
      ' . $pic . '
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A line has the equation `$eq`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Complete the table.</p>
    <table style="border-collapse:collapse;">
      <tr><td style="border:1px solid #d1d5db; padding:6px 14px; font-weight:700;">x</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x0</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x3</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:6px 14px; font-weight:700;">y</td><td style="border:1px solid #d1d5db; padding:6px 14px;">$answerbox[1]</td><td style="border:1px solid #d1d5db; padding:6px 14px;">$answerbox[2]</td><td style="border:1px solid #d1d5db; padding:6px 14px;">$answerbox[3]</td><td style="border:1px solid #d1d5db; padding:6px 14px;">$answerbox[4]</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Graph the line. Use the line tool and click two points on the line.</p>
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$sol
