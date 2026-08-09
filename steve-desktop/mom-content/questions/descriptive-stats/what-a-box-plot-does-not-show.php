// === NAME - DESCRIPTION: What a Box Plot Does Not Show - Compare two box plots with the same range but different box widths and sample sizes to see that a wider box means more spread, not more data, and that a box plot never shows counts at all ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "Data 1 is the study time, in minutes per day, logged by a sample of students in an early-morning study group. Data 2 is the study time logged by a much larger sample of students in a self-paced online section."
  $axisName = "Study time in minutes per day"
  $unitWord = "minutes"
  $n1 = 40
  $n2 = 200
}
else {
  $intro = "Data 1 is the number of push-ups completed in one minute by a sample of competitors at a local fitness challenge. Data 2 is the number of push-ups completed by a much larger sample of competitors at the regional challenge."
  $axisName = "Push-ups completed in one minute"
  $unitWord = "push-ups"
  $n1 = 30
  $n2 = 150
}

// Both datasets share the same overall minimum and maximum, so both whiskers start and end at the
// same two points on the axis. Only the boxes differ.
$minV = 0
$maxV = 100

// Data 1's box is wide -- its width (Q3 - Q1) is always at least 50, more than double Data 2's
// fixed width of 20. $q1_1 and $med1 vary independently but always keep $minV < $q1_1 < $med1 <
// $q3_1 < $maxV, and every value stays a multiple of ten.
$q1_1 = 10 + 10 * rand(0, 1)
$q3_1 = 70 + 10 * rand(0, 1)
$med1 = 40 + 10 * rand(0, 1)

// Data 2's box is fixed and narrow, centred lower than Data 1's box.
$q1_2 = 20
$q3_2 = 40
$med2 = 30

// The number named in part (a). By construction it always sits strictly inside both boxes: it is
// above both $q1_1 (at most 20) and $q1_2 (20), and below both $q3_1 (at least 70) and $q3_2 (40)
// -- so the plots can never separate the two groups on this value.
$statedX = 30

// Plot geometry: shared axis runs from x=105 to x=500, range 0..100.
$xStart = 105
$xEnd = 500
$span = $xEnd - $xStart
$axisMax = $maxV

$xMinAll = round($xStart + $minV * $span / $axisMax, 2)
$xMaxAll = round($xStart + $maxV * $span / $axisMax, 2)

$xQ1_1 = round($xStart + $q1_1 * $span / $axisMax, 2)
$xMed1 = round($xStart + $med1 * $span / $axisMax, 2)
$xQ3_1 = round($xStart + $q3_1 * $span / $axisMax, 2)
$boxW1 = round($xQ3_1 - $xQ1_1, 2)

$xQ1_2 = round($xStart + $q1_2 * $span / $axisMax, 2)
$xMed2 = round($xStart + $med2 * $span / $axisMax, 2)
$xQ3_2 = round($xStart + $q3_2 * $span / $axisMax, 2)
$boxW2 = round($xQ3_2 - $xQ1_2, 2)

$xStated = round($xStart + $statedX * $span / $axisMax, 2)

$ticks = ""
for ($g=0..10) {
  $val = 10 * $g
  $tx = round($xStart + $val * $span / $axisMax, 2)
  $ticks = $ticks . '<line x1="' . $tx . '" y1="160" x2="' . $tx . '" y2="166" stroke="#374151" stroke-width="1"/>'
  $ticks = $ticks . '<line x1="' . $tx . '" y1="16" x2="' . $tx . '" y2="160" stroke="#eef2f7" stroke-width="1"/>'
  $ticks = $ticks . '<text x="' . $tx . '" y="182" font-size="11" fill="#374151" text-anchor="middle">' . $val . '</text>'
}

$svg = '<svg viewBox="0 0 520 220" width="100%" style="max-width:520px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $ticks

$svg = $svg . '<text x="8" y="60" font-size="13" font-weight="700" fill="#1e3a8a">Data 1</text>'
$svg = $svg . '<line x1="' . $xMinAll . '" y1="55" x2="' . $xQ1_1 . '" y2="55" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xQ3_1 . '" y1="55" x2="' . $xMaxAll . '" y2="55" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMinAll . '" y1="39" x2="' . $xMinAll . '" y2="71" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMaxAll . '" y1="39" x2="' . $xMaxAll . '" y2="71" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<rect x="' . $xQ1_1 . '" y="29" width="' . $boxW1 . '" height="52" fill="#bfdbfe" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMed1 . '" y1="29" x2="' . $xMed1 . '" y2="81" stroke="#1e3a8a" stroke-width="3"/>'

$svg = $svg . '<text x="8" y="125" font-size="13" font-weight="700" fill="#1e3a8a">Data 2</text>'
$svg = $svg . '<line x1="' . $xMinAll . '" y1="120" x2="' . $xQ1_2 . '" y2="120" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xQ3_2 . '" y1="120" x2="' . $xMaxAll . '" y2="120" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMinAll . '" y1="104" x2="' . $xMinAll . '" y2="136" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMaxAll . '" y1="104" x2="' . $xMaxAll . '" y2="136" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<rect x="' . $xQ1_2 . '" y="94" width="' . $boxW2 . '" height="52" fill="#bfdbfe" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMed2 . '" y1="94" x2="' . $xMed2 . '" y2="146" stroke="#1e3a8a" stroke-width="3"/>'

// Dashed reference line at the value named in part (a), so it is visible on the drawing that the
// same value falls inside both boxes.
$svg = $svg . '<line x1="' . $xStated . '" y1="16" x2="' . $xStated . '" y2="160" stroke="#b91c1c" stroke-width="1.5" stroke-dasharray="4,3"/>'
$svg = $svg . '<text x="' . $xStated . '" y="12" font-size="11" fill="#b91c1c" text-anchor="middle">' . $statedX . '</text>'

$svg = $svg . '<line x1="105" y1="160" x2="500" y2="160" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<text x="302" y="204" font-size="13" fill="#374151" text-anchor="middle">' . $axisName . '</text>'
$svg = $svg . '</svg>'

$questions[0] = array(
  "True -- Data 1 clearly has more values above " . $statedX . " " . $unitWord . ".",
  "False -- Data 2 clearly has more values above " . $statedX . " " . $unitWord . ".",
  "Impossible to tell from the box plots alone."
)
$answer[0] = 2

$questions[1] = array(
  "The middle half of the data in that group, from `Q_1` to `Q_3`, is spread out over more of the scale.",
  "More of the data values fall inside that box than inside a narrower one.",
  "That group has a larger sample size than the group with the narrower box.",
  "The values inside that box were measured less accurately."
)
$answer[1] = 0

$questions[2] = array(
  "Yes -- the five-number summary could match exactly, since a box plot shows position only, not how many values were sampled.",
  "No -- the shape of a box plot must change whenever the sample sizes differ.",
  "No -- the group with more values would always draw a wider box.",
  "Yes, but only if the two sample sizes happen to be equal."
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
      <p><span class="term-label">The two five-number summaries.</span> Data 1: min ' . $minV . ', `Q_1` ' . $q1_1 . ', median ' . $med1 . ', `Q_3` ' . $q3_1 . ', max ' . $maxV . '. Data 2: min ' . $minV . ', `Q_1` ' . $q1_2 . ', median ' . $med2 . ', `Q_3` ' . $q3_2 . ', max ' . $maxV . '. Both share the same min and max &mdash; only the boxes differ.</p>
      <p><span class="term-label">Part (a).</span> A box plot marks five positions and shows nothing about how many values sit near any of them. ' . $statedX . ' ' . $unitWord . ' falls inside both boxes here, and Data 1 (' . $n1 . ' values) and Data 2 (' . $n2 . ' values) are different sizes besides. There is no way to tell which group has more values above ' . $statedX . ' from the plots alone &mdash; the answer is <b>impossible to tell</b>.</p>
      <p><span class="term-label">Part (b) &mdash; what a wider box means.</span> Every section of a box plot, including the box itself, holds the same quarter of the data no matter how wide or narrow it is drawn. A wider box does not mean more values landed there &mdash; it means that middle half is <b>spread out over more of the scale</b>. Data 1\'s box runs from ' . $q1_1 . ' to ' . $q3_1 . ' (width ' . ($q3_1 - $q1_1) . '), while Data 2\'s box runs only from ' . $q1_2 . ' to ' . $q3_2 . ' (width ' . ($q3_2 - $q1_2) . '), even though Data 2 has the larger sample.</p>
      <p><span class="term-label">Part (c) &mdash; counts are invisible on a box plot.</span> A box plot is built entirely from five positions in the data, never from how many values were collected. Two groups of very different sizes &mdash; here ' . $n1 . ' values against ' . $n2 . ' &mdash; can produce the exact same five-number summary and therefore the exact same box plot. The answer is <b>yes</b>.</p>
      <p><b>Answer:</b> (a) impossible to tell &nbsp;&nbsp; (b) spread over more of the scale &nbsp;&nbsp; (c) yes, sample size is invisible on the plot</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$intro</p>
    <p style="margin:0 0 4px 0;">Data 1 comes from a sample of $n1 values. Data 2 comes from a sample of $n2 values. Both are plotted on the same axis below.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Consider this claim: "Data 1 has more values above $statedX $unitWord than Data 2 does." Is that claim true, false, or impossible to tell? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Data 1's box is much wider than Data 2's box. What does that wider box actually mean? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Could two groups as different in size as Data 1 and Data 2 ever produce identical box plots? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
