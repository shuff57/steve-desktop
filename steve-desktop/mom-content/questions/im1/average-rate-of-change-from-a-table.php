// === NAME - DESCRIPTION: Average Rate of Change from a Table - Find the average rate over an interval and over the whole table, with units, and pick the stretch where the output changes fastest ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (Rate Over an Interval). A table of four readings where the rate is not constant.
// The student finds the average rate of change over a stated interval and over the whole table,
// then picks the stretch where the output changes fastest. Four patterns of stretch rates are
// used (speeding up, slowing down, fastest in the middle, slowest in the middle). The three
// stretch rates are always different, so the fastest stretch is never a tie. Answers are
// rounded to the nearest hundredth by the student, so the tolerance is just over half a hundredth.
$anstypes = array("number", "number", "choices")

$ci = rand(0, 4)
$to = rand(0, 2)
$pat = rand(0, 3)
$pa = rand(0, 1)

$tAll = array(0, 2, 4, 6,  0, 1, 3, 6,  0, 2, 5, 8,   0, 1, 3, 6,  0, 2, 4, 8,  0, 1, 2, 4,   0, 3, 6, 9,  0, 2, 5, 8,  0, 2, 4, 6,   0, 2, 5, 10,  0, 1, 3, 6,  0, 2, 4, 8,   0, 10, 20, 40,  0, 5, 15, 30,  0, 10, 30, 60)
$tb = ($ci * 3 + $to) * 4
$tb1 = $tb + 1
$tb2 = $tb + 2
$tb3 = $tb + 3
$t0 = $tAll[$tb]
$t1 = $tAll[$tb1]
$t2 = $tAll[$tb2]
$t3 = $tAll[$tb3]
$w1 = $t1 - $t0
$w2 = $t2 - $t1
$w3 = $t3 - $t2

$sgns = array(-1, 1, 1, -1, 1)
$los = array(30, 80, 10, 50, 10)
$his = array(70, 120, 30, 120, 16)
$dhis = array(20, 25, 8, 30, 3)
$dens = array(10, 10, 10, 10, 100)
$y0los = array(100, 0, 40, 200, 0)
$y0his = array(100, 0, 60, 200, 0)
$sgn = $sgns[$ci]
$lo = $los[$ci]
$hi = $his[$ci]
$dhi = $dhis[$ci]
$den = $dens[$ci]
$y0lo = $y0los[$ci]
$y0hi = $y0his[$ci]
$y0 = rand($y0lo, $y0hi)

$rb = rand($lo, $hi) / $den
$dlo = ceil($dhi / 2)
$dd2 = rand($dlo, $dhi) / $den
$dd3 = rand($dlo, $dhi) / $den
$r1 = $rb
$r2 = $rb + $dd2
$r3 = $rb + $dd2 + $dd3
$fast = 2
if ($pat == 1) {
  $r1 = $rb + $dd2 + $dd3
  $r2 = $rb + $dd2
  $r3 = $rb
  $fast = 0
}
if ($pat == 2) {
  $r1 = $rb
  $r2 = $rb + $dd2 + $dd3
  $r3 = $rb + $dd2
  $fast = 1
}
if ($pat == 3) {
  $r1 = $rb + $dd2 + $dd3
  $r2 = $rb
  $r3 = $rb + $dd2
  $fast = 0
}
$y1 = round($y0 + $sgn * $r1 * $w1, 3)
$y2 = round($y1 + $sgn * $r2 * $w2, 3)
$y3 = round($y2 + $sgn * $r3 * $w3, 3)

$ti = $t0
$tj = $t2
$yi = $y0
$yj = $y2
if ($pa == 1) {
  $ti = $t1
  $tj = $t3
  $yi = $y1
  $yj = $y3
}
$ansA = ($yj - $yi) / ($tj - $ti)
$ansB = ($y3 - $y0) / ($t3 - $t0)
$ansAr = round($ansA, 2)
$ansBr = round($ansB, 2)

$intros = array("A phone battery was read at four times after it was unplugged.", "The distance of a cyclist from the start was recorded at four times.", "The temperature outside was recorded at four times through a morning.", "A tank was draining. The water left in it was measured at four times.", "The distance of a runner from the start was recorded at four times.")
$tLabels = array("Time in hours", "Time in hours", "Time in hours", "Time in minutes", "Time in minutes")
$yLabels = array("Battery in percent", "Distance in kilometers", "Temperature in degrees Fahrenheit", "Water in liters", "Distance in kilometers")
$qtys = array("battery charge", "distance", "temperature", "amount of water", "distance")
$yUnits = array("percent", "kilometers", "degrees", "liters", "kilometers")
$tUnits = array("hours", "hours", "hours", "minutes", "minutes")
$tSings = array("hour", "hour", "hour", "minute", "minute")
$intro = $intros[$ci]
$tLabel = $tLabels[$ci]
$yLabel = $yLabels[$ci]
$qty = $qtys[$ci]
$yUnit = $yUnits[$ci]
$tUnit = $tUnits[$ci]
$tSing = $tSings[$ci]

$tbl = '<table style="border-collapse:collapse; margin:10px 0; font-size:15px;">'
$tbl = $tbl . '<tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">' . $tLabel . '</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $t0 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $t1 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $t2 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $t3 . '</td></tr>'
$tbl = $tbl . '<tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">' . $yLabel . '</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $y0 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $y1 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $y2 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $y3 . '</td></tr>'
$tbl = $tbl . '</table>'

$answer[0] = $ansA
$answer[1] = $ansB
$abstolerance[0] = 0.006
$abstolerance[1] = 0.006

$questions[2] = array(
  "From " . $t0 . " to " . $t1 . " " . $tUnit,
  "From " . $t1 . " to " . $t2 . " " . $tUnit,
  "From " . $t2 . " to " . $t3 . " " . $tUnit,
  "The rate is the same in all three stretches."
)
$answer[2] = $fast

$c1 = round($y1 - $y0, 3)
$c2 = round($y2 - $y1, 3)
$c3 = round($y3 - $y2, 3)
$cA = round($yj - $yi, 3)
$cB = round($y3 - $y0, 3)
$dtA = $tj - $ti
$dtB = $t3 - $t0
$q1 = round($c1 / $w1, 4)
$q2 = round($c2 / $w2, 4)
$q3 = round($c3 / $w3, 4)

$solutionguide = '
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

      <p><span class="term-label">Part a: the interval from ' . $ti . ' to ' . $tj . ' ' . $tUnit . '.</span> The average rate of change is the change in the output divided by the change in the input: `(' . $yj . ' - ' . $yi . ') / (' . $tj . ' - ' . $ti . ') = ' . $cA . ' / ' . $dtA . ' = ' . $ansAr . '` ' . $yUnit . ' per ' . $tSing . '.</p>
      <p><span class="term-label">Part b: the whole table.</span> `(' . $y3 . ' - ' . $y0 . ') / (' . $t3 . ' - ' . $t0 . ') = ' . $cB . ' / ' . $dtB . ' = ' . $ansBr . '` ' . $yUnit . ' per ' . $tSing . '. This is not the same as every stretch, because the rate changes.</p>
      <p><span class="term-label">Part c: compare the three stretches.</span> The rates are `' . $c1 . ' / ' . $w1 . ' = ' . $q1 . '`, `' . $c2 . ' / ' . $w2 . ' = ' . $q2 . '` and `' . $c3 . ' / ' . $w3 . ' = ' . $q3 . '` ' . $yUnit . ' per ' . $tSing . '. The fastest is the one with the biggest size, ignoring the sign. The three rates are different, so the rate is not constant.</p>

    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    $tbl
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Round each rate to the nearest hundredth if you need to.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the average rate of change from $ti to $tj $tUnit. Give it in $yUnit per $tSing. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the average rate of change over the whole table, from $t0 to $t3 $tUnit. Give it in $yUnit per $tSing. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> In which stretch is the $qty changing the fastest? Judge by the size of the rate.<br><br>$answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
