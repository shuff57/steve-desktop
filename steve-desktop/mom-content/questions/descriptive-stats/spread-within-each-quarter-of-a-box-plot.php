// === NAME - DESCRIPTION: Spread Within Each Quarter of a Box Plot - Find which quarter of a box plot has the smallest spread, read that spread and the interquartile range, and compare two intervals with the 25%-per-quarter rule ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A box plot of the number of text messages a sample of teenagers sent in one day."
  $axisName = "Text messages per day"
  $unitWord = "text messages"
}
else {
  $intro = "A box plot of the number of pages a sample of readers read in one sitting."
  $axisName = "Pages read in one sitting"
  $unitWord = "pages"
}

// The four section widths are 10, 20, 30 and 40 in a rotated order, so every one of the five
// numbers is a multiple of ten and lands exactly on a labeled tick, and the four widths are
// guaranteed distinct by construction -- "smallest" and "widest" each have exactly one answer,
// never a tie decided by luck of the seed.
$wid = array(2, 4, 6, 8)
$rot = rand(0, 3)
$w1 = $wid[$rot]
$w2 = $wid[($rot + 1) % 4]
$w3 = $wid[($rot + 2) % 4]
$w4 = $wid[($rot + 3) % 4]

$minV = 2 * rand(0, 3)
$q1 = $minV + $w1
$med = $q1 + $w2
$q3 = $med + $w3
$maxV = $q3 + $w4

$iqr = $q3 - $q1

$smallestIdx = 0
$wMin = $w1
if ($w2 < $wMin) { $wMin = $w2; $smallestIdx = 1 }
if ($w3 < $wMin) { $wMin = $w3; $smallestIdx = 2 }
if ($w4 < $wMin) { $wMin = $w4; $smallestIdx = 3 }

$widestIdx = 0
$wMax = $w1
if ($w2 > $wMax) { $wMax = $w2; $widestIdx = 1 }
if ($w3 > $wMax) { $wMax = $w3; $widestIdx = 2 }
if ($w4 > $wMax) { $wMax = $w4; $widestIdx = 3 }

// Boundaries of the smallest-spread quarter -- this becomes Interval A, an entire quarter.
$aLo = $minV
$aHi = $q1
if ($smallestIdx == 1) { $aLo = $q1; $aHi = $med }
if ($smallestIdx == 2) { $aLo = $med; $aHi = $q3 }
if ($smallestIdx == 3) { $aLo = $q3; $aHi = $maxV }

// Boundaries of the widest quarter -- Interval B is trimmed ten off ONE side of it, so it sits
// strictly inside a single quarter rather than spanning the whole thing, and it is still wider
// on the page than Interval A even though it holds no more than one quarter's worth of data.
$bQLo = $minV
$bQHi = $q1
if ($widestIdx == 1) { $bQLo = $q1; $bQHi = $med }
if ($widestIdx == 2) { $bQLo = $med; $bQHi = $q3 }
if ($widestIdx == 3) { $bQLo = $q3; $bQHi = $maxV }

$trimSide = rand(0, 1)
$bLo = $bQLo
$bHi = $bQHi
if ($trimSide == 0) { $bLo = $bQLo + 10 }
else { $bHi = $bQHi - 10 }

$axisMax = $maxV + 2
$rem = $axisMax % 4
if ($rem > 0) { $axisMax = $axisMax + 4 - $rem }

// Plot geometry: the number line runs from x = 55 to x = 495.
$span = 495 - 55
$xMin = round(55 + $minV * $span / $axisMax, 2)
$xQ1 = round(55 + $q1 * $span / $axisMax, 2)
$xMed = round(55 + $med * $span / $axisMax, 2)
$xQ3 = round(55 + $q3 * $span / $axisMax, 2)
$xMax = round(55 + $maxV * $span / $axisMax, 2)
$boxW = round($xQ3 - $xQ1, 2)

$ticks = ""
$nTicks = $axisMax / 2
for ($g=0..$nTicks) {
  $val = 2 * $g
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

$questions[0] = array(
  "The lowest quarter, from the smallest value to `Q_1`.",
  "The second quarter, from `Q_1` to the median.",
  "The third quarter, from the median to `Q_3`.",
  "The highest quarter, from `Q_3` to the largest value."
)
$answer[0] = $smallestIdx
$noshuffle[0] = "all"

$answer[1] = $wMin
$reltolerance[1] = 0.01
$abstolerance[1] = 0.5

$answer[2] = $iqr
$reltolerance[2] = 0.01
$abstolerance[2] = 0.5

$questions[3] = array(
  "The interval from " . $aLo . " to " . $aHi . " holds more, because it spans one whole quarter of the data, which is always 25%, while the interval from " . $bLo . " to " . $bHi . " covers only part of one quarter.",
  "The interval from " . $bLo . " to " . $bHi . " holds more, because it is drawn wider on the number line than the interval from " . $aLo . " to " . $aHi . ".",
  "The two intervals hold exactly the same amount of data.",
  "There is not enough information to compare the two intervals."
)
$answer[3] = 0

$smallestName = "the lowest quarter"
if ($smallestIdx == 1) { $smallestName = "the second quarter" }
if ($smallestIdx == 2) { $smallestName = "the third quarter" }
if ($smallestIdx == 3) { $smallestName = "the highest quarter" }

$widestName = "the lowest quarter"
if ($widestIdx == 1) { $widestName = "the second quarter" }
if ($widestIdx == 2) { $widestName = "the third quarter" }
if ($widestIdx == 3) { $widestName = "the highest quarter" }

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
      <p><span class="term-label">What the five marks are.</span> Reading left to right: the left whisker end is the smallest value (' . $minV . '), the left edge of the box is `Q_1` (' . $q1 . '), the heavy line inside the box is the median (' . $med . '), the right edge is `Q_3` (' . $q3 . ') and the right whisker end is the largest value (' . $maxV . '). The four gaps between consecutive marks are ' . $w1 . ', ' . $w2 . ', ' . $w3 . ' and ' . $w4 . ' ' . $unitWord . ' wide, and each one holds exactly a quarter of the data no matter how wide it is drawn.</p>
      <p><span class="term-label">Parts (a) and (b) &mdash; the smallest spread.</span> The narrowest of the four gaps is <b>' . $smallestName . '</b>, ' . $wMin . ' ' . $unitWord . ' wide. A narrow gap means that quarter of the data is bunched close together, not that it holds fewer values &mdash; every quarter still holds the same 25%.</p>
      <p><span class="term-label">Part (c) &mdash; the interquartile range.</span> `"IQR" = Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ` <b>' . $iqr . '</b> ' . $unitWord . '. That is the width of the box, and it is the span of the middle half of the data.</p>
      <p><span class="term-label">Part (d) &mdash; comparing two intervals.</span> The interval from ' . $aLo . ' to ' . $aHi . ' is all of ' . $smallestName . ', so it is guaranteed to hold exactly 25% of the data. The interval from ' . $bLo . ' to ' . $bHi . ' lies entirely inside ' . $widestName . ' &mdash; it does not reach either end of that quarter &mdash; so it can hold at most 25%, and probably less, even though it is drawn wider on the page. Width on a box plot tells you how spread out a quarter is, not how many values it holds. So the first interval, ' . $aLo . ' to ' . $aHi . ', is the one guaranteed to hold more.</p>
      <p><b>Answer:</b> (a) ' . $smallestName . ' &nbsp;&nbsp; (b) ' . $wMin . ' &nbsp;&nbsp; (c) ' . $iqr . '</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which quarter of the data has the <b>smallest spread</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is that smallest spread? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the <b>interquartile range</b>? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Which of these two intervals is guaranteed to hold <b>more</b> of the data: from $aLo to $aHi, or from $bLo to $bHi? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
