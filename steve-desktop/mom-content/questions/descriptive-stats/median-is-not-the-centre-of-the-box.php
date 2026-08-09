// === NAME - DESCRIPTION: Where the Median Sits Inside the Box - A box plot whose median line is well off center; read it, compare the two halves of the middle fifty percent, and say what an off-center median reveals about the shape of the data ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A letting agency drew a box plot of the monthly rents, in dollars, of the flats on its books."
  $axisName = "Monthly rent in dollars"
  $unitWord = "dollars"
  $scaleUp = 10
}
else {
  $intro = "A hospital drew a box plot of the number of minutes patients waited before being seen."
  $axisName = "Waiting time in minutes"
  $unitWord = "minutes"
  $scaleUp = 1
}

// The median is deliberately pushed hard against one side of the box. Students read the median as
// "the middle of the box" because that is what a symmetric drawing lets them get away with; here
// that shortcut gives a visibly wrong number. Which side it leans to is randomized, so the habit
// cannot simply be relearned the other way round.
$leanLow = rand(0, 1)
$q1u = 4 * rand(2, 5)
$shortGap = 1 * rand(1, 2)
$longGap = 4 * rand(2, 3)
if ($leanLow == 1) {
  $gapA = $shortGap
  $gapB = $longGap
}
else {
  $gapA = $longGap
  $gapB = $shortGap
}
$medu = $q1u + $gapA
$q3u = $medu + $gapB
$minu = $q1u - 2 * rand(1, 3)
$maxu = $q3u + 2 * rand(1, 3)

$minV = $minu * $scaleUp
$q1 = $q1u * $scaleUp
$med = $medu * $scaleUp
$q3 = $q3u * $scaleUp
$maxV = $maxu * $scaleUp

$lowerHalf = $med - $q1
$upperHalf = $q3 - $med
$iqr = $q3 - $q1

$boxMiddle = ($q1 + $q3) / 2
$missBy = $boxMiddle - $med
if ($missBy < 0) { $missBy = 0 - $missBy }

$tighterName = "the lower half"
$widerName = "the upper half"
if ($leanLow == 0) {
  $tighterName = "the upper half"
  $widerName = "the lower half"
}

$skewIdx = 0
if ($leanLow == 0) { $skewIdx = 1 }

$questions[2] = array(
  "The quarter just above the median is spread over more ground than the quarter just below it.",
  "The quarter just below the median is spread over more ground than the quarter just above it.",
  "There are more data values above the median than below it.",
  "There are more data values below the median than above it."
)
$answer[2] = $skewIdx

$questions[3] = array(
  "Read the line inside the box. The middle of the box is `(Q_1 + Q_3)/2`, which only equals the median when the two halves happen to be equally wide.",
  "The middle of the box always is the median, so either way of reading it works.",
  "Read the line inside the box, because the middle of the box is the mean rather than the median.",
  "Read the middle of the box, because the drawn line marks the mean."
)
$answer[3] = 0

$axisMax = $maxV + 2 * $scaleUp
$tickStep = 5 * $scaleUp
$rem = $axisMax % $tickStep
if ($rem > 0) { $axisMax = $axisMax + $tickStep - $rem }

$span = 495 - 55
$xMin = round(55 + $minV * $span / $axisMax, 2)
$xQ1 = round(55 + $q1 * $span / $axisMax, 2)
$xMed = round(55 + $med * $span / $axisMax, 2)
$xQ3 = round(55 + $q3 * $span / $axisMax, 2)
$xMax = round(55 + $maxV * $span / $axisMax, 2)
$boxW = round($xQ3 - $xQ1, 2)

$ticks = ""
$nTicks = $axisMax / $tickStep
for ($g=0..$nTicks) {
  $val = $tickStep * $g
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

$answer[0] = $med
$reltolerance[0] = 0.005
$abstolerance[0] = 0.5

$answer[1] = $iqr
$reltolerance[1] = 0.005
$abstolerance[1] = 0.5

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
      <p><span class="term-label">Part (a) &mdash; read the line, not the box.</span> The median is the heavy line drawn <i>inside</i> the box: <b>' . $med . '</b> ' . $unitWord . '. The middle of the box is a different point &mdash; `(Q_1 + Q_3)/2 = (' . $q1 . ' + ' . $q3 . ')/2 = ' . $boxMiddle . '`, which misses the median by ' . $missBy . '. On a symmetric plot the two coincide and the shortcut goes unpunished; here it does not.</p>
      <p><span class="term-label">Part (b) &mdash; the interquartile range.</span> `"IQR" = Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ` <b>' . $iqr . '</b> ' . $unitWord . '.</p>
      <p><span class="term-label">Part (c) &mdash; what the off-center line means.</span> The box splits at the median into two quarters of the data. Here they are ' . $lowerHalf . ' wide below the median and ' . $upperHalf . ' wide above it &mdash; the same quarter of the data each, over very different amounts of ground. So ' . $widerName . ' of the middle fifty percent is the more spread out. It does <b>not</b> mean more values lie on that side: both sides hold exactly 25%, always.</p>
      <p><span class="term-label">Part (d) &mdash; the habit to keep.</span> Always read the drawn line. The center of the box equals the median only when the two halves happen to be equally wide, which is precisely the case that teaches the wrong lesson.</p>
      <p><b>Answer:</b> (a) ' . $med . ' &nbsp;&nbsp; (b) ' . $iqr . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$intro</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>median</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>interquartile range</b>? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The median line does not sit in the middle of the box. What does that tell you? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> To find the median on a box plot, should you read the drawn line or the middle of the box? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
