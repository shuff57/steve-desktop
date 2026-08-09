// === NAME - DESCRIPTION: Compare Three Box Plots - Read three box plots on a shared axis to find the largest median, find one group's IQR, and say what comparing medians does and does not tell you ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "The final exam scores, out of 100 points, for three sections of the same statistics course."
  $axisName = "Exam score (points)"
  $unitWord = "points"
  $nameA = "Morning"
  $nameB = "Afternoon"
  $nameC = "Evening"
  $groupWord = "sections"
}
else {
  $intro = "The delivery times, in minutes, recorded at three regional warehouses last month."
  $axisName = "Delivery time (minutes)"
  $unitWord = "minutes"
  $nameA = "North"
  $nameB = "South"
  $nameC = "East"
  $groupWord = "warehouses"
}

// Four candidate medians, all multiples of ten. Taking three CONSECUTIVE entries (mod 4) from a
// rotated start always yields three distinct values, so the three groups' medians can never tie --
// guaranteed by construction, not by luck.
$medCandidates = array(40, 60, 80, 100)
$rot = rand(0, 3)
$med0 = $medCandidates[$rot]
$med1 = $medCandidates[($rot + 1) % 4]
$med2 = $medCandidates[($rot + 2) % 4]

// Each group's box and whiskers are built from its own median outward in steps of 10 or 20, so
// every one of the fifteen numbers stays a multiple of ten. With medians in 40..100 and each step
// capped at 20, the smallest possible value is 40-20-20=0 and the largest is 100+20+20=140 -- both
// exactly inside the fixed 0..140 axis, so the axis never needs to be recomputed per draw.
$qL0 = 10 * (1 + rand(0, 1))
$qR0 = 10 * (1 + rand(0, 1))
$wL0 = 10 * (1 + rand(0, 1))
$wR0 = 10 * (1 + rand(0, 1))
$q1_0 = $med0 - $qL0
$q3_0 = $med0 + $qR0
$min0 = $q1_0 - $wL0
$max0 = $q3_0 + $wR0
$iqr0 = $q3_0 - $q1_0

$qL1 = 10 * (1 + rand(0, 1))
$qR1 = 10 * (1 + rand(0, 1))
$wL1 = 10 * (1 + rand(0, 1))
$wR1 = 10 * (1 + rand(0, 1))
$q1_1 = $med1 - $qL1
$q3_1 = $med1 + $qR1
$min1 = $q1_1 - $wL1
$max1 = $q3_1 + $wR1
$iqr1 = $q3_1 - $q1_1

$qL2 = 10 * (1 + rand(0, 1))
$qR2 = 10 * (1 + rand(0, 1))
$wL2 = 10 * (1 + rand(0, 1))
$wR2 = 10 * (1 + rand(0, 1))
$q1_2 = $med2 - $qL2
$q3_2 = $med2 + $qR2
$min2 = $q1_2 - $wL2
$max2 = $q3_2 + $wR2
$iqr2 = $q3_2 - $q1_2

// Plot geometry: shared axis runs from x=105 to x=500, fixed range 0..140.
$xStart = 105
$xEnd = 500
$span = $xEnd - $xStart
$axisMax = 140

$xMin0 = round($xStart + $min0 * $span / $axisMax, 2)
$xQ1_0 = round($xStart + $q1_0 * $span / $axisMax, 2)
$xMed0 = round($xStart + $med0 * $span / $axisMax, 2)
$xQ3_0 = round($xStart + $q3_0 * $span / $axisMax, 2)
$xMax0 = round($xStart + $max0 * $span / $axisMax, 2)
$boxW0 = round($xQ3_0 - $xQ1_0, 2)

$xMin1 = round($xStart + $min1 * $span / $axisMax, 2)
$xQ1_1 = round($xStart + $q1_1 * $span / $axisMax, 2)
$xMed1 = round($xStart + $med1 * $span / $axisMax, 2)
$xQ3_1 = round($xStart + $q3_1 * $span / $axisMax, 2)
$xMax1 = round($xStart + $max1 * $span / $axisMax, 2)
$boxW1 = round($xQ3_1 - $xQ1_1, 2)

$xMin2 = round($xStart + $min2 * $span / $axisMax, 2)
$xQ1_2 = round($xStart + $q1_2 * $span / $axisMax, 2)
$xMed2 = round($xStart + $med2 * $span / $axisMax, 2)
$xQ3_2 = round($xStart + $q3_2 * $span / $axisMax, 2)
$xMax2 = round($xStart + $max2 * $span / $axisMax, 2)
$boxW2 = round($xQ3_2 - $xQ1_2, 2)

$ticks = ""
for ($g=0..14) {
  $val = 10 * $g
  $tx = round($xStart + $val * $span / $axisMax, 2)
  $ticks = $ticks . '<line x1="' . $tx . '" y1="250" x2="' . $tx . '" y2="256" stroke="#374151" stroke-width="1"/>'
  $ticks = $ticks . '<line x1="' . $tx . '" y1="20" x2="' . $tx . '" y2="250" stroke="#eef2f7" stroke-width="1"/>'
  $ticks = $ticks . '<text x="' . $tx . '" y="270" font-size="11" fill="#374151" text-anchor="middle">' . $val . '</text>'
}

$svg = '<svg viewBox="0 0 520 310" width="100%" style="max-width:520px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $ticks

$svg = $svg . '<text x="8" y="60" font-size="13" font-weight="700" fill="#1e3a8a">' . $nameA . '</text>'
$svg = $svg . '<line x1="' . $xMin0 . '" y1="55" x2="' . $xQ1_0 . '" y2="55" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xQ3_0 . '" y1="55" x2="' . $xMax0 . '" y2="55" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMin0 . '" y1="39" x2="' . $xMin0 . '" y2="71" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMax0 . '" y1="39" x2="' . $xMax0 . '" y2="71" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<rect x="' . $xQ1_0 . '" y="29" width="' . $boxW0 . '" height="52" fill="#bfdbfe" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMed0 . '" y1="29" x2="' . $xMed0 . '" y2="81" stroke="#1e3a8a" stroke-width="3"/>'

$svg = $svg . '<text x="8" y="135" font-size="13" font-weight="700" fill="#1e3a8a">' . $nameB . '</text>'
$svg = $svg . '<line x1="' . $xMin1 . '" y1="130" x2="' . $xQ1_1 . '" y2="130" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xQ3_1 . '" y1="130" x2="' . $xMax1 . '" y2="130" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMin1 . '" y1="114" x2="' . $xMin1 . '" y2="146" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMax1 . '" y1="114" x2="' . $xMax1 . '" y2="146" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<rect x="' . $xQ1_1 . '" y="104" width="' . $boxW1 . '" height="52" fill="#bfdbfe" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMed1 . '" y1="104" x2="' . $xMed1 . '" y2="156" stroke="#1e3a8a" stroke-width="3"/>'

$svg = $svg . '<text x="8" y="210" font-size="13" font-weight="700" fill="#1e3a8a">' . $nameC . '</text>'
$svg = $svg . '<line x1="' . $xMin2 . '" y1="205" x2="' . $xQ1_2 . '" y2="205" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xQ3_2 . '" y1="205" x2="' . $xMax2 . '" y2="205" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMin2 . '" y1="189" x2="' . $xMin2 . '" y2="221" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMax2 . '" y1="189" x2="' . $xMax2 . '" y2="221" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<rect x="' . $xQ1_2 . '" y="179" width="' . $boxW2 . '" height="52" fill="#bfdbfe" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMed2 . '" y1="179" x2="' . $xMed2 . '" y2="231" stroke="#1e3a8a" stroke-width="3"/>'

$svg = $svg . '<line x1="105" y1="250" x2="500" y2="250" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<text x="302" y="292" font-size="13" fill="#374151" text-anchor="middle">' . $axisName . '</text>'
$svg = $svg . '</svg>'

$questions[0] = array($nameA, $nameB, $nameC, "They are all the same.")
$maxMedIdx = 0
$maxMed = $med0
if ($med1 > $maxMed) {
  $maxMed = $med1
  $maxMedIdx = 1
}
if ($med2 > $maxMed) {
  $maxMed = $med2
  $maxMedIdx = 2
}
$answer[0] = $maxMedIdx
$noshuffle[0] = "last"

$maxMedName = $nameA
if ($maxMedIdx == 1) { $maxMedName = $nameB }
if ($maxMedIdx == 2) { $maxMedName = $nameC }

$askIdx = rand(0, 2)
$askName = $nameA
$askIQR = $iqr0
$askQ1 = $q1_0
$askQ3 = $q3_0
if ($askIdx == 1) {
  $askName = $nameB
  $askIQR = $iqr1
  $askQ1 = $q1_1
  $askQ3 = $q3_1
}
if ($askIdx == 2) {
  $askName = $nameC
  $askIQR = $iqr2
  $askQ1 = $q1_2
  $askQ3 = $q3_2
}
$answer[1] = $askIQR
$reltolerance[1] = 0.01
$abstolerance[1] = 0.5

$questions[2] = array(
  "Which group tends to have higher values overall &mdash; a median alone says nothing about how spread out each group's data is.",
  "Which group has the most data values, since a wider box always means more data.",
  "Which group is more spread out.",
  "That the group with the higher median must also have the smaller `\"IQR\"`."
)
$answer[2] = 0

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
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">The three five-number summaries.</span> ' . $nameA . ': min ' . $min0 . ', `Q_1` ' . $q1_0 . ', median ' . $med0 . ', `Q_3` ' . $q3_0 . ', max ' . $max0 . '. ' . $nameB . ': min ' . $min1 . ', `Q_1` ' . $q1_1 . ', median ' . $med1 . ', `Q_3` ' . $q3_1 . ', max ' . $max1 . '. ' . $nameC . ': min ' . $min2 . ', `Q_1` ' . $q1_2 . ', median ' . $med2 . ', `Q_3` ' . $q3_2 . ', max ' . $max2 . '.</p>
      <p><span class="term-label">Part (a) &mdash; the largest median.</span> The median is the heavy line inside each box. Comparing the three &mdash; ' . $med0 . ', ' . $med1 . ' and ' . $med2 . ' &mdash; the largest belongs to <b>' . $maxMedName . '</b>.</p>
      <p><span class="term-label">Part (b) &mdash; interquartile range.</span> For ' . $askName . ', `"IQR" = Q_3 - Q_1 = ' . $askQ3 . ' - ' . $askQ1 . ' = ` <b>' . $askIQR . '</b> ' . $unitWord . ', the width of that group\'s box.</p>
      <p><span class="term-label">Part (c) &mdash; what the medians alone tell you.</span> A median ranks the CENTER of a group against the others. It says nothing about how wide or narrow that group\'s box or whiskers are &mdash; two groups can share a median and still have very different spreads, and the group with the higher median is not guaranteed to have the smaller (or larger) `"IQR"`. Box width tracks spread, not how many values were sampled.</p>
      <p><b>Answer:</b> (a) ' . $maxMedName . ' &nbsp;&nbsp; (b) ' . $askIQR . ' &nbsp;&nbsp; (c) comparing medians only</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$intro Box plots for the three $groupWord are drawn on the same axis below.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which of these three groups has the largest <b>median</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>interquartile range</b> of $askName? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Suppose you only compare the three <b>medians</b>. What does that comparison tell you? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
