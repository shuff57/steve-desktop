// === NAME - DESCRIPTION: Pre-FRQ Grade a Display Choice - The scenario and grading checklist of the choosing-a-display FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 2.2, built like the chapter 1 ones: the SAME scenario and the SAME grading checklist
// as frq/descriptive-statistics/q4-choosing-the-right-display, with the writing replaced by grading.
// The category students drop here is the RECOMMENDATION: having described both displays fairly, they
// stop without ever answering the question that was asked, which is which one to use.
//
// Every response is assembled from fixed sentence-parts, so which categories each earns is structural
// and cannot drift with the seed.
$anstypes = array("choices", "multans", "choices")

$si = rand(0, 2)
if ($si == 0) {
  $goal = "find out whether the delivery times have one peak or two"
  $best = "histogram"
  $whyBest = "only a histogram shows the actual shape, and a second peak is a feature of the shape"
  $blindSpot = "a box plot would draw the same five numbers whether the data had one hump or two"
}
elseif ($si == 1) {
  $goal = "compare the spread of exam scores across four different sections at a glance"
  $best = "box plot"
  $whyBest = "several box plots sit side by side on one axis, so the boxes and whiskers can be compared directly"
  $blindSpot = "four separate histograms are hard to line up and compare"
}
else {
  $goal = "check whether any of the recorded repair costs is an outlier"
  $best = "box plot"
  $whyBest = "a box plot is built from the quartiles, so the 1.5 IQR fences and any outlier are shown directly"
  $blindSpot = "a histogram only shows a short bar out at the end, which is not the same as flagging an outlier"
}

$sHist = "A histogram groups the data into classes and draws a bar for each, so it shows the SHAPE of the distribution: where the values pile up, whether there is one peak or several, and which way any tail runs."
$sBox = "A box plot draws the five-number summary, so it shows the median, the quartiles and the range at a glance. Its strength over a histogram is that several groups can be placed on one axis and compared directly, and that outliers are flagged by the 1.5 IQR rule rather than left to the eye."
$sRec = "For this goal the " . $best . " is the better choice, because " . $whyBest . ": " . $blindSpot . "."

$rFull = $sHist . " " . $sBox . " " . $sRec
$rNoRec = $sHist . " " . $sBox . " Both displays are useful and each has its own advantages."
$rNoBox = $sHist . " " . $sRec
$rDescOnly = $sHist . " " . $sBox

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoRec
$rC = $rNoBox
$rD = $rDescOnly
if ($pos == 1) {
  $rA = $rNoRec
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoBox
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rDescOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noRecLabel = "B"
if ($pos == 1) { $noRecLabel = "A" }

$questions[1] = array(
  "Histogram (4 pts)",
  "Boxplot (3 pts)",
  "Recommendation (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Describing both displays fairly is not the same as answering the question that was asked, which is which one to use for THIS goal. The rubric awards the recommendation separately because it is the decision the whole answer exists to reach.",
  "Yes. Once both displays have been described accurately the better choice is obvious and need not be stated.",
  "No, but only because the response was too short. Describing the histogram in more detail would have earned it.",
  "Yes, provided the strength of box plots over histograms was mentioned."
)
$answer[2] = 0

$css = '
<style>
  .qscope7 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope7 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope7 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope7 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope7 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope7 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope7 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope7 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope7 .row-colored { background:#fff9ea; }
  .qscope7 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope7 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope7">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Histogram<br>(4 pts)</b></td>
            <td>Describe what a histogram displays, and what aspect of the distribution it is especially good at showing.</td></tr>
          <tr><td style="text-align:center;"><b>Boxplot<br>(3 pts)</b></td>
            <td>Describe what a box plot displays, and identify a strength it has that a histogram does not.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Recommendation<br>(3 pts)</b></td>
            <td>State which display is more appropriate for the stated goal, and justify the choice.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope7">
  <div class="resp"><b>Response A.</b> ' . $rA . '</div>
  <div class="resp"><b>Response B.</b> ' . $rB . '</div>
  <div class="resp"><b>Response C.</b> ' . $rC . '</div>
  <div class="resp"><b>Response D.</b> ' . $rD . '</div>
</div>'

$fullLabel = "A"
if ($pos == 1) { $fullLabel = "B" }
if ($pos == 2) { $fullLabel = "C" }
if ($pos == 3) { $fullLabel = "D" }

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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> describes what a histogram shows, describes what a box plot shows AND names a strength it has over a histogram, and then makes a recommendation with a reason tied to the goal. Each of the other three drops a whole category, and a dropped category scores zero however well the rest reads.</p>
      <p><span class="term-label">The right recommendation here.</span> The goal was to ' . $goal . ', so the <b>' . $best . '</b> is the better display: ' . $whyBest . '. The other one falls short because ' . $blindSpot . '.</p>
      <p><span class="term-label">Part (b): grading Response ' . $noRecLabel . ' line by line.</span></p>
      <ul>
        <li><b>Histogram: earned.</b> It says what a histogram displays and that shape is what it shows best.</li>
        <li><b>Boxplot: earned.</b> It says what a box plot displays and names a strength over a histogram.</li>
        <li><b>Recommendation: NOT earned.</b> It closes with "both are useful and each has its own advantages". That is a refusal to choose, and choosing was the question. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c): why the recommendation is its own category.</span> Two accurate descriptions do not answer "which should I use". A reader with a decision to make gets nothing from an even-handed summary; the rubric pays for the decision AND the reason, because a choice without a reason is a guess and a reason without a choice is an essay. Note that neither display is better in general: the answer depends entirely on the goal, which is why the goal is stated in the prompt and why the justification has to refer to it.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The recommendation is the category most often missing, because describing both options feels balanced and thorough: and balance is exactly what loses the points here.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;"><b>The scenario.</b> An analyst has a data set and wants to $goal. She is deciding between a histogram and a box plot.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Explain what each display shows, and recommend which one she should use for this goal.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 4px 0;"><b>Four students answered.</b></p>
    $responses
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response earns <b>full credit</b> on all three categories? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noRecLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is describing both displays accurately enough on its own to cover the recommendation? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
