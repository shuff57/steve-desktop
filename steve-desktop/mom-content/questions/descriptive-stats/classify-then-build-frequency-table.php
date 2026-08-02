// === NAME - DESCRIPTION: Classify Then Build a Frequency Table - Classify the level of measurement of a 1-5 satisfaction rating, then read its frequency, relative frequency, and cumulative relative frequency straight off a raw list of responses ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number", "number", "number")

// Randomize the business, the service being rated, and what to call the respondents.
$bizName = array("Riverside Diner", "BrightWave Internet", "Trailhead Outfitters", "Cedar Dental Clinic")
$serviceLabel = array("their most recent meal", "their internet service this month", "their guided sunrise hike", "their most recent visit")
$whoLabel = array("diners", "customers", "hikers", "patients")
$ci = rand(0, 3)
$biz = $bizName[$ci]
$svc = $serviceLabel[$ci]
$who = $whoLabel[$ci]

// Raw data: n responses, each a satisfaction rating from 1 to 5.
$n = rand(15, 18)
$n1 = $n - 1
$vals = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)
$counts = array(0,0,0,0,0,0)
for ($i=0..$n1) {
  $v = rand(1, 5)
  $vals[$i] = $v
  $counts[$v] = $counts[$v] + 1
}

// The target rating is pulled from an actual response, so its frequency is never zero.
$tv = $vals[rand(0, $n1)]
$freq = $counts[$tv]
$relFreq = $freq / $n
$relRounded = round($relFreq, 3)

$cumCount = 0
for ($v=1..$tv) {
  $cumCount = $cumCount + $counts[$v]
}
$cumRel = $cumCount / $n
$cumRounded = round($cumRel, 3)

// The raw response list, in the order it was recorded.
$rawStr = ""
$sep = ""
for ($i=0..$n1) {
  $rawStr = $rawStr . $sep . $vals[$i]
  $sep = "; "
}

// Part (a): classify the level of measurement of the rating variable.
$scaleNames = array("Nominal", "Ordinal", "Interval", "Ratio")
$questions[0] = $scaleNames
$answer[0] = 1

$answer[1] = $freq
$answerformat[1] = "integer"

$answer[2] = $relFreq
$abstolerance[2] = 0.0011
$showanswer[2] = $relRounded

$answer[3] = $cumRel
$abstolerance[3] = 0.0011
$showanswer[3] = $cumRounded

// A completed tally, for the solution guide.
$fullCum = 0
$tallyRows = ""
for ($v=1..5) {
  $fullCum = $fullCum + $counts[$v]
  $rowRel = round($counts[$v] / $n, 3)
  $rowCum = round($fullCum / $n, 3)
  $tallyRows = $tallyRows . '<tr><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $v . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $counts[$v] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $rowRel . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $rowCum . '</td></tr>'
}

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
      <p><span class="term-label">a. Level of measurement:</span> the ratings are ordered &mdash; a 5 reflects more satisfaction than a 4 &mdash; so this is at least ordinal. But the gap from a 3 to a 4 is not a measured quantity, and there is no reason to think it equals the gap from a 4 to a 5; a rating scale like this has no true zero either. Differences cannot be calculated and ratios are not meaningful, so before this list can be treated as raw frequency-table data it has to be classified as <b>Ordinal</b>, not Interval or Ratio.</p>
      <p><span class="term-label">b. Frequency of a rating of ' . $tv . ':</span> count how many of the ' . $n . ' responses equal ' . $tv . ' in the raw list. That count is <b>' . $freq . '</b>.</p>
      <p><span class="term-label">c. Relative frequency of a rating of ' . $tv . ':</span> divide that frequency by the total number of responses: ' . $freq . ' &divide; ' . $n . ' &approx; <b>' . $relRounded . '</b>.</p>
      <p><span class="term-label">d. Cumulative relative frequency through a rating of ' . $tv . ':</span> add the frequencies of every rating at or below ' . $tv . ', then divide by ' . $n . '. That gives ' . $cumCount . ' &divide; ' . $n . ' &approx; <b>' . $cumRounded . '</b>, the proportion of ' . $who . ' who rated ' . $tv . ' or lower.</p>
      <p style="margin-top:1em;"><b>The completed frequency table:</b></p>
      <table style="border-collapse:collapse; margin:10px 0; font-family:Arial,sans-serif; font-size:14px;">
        <tr style="background:#eef2f7;"><th style="border:1px solid #ccc; padding:6px 12px;">Rating</th><th style="border:1px solid #ccc; padding:6px 12px;">Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Relative Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Cumulative Relative Frequency</th></tr>
        ' . $tallyRows . '
      </table>
      <p><b>The usual trap:</b> because the responses are written as numbers, it is tempting to average them or call the scale interval or ratio. The number is a stand-in for a ranked label, not a quantity with equal-sized steps &mdash; that is what makes it ordinal, and it is exactly what has to be decided before the frequency, relative frequency, and cumulative relative frequency columns mean anything.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$biz surveyed $n $who, asking each to rate their satisfaction with $svc on a scale from 1 (very dissatisfied) to 5 (very satisfied). The responses, in the order they were recorded, were:</p>
    <p style="margin:12px 0 0 0; font-family:'DM Mono',monospace;">$rawStr</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>level of measurement</b> of this satisfaction rating? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> From the raw list, find the <b>frequency</b> of a rating of $tv &mdash; how many of the $n $who gave that rating. Enter a whole number. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the <b>relative frequency</b> of a rating of $tv. Enter a decimal rounded to <b>three decimal places</b>. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Find the <b>cumulative relative frequency</b> through a rating of $tv &mdash; the proportion of $who whose rating was $tv or lower. Enter a decimal rounded to <b>three decimal places</b>. $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
