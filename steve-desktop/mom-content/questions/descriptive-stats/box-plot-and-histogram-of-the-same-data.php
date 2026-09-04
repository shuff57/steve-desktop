// === NAME - DESCRIPTION: Box Plot and Histogram of the Same Data - Read a class count only the histogram shows, then judge which display reveals a second hump and which gives the IQR directly ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices", "choices")

// Two fully-worked cases. Each case's five-number summary is the ACTUAL five-number summary of
// 16 real data values that were tallied into the six classes below: the counts and the summary
// describe one data set, not two coincidentally-compatible pictures.
$ci = rand(0, 1)
if ($ci == 0) {
  // Right-skewed: waiting times. Data (sorted): 3,8,11,13,14,17,19,21,24,26,29,31,34,38,44,57
  $intro = "A hospital recorded the waiting time, in minutes, for a sample of 16 patients in the emergency room one evening."
  $axisName = "Waiting time in minutes"
  $unitWord = "minutes"
  $counts = array(2, 5, 4, 3, 1, 1)
  $binIdx = 1
  $dataMin = 3
  $dataQ1 = 13.5
  $dataMed = 22.5
  $dataQ3 = 32.5
  $dataMax = 57
}
else {
  // Left-skewed: exam scores. Data (sorted): 4,15,22,25,28,31,33,36,39,41,43,45,47,49,52,57
  $intro = "An instructor recorded the exam score, out of 60 points, for a sample of 16 students in an introductory statistics course."
  $axisName = "Exam score in points"
  $unitWord = "points"
  $counts = array(1, 1, 3, 4, 5, 2)
  $binIdx = 3
  $dataMin = 4
  $dataQ1 = 26.5
  $dataMed = 37.5
  $dataQ3 = 46
  $dataMax = 57
}

$n = $counts[0] + $counts[1] + $counts[2] + $counts[3] + $counts[4] + $counts[5]
$binCount = $counts[$binIdx]
$binLo = 10 * $binIdx
$binHi = $binLo + 10
$iqr = round($dataQ3 - $dataQ1, 2)

$answer[0] = $binCount
$reltolerance[0] = 0.001
$abstolerance[0] = 0.1

$questions[1] = array(
  "The histogram: a box plot cannot show two humps, because very different-shaped distributions can share the same five-number summary.",
  "The box plot: it displays the median directly.",
  "Either display shows this equally well.",
  "Neither display can show this."
)
$answer[1] = 0

$questions[2] = array(
  "The histogram, by comparing the tallest and shortest bars.",
  "The box plot, as the width of the box.",
  "Either display gives it equally directly.",
  "Neither display gives the IQR directly."
)
$answer[2] = 1

// Shared geometry: both drawings map data value 0..60 onto the SAME pixel range (plotL..plotR),
// so a class boundary and the box plot's whisker positions sit at the same x-coordinate. The
// histogram's own classes are 10 units wide, matching the tick spacing drawn below both charts.
$axisMax = 60
$plotL = 55
$plotR = 475
$span = $plotR - $plotL
$barW = $span / 6
$unitPx = $span / $axisMax

$histT = 15
$histB = 170
$freqMax = 6
$freqUnitPx = ($histB - $histT) / $freqMax

// Vertical guide lines run the full height of the figure at every class boundary, so a student
// can trace straight down from a histogram class edge to the box plot below it.
$grid = ""
for ($g=0..6) {
  $v = 10 * $g
  $gx = round($plotL + $v * $unitPx, 2)
  $grid = $grid . '<line x1="' . $gx . '" y1="15" x2="' . $gx . '" y2="270" stroke="#eef2f7" stroke-width="1"/>'
}

$freqGrid = ""
for ($f=0..6) {
  $gy = round($histB - $f * $freqUnitPx, 2)
  $freqGrid = $freqGrid . '<line x1="55" y1="' . $gy . '" x2="475" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $freqGrid = $freqGrid . '<text x="48" y="' . ($gy + 4) . '" font-size="11" fill="#6b7280" text-anchor="end">' . $f . '</text>'
}

$bars = ""
for ($k=0..5) {
  $bx = round($plotL + $k * $barW, 2)
  $bh = round($counts[$k] * $freqUnitPx, 2)
  $by = round($histB - $bh, 2)
  $bars = $bars . '<rect x="' . $bx . '" y="' . $by . '" width="' . $barW . '" height="' . $bh . '" fill="#93c5fd" stroke="#1e40af" stroke-width="1.5"/>'
}

$ticks = ""
for ($g=0..6) {
  $v = 10 * $g
  $tx = round($plotL + $v * $unitPx, 2)
  $ticks = $ticks . '<line x1="' . $tx . '" y1="170" x2="' . $tx . '" y2="178" stroke="#374151" stroke-width="1"/>'
  $ticks = $ticks . '<text x="' . $tx . '" y="192" font-size="12" fill="#374151" text-anchor="middle">' . $v . '</text>'
}

$xMinPx = round($plotL + $dataMin * $unitPx, 2)
$xQ1Px = round($plotL + $dataQ1 * $unitPx, 2)
$xMedPx = round($plotL + $dataMed * $unitPx, 2)
$xQ3Px = round($plotL + $dataQ3 * $unitPx, 2)
$xMaxPx = round($plotL + $dataMax * $unitPx, 2)
$boxWPx = round($xQ3Px - $xQ1Px, 2)

$svg = '<svg viewBox="0 0 520 290" width="100%" style="max-width:520px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $grid
$svg = $svg . $freqGrid
$svg = $svg . $bars
$svg = $svg . '<line x1="55" y1="15" x2="55" y2="170" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<line x1="55" y1="170" x2="475" y2="170" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<text x="20" y="95" font-size="13" fill="#374151" text-anchor="middle" transform="rotate(-90 20 95)">Frequency</text>'
$svg = $svg . $ticks
$svg = $svg . '<text x="265" y="212" font-size="13" fill="#374151" text-anchor="middle">' . $axisName . '</text>'
$svg = $svg . '<line x1="' . $xMinPx . '" y1="250" x2="' . $xQ1Px . '" y2="250" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xQ3Px . '" y1="250" x2="' . $xMaxPx . '" y2="250" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMinPx . '" y1="238" x2="' . $xMinPx . '" y2="262" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMaxPx . '" y1="238" x2="' . $xMaxPx . '" y2="262" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<rect x="' . $xQ1Px . '" y="230" width="' . $boxWPx . '" height="40" fill="#bfdbfe" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMedPx . '" y1="230" x2="' . $xMedPx . '" y2="270" stroke="#1e3a8a" stroke-width="3"/>'
$svg = $svg . '</svg>'

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
      <p><span class="term-label">Part (a): only the histogram gives a class count.</span> Find the bar running from ' . $binLo . ' to ' . $binHi . ' and read its height off the frequency axis: <b>' . $binCount . '</b>. The box plot below shows the same data set, but it was built from just five numbers, the smallest value, the two quartiles, the median and the largest value, so it has no way to tell you how many observations landed in one narrow class.</p>
      <p><span class="term-label">Part (b): which display would show two humps.</span> The five-number summary behind this box plot is minimum ' . $dataMin . ', `Q_1 = ' . $dataQ1 . '`, median ' . $dataMed . ', `Q_3 = ' . $dataQ3 . '`, maximum ' . $dataMax . ' ' . $unitWord . '. Those five numbers are all a box plot ever draws, and a completely different-shaped data set: say, one with a pile-up near ' . $dataMin . ' and a second pile-up near ' . $dataMax . ', with almost nothing in between: can produce that exact same box plot. The histogram cannot hide that split, because it draws every class, not just five summary points. <b>The histogram</b> is the display that would reveal two separate humps.</p>
      <p><span class="term-label">Part (c): which display gives the IQR directly.</span> `"IQR" = Q_3 - Q_1 = ' . $dataQ3 . ' - ' . $dataQ1 . ' = ' . $iqr . '` ' . $unitWord . ', and that is exactly the width of the box: no arithmetic on the histogram\'s bars gets you there without first working out the quartiles yourself. <b>The box plot</b> gives the IQR directly.</p>
      <p><b>Answer:</b> (a) ' . $binCount . ' &nbsp;&nbsp; (b) the histogram &nbsp;&nbsp; (c) the box plot</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$intro The histogram and the box plot below display this same data set, lined up on the same horizontal scale.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Using the <b>histogram</b>, how many observations fall in the class from $binLo to $binHi? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Suppose this data actually had two separate humps: two ranges where values pile up, with few values between them. Which display would reveal that? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which display gives you the interquartile range, `"IQR"`, directly, without further calculation? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
