// === NAME - DESCRIPTION: Percentages From a Relative-Frequency Table That Falls Short of 1 - A poll's income bands are given as relative frequencies adding to less than 1; find the missing not-sure share, total adjacent bands, and explain the shortfall ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$b0 = "Under &#36;20,000"
$b1 = "&#36;20,000 to &#36;29,999"
$b2 = "&#36;30,000 to &#36;39,999"
$b3 = "&#36;40,000 to &#36;49,999"
$b4 = "&#36;50,000 to &#36;74,999"
$b5 = "&#36;75,000 or more"
$labels = array($b0, $b1, $b2, $b3, $b4, $b5)

$total = rand(88, 97)
$shares = array(0, 0, 0, 0, 0, 0)
$remaining = $total
for ($k=0..4) {
  $left = 5 - $k
  $cap = $remaining - $left
  if ($cap > 24) { $cap = 24 }
  if ($cap < 1) { $cap = 1 }
  $shares[$k] = rand(1, $cap)
  $remaining = $remaining - $shares[$k]
}
$shares[5] = $remaining

$rfStr = array("", "", "", "", "", "")
for ($k=0..5) {
  if ($shares[$k] < 10) { $rfStr[$k] = "0.0" . $shares[$k] }
  else { $rfStr[$k] = "0." . $shares[$k] }
}

$tableRows = ""
for ($k=0..5) {
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 16px;">' . $labels[$k] . '</td><td style="border:1px solid #d1d5db; padding:6px 16px; text-align:center;">' . $rfStr[$k] . '</td></tr>'
}

$notSure = 100 - $total
if ($notSure < 10) { $notSureStr = "0.0" . $notSure }
else { $notSureStr = "0." . $notSure }

$spanLen = 2
if (rand(0, 1) == 1) { $spanLen = 3 }
$spanCountWord = "two"
if ($spanLen == 3) { $spanCountWord = "three" }
$spanStart = rand(0, 6 - $spanLen)
$spanEnd = $spanStart + $spanLen - 1

$spanSum = 0
for ($k=0..5) {
  if ($k >= $spanStart && $k <= $spanEnd) { $spanSum = $spanSum + $shares[$k] }
}
if ($spanSum < 10) { $spanSumStr = "0.0" . $spanSum }
else { $spanSumStr = "0." . $spanSum }

$spanLowLabel = $labels[$spanStart]
$spanHighLabel = $labels[$spanEnd]
$spanRangeText = $spanLowLabel . " through " . $spanHighLabel

$spanMid = $spanStart + 1
if ($spanLen == 2) { $spanExpr = $rfStr[$spanStart] . ' + ' . $rfStr[$spanEnd] }
else { $spanExpr = $rfStr[$spanStart] . ' + ' . $rfStr[$spanMid] . ' + ' . $rfStr[$spanEnd] }

$sumExpr = $rfStr[0] . ' + ' . $rfStr[1] . ' + ' . $rfStr[2] . ' + ' . $rfStr[3] . ' + ' . $rfStr[4] . ' + ' . $rfStr[5]
$totalStr = "0." . $total

$answer[0] = $notSure
$answerformat[0] = "integer"

$answer[1] = $spanSum
$answerformat[1] = "integer"

$c0 = "Some respondents did not name a salary band at all, so they are not counted in any row: the bands only cover people who gave an answer."
$c1 = "The table has an error: one of the salary bands must be missing from the list."
$c2 = "Rounding each relative frequency to two decimal places is what makes the column fall short of 1."
$c3 = "Relative frequencies in a table like this never have to add up to 1."
$questions[2] = array($c0, $c1, $c2, $c3)
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
      <p><span class="term-label">Step 1: add up the six shown relative frequencies.</span> ' . $sumExpr . ' = <b>' . $totalStr . '</b>. That is the share of the FULL sample accounted for by the six salary bands.</p>
      <p><span class="term-label">Step 2: the rest said &ldquo;not sure.&rdquo;</span> Every respondent is somewhere: either in one of the six bands, or in the not-sure group. So 1.00 &minus; ' . $totalStr . ' = <b>' . $notSureStr . '</b>, which is <b>' . $notSure . '%</b>.</p>
      <p><span class="term-label">Step 3: add the ' . $spanCountWord . ' bands in the stated span.</span> ' . $spanExpr . ' = <b>' . $spanSumStr . '</b>, which is <b>' . $spanSum . '%</b> of the full sample.</p>
      <p><span class="term-label">Step 4: why the shortfall is not an error.</span> The bands only cover respondents who actually named an income. Someone who answered &ldquo;not sure&rdquo; gave no figure, so that response is not part of any band: it is simply outside the table. A relative-frequency column only has to sum to 1 when every observation falls in exactly one of the listed categories, and here one possible response (not sure) is not listed at all.</p>
      <p><b>Answer:</b> (a) ' . $notSure . '% &nbsp;&nbsp; (b) ' . $spanSum . '% &nbsp;&nbsp; (c) Some respondents gave no figure, so they fall in none of the bands.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A national poll asked a random sample of adults to name the annual household income they consider the threshold for &ldquo;middle class.&rdquo; Each respondent either named one of the salary bands below or said they were not sure. For each band, the table shows the relative frequency: the proportion of the FULL sample who named that band.</p>
    <table style="border-collapse:collapse; margin:0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:7px 16px; text-align:left;">Annual household income</th>
          <th style="border:1px solid #d1d5db; padding:7px 16px;">Relative frequency</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What percent of the full sample said they were not sure? (Give your answer as a number, without the percent sign.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What percent of the full sample named an income somewhere in the combined span of the $spanCountWord bands from $spanRangeText? (Give your answer as a number, without the percent sign.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The six relative frequencies shown add up to less than 1. Why is that not an error in the table? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
