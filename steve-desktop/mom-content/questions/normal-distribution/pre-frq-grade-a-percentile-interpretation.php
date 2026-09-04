// === NAME - DESCRIPTION: Pre-FRQ Grade a Percentile Interpretation - the scenario and grading checklist of the percentile FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ mirror of frq/normal-distribution/q12-normal-distribution-and-percentiles.php.
// Categories: Formula Application (4) / Threshold Interpretation (3) / Population Count (3) = 10.
//
// The dropped category is THRESHOLD INTERPRETATION. A student can apply the formula and report
// the expected count without ever saying what the cutoff means in context: the section's own
// Insight Note ("a percentile is a rank, not a score") is exactly the step a plausible answer
// skips.
//
// CATEGORY PURITY: $sFormula states the arithmetic and nothing else; $sInterp states what the
// cutoff means and nothing else; $sCount states the expected count and nothing else. Each
// sentence earns exactly one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$contexts = array(
  array("a standardized aptitude test", "test score", "test-takers", 1000, 200, 1280, 5000),
  array("annual household incomes, in thousands of dollars", "income level", "households", 60, 15, 79, 4000),
  array("employee performance review scores", "performance score", "employees", 350, 50, 414, 3000)
)
$zs = array(1.28, 1.28, 1.28)

$i = rand(0, 2)
$topic = $contexts[$i][0]
$score_name = $contexts[$i][1]
$group_name = $contexts[$i][2]
$mu = $contexts[$i][3]
$sigma = $contexts[$i][4]
$threshold = $contexts[$i][5]
$pop = $contexts[$i][6]
$z = $zs[$i]

$top_count = round($pop * 0.10)

$sFormula = "To find the " . $score_name . " needed for the top 10%, use x = mu + z*sigma: x = " . $mu . " + 1.28*" . $sigma . " = " . $threshold . "."
$sInterp = "A " . $score_name . " of " . $threshold . " is the cutoff to place in the top 10% of all " . $group_name . "."
$sCount = "Out of " . $pop . " " . $group_name . ", 10% comes to " . $top_count . ", so roughly " . $top_count . " " . $group_name . " would be expected to reach or exceed this threshold."

$rFull    = $sFormula . ' ' . $sInterp . ' ' . $sCount
$rNoInterp = $sFormula . ' ' . $sCount
$rNoCount = $sFormula . ' ' . $sInterp
$rMinimal  = $sFormula . ' The number is the answer.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoInterp
$rC = $rNoCount
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoInterp
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoCount
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noInterpLabel = "B"
if ($pos == 1) { $noInterpLabel = "A" }

$questions[1] = array(
  "Formula Application (4 pts)",
  "Threshold Interpretation (3 pts)",
  "Population Count (3 pts)"
)
$answer[1] = "0,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The formula can be applied and the count reported without ever saying what the cutoff means in context, so the interpretation has to be stated on its own.",
  "Yes. Once the cutoff is computed, what it means follows automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the count is reported, the interpretation does not need its own sentence."
)
$answer[2] = 0

$css = '
<style>
  .qscope52 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope52 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope52 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope52 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope52 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope52 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope52 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope52 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope52 .row-colored { background:#fff9ea; }
  .qscope52 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope52 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope52">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Formula Application<br>(4 pts)</b></td>
            <td>Identify the formula for converting a z-score to a raw score, substitute, and show the calculation.</td></tr>
          <tr><td style="text-align:center;"><b>Threshold Interpretation<br>(3 pts)</b></td>
            <td>Explain what the calculated score represents in the context of the problem.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Population Count<br>(3 pts)</b></td>
            <td>Calculate how many individuals out of the total would reach or exceed the threshold.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope52">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> applies the formula, interprets the cutoff, and reports the expected count. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sFormula . ' ' . $sInterp . ' ' . $sCount . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noInterpLabel . ' line by line.</span></p>
      <ul>
        <li><b>Formula Application: earned.</b> The formula is identified, the values are substituted, and the calculation is shown.</li>
        <li><b>Threshold Interpretation: NOT earned.</b> The response never says what the cutoff means in context, so the interpretation is missing.</li>
        <li><b>Population Count: earned.</b> The expected number of ' . $group_name . ' at or above the threshold is reported.</li>
      </ul>
      <p><span class="term-label">Part (c): why the interpretation is its own category.</span> A percentile is a rank, not a score: reporting "the 90th percentile is 1280" tells a reader almost nothing on its own. The sentence "90% of ' . $group_name . ' are 1280 or less" is the entire reason anyone computes a percentile in the first place, and it is a separate judgement from the arithmetic.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The interpretation sentence is the category most often skipped, because once the number is computed the meaning feels obvious.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> Scores on $topic are normally distributed with a mean of $mu and a standard deviation of $sigma. There are $pop $group_name in this group.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Calculate the $score_name needed to be in the top 10% (z = 1.28), explain what this score represents, and determine how many $group_name out of $pop would be expected to reach or exceed it.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noInterpLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is applying the formula and reporting the count enough on its own to cover explaining what the cutoff means? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
