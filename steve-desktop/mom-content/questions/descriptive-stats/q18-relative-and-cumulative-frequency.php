// === NAME - DESCRIPTION: Relative and Cumulative Relative Frequency from a Grouped Table - Compute the relative frequency of one class, the cumulative relative frequency through that class, and recover a missing frequency from the known total ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

// Randomize the context: what was measured, the units, and the class width.
$ci = rand(0, 3)

$colName = array("Survival Length (in days)", "Hours Played per Week", "Minutes of Daily Exercise", "Nights Spent in the Hospital")
$unitWord = array("days", "hours", "minutes", "nights")
$subject = array("patients", "students", "members", "patients")
$intro = array(
  "A pharmaceutical company is studying a new antiviral. For each patient in the study, researchers record the number of days from the start of treatment until symptoms are alleviated.",
  "Researchers survey students at Rivergate High School, recording the number of hours of video games each student plays in a typical week.",
  "A fitness center surveys its members, recording the number of minutes each member exercises on a typical day.",
  "A hospital reviews its discharge records, recording the number of nights each patient stayed."
)

// Class boundaries: a starting boundary and one of two class widths per context.
$startAll = array(0.5, 0, 0, 0.5)
$widthA = array(6, 2, 15, 3)
$widthB = array(5, 3, 20, 4)
$start = $startAll[$ci]
$wPair = array($widthA[$ci], $widthB[$ci])
$w = $wPair[rand(0, 1)]

// Four or five classes, whole-number frequencies, total n is whatever they add to.
$k = rand(4, 5)
$k1 = $k - 1
$f = array(0, 0, 0, 0, 0)
$n = 0
for ($i=0..$k1) {
  $f[$i] = rand(8, 45)
  $n = $n + $f[$i]
}

// One class is blanked; the class asked about in (a) and (b) sits above it,
// so parts (a) and (b) can be answered without first recovering the missing count.
// The target class is never the first one, so the cumulative total in (b) really adds classes.
$bi = rand(2, $k1)
$ti = rand(1, $bi - 1)

$labels = array("", "", "", "", "")
for ($i=0..$k1) {
  $lo = $start + $i * $w
  $hi = $lo + $w
  $labels[$i] = $lo . "&ndash;" . $hi
}
$tLabel = $labels[$ti]
$bLabel = $labels[$bi]

// Cumulative count through the class asked about.
$cumCount = 0
$sumStr = ""
$sep = ""
for ($i=0..$ti) {
  $cumCount = $cumCount + $f[$i]
  $sumStr = $sumStr . $sep . $f[$i]
  $sep = " + "
}

// The frequencies that ARE printed, for the missing-value deduction.
$knownSum = 0
$knownStr = ""
$sep2 = ""
for ($i=0..$k1) {
  if ($i != $bi) {
    $knownSum = $knownSum + $f[$i]
    $knownStr = $knownStr . $sep2 . $f[$i]
    $sep2 = " + "
  }
}

$relFreq = $f[$ti] / $n
$cumRel = $cumCount / $n
$missing = $f[$bi]
$tCount = $f[$ti]

$answer[0] = $relFreq
$abstolerance[0] = 0.0011
$showanswer[0] = round($relFreq, 3)

$answer[1] = $cumRel
$abstolerance[1] = 0.0011
$showanswer[1] = round($cumRel, 3)

$answer[2] = $missing
$abstolerance[2] = 0.0001
$answerformat[2] = "integer"

// The table the student reads: class intervals and frequencies, with one cell blank.
$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">' . $colName[$ci] . '</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Frequency</th></tr>'
for ($i=0..$k1) {
  $cell = $f[$i]
  $cell = '<b style="color:#1865f2;">?</b>' if ($i == $bi)
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $labels[$i] . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cell . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:7px 16px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>' . $n . '</b></td></tr>'
$tableHtml = $tableHtml . '</table>'

// The completed table, for the solution guide.
$solTable = '<table style="border-collapse:collapse; margin:10px 0; font-family:Arial,sans-serif; font-size:14px;">'
$solTable = $solTable . '<tr style="background:#eef2f7;"><th style="border:1px solid #ccc; padding:6px 12px; text-align:left;">' . $colName[$ci] . '</th><th style="border:1px solid #ccc; padding:6px 12px;">Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Relative Frequency</th><th style="border:1px solid #ccc; padding:6px 12px;">Cumulative Relative Frequency</th></tr>'
$run = 0
for ($i=0..$k1) {
  $run = $run + $f[$i]
  $rowRel = round($f[$i] / $n, 3)
  $rowCum = round($run / $n, 3)
  $solTable = $solTable . '<tr><td style="border:1px solid #ccc; padding:6px 12px;">' . $labels[$i] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $f[$i] . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $rowRel . '</td><td style="border:1px solid #ccc; padding:6px 12px; text-align:center;">' . $rowCum . '</td></tr>'
}
$solTable = $solTable . '</table>'

$introText = $intro[$ci]
$relRounded = round($relFreq, 3)
$cumRounded = round($cumRel, 3)
$who = $subject[$ci]
$unit = $unitWord[$ci]

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
      <p><span class="term-label">a. Relative frequency of ' . $tLabel . ':</span> relative frequency is the frequency of that one class divided by the total number of values. That class holds ' . $tCount . ' of the ' . $n . ' ' . $who . ', so the relative frequency is ' . $tCount . ' &divide; ' . $n . ' &approx; <b>' . $relRounded . '</b>. It is a proportion of the whole, never a count.</p>
      <p><span class="term-label">b. Cumulative relative frequency through ' . $tLabel . ':</span> add the frequencies from the first class down through ' . $tLabel . ', then divide by the total: or equivalently add the relative frequencies down the column. Here ' . $sumStr . ' = ' . $cumCount . ', and ' . $cumCount . ' &divide; ' . $n . ' &approx; <b>' . $cumRounded . '</b>. That is the proportion of ' . $who . ' at or below the top of the ' . $tLabel . ' class.</p>
      <p><span class="term-label">c. The missing frequency for ' . $bLabel . ':</span> every value falls in exactly one class, so the Frequency column has to add to ' . $n . '. The printed frequencies add to ' . $knownStr . ' = ' . $knownSum . ', which leaves ' . $n . ' &minus; ' . $knownSum . ' = <b>' . $missing . '</b> for the ' . $bLabel . ' class.</p>
      <p style="margin-top:1em;"><b>The completed table:</b></p>
      ' . $solTable . '
      <p><b>Two checks worth running every time:</b> the Frequency column must add back to ' . $n . ', and the Cumulative Relative Frequency column must end at 1.000. If either misses, a class was miscounted or a division used the wrong total.</p>
      <p><b>The usual trap:</b> a cumulative relative frequency is the running total of the relative frequencies, not the running total of the counts. Dividing by ' . $n . ' happens once at the end (or once per class), never twice.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText</p>
    <p style="margin:12px 0 0 0;">In all, <b>$n</b> $who were recorded. The results are grouped into classes below, but <b>one frequency has been left out</b>.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>relative frequency</b> of the class <b>$tLabel</b> $unit. Enter a decimal rounded to <b>three decimal places</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>cumulative relative frequency</b> through the class <b>$tLabel</b> $unit: the proportion of all $n $who in that class or any class below it. Enter a decimal rounded to <b>three decimal places</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the <b>missing frequency</b> for the class <b>$bLabel</b> $unit. Enter a whole number. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
