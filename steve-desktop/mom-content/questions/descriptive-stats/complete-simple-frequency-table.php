// === NAME - DESCRIPTION: Complete a Simple Frequency Table - Given a 3-row frequency table with one missing frequency, find the missing value, relative frequency, cumulative relative frequency, and percents ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "number")

$ci = rand(0, 3)

$colName = array("# of Courses", "# of Credit Hours", "# of Tutoring Sessions per Week", "# of Textbooks Purchased")
$contextIntro = array(
  "A college advisor surveyed part-time students, asking how many courses they were taking this term.",
  "A registrar surveyed part-time students, asking how many credit hours they were enrolled in this semester.",
  "A learning center surveyed students, asking how many tutoring sessions they attend per week.",
  "A bookstore surveyed students, asking how many textbooks they purchased this term."
)
$rowLabelsAll = array(
  array("1", "2", "3"),
  array("3", "6", "9"),
  array("1", "2", "3"),
  array("1", "2", "3")
)
$exactlyTwoText = array("exactly two courses", "exactly 6 credit hours", "exactly two sessions per week", "exactly two textbooks")
$oneOrTwoText = array("one or two courses", "3 or 6 credit hours", "one or two sessions per week", "one or two textbooks")

$n = rand(30, 80)
$f1 = rand(8, $n - 20)
$f2 = rand(6, $n - $f1 - 4)
$f3 = $n - $f1 - $f2

$mi = rand(0, 2)

$missing = 0
if ($mi == 0) { $missing = $f1 }
if ($mi == 1) { $missing = $f2 }
if ($mi == 2) { $missing = $f3 }

$relFreqRow2 = $f2 / $n
$cumRelRow2 = ($f1 + $f2) / $n
$pctExactlyTwo = $relFreqRow2 * 100
$pctOneOrTwo = $cumRelRow2 * 100

$answer[0] = $missing
$answerformat[0] = "integer"

$answer[1] = $relFreqRow2
$abstolerance[1] = 0.0011

$answer[2] = $cumRelRow2
$abstolerance[2] = 0.0011

$answer[3] = $pctExactlyTwo
$abstolerance[3] = 0.051

$answer[4] = $pctOneOrTwo
$abstolerance[4] = 0.051

$labels = $rowLabelsAll[$ci]
$introText = $contextIntro[$ci]
$exactlyTwo = $exactlyTwoText[$ci]
$oneOrTwo = $oneOrTwoText[$ci]

$freqs = array($f1, $f2, $f3)

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">' . $colName[$ci] . '</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Frequency</th></tr>'
for ($i=0..2) {
  $cell = $freqs[$i]
  if ($i == $mi) {
    $cell = '<b style="color:#1865f2;">?</b>'
  }
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $labels[$i] . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cell . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:7px 16px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>' . $n . '</b></td></tr>'
$tableHtml = $tableHtml . '</table>'

$knownStr = ""
$sep = ""
for ($i=0..2) {
  if ($i != $mi) {
    $knownStr = $knownStr . $sep . $freqs[$i]
    $sep = " + "
  }
}
$knownSum = $n - $missing

$relRounded = round($relFreqRow2, 3)
$cumRounded = round($cumRelRow2, 3)
$pctExactRounded = round($pctExactlyTwo, 1)
$pctOneTwoRounded = round($pctOneOrTwo, 1)
$f1plusf2 = $f1 + $f2
$rf1 = round($f1 / $n, 3)
$rf3 = round($f3 / $n, 3)

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
      <p><span class="term-label">a. The missing frequency:</span> the Frequency column must add to the total of ' . $n . '. The known frequencies add to ' . $knownStr . ' = ' . $knownSum . ', so the missing one is ' . $n . ' &minus; ' . $knownSum . ' = <b>' . $missing . '</b>.</p>
      <p><span class="term-label">b. Relative frequency for row 2:</span> divide the frequency of that row by the total. Row 2 has a frequency of ' . $f2 . ', so ' . $f2 . ' &divide; ' . $n . ' &approx; <b>' . $relRounded . '</b>.</p>
      <p><span class="term-label">c. Cumulative relative frequency through row 2:</span> add the frequencies of rows 1 and 2, then divide by the total. ' . $f1 . ' + ' . $f2 . ' = ' . $f1plusf2 . ', and ' . $f1plusf2 . ' &divide; ' . $n . ' &approx; <b>' . $cumRounded . '</b>.</p>
      <p><span class="term-label">d. Percent taking ' . $exactlyTwo . ':</span> multiply the relative frequency from part (b) by 100. ' . $relRounded . ' &times; 100 = <b>' . $pctExactRounded . '%</b>.</p>
      <p><span class="term-label">e. Percent taking ' . $oneOrTwo . ':</span> multiply the cumulative relative frequency from part (c) by 100. ' . $cumRounded . ' &times; 100 = <b>' . $pctOneTwoRounded . '%</b>.</p>
      <p style="margin-top:1em;"><b>The completed table:</b></p>
      <table style="border-collapse:collapse; margin:10px 0; font-family:Arial,sans-serif; font-size:14px;">
        <tr style="background:#eef2f7;"><th style="border:1px solid #ccc; padding:6px 12px; text-align:left;">' . $colName[$ci] . '</th><th style="border:1px solid #ccc; padding:6px 12px;">Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Relative Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Cumulative Relative Frequency</th></tr>
        <tr><td style="border:1px solid #ccc; padding:6px 12px;">' . $labels[0] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $f1 . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $rf1 . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $rf1 . '</td></tr>
        <tr><td style="border:1px solid #ccc; padding:6px 12px;">' . $labels[1] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $f2 . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $relRounded . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $cumRounded . '</td></tr>
        <tr><td style="border:1px solid #ccc; padding:6px 12px;">' . $labels[2] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $f3 . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $rf3 . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">1.000</td></tr>
      </table>
      <p><b>Check:</b> the cumulative relative frequency column must end at 1.000, and the Frequency column must add back to ' . $n . '. If either misses, a row was miscounted or a division used the wrong total.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText</p>
    <p style="margin:12px 0 0 0;">In all, <b>$n</b> students were surveyed. The (incomplete) results are shown below.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>missing frequency</b>. Enter a whole number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>relative frequency</b> for row 2. Enter a decimal rounded to <b>three decimal places</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the <b>cumulative relative frequency</b> through row 2. Enter a decimal rounded to <b>three decimal places</b>. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What <b>percent</b> of students take $exactlyTwo? Enter the percent rounded to <b>one decimal place</b>, without the % sign. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> What <b>percent</b> of students take $oneOrTwo? Enter the percent rounded to <b>one decimal place</b>, without the % sign. $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
