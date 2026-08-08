// === NAME - DESCRIPTION: Outliers and Empty Rows in a Stemplot - One value sits far above a tight cluster; read the outlier off the plot, count the stem rows with no leaves, compute the range, and say why the empty rows must still be drawn ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
$contexts = array(
  "the daily high temperature, in degrees Fahrenheit, in one town for a month",
  "the number of minutes each member of a walking group spent walking on one day"
)
$units = array("&deg;F", "minutes")
$unitWords = array("degrees Fahrenheit", "minutes")
$context = $contexts[$ci]
$unit = $units[$ci]
$unitWord = $unitWords[$ci]

// The cluster occupies two adjacent stems, walked digit by digit so no value repeats more than
// twice and neither cluster row can come out empty.
$lo = rand(4, 5)
$mid = $lo + 1

$n = 0
$minVal = 99
$cnt = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
$leafText = array("", "", "", "", "", "", "", "", "", "")

for ($s=$lo..$mid) {
  $f1 = rand(0, 4)
  $f2 = rand(5, 9)
  for ($d=0..9) {
    $r = rand(0, 9)
    $take = 0
    if ($r < 5) { $take = 1 }
    if ($d == $f1) { $take = 1 }
    if ($d == $f2) { $take = 1 }
    if ($take == 1) {
      $v = 10 * $s + $d
      $leafText[$s] = $leafText[$s] . $d . "&nbsp;&nbsp;"
      $cnt[$s] = $cnt[$s] + 1
      $n = $n + 1
      if ($v < $minVal) { $minVal = $v }
    }
  }
}

// One record value, far enough above the cluster to leave at least one blank stem row behind it.
$oStem = rand($mid + 2, 9)
$oLeaf = rand(0, 9)
$outlier = 10 * $oStem + $oLeaf
$leafText[$oStem] = $leafText[$oStem] . $oLeaf . "&nbsp;&nbsp;"
$cnt[$oStem] = $cnt[$oStem] + 1
$n = $n + 1

// Rows run unbroken from the smallest stem to the largest, gaps included -- that is the point.
$emptyRows = 0
$stemRows = ""
for ($s=$lo..$oStem) {
  if ($cnt[$s] == 0) { $emptyRows = $emptyRows + 1 }
  $stemRows = $stemRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; font-weight:700; background:#f8fafc;">' . $s . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; font-family:ui-monospace,Menlo,Consolas,monospace; letter-spacing:1px; min-width:220px;">' . $leafText[$s] . '</td></tr>'
}

$range = $outlier - $minVal
$clusterTop = 10 * $mid + 9
$keyExample = 10 * $lo + 4

$questions[3] = array(
  "They show the gap. Dropping them would butt the record value up against the cluster and hide how unusual it is.",
  "They are required only so that the number of rows matches the number of data values.",
  "They mark where values were lost, so the reader knows the data set is incomplete.",
  "They are optional formatting; a stemplot may skip any stem that has no leaves."
)
$answer[3] = 0

$answer[0] = $outlier
$answerformat[0] = "integer"

$answer[1] = $emptyRows
$answerformat[1] = "integer"

$answer[2] = $range
$answerformat[2] = "integer"

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
      <p><span class="term-label">Step 1 &mdash; find the outlier.</span> Almost every value sits on stems ' . $lo . ' and ' . $mid . ', so the bulk of the data runs from ' . $minVal . ' to ' . $clusterTop . ' ' . $unitWord . '. One lone leaf sits far above the rest of the plot, on stem ' . $oStem . ' with leaf ' . $oLeaf . ' &mdash; the value <b>' . $outlier . '</b>. A stemplot makes an outlier obvious because the isolated leaf has empty rows on either side of it.</p>
      <p><span class="term-label">Step 2 &mdash; count the blank rows.</span> The stems must run unbroken from ' . $lo . ' to ' . $oStem . '. Of those rows, <b>' . $emptyRows . '</b> carry no leaves at all. Those blank rows are the gap between the everyday values and the record one.</p>
      <p><span class="term-label">Step 3 &mdash; compute the range.</span> The range is the largest value minus the smallest. Both are still readable off the plot exactly: ' . $outlier . ' &minus; ' . $minVal . ' = <b>' . $range . '</b> ' . $unitWord . '. Note how much one unusual value inflates it &mdash; without the outlier the spread would be only ' . ($clusterTop - $minVal) . '.</p>
      <p><span class="term-label">Step 4 &mdash; why keep the blank rows.</span> A stemplot puts values on a real number line. Skipping the empty stems would pull the record value down next to the cluster and make it look ordinary, which is exactly the thing the display is supposed to reveal.</p>
      <p><b>Answer:</b> (a) ' . $outlier . ' &nbsp;&nbsp; (b) ' . $emptyRows . ' &nbsp;&nbsp; (c) ' . $range . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">The stem-and-leaf plot below shows $context. There are $n measurements in all.</p>
    <table style="border-collapse:collapse; margin:10px 0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">Stem</th>
          <th style="border:1px solid #d1d5db; padding:8px 14px; text-align:left;">Leaf</th>
        </tr>
      </thead>
      <tbody>
        $stemRows
      </tbody>
    </table>
    <p style="margin:8px 0 0 0; font-size:14px; color:#666;">Key: a stem of $lo with a leaf of 4 means $keyExample $unit.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> One measurement sits well away from all the others. What is its value? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many rows of the plot have no leaves on them at all? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the range of the data, that is, the largest value minus the smallest? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Why must the rows with no leaves still be drawn in the plot? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
