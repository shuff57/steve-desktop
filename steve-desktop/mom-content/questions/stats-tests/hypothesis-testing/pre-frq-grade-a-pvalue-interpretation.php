// === NAME - DESCRIPTION: Pre-FRQ Grade a P-Value Interpretation - the scenario and grading checklist of the single-mean interpreting-p-value FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ mirror of frq/inference-for-means/q2-single-mean-interpreting-p-value.php.
// Categories: Statistical Decision (4) / Conclusion in Context (3) / Interpretation of
// Evidence (3) = 10.
//
// The dropped category is INTERPRETATION OF EVIDENCE. A student can compare the p-value to
// alpha and write the conclusion in context without ever explaining what the p-value measures
// in repeated sampling - the section's own "the p-value measures the surprise, not the truth"
// is exactly the step a plausible answer skips. This is DIFFERENT from 7.3's pre-FRQ drop
// (Statistical Decision) - the two p-value pre-FRQs must not teach the same lesson.
//
// CATEGORY PURITY: $sDecision states the decision and nothing else; $sConclusion writes the
// conclusion in context and nothing else; $sEvidence interprets the evidence and nothing else.
$anstypes = array("choices", "multans", "choices")

$contexts = array(
  array("A school principal claims the average nightly sleep time for students at the school is less than 7.0 hours. A sample of 64 students gives a sample mean of 6.8 hours and a p-value of 0.12 at alpha = 0.05.",
        "Since the p-value (0.12) is greater than alpha = 0.05, the result is not statistically significant and we fail to reject the null hypothesis.",
        "In context, the sample does not provide strong enough evidence that students at this school average less than 7.0 hours of sleep per night.",
        "The p-value of 0.12 means that if the true mean really were 7.0 hours, a sample mean this low or lower would turn up about 12% of the time."),
  array("A factory manager claims the average fill weight of cereal boxes from a production line is greater than 500 grams. A sample of 40 boxes gives a sample mean of 501.2 grams and a p-value of 0.018 at alpha = 0.05.",
        "Since the p-value (0.018) is less than alpha = 0.05, the result is statistically significant and we reject the null hypothesis.",
        "In context, the sample provides convincing evidence that the true average fill weight is above 500 grams on this production line.",
        "The p-value of 0.018 means that if the true mean really were 500 grams, a sample mean this high or higher would turn up about 1.8% of the time."),
  array("A nutritionist claims the average daily sugar intake for clients in a wellness program is less than 35 grams. A sample of 55 clients gives a sample mean of 33.9 grams and a p-value of 0.041 at alpha = 0.01.",
        "Since the p-value (0.041) is greater than alpha = 0.01, the result is not statistically significant and we fail to reject the null hypothesis.",
        "In context, the sample does not provide strong enough evidence that the true average daily sugar intake is below 35 grams for clients in this program.",
        "The p-value of 0.041 means that if the true mean really were 35 grams, a sample mean this low or lower would turn up about 4.1% of the time.")
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i][0]
$sDecision = $contexts[$i][1]
$sConclusion = $contexts[$i][2]
$sEvidence = $contexts[$i][3]

$rFull    = $sDecision . ' ' . $sConclusion . ' ' . $sEvidence
$rNoEvidence = $sDecision . ' ' . $sConclusion
$rNoConclusion = $sDecision . ' ' . $sEvidence
$rMinimal = $sDecision . ' The p-value is the whole story.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoEvidence
$rC = $rNoConclusion
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoEvidence
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoConclusion
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noEvidenceLabel = "B"
if ($pos == 1) { $noEvidenceLabel = "A" }

$questions[1] = array(
  "Statistical Decision (4 pts)",
  "Conclusion in Context (3 pts)",
  "Interpretation of Evidence (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The decision can be made and the conclusion written without ever explaining what the p-value measures in repeated sampling, so interpreting the evidence has to be its own category.",
  "Yes. Once the decision is made, the interpretation follows automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the p-value is reported, the interpretation does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope84 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope84 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope84 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope84 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope84 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope84 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope84 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope84 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope84 .row-colored { background:#fff9ea; }
  .qscope84 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope84 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope84">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Statistical Decision<br>(4 pts)</b></td>
            <td>Compare the p-value to the significance level and make the correct reject / fail-to-reject decision.</td></tr>
          <tr><td style="text-align:center;"><b>Conclusion in Context<br>(3 pts)</b></td>
            <td>Write the conclusion in plain sentences about the population and the original claim.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Interpretation of Evidence<br>(3 pts)</b></td>
            <td>Explain what the p-value measures: the probability of a sample result this extreme or more so, if the null were true.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope84">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> makes the decision, writes the conclusion in context, and interprets the evidence. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sDecision . ' ' . $sConclusion . ' ' . $sEvidence . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noEvidenceLabel . ' line by line.</span></p>
      <ul>
        <li><b>Statistical Decision: earned.</b> The p-value is compared to alpha and the correct decision is made.</li>
        <li><b>Conclusion in Context: earned.</b> The conclusion is written in plain sentences about the population and the claim.</li>
        <li><b>Interpretation of Evidence: NOT earned.</b> The response never explains what the p-value measures in repeated sampling.</li>
      </ul>
      <p><span class="term-label">Part (c): why the interpretation is its own category.</span> The decision and the conclusion are both possible without ever explaining what the p-value measures: the interpretation is the step that says what the number means, and a response that skips it has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The interpretation of evidence is the category most often skipped, because once the decision is made the p-value feels like a number that speaks for itself.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $ctx</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Compare the p-value to the significance level and make the decision, write the conclusion in context, and explain what the p-value measures.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noEvidenceLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is making the decision and writing the conclusion enough on its own to cover interpreting the evidence? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
