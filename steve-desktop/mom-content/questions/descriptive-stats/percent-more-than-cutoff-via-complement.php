// === NAME - DESCRIPTION: Percent More Than a Cutoff via the Complement Rule - Find the percent above a cutoff as 1 minus the cumulative relative frequency through the cutoff row, then confirm it by adding the relative frequencies of the rows above the cutoff ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number")

$ctxIdx = rand(0, 1)

$subjList = array("community-college students", "households")
$itemPluralList = array("books", "pets")
$itemSingularList = array("book", "pet")
$verbList = array("read", "own")
$colHeaderList = array("Books Read Last Month", "Pets Owned")
$surveyorList = array("A librarian", "An animal shelter")
$activityList = array("read for pleasure last month", "own")

$subj = $subjList[$ctxIdx]
$itemPlural = $itemPluralList[$ctxIdx]
$itemSingular = $itemSingularList[$ctxIdx]
$verb = $verbList[$ctxIdx]
$colHeader = $colHeaderList[$ctxIdx]
$surveyor = $surveyorList[$ctxIdx]
$activity = $activityList[$ctxIdx]

$f0 = rand(4, 9)
$f1 = rand(9, 18)
$f2 = rand(11, 20)
$f3 = rand(7, 15)
$f4 = rand(4, 10)
$f5 = rand(2, 6)
$freq = array($f0, $f1, $f2, $f3, $f4, $f5)
$vals = array(0, 1, 2, 3, 4, 5)
$N = $f0 + $f1 + $f2 + $f3 + $f4 + $f5

$cutoffIdx = rand(1, 3)
$cutoffVal = $vals[$cutoffIdx]

$relF = array(0, 0, 0, 0, 0, 0)
$cumF = array(0, 0, 0, 0, 0, 0)
$cumSum = 0
for ($i=0..5) {
  $cumSum = $cumSum + $freq[$i]
  $relF[$i] = $freq[$i] / $N
  $cumF[$i] = $cumSum / $N
}

$relFdisp0 = round($relF[0], 3)
$relFdisp1 = round($relF[1], 3)
$relFdisp2 = round($relF[2], 3)
$relFdisp3 = round($relF[3], 3)
$relFdisp4 = round($relF[4], 3)
$relFdisp5 = round($relF[5], 3)

$cumFdisp0 = round($cumF[0], 3)
$cumFdisp1 = round($cumF[1], 3)
$cumFdisp2 = round($cumF[2], 3)
$cumFdisp3 = round($cumF[3], 3)
$cumFdisp4 = round($cumF[4], 3)
$cumFdisp5 = round($cumF[5], 3)

$cumCutoff = $cumF[$cutoffIdx]
$cumCutoffDisp = round($cumCutoff, 3)

$moreThanFrac = 1 - $cumCutoff
$moreThanFracDisp = round($moreThanFrac, 3)
$moreThanPct = round(100 * $moreThanFrac, 1)

$sumAbove = 0
for ($i=($cutoffIdx+1)..5) {
  $sumAbove = $sumAbove + $freq[$i]
}
$fracAbove = $sumAbove / $N
$fracAboveDisp = round($fracAbove, 3)
$pctAbove = round(100 * $fracAbove, 1)

if ($cutoffVal == 1) {
  $itemWordForCutoff = $itemSingular
} else {
  $itemWordForCutoff = $itemPlural
}

$moreThanPhrase = $verb . " more than " . $cutoffVal . " " . $itemWordForCutoff

if ($cutoffIdx == 1) {
  $atOrBelowListStr = "0 and 1"
  $aboveListStr = "2, 3, 4, and 5"
} elseif ($cutoffIdx == 2) {
  $atOrBelowListStr = "0, 1, and 2"
  $aboveListStr = "3, 4, and 5"
} else {
  $atOrBelowListStr = "0, 1, 2, and 3"
  $aboveListStr = "4 and 5"
}

$answer[0] = $moreThanPct
$abstolerance[0] = 0.05

$answer[1] = $pctAbove
$abstolerance[1] = 0.05

$introText = $surveyor . " surveyed " . $N . " " . $subj . " about how many " . $itemPlural . " they " . $activity . ". The results are summarized below, with the relative frequency and cumulative relative frequency columns already filled in."

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">' . $colHeader . '</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Frequency</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Relative Frequency</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Cumulative Relative Frequency</th></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">0</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $f0 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $relFdisp0 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cumFdisp0 . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">1</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $f1 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $relFdisp1 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cumFdisp1 . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">2</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $f2 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $relFdisp2 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cumFdisp2 . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">3</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $f3 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $relFdisp3 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cumFdisp3 . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">4</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $f4 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $relFdisp4 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cumFdisp4 . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">5</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $f5 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $relFdisp5 . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $cumFdisp5 . '</td></tr>'
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:7px 16px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>' . $N . '</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>1.000</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"></td></tr>'
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
      <p><span class="term-label">Step 1 &mdash; Find the row at the cutoff.</span> &#8220;More than ' . $cutoffVal . ' ' . $itemWordForCutoff . '&#8221; means everything above the row for ' . $cutoffVal . ' ' . $itemPlural . '. That row already has every value at or below it (' . $atOrBelowListStr . ') baked into its cumulative relative frequency, so it is the row to start from.</p>
      <p><span class="term-label">Step 2 &mdash; Read the cumulative relative frequency for that row.</span> It is <b>' . $cumCutoffDisp . '</b>.</p>
      <p><span class="term-label">Step 3 &mdash; Subtract from 1 (the complement).</span> The whole table sums to 1, so whatever is not accounted for at or below the cutoff must be the part above it: 1 &minus; ' . $cumCutoffDisp . ' = ' . $moreThanFracDisp . ', which is <b>' . $moreThanPct . '%</b>.</p>
      <p><span class="term-label">Step 4 &mdash; Confirm by adding the rows above the cutoff directly.</span> The rows for ' . $aboveListStr . ' ' . $itemPlural . ' have frequencies that add to ' . $sumAbove . ' out of ' . $N . ': ' . $sumAbove . ' &divide; ' . $N . ' &approx; ' . $fracAboveDisp . ' = <b>' . $pctAbove . '%</b>. Nothing is left over between the two methods &mdash; the complement is just a shortcut for the same sum, not a different number.</p>
      <p><b>Answers:</b> a) ' . $moreThanPct . '%, b) ' . $pctAbove . '% (the two methods agree).</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What percent of $subj $moreThanPhrase? Use the <b>complement trick</b>: read the cumulative relative frequency through the row at or below the cutoff, then subtract it from 1 &mdash; do not add up every row above the cutoff by hand. Enter your answer as a percent, rounded to one decimal place (for example, if your answer were 12.34%, enter 12.3). $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Now confirm part (a) a different way: add the <b>relative frequencies</b> of just the rows above the cutoff directly, without using the complement. What percent do you get left over? It should match part (a) exactly. Enter your answer as a percent, rounded to one decimal place. $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
