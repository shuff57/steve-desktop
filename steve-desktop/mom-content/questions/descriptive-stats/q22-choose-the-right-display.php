// === NAME - DESCRIPTION: Choose the Right Display for the Purpose - Match a data set and a stated purpose to a pie chart, bar graph, Pareto chart, or histogram, using what each display promises about the data ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

// $pi picks the PURPOSE, which is what decides the correct display, so the answer rotates
// across all four options. 0 = shares of one whole (pie), 1 = raw counts side by side (bar),
// 2 = seven categories ranked largest to smallest (Pareto), 3 = quantitative data grouped
// into intervals (histogram).
$pi = rand(0, 3)

// $vi picks the context inside the Pareto and histogram cases.
$vi = rand(0, 2)

// $pj picks the pair of category names used by the pie and bar cases.
$pj = rand(0, 2)

// Two distinct colleges.
$colleges = array("Lakeview College", "Rockridge College", "Harbor Point College", "Pine Valley College", "Grand Mesa College", "Cedar Ridge College")
$ca = rand(0, 5)
$cb = rand(0, 4)
if ($cb >= $ca) {
  $cb = $cb + 1
}
$collegeA = $colleges[$ca]
$collegeB = $colleges[$cb]

// Category-pair labels, stored flat so each lookup stays a plain scalar. Each pair is
// non-overlapping and exhaustive, so a pie chart is genuinely available in case 0.
$pairLabels = array("full-time", "part-time", "living in campus housing", "living off campus", "degree-seeking", "non-degree-seeking")
$labA = $pairLabels[2 * $pj]
$labB = $pairLabels[2 * $pj + 1]

$n1 = rand(2400, 9200)
$n2 = rand(2400, 9200)
$m1 = rand(2400, 9200)
$m2 = rand(2400, 9200)
$nStu = rand(180, 640)

// Seven category names per Pareto context, flat for the same reason.
$paretoCats = array(
  "Asian", "Black", "Filipino", "Hispanic", "Native American", "Pacific Islander", "White",
  "schedule conflict", "course too difficult", "changed major", "work hours increased", "family obligation", "health reasons", "transferred out",
  "Accounting", "Economics", "Finance", "Human Resources", "Logistics", "Management", "Marketing"
)
$catList = ""
$sep = ""
for ($i=0..6) {
  $catList = $catList . $sep . $paretoCats[7 * $vi + $i]
  $sep = ", "
}

$paretoWho = array("researcher", "dean", "advisor")
$paretoIntro = array(
  "A researcher at " . $collegeA . " has a frequency table giving the number of students in each of seven ethnicity categories: ",
  "A dean at " . $collegeA . " has a frequency table giving the number of students who gave each of seven reasons for dropping a course: ",
  "An advisor at " . $collegeA . " has a frequency table giving the number of students in each of seven declared majors in the business division: "
)

$histWho = array("registrar", "program director", "transportation office")
$histVar = array("credit hours completed", "hours logged before certification", "minutes spent traveling to campus")
$histIntro = array(
  "The registrar at " . $collegeA . " has recorded the number of credit hours completed this term by each of " . $nStu . " students.",
  "A program director has recorded the number of hours logged before certification by each of " . $nStu . " electrician apprentices.",
  "A transportation office at " . $collegeA . " has recorded the number of minutes spent traveling to campus by each of " . $nStu . " commuter students."
)

// Build the scenario and the purpose as scalars, so the prompt and the answer key cannot drift.
$dataText = ""
$purposeText = ""

if ($pi == 0) {
  $dataText = "The registrar at " . $collegeA . " has this term's enrollment counts: " . $n1 . " students are " . $labA . " and " . $n2 . " students are " . $labB . ". Every enrolled student falls into exactly one of these two categories."
  $purposeText = "The registrar wants one display showing how the student body <b>splits between the two categories: " . $labA . " and " . $labB . ": as shares of the whole</b>."
}
if ($pi == 1) {
  $dataText = "A state office has this term's enrollment counts for two colleges. At " . $collegeA . ", " . $n1 . " students are " . $labA . " and " . $n2 . " are " . $labB . ". At " . $collegeB . ", " . $m1 . " students are " . $labA . " and " . $m2 . " are " . $labB . "."
  $purposeText = "The office wants one display that sets the <b>raw counts for the two colleges side by side</b>, so all four numbers can be compared directly against the same scale."
}
if ($pi == 2) {
  $dataText = $paretoIntro[$vi] . $catList . ". The categories do not overlap, and every student is counted exactly once."
  $purposeText = "The " . $paretoWho[$vi] . " wants one display that <b>ranks the seven categories from largest to smallest</b>, so the biggest contributors are easy to read off."
}
if ($pi == 3) {
  $dataText = $histIntro[$vi]
  $purposeText = "The " . $histWho[$vi] . " wants one display of these <b>quantitative</b> values <b>grouped into intervals</b>, so the shape of the distribution is visible."
}

// MyOpenMath shuffles the options, so the solution guide names the display by text, never by
// position.
$questions = array("A pie chart", "A bar graph", "A Pareto chart", "A histogram")
$answer = $pi

$displayNames = array("a pie chart", "a bar graph", "a Pareto chart", "a histogram")
$correctName = $displayNames[$pi]

$whyHtml = ""
if ($pi == 0) {
  $whyHtml = '<p><span class="term-label">Why a pie chart:</span> the purpose is <b>shares of the whole</b>, and a pie chart is the only one of the four that makes that promise: each wedge is proportional to its category\'s percent of the circle, the wedges add to 100%, and each individual sits in exactly one wedge. Here every student is either ' . $labA . ' or ' . $labB . ' and nobody is both, so the promise holds and the pie is honest.</p>
      <p><span class="term-label">Why not a bar graph:</span> two bars would show the two counts perfectly well, but a bar graph makes <b>no</b> claim that the categories together account for everyone. When the point is what fraction of the whole each piece is, that missing promise is the whole point.</p>'
}
if ($pi == 1) {
  $whyHtml = '<p><span class="term-label">Why a bar graph:</span> in a bar graph the length of each bar is proportional to the number in that category, and bars for both colleges stand against the <b>same ruler</b>. That is exactly what "compare the raw counts side by side" asks for.</p>
      <p><span class="term-label">Why not a pie chart:</span> a pie shows <b>one</b> whole. Two colleges means two separate pies, and each pie rescales its own college to 100%, so the wedges report shares, not counts. ' . $collegeA . ' and ' . $collegeB . ' have different totals, so equal-looking wedges would stand for different numbers of students.</p>'
}
if ($pi == 2) {
  $whyHtml = '<p><span class="term-label">Why a Pareto chart:</span> a Pareto chart <b>is</b> a bar graph: one whose bars have been sorted from largest to smallest. Sorting is the entire difference, and sorting is exactly what was asked for.</p>
      <p><span class="term-label">Why not a plain bar graph:</span> an unsorted bar graph of seven categories, listed alphabetically, holds the same information but is hard to read: the eye has to hunt across the chart to find the biggest category. Sorting puts the biggest contributors first, so they can be read straight off.</p>'
}
if ($pi == 3) {
  $whyHtml = '<p><span class="term-label">Why a histogram:</span> ' . $histVar[$vi] . ' is a <b>number</b> recorded on each individual, so this is quantitative data, not categories. A histogram is the display for quantitative data grouped into intervals, and the intervals are what make the shape of the distribution visible.</p>
      <p><span class="term-label">Why none of the other three:</span> pie charts, bar graphs, and Pareto charts are all displays of <b>qualitative</b> (categorical) data: one wedge or one bar per category name. There are no category names here, only numbers.</p>'
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
  .term-row { margin:0.6em 0; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>The data alone never picks the display. Read the <b>purpose</b>, then pick the display whose promise matches it.</p>
      <p><span class="term-label">Answer:</span> ' . $correctName . '.</p>
      ' . $whyHtml . '
      <p style="margin-top:1em;"><b>The rule that decides it:</b></p>
      <div class="term-row"><span class="term-label">Pie chart:</span> wedges of one circle, proportional to each category\'s percent. It <b>promises</b> that the wedges partition the whole and that each individual sits in exactly one wedge. Use it for shares of a single whole.</div>
      <div class="term-row"><span class="term-label">Bar graph:</span> one bar per category, length proportional to the number or percent. It makes <b>no</b> such promise: categories may overlap, and some may be missing. Use it to compare sizes, including across groups.</div>
      <div class="term-row"><span class="term-label">Pareto chart:</span> a bar graph with the bars sorted from largest to smallest. Use it when the ranking is the message and there are enough categories that an unsorted chart is hard to read.</div>
      <div class="term-row"><span class="term-label">Histogram:</span> for <b>quantitative</b> data grouped into intervals, not for categories. If what was recorded on each individual is a number, none of the other three apply.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$dataText</p>
    <p style="margin:12px 0 0 0;">$purposeText</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Which display <b>best serves that purpose</b>?</p>
    $answerbox
  </div>
</div>

// === ANSWER ===

$solutionguide
