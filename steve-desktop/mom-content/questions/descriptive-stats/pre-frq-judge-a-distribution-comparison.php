// === NAME - DESCRIPTION: Pre-FRQ Grade a Distribution Comparison - The scenario, summary table and grading checklist of the compare-two-distributions FRQ, but the student grades sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A "pre-FRQ": the same scenario, the same summary table and the SAME grading checklist as the real
// free-response item (questions/frq/descriptive-statistics/q11-compare-distributions-essay.php), but
// every part auto-grades, so it can live in homework where free response is not allowed. The student
// applies the rubric to somebody else's answer before having to satisfy it from a blank page.
// Keep the four categories and their point values identical to q11 -- the whole value of this
// question is that the checklist is the one they will actually be marked against.
$anstypes = array("choices", "multans", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $labelA = "Class A"
  $labelB = "Class B"
  $ctx = "the end-of-semester exam scores in two algebra classes"
  $varName = "exam score"
}
else {
  $labelA = "Route 9"
  $labelB = "Route 14"
  $ctx = "the number of minutes each bus on two routes ran behind schedule"
  $varName = "delay in minutes"
}

// A is centered higher and tighter; B is right-skewed, more variable, and holds the largest value.
// Both differences are needed: if the groups differed only in center, the response that never
// mentions spread would not be missing anything, and part (b) would have no answer.
$medB = 5 * rand(12, 16)
$medA = $medB + 5 * rand(1, 3)
$meanA = $medA + rand(0, 1)
// Right skew pulls the mean ABOVE the median -- the table then shows the student why the median is
// the fairer center for B, which is the justification the rubric asks for under Center.
$meanB = $medB + rand(3, 6)
$sdA = rand(3, 5)
$sdB = $sdA + rand(4, 7)
$minA = $medA - rand(11, 15)
$maxA = $medA + rand(11, 15)
$minB = $medB - rand(4, 7)
$maxB = $maxA + rand(6, 12)

$shapeA = "roughly symmetric, no outliers"
$shapeB = "skewed right, with a few unusually high values"

$rFull = $labelA . " is " . $shapeA . ", while " . $labelB . " is " . $shapeB . ". Because " . $labelB . " is skewed, the median is the fairer measure of center to compare: " . $labelA . " has the higher median " . $varName . ", " . $medA . " against " . $medB . ". " . $labelA . " is also the more consistent of the two, with a standard deviation of " . $sdA . " compared with " . $sdB . " for " . $labelB . ". Overall " . $labelA . " runs higher on " . $varName . " and is steadier, while " . $labelB . " is more scattered and drags out a long tail of high values."

$rListed = $labelA . " is " . $shapeA . ". Its mean is " . $meanA . ", its median is " . $medA . " and its standard deviation is " . $sdA . ". " . $labelB . " is " . $shapeB . ". Its mean is " . $meanB . ", its median is " . $medB . " and its standard deviation is " . $sdB . "."

$rVague = "The first one is bigger than the second one. Its middle value is higher and its numbers are closer together, so it is the better of the two."

$rOneValue = $labelB . " reaches " . $maxB . ", the single largest value anywhere in the study, so " . $labelB . " is clearly the higher of the two. " . $labelA . " never gets close to that."

$questions[0] = array($rFull, $rListed, $rVague, $rOneValue)
$answer[0] = 0

// Part (b) is graded against the checklist itself: which CATEGORIES did the response fail to earn.
// The listed response does describe both shapes, so Shape IS earned -- that is what stops the part
// being passed by selecting all four.
$questions[1] = array(
  "Shape (2 pts)",
  "Center (3 pts)",
  "Spread (3 pts)",
  "In-Context Verdict (2 pts)"
)
$answers[1] = "1,2,3"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "Quoting a figure for each group is not comparing them. Center is earned only when the answer itself states which group is higher, in context.",
  "It does compare them, because a reader can see both medians and work out for themselves which is higher.",
  "It does compare them, as long as the mean and the median it quotes are both correct.",
  "It fails only because it left out the minimum and maximum; quoting those as well would have earned Center."
)
$answer[2] = 0

$css_block = '
<style>
  .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .rubric-container summary::-webkit-details-marker { display:none; }
  .arrow-open { display:none; }
  .rubric-container details[open] .arrow-closed { display:none; }
  .rubric-container details[open] .arrow-open { display:inline; }
  .rubric-content { background:#fafafa; padding:0.75em; box-sizing:border-box; }
  .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .row-colored { background:#fff9ea; }
  .summary-table { border-collapse:collapse; margin:0.6em 0; font-family:Arial; font-size:14px; }
  .summary-table th, .summary-table td { border:1px solid #ccc; padding:6px 12px; text-align:center; }
  .summary-table th { background:#f2f2f2; }
  .resp-box { border:1px solid #ccc; border-left:4px solid #f59e0b; background:#fffbeb; padding:12px; border-radius:4px; margin:10px 0; font-family:Arial; }
</style>'

$summaryTable = '<table class="summary-table">
<tr><th>Group</th><th>Mean</th><th>Median</th><th>s (SD)</th><th>Min</th><th>Max</th><th>Shape</th></tr>
<tr><td><b>' . $labelA . '</b></td><td>' . $meanA . '</td><td>' . $medA . '</td><td>' . $sdA . '</td><td>' . $minA . '</td><td>' . $maxA . '</td><td>' . $shapeA . '</td></tr>
<tr><td><b>' . $labelB . '</b></td><td>' . $meanB . '</td><td>' . $medB . '</td><td>' . $sdB . '</td><td>' . $minB . '</td><td>' . $maxB . '</td><td>' . $shapeB . '</td></tr>
</table>'

// Open by default. On the real FRQ the student may leave the checklist closed; here they are being
// asked to grade WITH it, so hiding it behind a click would just be a trap.
$rubricblock = $css_block . '
<div class="rubric-container">
  <details open>
    <summary><span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span> Grading Checklist &mdash; 10 points</summary>
    <div class="rubric-content">
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> &mdash; a full-credit comparison must address:</p>
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Shape<br>(2 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>Describe the shape of each distribution.</li>
              <li>Note any obvious outliers.</li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Center<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>Compare the typical value (mean OR median, justified by shape).</li>
              <li>State which group has a higher center, in context.</li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Spread<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>Compare the spread (standard deviation or IQR).</li>
              <li>State which group is more variable, in context.</li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>In-Context Verdict<br>(2 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>Write a concluding sentence using the real-world variable, not just numbers.</li>
            </ul></td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

$respBox = '<div class="resp-box">' . $rListed . '</div>'

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
      <p><span class="term-label">Part (a).</span> Only one response earns all four categories. It names both shapes, picks the median for the center <i>and says why</i> &mdash; ' . $labelB . ' is skewed, so its mean (' . $meanB . ') sits above its median (' . $medB . '), which you can read straight off the table &mdash; compares the standard deviations, and finishes with a sentence about ' . $varName . ' rather than about numbers.</p>
      <p><span class="term-label">Part (b) &mdash; grade it category by category.</span> The response is not wrong anywhere; every figure in it is correct. Work down the checklist:</p>
      <ul>
        <li><b>Shape &mdash; earned.</b> It describes both distributions. This is the one category it does get.</li>
        <li><b>Center &mdash; not earned.</b> It quotes both medians but never says which group is higher.</li>
        <li><b>Spread &mdash; not earned.</b> It quotes both standard deviations but never says which group is more variable.</li>
        <li><b>In-Context Verdict &mdash; not earned.</b> There is no concluding sentence about ' . $varName . ' at all.</li>
      </ul>
      <p>That is <b>2 out of 10</b> for an answer containing every correct number, which is exactly how this rubric bites.</p>
      <p><span class="term-label">Part (c) &mdash; the idea the checklist cannot spell out.</span> Writing "' . $labelA . ' has a median of ' . $medA . '. ' . $labelB . ' has a median of ' . $medB . '." puts two facts on the page and stops. A reader can of course see which is bigger, but doing that work is the answer\'s job, not the reader\'s. Center asks for a <i>claim</i>: ' . $labelA . ' is higher. Adding more numbers never repairs it, because what is missing is not a number.</p>
      <p><span class="term-label">Why this exists.</span> On the lab and on the test this same scenario arrives with a blank box and this same checklist. Spotting the missing category in someone else\'s answer is the quickest way to stop leaving it out of your own.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#1865f2; letter-spacing:0.04em;">FREE-RESPONSE PROMPT</p>
    <p style="margin:0 0 8px 0;">A study recorded $ctx. The summary statistics are below.</p>
    $summaryTable
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task:</b> Compare the two distributions of $varName. Use shape, center and spread, and write your conclusion in context.</p>
    $rubricblock
    <p style="margin:8px 0 0 0; font-size:14px; color:#6b7280;">You are not writing the answer here. You are grading it, against the checklist above.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response earns <b>all 10 points</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Now grade this one:
    $respBox
    Select <b>every category it fails to earn</b>. Not all four are missing &mdash; read it against the checklist before you choose. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> That response quotes the correct median for both groups. Why is that not enough to earn <b>Center</b>? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
