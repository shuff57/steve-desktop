// === NAME - DESCRIPTION: Outlier Check from a Box Plot - Take the quartiles off a drawn box plot, build the 1.5 times IQR fences from them, decide whether a newly measured value is an outlier, and separate a long whisker from an outlier ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A clinic drew a box plot of the resting heart rates, in beats per minute, of the adults it saw last month."
  $axisName = "Resting heart rate in bpm"
  $unitWord = "bpm"
  $newThing = "one more adult"
}
else {
  $intro = "A depot drew a box plot of the weights, in kilograms, of the parcels it handled in one shift."
  $axisName = "Parcel weight in kg"
  $unitWord = "kg"
  $newThing = "one more parcel"
}

// Every plotted value is a multiple of ten, so each one sits on a labeled tick and nothing has to
// be estimated. The IQR is then a multiple of ten as well, which keeps 1.5 x IQR a whole number
// and the fences exact.
//
// Ticks are every TEN here, not every two. This question is the one exception to that rule: the
// axis has to carry the box, both 1.5 x IQR fences and an outlier beyond them, and at a step of two
// that is thirty-nine labels on a 520px axis. Ten is the finest step that stays readable, and every
// value the student reads off is a multiple of ten, so all of them still land ON a label -- which is
// what the step rule is actually for. It was previously every TWENTY, which left half the quartiles
// sitting between gridlines.
$wid = array(10, 20, 30, 40)
$rot = rand(0, 3)
$w1 = $wid[$rot]
$w2 = $wid[($rot + 1) % 4]
$w3 = $wid[($rot + 2) % 4]
$w4 = $wid[($rot + 3) % 4]

$minV = 10 * rand(3, 6)
$q1 = $minV + $w1
$med = $q1 + $w2
$q3 = $med + $w3
$maxV = $q3 + $w4

$iqr = $q3 - $q1
$half = $iqr / 2
$reach = $iqr + $half
$lowFence = $q1 - $reach
$upFence = $q3 + $reach

// The new value sits clearly on one side of a fence or clearly inside, never within 5 of either,
// so the verdict can never be a judgement call. Which case appears is randomized so a student
// cannot learn that the answer is always "outlier".
$caseId = rand(0, 2)
$newVal = $q3 + 5
if ($caseId == 0) { $newVal = $upFence + 5 + 10 * rand(0, 3) }
if ($caseId == 1) { $newVal = $lowFence - 5 - 10 * rand(0, 3) }
if ($caseId == 2) { $newVal = $q3 + 5 }

$isOutlier = 1
if ($caseId == 2) { $isOutlier = 0 }

$verdictText = "Yes &mdash; it lies outside the fences, so the 1.5 rule flags it as an outlier."
if ($isOutlier == 0) { $verdictText = "No &mdash; it lies between the fences, so the 1.5 rule does not flag it." }

$axisMax = $maxV + 10
if ($newVal > $axisMax) { $axisMax = $newVal + 10 }
$axisMin = 0
$rem = $axisMax % 20
if ($rem > 0) { $axisMax = $axisMax + 20 - $rem }

$span = 495 - 55
$xMin = round(55 + $minV * $span / $axisMax, 2)
$xQ1 = round(55 + $q1 * $span / $axisMax, 2)
$xMed = round(55 + $med * $span / $axisMax, 2)
$xQ3 = round(55 + $q3 * $span / $axisMax, 2)
$xMax = round(55 + $maxV * $span / $axisMax, 2)
$boxW = round($xQ3 - $xQ1, 2)

$ticks = ""
$nTicks = $axisMax / 10
for ($g=0..$nTicks) {
  $val = 10 * $g
  $tx = round(55 + $val * $span / $axisMax, 2)
  $ticks = $ticks . '<line x1="' . $tx . '" y1="110" x2="' . $tx . '" y2="116" stroke="#374151" stroke-width="1"/>'
  $ticks = $ticks . '<line x1="' . $tx . '" y1="26" x2="' . $tx . '" y2="110" stroke="#eef2f7" stroke-width="1"/>'
  $ticks = $ticks . '<text x="' . $tx . '" y="132" font-size="12" fill="#374151" text-anchor="middle">' . $val . '</text>'
}

$svg = '<svg viewBox="0 0 520 160" width="100%" style="max-width:520px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $ticks
$svg = $svg . '<line x1="' . $xMin . '" y1="68" x2="' . $xQ1 . '" y2="68" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xQ3 . '" y1="68" x2="' . $xMax . '" y2="68" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMin . '" y1="52" x2="' . $xMin . '" y2="84" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMax . '" y1="52" x2="' . $xMax . '" y2="84" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<rect x="' . $xQ1 . '" y="42" width="' . $boxW . '" height="52" fill="#bfdbfe" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMed . '" y1="42" x2="' . $xMed . '" y2="94" stroke="#1e3a8a" stroke-width="3"/>'
$svg = $svg . '<line x1="55" y1="110" x2="495" y2="110" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<text x="275" y="152" font-size="13" fill="#374151" text-anchor="middle">' . $axisName . '</text>'
$svg = $svg . '</svg>'

$answer[0] = $iqr
$reltolerance[0] = 0.01
$abstolerance[0] = 0.5

$answer[1] = $upFence
$reltolerance[1] = 0.01
$abstolerance[1] = 0.5

$questions[2] = array(
  "Yes &mdash; it lies outside the fences, so the 1.5 rule flags it as an outlier.",
  "No &mdash; it lies between the fences, so the 1.5 rule does not flag it."
)
$answer[2] = 1
if ($isOutlier == 1) { $answer[2] = 0 }
$noshuffle[2] = "all"

$questions[3] = array(
  "No. The whisker only reaches the largest value that is still inside the fences, so a long whisker means that quarter of the data is spread out, not that anything is unusual.",
  "Yes. Any value at the end of a whisker is by definition an outlier.",
  "Yes, but only if the whisker is longer than the box.",
  "No, because outliers are never shown on a box plot at all."
)
$answer[3] = 0

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
      <p><span class="term-label">Step 1 &mdash; read the quartiles off the box.</span> The box runs from `Q_1` = ' . $q1 . ' to `Q_3` = ' . $q3 . ', so `"IQR" = ' . $q3 . ' - ' . $q1 . ' = ` <b>' . $iqr . '</b> ' . $unitWord . '. Only the two box edges matter here; the whiskers and the median play no part in the fences.</p>
      <p><span class="term-label">Step 2 &mdash; build the fences.</span> Reach out `1.5 xx "IQR" = 1.5 xx ' . $iqr . ' = ' . $reach . '` from each edge. Upper fence `= Q_3 + ' . $reach . ' = ' . $q3 . ' + ' . $reach . ' = ` <b>' . $upFence . '</b>; lower fence `= Q_1 - ' . $reach . ' = ' . $lowFence . '`. The fences are not drawn on the plot &mdash; they are computed from it, which is why this cannot be answered by looking alone.</p>
      <p><span class="term-label">Step 3 &mdash; test the new value.</span> The new reading is ' . $newVal . ' ' . $unitWord . '. ' . $verdictText . '</p>
      <p><span class="term-label">Step 4 &mdash; a long whisker is not an outlier.</span> A whisker stops at the most extreme value that is still <i>inside</i> the fences. So a long whisker says that quarter of the data is spread thinly over a wide range; it says nothing unusual about any single value. Outliers, when a plot marks them, are drawn as separate points beyond the whisker.</p>
      <p><b>Answer:</b> (a) ' . $iqr . ' &nbsp;&nbsp; (b) ' . $upFence . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$intro</p>
    $svg
    <p style="margin:6px 0 0 0;">A value counts as an <b>outlier</b> if it falls more than `1.5 xx "IQR"` beyond either edge of the box.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>interquartile range</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>upper fence</b>, `Q_3 + 1.5 xx "IQR"`? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $newThing is measured at <b>$newVal $unitWord</b>. Is that an outlier? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Suppose a box plot has one whisker much longer than the other. Does that long whisker mean the value at its end is an outlier? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
