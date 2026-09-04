// === NAME - DESCRIPTION: Proportion and Percent of a Total from a Frequency Table - Compute the proportion in an inclusive span of rows and the percent in a strictly-between span, then classify the counts as quantitative discrete ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

// Randomize the context: what is being counted, and whether the rows are years or months.
$ci = rand(0, 3)

$isYearAll = array(1, 0, 1, 0)
$catColAll = array("Year", "Month", "Year", "Month")
$countColAll = array("Deaths Worldwide from Earthquakes", "Tickets Sold", "Wildfires Reported", "Emergency Room Visits")
$nounAll = array("earthquake deaths", "tickets sold", "wildfires reported", "emergency room visits")
$unitAll = array("years", "months", "years", "months")
$introAll = array(
  "The table below lists the total number of deaths worldwide from earthquakes in each of eight consecutive years.",
  "The Fairmont Community Theater tracks how many tickets it sells each month. The table below lists the tickets sold in each of eight consecutive months of one season.",
  "A state forestry office keeps a record of how many wildfires are reported statewide each year. The table below lists the reported wildfires in each of eight consecutive years.",
  "A walk-in clinic records how many patients arrive at its emergency room each month. The table below lists the visits in each of eight consecutive months."
)
$lowAll = array(150, 620, 45, 280)
$highAll = array(92000, 2400, 900, 1500)

$isYr = $isYearAll[$ci]
$catColName = $catColAll[$ci]
$countColName = $countColAll[$ci]
$nounText = $nounAll[$ci]
$spanUnit = $unitAll[$ci]
$introText = $introAll[$ci]

// Eight row labels: consecutive years, or eight consecutive calendar months.
$monthNames = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")
$y0 = rand(1996, 2016)
$m0 = rand(0, 4)
$labels = array("", "", "", "", "", "", "", "")
for ($i=0..7) {
  if ($isYr == 1) {
    $labels[$i] = $y0 + $i
  }
  if ($isYr == 0) {
    $labels[$i] = $monthNames[$m0 + $i]
  }
}

// Every count is randomized, and the grand total is whatever they add to.
$counts = array(0, 0, 0, 0, 0, 0, 0, 0)
$total = 0
for ($i=0..7) {
  $counts[$i] = rand($lowAll[$ci], $highAll[$ci])
  $total = $total + $counts[$i]
}

// Two disjoint blocks of four rows. One block supplies the inclusive span asked about
// in (a), the other supplies the strictly-between span asked about in (b); which block
// plays which role is randomized, so the two spans are never the same rows.
$swap = rand(0, 1)
$incBase = 0
$betBase = 4
if ($swap == 1) {
  $incBase = 4
  $betBase = 0
}

// (a) inclusive span: two or three consecutive rows, endpoints counted.
$aLo = $incBase + rand(0, 1)
$aHi = $aLo + rand(1, 2)

// (b) strictly-between span: named endpoints two or three rows apart, endpoints NOT counted.
$gap = rand(2, 3)
$bLo = $betBase
if ($gap == 2) {
  $bLo = $betBase + rand(0, 1)
}
$bHi = $bLo + $gap
$innerN = $bHi - $bLo - 1

$aSum = 0
$aStr = ""
$sepA = ""
$bSum = 0
$bStr = ""
$sepB = ""
$bRowStr = ""
$sepR = ""
for ($i=0..7) {
  if ($i >= $aLo && $i <= $aHi) {
    $aSum = $aSum + $counts[$i]
    $aStr = $aStr . $sepA . $counts[$i]
    $sepA = " + "
  }
  if ($i > $bLo && $i < $bHi) {
    $bSum = $bSum + $counts[$i]
    $bStr = $bStr . $sepB . $counts[$i]
    $sepB = " + "
    $bRowStr = $bRowStr . $sepR . $labels[$i]
    $sepR = " and "
  }
}

$prop = $aSum / $total
$pct = 100 * $bSum / $total

$answer[0] = $prop
$abstolerance[0] = 0.000051
$showanswer[0] = round($prop, 4)

$answer[1] = $pct
$abstolerance[1] = 0.0051
$showanswer[1] = round($pct, 2)

// (c) the data type of the counts themselves. Counts of events land on whole numbers only.
$choices[2] = array("Qualitative (categorical)", "Quantitative discrete", "Quantitative continuous")
$answer[2] = 1

// Scalars for the question text and the solution guide: neither can index an array.
$aLoLabel = $labels[$aLo]
$aHiLabel = $labels[$aHi]
$bLoLabel = $labels[$bLo]
$bHiLabel = $labels[$bHi]
$aRows = $aHi - $aLo + 1
$propRounded = round($prop, 4)
$pctRounded = round($pct, 2)
$wrongPct = round(100 * ($bSum + $counts[$bLo] + $counts[$bHi]) / $total, 2)

// The table the student reads.
$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">' . $catColName . '</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">' . $countColName . '</th></tr>'
for ($i=0..7) {
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $labels[$i] . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $counts[$i] . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:7px 16px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>' . $total . '</b></td></tr>'
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
      <p><b>The rule for both (a) and (b):</b> a proportion is the part divided by the whole. Add only the rows the question names, then divide by the grand total ' . $total . '. A percent is that same quotient multiplied by 100.</p>
      <p><span class="term-label">a. Proportion, ' . $aLoLabel . ' through ' . $aHiLabel . ' inclusive:</span> "inclusive" means the two named ' . $spanUnit . ' are part of the span, so ' . $aRows . ' rows get added: ' . $aStr . ' = ' . $aSum . '. Then ' . $aSum . ' &divide; ' . $total . ' &approx; <b>' . $propRounded . '</b>. A proportion stays between 0 and 1: it is never a count and never a percent.</p>
      <p><span class="term-label">b. Percent, strictly between ' . $bLoLabel . ' and ' . $bHiLabel . ':</span> "strictly between" excludes both named endpoints, so only ' . $bRowStr . ' get added: ' . $bStr . ' = ' . $bSum . '. Then ' . $bSum . ' &divide; ' . $total . ' &times; 100 &approx; <b>' . $pctRounded . '%</b>. Adding the endpoint rows in as well would have given ' . $wrongPct . '%, which answers a different question.</p>
      <p><span class="term-label">c. The type of data in the ' . $countColName . ' column:</span> each entry is a count of how many ' . $nounText . ' there were, and counting gives whole things only: there is no such thing as a fraction of one. Counts are <b>quantitative discrete</b>.</p>
      <p><b>Why not the other two:</b> the column is not qualitative, because the entries are numbers that measure how many rather than names of a category: the ' . $catColName . ' column is the qualitative-looking one. It is not quantitative continuous either, because continuous data come from measuring on a scale that can always be read more finely, the way a weight or a time can.</p>
      <p><b>The trap worth naming:</b> "between ' . $bLoLabel . ' and ' . $bHiLabel . '" in everyday speech often gets read as including both ends. In this exercise it does not: read the wording of every span before you start adding, and check whether the endpoints are in or out.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText</p>
    $tableHtml
    <p style="margin:12px 0 0 0;">Across all eight $spanUnit there were <b>$total</b> $nounText in total.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What <b>proportion</b> of the $total $nounText fell in the $spanUnit from <b>$aLoLabel</b> through <b>$aHiLabel</b>, <b>counting both $aLoLabel and $aHiLabel</b>? Enter a decimal rounded to <b>four decimal places</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What <b>percent</b> of the $total $nounText fell <b>strictly between $bLoLabel and $bHiLabel</b>: that is, in the $innerN $spanUnit that lie after $bLoLabel and before $bHiLabel, <b>not counting $bLoLabel or $bHiLabel themselves</b>? Enter the percent rounded to <b>two decimal places</b>, without the % sign. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What type of data are the entries in the <b>$countColName</b> column of the table? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
