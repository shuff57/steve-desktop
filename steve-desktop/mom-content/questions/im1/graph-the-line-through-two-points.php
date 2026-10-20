// === NAME - DESCRIPTION: Graph the Line Through Two Points - Find the slope from two points with the slope formula, then plot both points and draw the line ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.3 (2.3.3, Definition 2.3.4, Problem 9). The slope is p/q with q from 1 to 3 and p from -3 to 3,
// reduced, so it is a whole number, a unit fraction or a simple fraction and is often negative. The two points
// are one or two steps of rise p and run q apart, so both have integer coordinates inside -7 to 7 and the
// pair shown can be reduced. About half the draws list the right-hand point first, so the run in the
// formula is sometimes negative. The line is written through the first point with the exact slope, so no
// rounding enters the key. Every lattice point on the line inside the -8 to 8 window is an optional dot.
$anstypes = array("calculated", "draw")

$q = randfrom(array(1, 2, 3))
$p = randfrom(array(-3, -2, -1, 1, 2, 3))
$g = gcd(abs($p), $q)
$p = $p / $g
$q = $q / $g
$steps = randfrom(array(1, 2))
$dx = $q * $steps
$dy = $p * $steps

$xLeft = rand(-7, 7 - $dx)
$yLo = max(-7, -7 - $dy)
$yHi = min(7, 7 - $dy)
$yLeft = rand($yLo, $yHi)
$xRight = $xLeft + $dx
$yRight = $yLeft + $dy

$swap = rand(0, 1)
$xa = $xLeft
$ya = $yLeft
$xb = $xRight
$yb = $yRight
if ($swap == 1) {
  $xa = $xRight
  $ya = $yRight
  $xb = $xLeft
  $yb = $yLeft
}
$rise = $yb - $ya
$run = $xb - $xa
$m = $p / $q

$mTxt = $p
if ($q != 1) {
  $mTxt = $p . "/" . $q
}
$sya = $ya
if ($ya < 0) {
  $sya = "(" . $ya . ")"
}
$sxa = $xa
if ($xa < 0) {
  $sxa = "(" . $xa . ")"
}
$sRise = $rise
if ($rise < 0) {
  $sRise = "(" . $rise . ")"
}
$sRun = $run
if ($run < 0) {
  $sRun = "(" . $run . ")"
}
$dirWhy = "positive, so the line climbs from left to right"
$dir = "up"
if ($p < 0) {
  $dirWhy = "negative, so the line falls from left to right"
  $dir = "down"
}

$ans = array("($p/$q) * (x - ($xa)) + ($ya)")
$k = 1
for ($i = 0..32) {
  $t = $i - 16
  $xx = $xa + $q * $t
  $yy = $ya + $p * $t
  if (abs($xx) <= 8 && abs($yy) <= 8) {
    $ans[$k] = "optional,$xx,$yy"
    $k = $k + 1
  }
}

$answer[0] = $m
$answerformat[0] = "reducedfraction"
$showanswer[0] = $mTxt
$answerboxsize[0] = 8
$abstolerance[0] = 0.0001

$answers[1] = $ans
$answerformat[1] = "twopoint,dot,line"
$reltolerance[1] = 0.2
$grid[1] = "-8,8,-8,8,1,1,425,425"
$snaptogrid[1] = 1

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
      <p><span class="term-label">Name the points.</span> Let `(x_1, y_1) = (' . $xa . ', ' . $ya . ')` and `(x_2, y_2) = (' . $xb . ', ' . $yb . ')`.</p>
      <p><span class="term-label">(a) Slope.</span> Subtract in the same order on the top and the bottom: `m = (y_2 - y_1)/(x_2 - x_1) = (' . $yb . ' - ' . $sya . ')/(' . $xb . ' - ' . $sxa . ') = ' . $sRise . '/' . $sRun . '`, which is ' . $mTxt . ' in lowest terms. Sign check: the slope is ' . $dirWhy . '.</p>
      <p><span class="term-label">(b) Graph.</span> Plot `(' . $xa . ', ' . $ya . ')` and `(' . $xb . ', ' . $yb . ')`. Draw the line through both points. Check with the slope: from either point, go ' . $q . ' to the right and ' . abs($p) . ' ' . $dir . ' and you land on the line again.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A line passes through the points `($xa, $ya)` and `($xb, $yb)`.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter the slope as a whole number or a fraction in lowest terms, such as -3/2.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The slope `m` is $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Graph the line through the two points.
    <p style="margin:10px 0 0 0;">$answerbox[1]</p>
  </div>
</div>

// === ANSWER ===

$sol
