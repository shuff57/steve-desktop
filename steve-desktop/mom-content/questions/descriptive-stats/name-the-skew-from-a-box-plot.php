// === NAME - DESCRIPTION: Name the Skew from a Box Plot - Use the median's position in the box and the two whisker lengths to name the shape, then predict where the mean sits relative to the median ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// 2.6 reads skew off histograms; 2.4 reads five numbers off box plots. Neither asks the student to
// name a SHAPE from a box plot, which is the form the question usually takes on a test because a box
// plot is compact. The cues are different from a histogram's: there are no bars to look at, only how
// far the median sits from each edge of the box and how long each whisker is.
//
// Both cues are made to agree by construction -- the long whisker is always on the same side as the
// wide half of the box -- so the picture can never send two contradictory signals. All five values
// are even and the axis carries a tick every 2, so everything a student reads lands on a label.
$anstypes = array("choices", "choices", "number")

$dir = rand(0, 1)

// Build the five numbers from a short side and a long side, then assign the long side by $dir.
$shortBox = 2 * rand(2, 3)
$longBox = $shortBox + 2 * rand(3, 5)
$shortWhisk = 2 * rand(1, 2)
$longWhisk = $shortWhisk + 2 * rand(4, 7)

$med = 2 * rand(9, 13)
if ($dir == 0) {
  // Skewed RIGHT: the stretch is above the median.
  $q1 = $med - $shortBox
  $q3 = $med + $longBox
  $minV = $q1 - $shortWhisk
  $maxV = $q3 + $longWhisk
}
else {
  // Skewed LEFT: the stretch is below the median.
  $q1 = $med - $longBox
  $q3 = $med + $shortBox
  $minV = $q1 - $longWhisk
  $maxV = $q3 + $shortWhisk
}
$iqr = $q3 - $q1

$trueName = "skewed right"
$tailWord = "the RIGHT, toward the larger values"
$meanWord = "GREATER than"
if ($dir == 1) {
  $trueName = "skewed left"
  $tailWord = "the LEFT, toward the smaller values"
  $meanWord = "LESS than"
}

$questions[0] = array(
  "Skewed right &mdash; the box and the whisker are both stretched toward the larger values",
  "Skewed left &mdash; the box and the whisker are both stretched toward the smaller values",
  "Roughly symmetric &mdash; the median sits in the middle of the box and the whiskers match",
  "Bimodal &mdash; a box plot shows two peaks"
)
$answer[0] = $dir

$questions[1] = array(
  "The mean is GREATER than the median, because the long tail toward the large values pulls it up",
  "The mean is LESS than the median, because the long tail toward the small values pulls it down",
  "The mean equals the median, because both are marked on the box plot",
  "A box plot gives no information about where the mean sits"
)
$answer[1] = $dir

$answer[2] = $iqr
$answerboxsize = 5

// --- Plot geometry. Axis starts at 0 and runs a little past the maximum, with a tick every 2 so
// every plotted value lands on a labeled gridline.
$axisMax = $maxV + 2
$rem = $axisMax % 4
if ($rem > 0) { $axisMax = $axisMax + 4 - $rem }

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
$svg = $svg . '<line x1="' . $xMin . '" y1="65" x2="' . $xQ1 . '" y2="65" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xQ3 . '" y1="65" x2="' . $xMax . '" y2="65" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMin . '" y1="47" x2="' . $xMin . '" y2="83" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMax . '" y1="47" x2="' . $xMax . '" y2="83" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<rect x="' . $xQ1 . '" y="38" width="' . $boxW . '" height="54" fill="#bfdbfe" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMed . '" y1="38" x2="' . $xMed . '" y2="92" stroke="#1e3a8a" stroke-width="3"/>'
$svg = $svg . '<line x1="55" y1="110" x2="495" y2="110" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<text x="275" y="152" font-size="13" fill="#374151" text-anchor="middle">Time in minutes</text>'
$svg = $svg . '</svg>'

$lowHalf = $med - $q1
$highHalf = $q3 - $med
$lowWhisk = $q1 - $minV
$highWhisk = $maxV - $q3

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
      <p><span class="term-label">Step 1 &mdash; read the five numbers.</span> Minimum ' . $minV . ', `Q_1 = ' . $q1 . '`, median ' . $med . ', `Q_3 = ' . $q3 . '`, maximum ' . $maxV . '.</p>
      <p><span class="term-label">Step 2 &mdash; compare the two halves of the box.</span> From `Q_1` up to the median is ' . $lowHalf . ' minutes; from the median up to `Q_3` is ' . $highHalf . '. Both halves hold the same 25% of the data, so the wider one covers the same count over more ground &mdash; that side is more spread out.</p>
      <p><span class="term-label">Step 3 &mdash; compare the whiskers.</span> The lower whisker is ' . $lowWhisk . ' long and the upper is ' . $highWhisk . '. The longer whisker points the same way as the wider half of the box, and that direction is the tail: ' . $tailWord . '.</p>
      <p>Both cues agree, so this distribution is <b>' . $trueName . '</b>.</p>
      <p><span class="term-label">Step 4 &mdash; where the mean must sit.</span> The mean chases the tail, so it is <b>' . $meanWord . '</b> the median. Notice that a box plot never MARKS the mean &mdash; you are predicting its position from the shape, not reading it off.</p>
      <p><span class="term-label">Step 5 &mdash; the interquartile range.</span> `"IQR" = Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ` <b>' . $iqr . '</b> minutes.</p>
      <p><span class="term-label">The trap.</span> Judging skew from which whisker looks longer ALONE. Read both cues; when a real data set makes them disagree, that is a sign the shape is closer to symmetric than either cue suggests on its own.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A box plot of the time, in minutes, that callers spent waiting on hold.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What shape does this box plot show? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Where would the <b>mean</b> sit relative to the median? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the interquartile range? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
