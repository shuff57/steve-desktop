// === NAME - DESCRIPTION: Diagnose a Cumulative-vs-Individual Percent Claim - Catch a published claim that reports a row's cumulative relative frequency as if it were that row's own relative frequency, then compute the row's true percent ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number")

$ci = rand(0, 3)

$contextIntro = array(
  "Adults at a fitness center were asked how many group classes they attend per week.",
  "Students in a statistics class were asked how many days per week they eat breakfast.",
  "Shoppers at a grocery store were asked how many loyalty-program purchases they made last month.",
  "Patients at a dental clinic were asked how many cavities were found at their last checkup."
)
$colName = array("# of Group Classes per Week", "# of Breakfast Days per Week", "# of Loyalty Purchases", "# of Cavities Found")
$rowLabels = array("0", "1", "2", "3", "4")
$subject = array("adults", "students", "shoppers", "patients")
$unit = array("group classes per week", "breakfast days per week", "loyalty purchases", "cavities")
$sourceText = array("the fitness center's newsletter", "the statistics class's course blog", "the store's marketing report", "the clinic's patient-outcomes report")

$introText = $contextIntro[$ci]
$who = $subject[$ci]
$unitText = $unit[$ci]
$source = $sourceText[$ci]

$f1 = rand(10, 35)
$f2 = rand(8, 30)
$f3 = rand(5, 25)
$f4 = rand(4, 20)
$f5 = rand(2, 15)
$n = $f1 + $f2 + $f3 + $f4 + $f5

$freqs = array($f1, $f2, $f3, $f4, $f5)
$cums = array(0, 0, 0, 0, 0)
$cumCounts = array(0, 0, 0, 0, 0)
$run = 0
for ($i=0..4) {
  $run = $run + $freqs[$i]
  $cumCounts[$i] = $run
  $cums[$i] = $run / $n
}

$targetRow = rand(1, 4)
$rowLabelTarget = $rowLabels[$targetRow]
$freqTarget = $freqs[$targetRow]
$cumCountThrough = $cumCounts[$targetRow]

$statedPctRaw = $cums[$targetRow] * 100
$statedPct = round($statedPctRaw, 1)

$truePctRaw = $freqTarget / $n * 100
$truePctRounded = round($truePctRaw, 1)

$relTargetRounded = round($freqTarget / $n, 4)
$cumTargetRounded = round($cums[$targetRow], 4)

$answer[1] = $truePctRaw
$abstolerance[1] = 0.051
$showanswer[1] = $truePctRounded

$choices[0] = array(
  "The " . $statedPct . "% figure is the table's <b>cumulative</b> relative frequency through the row for " . $rowLabelTarget . " " . $unitText . " &mdash; it already includes every row at or below it, not just that one row's share.",
  "The " . $statedPct . "% figure is really a raw <b>count</b> from the table, mistakenly reported as if it were already a percent.",
  "The claim is correct as stated."
)
$answer[0] = 0

$sumBeforeStr = ''
for ($i=0..$targetRow) {
  if ($i > 0) {
    $sumBeforeStr = $sumBeforeStr . ' + '
  }
  $sumBeforeStr = $sumBeforeStr . $freqs[$i]
}

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">' . $colName[$ci] . '</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Frequency</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Relative Frequency</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Cumulative Relative Frequency</th></tr>'
for ($i=0..4) {
  $relCell = round($freqs[$i] / $n, 4)
  $cumCell = round($cums[$i], 4)
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $rowLabels[$i] . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $freqs[$i] . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $relCell . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cumCell . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:7px 16px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>' . $n . '</b></td><td style="border:1px solid #d9dee8; padding:7px 16px;"></td><td style="border:1px solid #d9dee8; padding:7px 16px;"></td></tr>'
$tableHtml = $tableHtml . '</table>'

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
      <p><span class="term-label">Step 1 &mdash; Find where the cumulative column comes from.</span> The cumulative relative frequency through the row for ' . $rowLabelTarget . ' ' . $unitText . ' adds up every frequency at or below that row: ' . $sumBeforeStr . ' = ' . $cumCountThrough . ' out of ' . $n . '. That gives ' . $cumCountThrough . ' &divide; ' . $n . ' &approx; ' . $cumTargetRounded . ', or <b>' . $statedPct . '%</b> &mdash; exactly the number the claim used.</p>
      <p><span class="term-label">Step 2 &mdash; Find that row\'s own share.</span> The row for ' . $rowLabelTarget . ' ' . $unitText . ' has a frequency of ' . $freqTarget . ' by itself, out of ' . $n . ' total: ' . $freqTarget . ' &divide; ' . $n . ' &approx; ' . $relTargetRounded . ', or <b>' . $truePctRounded . '%</b>.</p>
      <p><span class="term-label">Step 3 &mdash; Diagnose the claim.</span> The claim reported ' . $statedPct . '%, but that is the <b>cumulative</b> relative frequency through the row, which counts every row at or below it &mdash; not the individual relative frequency for that row alone. The correct diagnosis is that the stated figure is cumulative, not individual.</p>
      <p><span class="term-label">Step 4 &mdash; The true percent.</span> Only ' . $truePctRounded . '% of ' . $who . ' reported a value of exactly ' . $rowLabelTarget . ' ' . $unitText . '.</p>
      <p><b>Answers:</b> a) the stated figure is the cumulative relative frequency, not the individual relative frequency; b) ' . $truePctRounded . '%.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText In all, <b>$n</b> $who were surveyed. The full results are shown below.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$source summarized the survey with this claim:</p>
    <p style="margin:10px 0 0 0; font-style:italic; padding:10px 14px; background:#f6f8fc; border-radius:8px; border:1px solid #e5e7eb;">&ldquo;$statedPct% of $who reported a value of exactly $rowLabelTarget $unitText.&rdquo;</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which of the following correctly diagnoses the claim? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>true percent</b> of $who who reported a value of exactly $rowLabelTarget $unitText? Enter the percent rounded to <b>one decimal place</b>, without the % sign. $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
