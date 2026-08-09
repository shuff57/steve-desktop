// === NAME - DESCRIPTION: Five-Number Summary from an Unsorted Data Set - Sort a set of readings recorded out of order, find the two quartiles and the median, and compute the interquartile range that spans the middle half of the data ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A coach recorded how many minutes each player was on the field during one match."
  $unitWord = "minutes"
  $thing = "players"
}
else {
  $intro = "A garage recorded how many minutes each service appointment took on one day."
  $unitWord = "minutes"
  $thing = "appointments"
}

// n = 19 on purpose. Then n + 1 = 20, so the quartile positions are i = 5, 10 and 15 -- all whole
// numbers, so no two values ever need averaging. That matters here more than anywhere else:
// software does not all use the same quartile convention, and a data set where the position lands
// between two values can give a calculator one answer and the textbook formula another. At n = 19 the
// (n+1) rule and the split-at-the-median rule agree exactly, so the tool and the method cannot
// disagree and a student is never punished for trusting whatever tool they used.
$n = 19
$sorted = array()
$v = rand(12, 25)
for ($j=0..18) {
  $sorted[$j] = $v
  $v = $v + rand(2, 7)
}

$q1 = $sorted[4]
$med = $sorted[9]
$q3 = $sorted[14]
$iqr = $q3 - $q1
$minV = $sorted[0]
$maxV = $sorted[18]

// Presented out of order, so sorting is real work the tool does rather than something already done.
// Stepping by 7 through 19 positions visits every one exactly once, since 7 and 19 share no factor.
$valueList = ""
for ($j=0..18) {
  $idx = (7 * $j) % 19
  if ($j == 0) { $valueList = "" . $sorted[$idx] }
  if ($j > 0) { $valueList = $valueList . ", " . $sorted[$idx] }
}

$answer[0] = $q1
$reltolerance[0] = 0.01
$abstolerance[0] = 0.05

$answer[1] = $med
$reltolerance[1] = 0.01
$abstolerance[1] = 0.05

$answer[2] = $q3
$reltolerance[2] = 0.01
$abstolerance[2] = 0.05

$answer[3] = $iqr
$reltolerance[3] = 0.01
$abstolerance[3] = 0.05

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
      <p><span class="term-label">Sort first.</span> Put the ' . $n . ' values in order and the five-number summary reads straight off: minimum <b>' . $minV . '</b>, `Q_1` <b>' . $q1 . '</b>, median <b>' . $med . '</b>, `Q_3` <b>' . $q3 . '</b>, maximum <b>' . $maxV . '</b>. Sorting is the slow part and the part where mistakes happen, so it is worth letting a calculator do it and spending your attention on the positions.</p>
      <p><span class="term-label">Checking it by hand.</span> With `n = ' . $n . '`, `n + 1 = 20`, so `Q_1` is at position `(25/100)(20) = 5`, the median at position 10, and `Q_3` at position 15. All three are whole numbers, so each quartile is simply the value sitting at that position in the sorted list &mdash; nothing needs averaging. Worth knowing why that is convenient: statistics packages do not all use the same quartile convention, and on a data set where a position falls between two values they can legitimately disagree with each other. Here they cannot.</p>
      <p><span class="term-label">The interquartile range.</span> `"IQR" = Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ` <b>' . $iqr . '</b> ' . $unitWord . '.</p>
      <p><span class="term-label">What it tells you.</span> A quarter of the ' . $thing . ' came in below ' . $q1 . ', a quarter above ' . $q3 . ', and the middle half sits between them &mdash; a span of ' . $iqr . ' ' . $unitWord . '. Because it ignores the top and bottom quarters entirely, one unusually large value cannot stretch it, which is what makes the `"IQR"` the spread to quote when a data set has outliers.</p>
      <p><b>Answer:</b> `Q_1` = ' . $q1 . ' &nbsp;&nbsp; median = ' . $med . ' &nbsp;&nbsp; `Q_3` = ' . $q3 . ' &nbsp;&nbsp; `"IQR"` = ' . $iqr . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$intro The $n readings, in $unitWord, are listed below in the order they were recorded.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> First quartile, `Q_1` = $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Median = $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Third quartile, `Q_3` = $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Interquartile range, `"IQR"` = $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
