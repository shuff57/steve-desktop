// === NAME - DESCRIPTION: Grouped Frequency Table and Graphs - Read frequencies, relative frequencies, and cumulative relative frequencies from a grouped frequency table of CEO ages, and identify which graph shape corresponds to each type ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "choices")

$lowersets = array(
  array(38, 43, 48, 53, 58, 63, 68),
  array(40, 45, 50, 55, 60, 65, 70),
  array(42, 47, 52, 57, 62, 67, 72)
)
$iset = rand(0, 2)
$lowers = $lowersets[$iset]

$freqsets = array(
  array(3, 11, 13, 16, 10, 6, 1),
  array(4, 8, 15, 18, 9, 4, 2),
  array(5, 9, 12, 14, 11, 7, 2),
  array(2, 7, 14, 20, 12, 3, 2)
)
$fidx = rand(0, 3)
$freqs = $freqsets[$fidx]
$total = 60

$intLabels = array()
$ansA = 0
$ansB = 0
$ansC = 0
$ansDcum = 0
for ($i=0..6) {
  $lo = $lowers[$i]
  $hi = $lo + 4
  $intLabels[$i] = $lo . "&ndash;" . $hi
  if ($hi >= 54 && $lo <= 65) {
    $ansA = $ansA + $freqs[$i]
  }
  if ($lo >= 65) {
    $ansB = $ansB + $freqs[$i]
  }
  if ($hi < 50) {
    $ansC = $ansC + $freqs[$i]
  }
  if ($hi < 55) {
    $ansDcum = $ansDcum + $freqs[$i]
  }
}
$ansBdec = round($ansB / $total, 4)
$ansCdec = round($ansC / $total, 4)
$ansDdec = round($ansDcum / $total, 4)
$ansBpct = round($ansB / $total * 100, 2)
$ansCpct = round($ansC / $total * 100, 2)
$ansDpct = round($ansDcum / $total * 100, 2)

$answer[0] = $ansA
$answer[1] = $ansBdec
$answer[2] = $ansCdec
$answer[3] = $ansDdec

$descPairs = array(
  array(
    "rises to a peak and then falls away",
    "climbs steadily without ever dropping, and the last bar reaches the top of the scale"
  ),
  array(
    "increases to a single highest bar and then decreases",
    "never goes down &mdash; each bar is at least as tall as the one before it, and the last bar reaches 1"
  )
)
$dpick = rand(0, 1)
$descs = $descPairs[$dpick]
$swap = rand(0, 1)
if ($swap == 0) {
  $graphADesc = $descs[0]
  $graphBDesc = $descs[1]
  $answer[4] = 0
} else {
  $graphADesc = $descs[1]
  $graphBDesc = $descs[0]
  $answer[4] = 1
}

$choices[4] = array(
  "Graph A shows the relative frequency and Graph B shows the cumulative relative frequency.",
  "Graph A shows the cumulative relative frequency and Graph B shows the relative frequency."
)
$noshuffle[4] = "all"

$tableRows = ""
for ($i=0..6) {
  $tableRows = $tableRows . "<tr><td>" . $intLabels[$i] . "</td><td>" . $freqs[$i] . "</td></tr>"
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
      <p><strong>Step 1 &mdash; Confirm the sample size.</strong> The frequencies add to ' . $total . ', which matches the ' . $total . ' CEOs in the sample.</p>
      <p><strong>Step 2 &mdash; Part (a): frequency for ages between 54 and 65.</strong> Add the frequencies of the intervals that overlap with 54 to 65: <b>' . $ansA . '</b>.</p>
      <p><strong>Step 3 &mdash; Part (b): relative frequency of CEOs 65 or older.</strong> The total frequency for ages 65 and older is ' . $ansB . '. Divide by the total: ' . $ansB . '/' . $total . ' = ' . $ansBdec . ' (' . $ansBpct . '%).</p>
      <p><strong>Step 4 &mdash; Part (c): relative frequency of ages under 50.</strong> The total frequency for ages under 50 is ' . $ansC . '. Divide by the total: ' . $ansC . '/' . $total . ' = ' . $ansCdec . ' (' . $ansCpct . '%).</p>
      <p><strong>Step 5 &mdash; Part (d): cumulative relative frequency for CEOs younger than 55.</strong> The total frequency for ages under 55 is ' . $ansDcum . '. Divide by the total: ' . $ansDcum . '/' . $total . ' = ' . $ansDdec . ' (' . $ansDpct . '%).</p>
      <p><strong>Step 6 &mdash; Part (e): identify the graphs.</strong> A relative-frequency graph shows each interval on its own, so its bars rise and then fall with the data. A cumulative graph can never go down &mdash; each bar includes everything before it &mdash; so its bars climb steadily and the last bar reaches 1.</p>
      <p><strong>Answers:</strong> (a) ' . $ansA . ', (b) ' . $ansBdec . ', (c) ' . $ansCdec . ', (d) ' . $ansDdec . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">The table below shows the ages of the chief executive officers for the first $total ranked small businesses.</p>
    <table style="border-collapse:collapse; width:100%; margin:10px 0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:8px; text-align:center; font-weight:700;">Age</th>
          <th style="border:1px solid #d1d5db; padding:8px; text-align:center; font-weight:700;">Frequency</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;"><strong>Two graphs were produced from this table.</strong></p>
    <p style="margin:0 0 5px 0;"><b>Graph A</b> $graphADesc.</p>
    <p style="margin:0 0 10px 0;"><b>Graph B</b> $graphBDesc.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;"><strong>Use the table to answer the following questions.</strong></p>
    <p style="margin:0 0 5px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the frequency for CEO ages between 54 and 65? $answerbox[0]</p>
    <p style="margin:0 0 5px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the relative frequency (as a decimal) of CEOs who are 65 years or older? $answerbox[1]</p>
    <p style="margin:0 0 5px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the relative frequency (as a decimal) of CEOs under 50? $answerbox[2]</p>
    <p style="margin:0 0 5px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What is the cumulative relative frequency (as a decimal) for CEOs younger than 55? $answerbox[3]</p>
    <p style="margin:0 0 5px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Which statement correctly identifies the two graphs? $answerbox[4]</p>
  </div>
</div>

// === ANSWER ===

$solutionguide
