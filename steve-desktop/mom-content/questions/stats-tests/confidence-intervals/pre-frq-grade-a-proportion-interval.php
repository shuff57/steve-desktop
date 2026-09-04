// === NAME - DESCRIPTION: Pre-FRQ Grade a Proportion Interval - the scenario and grading checklist of the single-proportion FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ mirror of frq/inference-for-proportions/q9-single-proportion-interpreting-results.php.
// Categories: Statistical Decision (4) / Conclusion in Context (3) / Interpretation of Evidence
// (3) = 10.
//
// The dropped category is STATISTICAL DECISION. A student can write a conclusion in context and
// interpret the evidence without ever comparing the p-value to the significance level: the
// section's own "the three points are the whole story" is exactly the step a plausible answer
// skips. This is DIFFERENT from §7.1's (dropped: Confidence Level Meaning) and §7.2's (dropped:
// Assessing the Claim) pre-FRQs: the three pre-FRQs must not teach the same lesson.
//
// CATEGORY PURITY: $sDecision states the p-value comparison and nothing else; $sContext states
// the conclusion in context and nothing else; $sEvidence states the evidence takeaway and
// nothing else.
$anstypes = array("choices", "multans", "choices")

$sDecision = "Since the p-value (0.03) is less than alpha = 0.05, the result is statistically significant and we reject the null hypothesis."
$sContext = "The sample showed only 66.5% customer satisfaction, and the data provide convincing evidence that satisfaction has dropped below the claimed 70%."
$sEvidence = "The manager should take the lower satisfaction rate seriously and consider what changes to the menu may be driving the decline."

$rFull    = $sDecision . ' ' . $sContext . ' ' . $sEvidence
$rNoDecision = $sContext . ' ' . $sEvidence
$rNoContext = $sDecision . ' ' . $sEvidence
$rMinimal  = $sDecision . ' The conclusion is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoDecision
$rC = $rNoContext
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoDecision
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoContext
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noDecisionLabel = "B"
if ($pos == 1) { $noDecisionLabel = "A" }

$questions[1] = array(
  "Statistical Decision (4 pts)",
  "Conclusion in Context (3 pts)",
  "Interpretation of Evidence (3 pts)"
)
$answer[1] = "1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The conclusion can be written in context and the evidence interpreted without ever comparing the p-value to the significance level, so the decision has to be stated on its own.",
  "Yes. Once the conclusion is written, the decision follows automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the p-value is reported, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope73 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope73 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope73 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope73 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope73 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope73 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope73 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope73 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope73 .row-colored { background:#fff9ea; }
  .qscope73 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope73 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope73">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Statistical Decision<br>(4 pts)</b></td>
            <td>Compare the p-value to the significance level and state whether the null hypothesis is rejected.</td></tr>
          <tr><td style="text-align:center;"><b>Conclusion in Context<br>(3 pts)</b></td>
            <td>Explain what the decision means for the specific claim, using the real-world context.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Interpretation of Evidence<br>(3 pts)</b></td>
            <td>Describe what the result tells us about the evidence and what the researcher should take away.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope73">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the statistical decision, writes the conclusion in context, and interprets the evidence. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sDecision . ' ' . $sContext . ' ' . $sEvidence . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noDecisionLabel . ' line by line.</span></p>
      <ul>
        <li><b>Statistical Decision: NOT earned.</b> The response never compares the p-value to the significance level, so the decision is missing.</li>
        <li><b>Conclusion in Context: earned.</b> The conclusion is written for the specific claim in the real-world context.</li>
        <li><b>Interpretation of Evidence: earned.</b> The evidence takeaway for the researcher is present.</li>
      </ul>
      <p><span class="term-label">Part (c): why the decision is its own category.</span> The whole point of a hypothesis test is the p-value comparison: whether the result is statistically significant is a separate judgement from what the result means. A response that skips the comparison has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The statistical decision is the category most often skipped, because once the conclusion is written the comparison feels like bookkeeping.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> A restaurant manager wants to test whether at least 70% of customers are satisfied with the new menu. They surveyed 200 randomly selected customers and found that 133 are satisfied. The hypothesis test produces a p-value of 0.03 with a significance level of alpha = 0.05. The hypotheses are H0: p = 0.70 and Ha: p < 0.70.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Write a final conclusion statement explaining what the manager should conclude from this test, addressing whether the null hypothesis is rejected and what the result means in context.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noDecisionLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is writing the conclusion in context and interpreting the evidence enough on its own to cover stating the statistical decision? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
