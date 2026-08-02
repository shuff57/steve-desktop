// === NAME - DESCRIPTION: Audit Frequency Table Errors - Given raw data and a published frequency table with two deliberate frequency errors, correct the frequencies, compute fractions, and explain how the error likely happened ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "string")

$ci = rand(0, 2)

$vals = array(0, 2, 4, 5, 7, 10, 12, 15, 20)

$freqSets = array(
  array(2, 3, 1, 3, 2, 2, 2, 2, 2),
  array(1, 2, 2, 4, 1, 3, 2, 2, 2),
  array(3, 2, 1, 2, 3, 2, 1, 3, 2)
)

$rawDataSets = array(
  "0; 0; 2; 2; 2; 4; 5; 5; 5; 7; 7; 10; 10; 12; 12; 15; 15; 20; 20",
  "1; 1; 1; 3; 3; 4; 4; 6; 6; 6; 8; 8; 9; 9; 11; 11; 13; 14; 14",
  "0; 0; 0; 2; 2; 3; 3; 5; 5; 5; 5; 7; 7; 8; 8; 10; 10; 12; 12"
)

$sortedDataSets = array(
  "0, 0, 2, 2, 2, 4, 5, 5, 5, 7, 7, 10, 10, 12, 12, 15, 15, 20, 20",
  "1, 1, 1, 3, 3, 4, 4, 6, 6, 6, 8, 8, 9, 9, 11, 11, 13, 14, 14",
  "0, 0, 0, 2, 2, 3, 3, 5, 5, 5, 5, 7, 7, 8, 8, 10, 10, 12, 12"
)

$trueFreqs = $freqSets[$ci]
$rawData = $rawDataSets[$ci]
$sortedData = $sortedDataSets[$ci]

$e1 = rand(0, 8) where ($trueFreqs[$e1] >= 2)
$e2 = rand(0, 8) where ($e2 != $e1 && $trueFreqs[$e2] >= 2)

$pubFreqs = array(0, 0, 0, 0, 0, 0, 0, 0, 0)
for ($i=0..8) {
  $pubFreqs[$i] = $trueFreqs[$i]
}
$pubFreqs[$e1] = $trueFreqs[$e1] - 1
$pubFreqs[$e2] = $trueFreqs[$e2] - 1

$corr1 = $trueFreqs[$e1]
$corr2 = $trueFreqs[$e2]

$sum57 = $trueFreqs[3] + $trueFreqs[4]

$sumLT12 = $trueFreqs[0] + $trueFreqs[1] + $trueFreqs[2] + $trueFreqs[3] + $trueFreqs[4] + $trueFreqs[5]

$frac57 = $sum57 / 19
$fracLT12 = $sumLT12 / 19

$answer[0] = $corr1
$answerformat[0] = "integer"

$answer[1] = $corr2
$answerformat[1] = "integer"

$answer[2] = $frac57
$abstolerance[2] = 0.0011

$answer[3] = $fracLT12
$abstolerance[3] = 0.0011

$answer[4] = "The data was not sorted before tallying, so the person missed the second occurrence of each repeated value. or The person scanned the unsorted list and missed the second occurrence of each repeated value. or The tally was done on unsorted data, causing the person to overlook the duplicate values. or The error happened because the data was not sorted, so the person missed the second copy of each repeated value when tallying."
$strflags[4] = "ignore_case,trim_whitespace,compress_whitespace"

$e1val = $vals[$e1]
$e2val = $vals[$e2]
$e1pub = $pubFreqs[$e1]
$e2pub = $pubFreqs[$e2]
$e1true = $trueFreqs[$e1]
$e2true = $trueFreqs[$e2]

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">Years in US</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Frequency</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Relative Frequency</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Cumulative Relative Frequency</th></tr>'
$cumSum = 0
for ($i=0..8) {
  $cumSum = $cumSum + $pubFreqs[$i]
  $relF = $pubFreqs[$i] / 19
  $cumF = $cumSum / 19
  $relStr = round($relF, 4)
  $cumStr = round($cumF, 4)
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $vals[$i] . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $pubFreqs[$i] . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $relStr . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cumStr . '</td></tr>'
}
$tableHtml = $tableHtml . '</table>'

$solTable = '<table style="border-collapse:collapse; margin:10px 0; font-family:Arial,sans-serif; font-size:14px;">'
$solTable = $solTable . '<tr style="background:#eef2f7;"><th style="border:1px solid #ccc; padding:6px 12px; text-align:left;">Years in US</th><th style="border:1px solid #ccc; padding:6px 12px;">Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Relative Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Cumulative Relative Frequency</th></tr>'
$cumSum2 = 0
for ($i=0..8) {
  $cumSum2 = $cumSum2 + $trueFreqs[$i]
  $relF2 = $trueFreqs[$i] / 19
  $cumF2 = $cumSum2 / 19
  $relStr2 = round($relF2, 4)
  $cumStr2 = round($cumF2, 4)
  $solTable = $solTable . '<tr><td style="border:1px solid #ccc; padding:6px 12px;">' . $vals[$i] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $trueFreqs[$i] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $relStr2 . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $cumStr2 . '</td></tr>'
}
$solTable = $solTable . '</table>'

$frac57rounded = round($frac57, 4)
$fracLT12rounded = round($fracLT12, 4)

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
      <p><span class="term-label">Step 1 — Sort the raw data and count each value.</span> The unsorted list makes it easy to miss a repeated value. Sorting gives:</p>
      <p><b>' . $sortedData . '</b></p>
      <p>Counting each value gives the true frequencies shown in the corrected table below.</p>
      <p><span class="term-label">Step 2 — Compare with the published table.</span> The published table lists ' . $e1val . ' with frequency ' . $e1pub . ' and ' . $e2val . ' with frequency ' . $e2pub . '. The correct frequencies are ' . $e1true . ' and ' . $e2true . ' &mdash; each is one too low in the published version. The frequency column therefore sums to 17 instead of 19, and the cumulative relative frequencies that depend on those rows are also wrong.</p>
      <p><span class="term-label">Step 3 — How the error likely happened.</span> The raw data list is unsorted. Someone tallying by scanning left to right would catch the first occurrence of each repeated value but miss the second, especially when the two copies are far apart in the list. Sorting the data before tallying prevents this, and summing the frequency column against the sample size (19) catches it.</p>
      <p><span class="term-label">Step 4 — Fraction for 5 or 7 years.</span> Five years has frequency ' . $trueFreqs[3] . ' and seven years has frequency ' . $trueFreqs[4] . ', so ' . $trueFreqs[3] . ' + ' . $trueFreqs[4] . ' = ' . $sum57 . ' out of 19: <b>' . $sum57 . '/19</b> &approx; ' . $frac57rounded . '.</p>
      <p><span class="term-label">Step 5 — Fraction fewer than 12 years.</span> Values less than 12 are 0, 2, 4, 5, 7, and 10. Their frequencies add to ' . $sumLT12 . ' out of 19: <b>' . $sumLT12 . '/19</b> &approx; ' . $fracLT12rounded . '.</p>
      <p style="margin-top:1em;"><b>The corrected table:</b></p>
      ' . $solTable . '
      <p><b>Answers:</b> a) ' . $e1true . ', b) ' . $e2true . ', c) ' . $sum57 . '/19 &approx; ' . $frac57rounded . ', d) ' . $sumLT12 . '/19 &approx; ' . $fracLT12rounded . ', e) The error likely happened because the data was not sorted before tallying, so the person missed the second occurrence of each repeated value.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>Nineteen people</b> were asked how many years, to the nearest year, they have lived in the United States. The data are as follows:</p>
    <p style="margin:12px 0 0 0; font-family:monospace; font-size:15px; background:#f6f8fc; padding:10px 14px; border-radius:8px; border:1px solid #e5e7eb;">$rawData</p>
    <p style="margin:12px 0 0 0;">A student assistant compiled the responses into the frequency table below. The table <b>contains two errors</b> in the Frequency column.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>corrected frequency</b> for the value <b>$e1val</b> years? Enter a whole number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>corrected frequency</b> for the value <b>$e2val</b> years? Enter a whole number. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What <b>fraction</b> of the people surveyed have lived in the US for <b>5 or 7 years</b>? Enter a decimal rounded to <b>four decimal places</b>. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What <b>fraction</b> of the people surveyed have lived in the US for <b>fewer than 12 years</b>? Enter a decimal rounded to <b>four decimal places</b>. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> In <b>one sentence</b>, explain how the error in the frequency table likely happened. $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
