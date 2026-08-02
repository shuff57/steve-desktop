// === NAME - DESCRIPTION: Do the Reported Percentages Allow a Pie Chart - Add three or four reported category percentages, then decide whether a pie chart is an honest display when the total runs over 100% because the categories overlap or falls under 100% because a category was left out ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices")

// Randomize the scenario AND the direction of the flaw. Contexts 0 and 1 report overlapping
// traits, so the reported percentages total more than 100%. Contexts 2 and 3 report categories
// that a respondent picks exactly one of, but with one category left out of the report, so the
// reported percentages total less than 100%.
$ci = rand(0, 3)
$isOver = 1
if ($ci >= 2) {
  $isOver = 0
}

$nAll = array(3, 4, 3, 4)
$n = $nAll[$ci]

$whoAll = array("Katherine Bruce", "Jordan Ellis", "Camila Reyes", "Priya Raman")
$whoName = $whoAll[$ci]

$groupAll = array("students at Lakeview College", "members of the campus hiking club", "members of the campus business club", "students at Rockridge College")
$groupText = $groupAll[$ci]

$introAll = array(
  "Katherine Bruce runs a survey of the students at Lakeview College and reports these results about the student body.",
  "Jordan Ellis surveys every member of the campus hiking club and reports these results about the membership.",
  "Camila Reyes, president of the campus business club, asks every member to name their one declared major and reports the results.",
  "Priya Raman surveys the students at Rockridge College about the one way they usually get to campus and reports the results."
)
$introText = $introAll[$ci]

// Category labels, four slots per context, indexed flat &mdash; the question text cannot index a
// nested array, and a flat lookup keeps every label a plain scalar.
$allCats = array(
  "work at least part-time", "commute more than 20 minutes to campus", "are parents of at least one child", "",
  "hold a job on campus", "play at least one intramural sport", "live in campus housing", "transferred in from another college",
  "major in Marketing", "major in Accounting", "major in Finance", "",
  "drive alone", "ride the campus bus", "bike to campus", "walk to campus"
)

// The category that context 2 and context 3 left out of the report.
$missingAll = array("", "", "Other/Undeclared major", "Other/No response")
$missingLabel = $missingAll[$ci]

$p0 = 0
$p1 = 0
$p2 = 0
$p3 = 0

if ($isOver == 1) {
  if ($n == 3) {
    $p0 = rand(42, 66)
    $p1 = rand(38, 60)
    $p2 = rand(24, 40)
  }
  if ($n == 4) {
    $p0 = rand(38, 58)
    $p1 = rand(30, 50)
    $p2 = rand(32, 52)
    $p3 = rand(20, 34)
  }
}
if ($isOver == 0) {
  if ($n == 3) {
    $hole = rand(8, 22)
    $p0 = rand(28, 42)
    $p1 = rand(18, 30)
    $p2 = 100 - $hole - $p0 - $p1
  }
  if ($n == 4) {
    $hole = rand(6, 14)
    $p0 = rand(24, 34)
    $p1 = rand(16, 24)
    $p2 = rand(12, 18)
    $p3 = 100 - $hole - $p0 - $p1 - $p2
  }
}

$pcts = array($p0, $p1, $p2, $p3)

$sum = 0
$listHtml = '<ul style="margin:10px 0 0 0; padding-left:22px;">'
$sumStr = ""
$sepS = ""
for ($i=0..3) {
  if ($i < $n) {
    $sum = $sum + $pcts[$i]
    $listHtml = $listHtml . '<li style="margin:4px 0;"><b>' . $pcts[$i] . '%</b> ' . $allCats[4 * $ci + $i] . '</li>'
    $sumStr = $sumStr . $sepS . $pcts[$i] . '%'
    $sepS = " + "
  }
}
$listHtml = $listHtml . '</ul>'

$excess = $sum - 100
$gap = 100 - $sum

// (a) is a computation, so it is typed in &mdash; offering totals to choose between would turn
// the addition into elimination.
$answer[0] = $sum
$abstolerance[0] = 0.001
$showanswer[0] = $sum

// (b) is a recognition task, so it is multiple choice. The last option is the mistake this topic
// actually produces: rescaling to 100% hides the overlap or the missing people.
$choices[1] = array(
  "A pie chart is fine. The categories are exhaustive and do not overlap, so every member of the group sits in exactly one wedge.",
  "No. The categories overlap, so no individual belongs to exactly one wedge. Use a bar graph, where each bar is measured on its own against the same 0% to 100% scale.",
  "No. The reported categories leave a gap. Either add an Other/Undeclared wedge so that the pie honestly totals 100%, or use a bar graph.",
  "The percentages should be rescaled so that they total 100%, and then drawn as a pie chart."
)
$answer[1] = 1
if ($isOver == 0) {
  $answer[1] = 2
}

// Scalars for the solution guide.
$correctText = "No. The categories overlap, so no individual belongs to exactly one wedge. Use a bar graph."
if ($isOver == 0) {
  $correctText = "No. The reported categories leave a gap. Add an Other/Undeclared wedge so the pie totals 100%, or use a bar graph."
}

$diagnosisHtml = '<p><span class="term-label">What the total tells you:</span> ' . $sum . '% is <b>more than 100%</b>, and it overruns by ' . $excess . ' percentage points. Percentages of the same group can only total more than 100% if some people were counted more than once, which means the categories <b>overlap</b>. One person can work part-time, commute a long way, and be a parent all at once.</p>
      <p><span class="term-label">Why that rules out a pie chart:</span> a pie is a whole. Each wedge promises that the individuals in it belong to that wedge and to no other. Overlapping categories break that promise, so there is no honest way to slice the circle. A bar graph makes no such promise &mdash; each bar is just a measurement against the same ruler, so overlapping categories sit side by side without lying about the group.</p>'
if ($isOver == 0) {
  $diagnosisHtml = '<p><span class="term-label">What the total tells you:</span> ' . $sum . '% is <b>less than 100%</b>, and it falls ' . $gap . ' percentage points short. Every member of the group has to land somewhere, so a shortfall of ' . $gap . '% means a category was left out of the report &mdash; here, the <b>' . $missingLabel . '</b> group, which holds real people.</p>
      <p><span class="term-label">Why that rules out this pie chart:</span> the wedges of a pie must add to 100%. Drawing only the ' . $n . ' reported categories forces the missing ' . $gap . '% to disappear, because whatever is drawn will fill the whole circle. Either add the ' . $missingLabel . ' wedge at ' . $gap . '% so the pie is complete, or use a bar graph, which never claims the categories are exhaustive.</p>'
}

$trapHtml = '<p><b>The trap:</b> "just rescale so they add to 100%." Rescaling divides each reported percentage by ' . $sum . '% and redraws it, which produces a tidy circle that is simply false &mdash; it reports overlapping categories as if each person had been counted once. The arithmetic works. The picture lies.</p>'
if ($isOver == 0) {
  $trapHtml = '<p><b>The trap:</b> "just rescale so they add to 100%." Rescaling divides each reported percentage by ' . $sum . '% and redraws it, which produces a tidy circle that is simply false &mdash; the ' . $gap . '% of the group in the ' . $missingLabel . ' category silently vanishes, and the remaining categories are drawn bigger than they really are. The arithmetic works. The picture lies.</p>'
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
      <p><span class="term-label">a. Add the reported percentages:</span> ' . $sumStr . ' = <b>' . $sum . '%</b>.</p>
      ' . $diagnosisHtml . '
      <p><span class="term-label">b. The honest call:</span> ' . $correctText . '</p>
      ' . $trapHtml . '
      <p><b>The rule to carry forward:</b> before drawing a pie chart, add the percentages. A pie chart is only honest when the categories are non-overlapping <i>and</i> exhaustive &mdash; that is, when the reported percentages already total 100%. Any other total means a bar graph, or a pie with the missing category restored.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText Of the $groupText:</p>
    $listHtml
    <p style="margin:12px 0 0 0;">$whoName wants to put all $n reported percentages on a single pie chart. Assume every reported percentage is correct.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Add the $n reported percentages. What is their <b>total</b>? Enter the number without the % sign. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does that total mean for the display $whoName wants to make? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
