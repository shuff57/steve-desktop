// === NAME - DESCRIPTION: Graph a Line from a Point and a Fraction Slope - Draw the line through a given point using rise over run, then find its y-intercept ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.6 (2.6.1). The draw part is the main part. The slope is a reduced fraction n/d with d from
// 2 to 4 and n from 1 to 4 (gcd(n, d) = 1, so the slope is never a whole number), positive or negative.
// The point is within 4 of the origin and never on an axis, so every draw leaves at least three lattice
// points of the line on the -8 to 8 grid. The y-intercept is (d*y1 - n*x1)/d and is never 0. The line is
// checked as y = (n/d)x + b, every lattice point on the grid is an optional dot, and the y-intercept is
// entered as a whole number or a fraction in lowest terms.
$anstypes = array("draw", "calculated")

$d = randfrom(array(2, 3, 4))
$n = rand(1, 4) where (gcd($n, $d) == 1)
$sg = randfrom(array(-1, 1))
$mn = $sg * $n
$x1 = nonzerorand(-4, 4)
$y1 = nonzerorand(-4, 4) where ($d * $y1 != $mn * $x1)

$bn = $d * $y1 - $mn * $x1
$g = gcd(abs($bn), $d)
$bnr = $bn / $g
$bdr = $d / $g
$bTxt = $bnr
if ($bdr != 1) {
  $bTxt = $bnr . "/" . $bdr
}

$answerformat[0] = "twopoint,dot,line"
$reltolerance[0] = 0.2
$grid[0] = "-8,8,-8,8,1,1,480,480"
$snaptogrid = 1

$lst = array("($mn/$d)*x+($bn/$d)")
for ($j = 0..16) {
  $k = $j - 8
  $xx = $x1 + $d * $k
  $yy = $y1 + $mn * $k
  if ($xx >= -8 && $xx <= 8 && $yy >= -8 && $yy <= 8) {
    $lst[] = "optional,$xx,$yy"
  }
}
$answers[0] = $lst

$answer[1] = $bn / $d
$answerformat[1] = "reducedfraction"
$showanswer[1] = $bTxt
$answerboxsize[1] = 8
$abstolerance[1] = 0.0001
$answeights = array(3, 1)

$riseTxt = "up " . $mn
if ($mn < 0) {
  $riseTxt = "down " . abs($mn)
}
$pp = $mn * $x1
$pTxt = $pp . "/" . $d
if ($pp < 0) {
  $pTxt = "(" . $pTxt . ")"
}
$lowest = ""
if ($g != 1) {
  $lowest = " In lowest terms, b is " . $bTxt . "."
}
$x2 = $x1 + $d
$y2 = $y1 + $mn
$pic = showplot(array("($mn/$d)*x+($bn/$d),blue", "dot,$x1,$y1,closed,red", "dot,$x2,$y2,closed,red"), -8, 8, -8, 8, 1, 1, 380, 380)

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
      <p><span class="term-label">(a) Graph.</span> Plot the point `(' . $x1 . ', ' . $y1 . ')`. The slope is rise over run, so a slope of `' . $mn . '/' . $d . '` means go right ' . $d . ' and ' . $riseTxt . '. That lands on `(' . $x2 . ', ' . $y2 . ')`. Draw the line through the two points.</p>
      ' . $pic . '
      <p><span class="term-label">(b) The y-intercept.</span> Write `y = mx + b` with the slope and put in the point: `' . $y1 . ' = ' . $mn . '/' . $d . '(' . $x1 . ') + b`. The product is `' . $mn . '/' . $d . '(' . $x1 . ') = ' . $pp . '/' . $d . '`, so `b = ' . $y1 . ' - ' . $pTxt . ' = ' . $bn . '/' . $d . '`.' . $lowest . '</p>
      <p><span class="term-label">Watch out.</span> The y-value of the point, ' . $y1 . ', is not b. It would only be b if the point were on the y-axis.</p>
      <p><span class="term-label">Check.</span> The line crosses the y-axis at `(0, ' . $bTxt . ')`. Compare that with your drawing.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A line passes through the point `($x1, $y1)` and has slope `m = $mn/$d`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Graph the line. Use the line tool and click two points on the line.</p>
    $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The y-intercept `b` is $answerbox[1]</p>
    <p style="margin:0; color:#374151; font-size:14px;">Enter a whole number or a fraction in lowest terms, such as -7/2.</p>
  </div>
</div>

// === ANSWER ===

$sol
