// === NAME - DESCRIPTION: Back-Solve Frequency from Cumulative Relative Frequency - Given a 5-row frequency table where the cumulative relative frequency for row 3 is known but the frequency for row 3 is missing, find the missing frequency, compute relative frequency, complete the cumulative column, and find percents ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "number")

$ci = rand(0, 3)

$contextIntro = array(
  "Adults with gum disease were asked how many times per week they used to floss before their diagnosis.",
  "Adults at a fitness center were asked how many times per week they exercise.",
  "Students in a statistics class were asked how many siblings they have.",
  "College students were asked how many hours they sleep on a typical night."
)
$colName = array("# of Times Flossing per Week", "# of Exercise Sessions per Week", "# of Siblings", "# of Hours of Sleep")
$rowLabels = array("0", "1", "2", "3", "4")
$subject = array("adults", "adults", "students", "students")
$unit = array("times per week", "sessions per week", "siblings", "hours")

$f1 = rand(10, 35)
$f2 = rand(8, 30)
$f3 = rand(5, 25)
$f4 = rand(4, 20)
$f5 = rand(2, 15)
$n = $f1 + $f2 + $f3 + $f4 + $f5

$cum3_raw = ($f1 + $f2 + $f3) / $n
$cum3 = round($cum3_raw, 4)

$relFreq2 = $f2 / $n
$cum4 = ($f1 + $f2 + $f3 + $f4) / $n
$pctRow4 = $f4 / $n * 100
$pctAtMost4 = $cum4 * 100

$answer[0] = $f3
$answerformat[0] = "integer"

$answer[1] = $relFreq2
$abstolerance[1] = 0.0011

$answer[2] = $cum4
$abstolerance[2] = 0.0011

$answer[3] = $pctRow4
$abstolerance[3] = 0.051

$answer[4] = $pctAtMost4
$abstolerance[4] = 0.051

$introText = $contextIntro[$ci]
$who = $subject[$ci]
$unitText = $unit[$ci]

$freqs = array($f1, $f2, $f3, $f4, $f5)
$cums = array(0, 0, 0, 0, 0)
$run = 0
for ($i=0..4) {
  $run = $run + $freqs[$i]
  $cums[$i] = round($run / $n, 4)
}

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">' . $colName[$ci] . '</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Frequency</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Cumulative Relative Frequency</th></tr>'
for ($i=0..4) {
  $freqCell = $freqs[$i]
  $cumCell = ''
  if ($i == 2) {
    $freqCell = '<b style="color:#1865f2;">?</b>'
    $cumCell = $cums[$i]
  }
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $rowLabels[$i] . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $freqCell . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cumCell . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:7px 16px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>' . $n . '</b></td><td style="border:1px solid #d9dee8; padding:7px 16px;"></td></tr>'
$tableHtml = $tableHtml . '</table>'

$knownSum = $f1 + $f2 + $f4 + $f5
$knownStr = $f1 . " + " . $f2 . " + " . $f4 . " + " . $f5
$cum3Count = $f1 + $f2 + $f3
$relRounded2 = round($relFreq2, 3)
$cumRounded4 = round($cum4, 3)
$pctRow4Rounded = round($pctRow4, 1)
$pctAtMost4Rounded = round($pctAtMost4, 1)
$f1plusf2 = $f1 + $f2
$f1plusf2plusf3 = $f1 + $f2 + $f3
$f1plusf2plusf3plusf4 = $f1 + $f2 + $f3 + $f4

$solTable = '<table style="border-collapse:collapse; margin:10px 0; font-family:Arial,sans-serif; font-size:14px;">'
$solTable = $solTable . '<tr style="background:#eef2f7;"><th style="border:1px solid #ccc; padding:6px 12px; text-align:left;">' . $colName[$ci] . '</th><th style="border:1px solid #ccc; padding:6px 12px;">Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Relative Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Cumulative Relative Frequency</th></tr>'
$sRun = 0
for ($i=0..4) {
  $sRun = $sRun + $freqs[$i]
  $sRowRel = round($freqs[$i] / $n, 4)
  $sRowCum = round($sRun / $n, 4)
  $solTable = $solTable . '<tr><td style="border:1px solid #ccc; padding:6px 12px;">' . $rowLabels[$i] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $freqs[$i] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $sRowRel . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $sRowCum . '</td></tr>'
}
$solTable = $solTable . '</table>'

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
      <p><span class="term-label">a. The missing frequency for row 2 (value of 2):</span> the cumulative relative frequency through row 2 is ' . $cum3 . ', which means the cumulative count through row 2 is ' . $cum3 . ' &times; ' . $n . ' &approx; ' . $cum3Count . '. The known frequencies for rows 0 and 1 are ' . $f1 . ' and ' . $f2 . ', so the missing frequency is ' . $cum3Count . ' &minus; ' . $f1plusf2 . ' = <b>' . $f3 . '</b>.</p>
      <p><span class="term-label">b. Relative frequency for row 1 (value of 1):</span> divide the frequency of that row by the total. Row 1 has a frequency of ' . $f2 . ', so ' . $f2 . ' &divide; ' . $n . ' &approx; <b>' . $relRounded2 . '</b>.</p>
      <p><span class="term-label">c. Cumulative relative frequency through row 3 (value of 3):</span> add the frequencies from row 0 through row 3, then divide by the total. ' . $f1 . ' + ' . $f2 . ' + ' . $f3 . ' + ' . $f4 . ' = ' . $f1plusf2plusf3plusf4 . ', and ' . $f1plusf2plusf3plusf4 . ' &divide; ' . $n . ' &approx; <b>' . $cumRounded4 . '</b>.</p>
      <p><span class="term-label">d. Percent for row 3 (value of 3):</span> multiply the relative frequency of that row by 100. The relative frequency for row 3 is ' . $f4 . ' &divide; ' . $n . ' &approx; ' . round($f4/$n, 4) . ', and ' . round($f4/$n, 4) . ' &times; 100 = <b>' . $pctRow4Rounded . '%</b>.</p>
      <p><span class="term-label">e. Percent for at most row 3 (value of 3):</span> multiply the cumulative relative frequency through row 3 by 100. ' . $cumRounded4 . ' &times; 100 = <b>' . $pctAtMost4Rounded . '%</b>.</p>
      <p style="margin-top:1em;"><b>The completed table:</b></p>
      ' . $solTable . '
      <p><b>Check:</b> the cumulative relative frequency column must end at 1.000, and the Frequency column must add back to ' . $n . '. If either misses, a row was miscounted or a division used the wrong total.</p>
      <p><b>The usual trap:</b> the cumulative relative frequency is a running total of the relative frequencies, not a count. Dividing by ' . $n . ' happens once per row, never twice.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText</p>
    <p style="margin:12px 0 0 0;">In all, <b>$n</b> $who were surveyed. The (incomplete) results are shown below. The cumulative relative frequency for the row with value <b>2</b> is given, but the frequency for that row is missing.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>missing frequency</b> for the row with value <b>2</b> $unitText. Enter a whole number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>relative frequency</b> for the row with value <b>1</b> $unitText. Enter a decimal rounded to <b>three decimal places</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the <b>cumulative relative frequency</b> through the row with value <b>3</b> $unitText &mdash; the proportion of all $n $who in that row or any row below it. Enter a decimal rounded to <b>three decimal places</b>. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What <b>percent</b> of $who reported a value of <b>3</b> $unitText? Enter the percent rounded to <b>one decimal place</b>, without the % sign. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> What <b>percent</b> of $who reported <b>at most 3</b> $unitText? Enter the percent rounded to <b>one decimal place</b>, without the % sign. $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
