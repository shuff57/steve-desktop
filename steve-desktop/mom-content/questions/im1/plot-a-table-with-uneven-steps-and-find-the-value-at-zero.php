// === NAME - DESCRIPTION: Plot a Table with Uneven Steps and Find the Value at Zero - Draw the line through four rows whose x-values are spaced unevenly, then read the value at x = 0 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.7 (2.7.1). The draw part is the main part. Four rows from a linear relationship, with x-values
// d times k, where k comes from a set of four whole numbers with unequal gaps and no 0 (so there is never a
// row at x = 0), and y = (n/d)x + b with b a whole number, so every row is a lattice point. The slope is
// n/d reduced (gcd(n, d) = 1), d is 1, 2 or 3, n is 1 to 3, either sign. The window is -10 to 10 on both
// axes and every row has |y| at most 9. The rows are shown out of order, in one of four fixed shuffles
// that are never sorted. The line is checked as y = (n/d)x + b, every lattice point on the grid is an
// optional dot, and the box asks for y at x = 0, which is b.
$anstypes = array("draw", "number")

$ki = rand(0, 8)
$ka = array(-1, -2, -3, -2, -2, -2, -3, -3, -3)
$kb = array(1, -1, -2, 1, -1, -1, -1, -2, -2)
$kc = array(2, 1, -1, 2, 2, 1, 1, 1, -1)
$kd = array(3, 2, 1, 3, 3, 3, 2, 2, 2)
$k0 = $ka[$ki]
$k1 = $kb[$ki]
$k2 = $kc[$ki]
$k3 = $kd[$ki]

$d = randfrom(array(1, 2, 3))
$n = rand(1, 3) where (gcd($n, $d) == 1)
$sg = randfrom(array(-1, 1))
$mn = $sg * $n
$b = nonzerorand(-6, 6) where (abs($mn * $k0 + $b) <= 9 && abs($mn * $k1 + $b) <= 9 && abs($mn * $k2 + $b) <= 9 && abs($mn * $k3 + $b) <= 9)

$xs = array($d * $k0, $d * $k1, $d * $k2, $d * $k3)
$ys = array($mn * $k0 + $b, $mn * $k1 + $b, $mn * $k2 + $b, $mn * $k3 + $b)

$pm = rand(0, 3)
$o1 = array(2, 1, 3, 1)
$o2 = array(0, 3, 0, 0)
$o3 = array(3, 0, 2, 3)
$o4 = array(1, 2, 1, 2)
$r1 = $o1[$pm]
$r2 = $o2[$pm]
$r3 = $o3[$pm]
$r4 = $o4[$pm]
$t1x = $xs[$r1]
$t2x = $xs[$r2]
$t3x = $xs[$r3]
$t4x = $xs[$r4]
$t1y = $ys[$r1]
$t2y = $ys[$r2]
$t3y = $ys[$r3]
$t4y = $ys[$r4]

$answerformat[0] = "twopoint,dot,line"
$reltolerance[0] = 0.2
$grid[0] = "-10,10,-10,10,1,1,480,480"
$snaptogrid = 1

$lst = array("($mn/$d)*x+($b)")
for ($j = 0..20) {
  $k = $j - 10
  $xx = $d * $k
  $yy = $mn * $k + $b
  if ($xx >= -10 && $xx <= 10 && $yy >= -10 && $yy <= 10) {
    $lst[] = "optional,$xx,$yy"
  }
}
$answers[0] = $lst

$answer[1] = $b
$answerboxsize[1] = 5
$answeights = array(3, 1)

$a0 = $xs[0]
$a1 = $xs[1]
$a2 = $xs[2]
$a3 = $xs[3]
$c0 = $ys[0]
$c1 = $ys[1]
$c2 = $ys[2]
$c3 = $ys[3]
$sc0 = $c0
if ($c0 < 0) {
  $sc0 = "(" . $c0 . ")"
}
$sa0 = $a0
if ($a0 < 0) {
  $sa0 = "(" . $a0 . ")"
}
$rise = $c1 - $c0
$run = $a1 - $a0
$g = gcd(abs($rise), abs($run))
$rr = $rise / $g
$rn = $run / $g
$mTxt = $rr
if ($rn != 1) {
  $mTxt = $rr . "/" . $rn
}
$pic = showplot(array("($mn/$d)*x+($b),blue", "dot,$a0,$c0,closed,red", "dot,$a1,$c1,closed,red", "dot,$a2,$c2,closed,red", "dot,$a3,$c3,closed,red", "dot,0,$b,open,green"), -10, 10, -10, 10, 1, 1, 400, 400)

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
      <p><span class="term-label">Put the rows in order.</span> By x the rows are `(' . $a0 . ', ' . $c0 . ')`, `(' . $a1 . ', ' . $c1 . ')`, `(' . $a2 . ', ' . $c2 . ')` and `(' . $a3 . ', ' . $c3 . ')`. The gaps in x are ' . ($a1 - $a0) . ', ' . ($a2 - $a1) . ' and ' . ($a3 - $a2) . ', so the steps are not even. Any two rows still give the slope.</p>
      <p><span class="term-label">The slope.</span> `(' . $c1 . ' - ' . $sc0 . ')/(' . $a1 . ' - ' . $sa0 . ') = ' . $rise . '/' . $run . '`, which is ' . $mTxt . ' in lowest terms. Another pair of rows gives the same number, so the four rows lie on one line.</p>
      <p><span class="term-label">Value at x = 0.</span> Go back to x = 0 from the row `(' . $a0 . ', ' . $c0 . ')`, or solve for b: `' . $c0 . ' = ' . $mTxt . '(' . $a0 . ') + b`. That gives `b = ' . $b . '`, so the value at x = 0 is ' . $b . '.</p>
      <p><span class="term-label">(a) Graph.</span> Plot the four rows and draw the line through them. It meets the vertical axis at `(0, ' . $b . ')`.</p>
      ' . $pic . '
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">The table shows four points on a line, in the order they were recorded.</p>
    <table style="border-collapse:collapse;">
      <tr><td style="border:1px solid #d1d5db; padding:6px 14px; font-weight:700;">x</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$t1x</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$t2x</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$t3x</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$t4x</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:6px 14px; font-weight:700;">y</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$t1y</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$t2y</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$t3y</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$t4y</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Plot the points and draw the line through them. Use the line tool and click two points on the line.</p>
    $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The value of y at `x = 0` is $answerbox[1]</p>
  </div>
</div>

// === ANSWER ===

$sol
