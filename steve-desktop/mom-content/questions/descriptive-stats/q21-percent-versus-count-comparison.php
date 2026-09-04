// === NAME - DESCRIPTION: Percent Versus Count When Comparing Two Groups of Different Size - Compute each institution's percent in one category from a two-by-two table of counts, then decide which comparison supports a claim about the make-up of the smaller institution's student body ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

// Randomize the two-category split and the wording that goes with it.
$ci = rand(0, 3)

$catAll = array("Part-time", "Evening", "Online", "Commuter")
$otherAll = array("Full-time", "Daytime", "In-person", "Residential")
$catWordAll = array("part-time", "evening", "online", "commuter")
$catDescAll = array(
  "enrolled part-time",
  "enrolled only in evening classes",
  "enrolled only in online classes",
  "commute to campus rather than live on it"
)
$termAll = array("spring term", "fall term", "spring term", "fall term")

$catLabel = $catAll[$ci]
$otherLabel = $otherAll[$ci]
$catWord = $catWordAll[$ci]
$catDesc = $catDescAll[$ci]
$termText = $termAll[$ci]

// Randomize the two institution names, without repeating one.
$nameAll = array("Marlowe College", "Bayside College", "Redwood Valley College", "Northgate College", "Cedar Hills College", "Lakemont College")
$n1 = rand(0, 5)
$n2 = $n1 + rand(1, 5)
if ($n2 > 5) {
  $n2 = $n2 - 6
}
$bigName = $nameAll[$n1]
$smallName = $nameAll[$n2]

// The larger school has the larger raw count in the category of interest; the smaller school
// has the larger SHARE. Target percents are kept apart so that ordering always holds, and the
// counts are jittered off the target so the answers are not round numbers.
$pctSmallTarget = rand(66, 76)
$pctBigTarget = rand(52, 62)

$smallTotal = rand(8000, 15000)
$bigTotal = $smallTotal + rand(9000, 15000)

$smallCat = round($smallTotal * $pctSmallTarget / 100) + rand(-40, 40)
$smallOther = $smallTotal - $smallCat

$bigCat = round($bigTotal * $pctBigTarget / 100) + rand(-60, 60)
$bigOther = $bigTotal - $bigCat

// Which school is asked about first is randomized, so (a) is not always the big one.
$firstIsBig = rand(0, 1)

$aName = $smallName
$aCat = $smallCat
$aOther = $smallOther
$aTotal = $smallTotal
$bName = $bigName
$bCat = $bigCat
$bOther = $bigOther
$bTotal = $bigTotal
if ($firstIsBig == 1) {
  $aName = $bigName
  $aCat = $bigCat
  $aOther = $bigOther
  $aTotal = $bigTotal
  $bName = $smallName
  $bCat = $smallCat
  $bOther = $smallOther
  $bTotal = $smallTotal
}

$aPct = 100 * $aCat / $aTotal
$bPct = 100 * $bCat / $bTotal

// (a) and (b) are computations, so they are typed in: offering percents to choose between
// would turn the arithmetic into elimination.
$answer[0] = $aPct
$abstolerance[0] = 0.051
$showanswer[0] = round($aPct, 1)

$answer[1] = $bPct
$abstolerance[1] = 0.051
$showanswer[1] = round($bPct, 1)

// (c) is a recognition task, so it is multiple choice. The distractors are the two comparisons
// the table invites but that do not support the claim, plus the size-difference reading.
$choices[2] = array(
  "The raw numbers in the " . $catLabel . " row: " . $bigName . " has " . $bigCat . " " . $catWord . " students and " . $smallName . " has " . $smallCat . ", so the larger count settles it.",
  "The percentages: " . $catWord . " students are a larger share of " . $smallName . " than of " . $bigName . ", and that share is more than half of the student body.",
  "The total enrollments: " . $smallName . " enrolls fewer students overall, so its student body must be mostly " . $catWord . ".",
  "Neither comparison can support the claim, because the two schools do not enroll the same number of students."
)
$answer[2] = 1

// Scalars for the question text and the solution guide: neither can index an array.
$bigPct = 100 * $bigCat / $bigTotal
$smallPct = 100 * $smallCat / $smallTotal
$bigPctR = round($bigPct, 1)
$smallPctR = round($smallPct, 1)
$aPctR = round($aPct, 1)
$bPctR = round($bPct, 1)
$countGap = $bigCat - $smallCat
$totalGap = $bigTotal - $smallTotal
$pctGap = round($smallPct - $bigPct, 1)

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">Status</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">' . $aName . '</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">' . $bName . '</th></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $catLabel . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $aCat . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $bCat . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $otherLabel . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $aOther . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $bOther . '</td></tr>'
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:7px 16px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>' . $aTotal . '</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>' . $bTotal . '</b></td></tr>'
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
      <p><b>The rule for (a) and (b):</b> a percent is the part divided by that school&rsquo;s own whole, times 100. The denominator is the total enrollment of the school you are describing: never the combined enrollment of both schools.</p>
      <p><span class="term-label">a. ' . $aName . ':</span> ' . $aCat . ' &divide; ' . $aTotal . ' &times; 100 &approx; <b>' . $aPctR . '%</b>.</p>
      <p><span class="term-label">b. ' . $bName . ':</span> ' . $bCat . ' &divide; ' . $bTotal . ' &times; 100 &approx; <b>' . $bPctR . '%</b>.</p>
      <p><span class="term-label">c. Which comparison supports the claim:</span> the <b>percentages</b>. ' . $catWord . ' students are ' . $smallPctR . '% of ' . $smallName . ' but only ' . $bigPctR . '% of ' . $bigName . ': a gap of ' . $pctGap . ' percentage points, and ' . $smallPctR . '% is more than half, which is what &ldquo;mostly ' . $catWord . '&rdquo; means.</p>
      <p><b>Why the counts point the other way:</b> ' . $bigName . ' has ' . $bigCat . ' ' . $catWord . ' students to ' . $smallName . '&rsquo;s ' . $smallCat . ': ' . $countGap . ' more. That happens because ' . $bigName . ' enrolls ' . $totalGap . ' more students overall, not because its student body is more ' . $catWord . '. Counts are pulled around by the size of the school; percentages divide that size out and describe the make-up of each student body.</p>
      <p><b>Why the totals say nothing:</b> ' . $smallTotal . ' against ' . $bigTotal . ' tells you which school is bigger and nothing at all about how either one is split. A small school can be almost entirely ' . $otherLabel . ' students, and a large school can be almost entirely ' . $catLabel . '.</p>
      <p><b>The rule to carry forward:</b> when two groups have different totals, compare percentages, not counts. Counts answer &ldquo;how many?&rdquo;; percentages answer &ldquo;what share?&rdquo;: and a claim about what a student body is <i>mostly</i> made of is a claim about share.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The table below compares the number of $catLabel and $otherLabel students at $aName and $bName for the most recent $termText. Every student at each school is counted in exactly one of the two rows, and the $catLabel students are the ones who $catDesc.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What <b>percent</b> of the $aTotal students at <b>$aName</b> are $catWord? Enter the percent rounded to <b>one decimal place</b>, without the % sign. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What <b>percent</b> of the $bTotal students at <b>$bName</b> are $catWord? Enter the percent rounded to <b>one decimal place</b>, without the % sign. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $smallName is the smaller school. Which comparison supports the claim that <b>$smallName serves a mostly $catWord student body</b>? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
