// === NAME - DESCRIPTION: Mean from a Grouped Frequency Table - Estimate the mean of data grouped into class intervals using class midpoints, and identify why the result is an estimate ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ci = rand(0, 2)
if ($ci == 0) {
  $intro = "A statistics instructor grouped a class's exam grades into intervals. The results are shown below."
  $valueLabel = "Exam grade"
  $unitWord = "students"
}
else if ($ci == 1) {
  $intro = "A researcher grouped the obesity rate (percent of adults) for a sample of countries into intervals. The results are shown below."
  $valueLabel = "Obesity rate (percent)"
  $unitWord = "countries"
}
else {
  $intro = "A health worker grouped the percentage of underweight children for a sample of villages into intervals. The results are shown below."
  $valueLabel = "Underweight children (percent)"
  $unitWord = "villages"
}

$lo0 = rand(0, 6) * 10 + rand(0, 1) * 5
$width = 10

$lo = array(0, 0, 0, 0, 0)
$hi = array(0, 0, 0, 0, 0)
$mid = array(0, 0, 0, 0, 0)
$f = array(0, 0, 0, 0, 0)
$n = 0
for ($c=0..4) {
  $lo[$c] = $lo0 + $c * $width
  $hi[$c] = $lo[$c] + $width
  $mid[$c] = ($lo[$c] + $hi[$c]) / 2
  $f[$c] = rand(2, 15)
  $n = $n + $f[$c]
}

$sumMidF = 0
for ($c=0..4) {
  $sumMidF = $sumMidF + $mid[$c] * $f[$c]
}
$estMean = round($sumMidF / $n, 2)

$tableRows = ""
for ($c=0..4) {
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $lo[$c] . ' &ndash; ' . $hi[$c] . '</td><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $f[$c] . '</td></tr>'
}

$askClass = rand(0, 4)
$askLo = $lo[$askClass]
$askHi = $hi[$askClass]
$askMid = $mid[$askClass]

$answer[0] = $askMid
$reltolerance[0] = 0.01
$abstolerance[0] = 0.05

$answer[1] = $estMean
$reltolerance[1] = 0.01
$abstolerance[1] = 0.05

$questions[2][0] = "The original values were lost when the data was grouped into classes, and this method assumes every value in a class sits at that class's midpoint."
$questions[2][1] = "Because the frequencies in the table are rounded."
$questions[2][2] = "Because the class midpoints are rounded."
$questions[2][3] = "It is not an estimate &mdash; this method gives the exact mean."
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
      <p><span class="term-label">Grouped data hides the individual values.</span> Once the ' . $n . ' ' . $unitWord . ' are sorted into classes, the table only says how many fall in each class, not what each one actually was. To estimate the mean, assume every value in a class sits at that class\'s midpoint, `"midpoint" = ("lower" + "upper")/2`.</p>
      <p><span class="term-label">Part (a) &mdash; the midpoint of the ' . $askLo . '&ndash;' . $askHi . ' class.</span> `(' . $askLo . ' + ' . $askHi . ')/2 = ` <b>' . $askMid . '</b>.</p>
      <p><span class="term-label">Part (b) &mdash; the estimated mean.</span> Multiply each class\'s midpoint by its frequency, add up the products, and divide by `n = ' . $n . '`:</p>
      <p>`bar x ~~ (sum "midpoint" * f)/n = ' . $sumMidF . '/' . $n . ' = ` <b>' . $estMean . '</b> (rounded to two decimal places).</p>
      <p><span class="term-label">Part (c) &mdash; why this is only an estimate.</span> Grouping the data into classes threw away the individual values, and the midpoint method assumes every value in a class landed exactly at the midpoint. It usually did not &mdash; so the true mean of the original data is close to <b>' . $estMean . '</b> but not necessarily equal to it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro The individual values are no longer available &mdash; only the class each one fell into.</p>
    <table style="border-collapse:collapse; margin:0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:7px 20px;">$valueLabel</th>
          <th style="border:1px solid #d1d5db; padding:7px 20px;">Frequency</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
    <p style="margin:12px 0 0 0; font-size:15px; color:#444;">To estimate the mean of grouped data, multiply each class's midpoint by its frequency, add the products, then divide by the total frequency.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the midpoint of the $askLo &ndash; $askHi class? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Estimate the mean of the data, to two decimal places. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why is the value from part (b) an estimate of the mean rather than the exact mean? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
