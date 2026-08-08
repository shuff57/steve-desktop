// === NAME - DESCRIPTION: Proportions, Percents and Fractions from a Totals Table - read four different part-of-whole quantities off a 13-row table of counts, then classify the count and a measured magnitude by data type ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "choices", "choices")

$deaths = array(231, 21357, 11685, 33819, 228802, 88003, 6605, 712, 88011, 1790, 320120, 21953, 768)
$total = 0
for ($i = 0..12) {
  $total = $total + $deaths[$i]
}

// Which ranges get asked about is what varies between students.
$ya = rand(7, 9)
$yb = rand(2, 4)
$yc = rand(3, 5)
$yd = rand(10, 11)

$sumA = 0
for ($i = 0..12) {
  $yr = $i + 1
  if ($yr >= $ya) { $sumA = $sumA + $deaths[$i] }
}
$sumB = 0
for ($i = 0..12) {
  $yr = $i + 1
  if ($yr < $yb) { $sumB = $sumB + $deaths[$i] }
}
$sumC = 0
for ($i = 0..12) {
  $yr = $i + 1
  if ($yr == $yc) { $sumC = $sumC + $deaths[$i] }
  if ($yr > $yd) { $sumC = $sumC + $deaths[$i] }
}
$sumD = $total - $deaths[12]

$propA = round($sumA / $total, 4)
$pctB = round($sumB / $total * 100, 2)
$pctC = round($sumC / $total * 100, 2)
$fracD = round($sumD / $total, 4)

$answer[0] = $propA
$abstolerance[0] = 0.00011
$answer[1] = $pctB
$abstolerance[1] = 0.011
$answer[2] = $pctC
$abstolerance[2] = 0.011
$answer[3] = $fracD
$abstolerance[3] = 0.00011

$questions[4] = array(
  "quantitative discrete",
  "quantitative continuous",
  "qualitative",
  "cumulative relative frequency"
)
$answer[4] = 0

$questions[5] = array(
  "quantitative continuous",
  "quantitative discrete",
  "qualitative",
  "a parameter of the population"
)
$answer[5] = 0

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:6px 14px;">Year</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Total Number of Deaths</th></tr>'
for ($i = 0..12) {
  $yr = $i + 1
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $yr . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:right;">' . $deaths[$i] . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:6px 14px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:right;"><b>' . $total . '</b></td></tr></table>'

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Every part is the same move: add the rows the question names, then divide by the total of ' . $total . '.</b> Only the wording of the answer changes &mdash; a proportion, a percent, or a fraction are the same number dressed differently.</p>
  <p><b>a &mdash; Year ' . $ya . ' through Year 13.</b> Those rows add to ' . $sumA . ', so the proportion is ' . $sumA . ' &divide; ' . $total . ' &approx; <b>' . $propA . '</b>.</p>
  <p><b>b &mdash; before Year ' . $yb . '.</b> "Before" excludes Year ' . $yb . ' itself. Those rows add to ' . $sumB . ', so the percent is ' . $sumB . ' &divide; ' . $total . ' &times; 100 &approx; <b>' . $pctB . '%</b>.</p>
  <p><b>c &mdash; Year ' . $yc . ' or after Year ' . $yd . '.</b> Two disjoint pieces, so add them: ' . $sumC . ' in total, giving ' . $pctC . '%. They cannot overlap, so nothing is double counted and no subtraction is needed.</p>
  <p><b>d &mdash; before Year 13.</b> Everything except the last row: ' . $sumD . ' &divide; ' . $total . ' &approx; <b>' . $fracD . '</b>.</p>
  <p><b>e &mdash; the number of deaths</b> is a <b>count</b>. You cannot record half a death, so it is quantitative <i>discrete</i>.</p>
  <p><b>f &mdash; earthquake magnitude</b> is <b>measured</b> on a continuous scale &mdash; 7.1, 7.14, 7.142 &mdash; so it is quantitative <i>continuous</i>. The contrast with part e is the whole point: both are numbers, and counting versus measuring is what separates them.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">The table shows the total number of deaths worldwide as a result of earthquakes over a 13-year period.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> What is the <b>proportion</b> of deaths between Year $ya and Year 13 (inclusive)? Round to <b>four decimal places</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> What <b>percent</b> of deaths occurred before Year $yb? Round to <b>two decimal places</b>, without the % sign. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> What <b>percent</b> of deaths occurred in Year $yc or after Year $yd? Round to <b>two decimal places</b>, without the % sign. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">d.</span> What <b>fraction</b> of deaths happened before Year 13? Enter it as a decimal rounded to <b>four decimal places</b>. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">e.</span> What kind of data is the <b>number of deaths</b>? $answerbox[4]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">f.</span> Earthquakes are quantified by <b>magnitude</b>, the amount of energy released. What kind of data is magnitude? $answerbox[5]
  </div>
</div>

// === ANSWER ===

$solutionguide
