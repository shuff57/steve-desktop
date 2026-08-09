// === NAME - DESCRIPTION: Read a Box Plot - Take the median and the quartiles off a drawn box plot, compute the interquartile range, and identify which quarter of the data is the most spread out ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A box plot of the number of minutes a sample of commuters spent travelling to work."
  $axisName = "Travel time (minutes)"
  $unitWord = "minutes"
}
else {
  // The largest value can reach 120, so the context must not carry a ceiling of its own -- a
  // "100-point project" scoring 120 renders perfectly and is nonsense.
  $intro = "A box plot of the weights, in pounds, of the dogs seen at a veterinary clinic in one week."
  $axisName = "Weight (pounds)"
  $unitWord = "pounds"
}

// The four section widths are 10, 20, 30 and 40 in a rotated order, so every one of the five
// numbers is a multiple of ten and lands exactly on a labelled tick -- a value the student is
// asked to read off must never sit between gridlines. The rotation also guarantees the four
// widths are distinct, so "which quarter is widest" has one defensible answer rather than a tie.
$wid = array(10, 20, 30, 40)
$rot = rand(0, 3)
$w1 = $wid[$rot]
$w2 = $wid[($rot + 1) % 4]
$w3 = $wid[($rot + 2) % 4]
$w4 = $wid[($rot + 3) % 4]

$minV = 10 * rand(0, 2)
$q1 = $minV + $w1
$med = $q1 + $w2
$q3 = $med + $w3
$maxV = $q3 + $w4

$iqr = $q3 - $q1

$widest = 0
$wMax = $w1
if ($w2 > $wMax) {
  $wMax = $w2
  $widest = 1
}
if ($w3 > $wMax) {
  $wMax = $w3
  $widest = 2
}
if ($w4 > $wMax) {
  $wMax = $w4
  $widest = 3
}

$axisMax = $maxV + 10
$rem = $axisMax % 20
if ($rem > 0) { $axisMax = $axisMax + 20 - $rem }

// Plot geometry: the number line runs from x = 55 to x = 495.
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

$answer[0] = $med
$reltolerance[0] = 0.01
$abstolerance[0] = 0.5

$answer[1] = $iqr
$reltolerance[1] = 0.01
$abstolerance[1] = 0.5

$answer[2] = $q1
$reltolerance[2] = 0.01
$abstolerance[2] = 0.5

$questions[3] = array(
  "The lowest quarter, from the smallest value to `Q_1`.",
  "The second quarter, from `Q_1` to the median.",
  "The third quarter, from the median to `Q_3`.",
  "The highest quarter, from `Q_3` to the largest value."
)
$answer[3] = $widest
$noshuffle[3] = "all"

$widestName = "the lowest quarter"
if ($widest == 1) { $widestName = "the second quarter" }
if ($widest == 2) { $widestName = "the third quarter" }
if ($widest == 3) { $widestName = "the highest quarter" }

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
      <p><span class="term-label">What the five marks are.</span> Reading left to right: the left whisker end is the smallest value (' . $minV . '), the left edge of the box is `Q_1` (' . $q1 . '), the heavy line inside the box is the median (' . $med . '), the right edge is `Q_3` (' . $q3 . ') and the right whisker end is the largest value (' . $maxV . ').</p>
      <p><span class="term-label">Parts (a) and (c).</span> The median is the line <i>inside</i> the box, <b>' . $med . '</b> ' . $unitWord . ' &mdash; not the middle of the box, which is a different point whenever the two halves are unequal. `Q_1` is the box\'s left edge, <b>' . $q1 . '</b>.</p>
      <p><span class="term-label">Part (b) &mdash; the interquartile range.</span> `"IQR" = Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ` <b>' . $iqr . '</b> ' . $unitWord . '. That is the width of the box, and it is the span of the middle half of the data.</p>
      <p><span class="term-label">Part (d) &mdash; the most spread-out quarter.</span> Each of the four sections holds a quarter of the data, so the widest section is the one whose quarter is most spread out. The widths are ' . $w1 . ', ' . $w2 . ', ' . $w3 . ' and ' . $w4 . ', so the answer is <b>' . $widestName . '</b>. A long whisker does not mean more values out there &mdash; it means the same quarter of the data, stretched over more ground.</p>
      <p><b>Answer:</b> (a) ' . $med . ' &nbsp;&nbsp; (b) ' . $iqr . ' &nbsp;&nbsp; (c) ' . $q1 . '</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is `Q_1`, the first quartile? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Each of the four sections holds a quarter of the data. Which quarter is spread over the widest range? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
