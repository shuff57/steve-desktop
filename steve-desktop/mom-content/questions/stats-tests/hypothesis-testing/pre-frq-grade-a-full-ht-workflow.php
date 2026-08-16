// === NAME - DESCRIPTION: Pre-FRQ Grade a Full HT Workflow - the scenario and grading checklist of the full-HT-workflow FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ mirror of frq/inference-for-proportions/q14-full-ht-workflow-context.php.
// Categories: Hypotheses (2) / Conditions (3) / Test stat + p-value (3) /
// Decision + Conclusion (2) = 10.
//
// The dropped category is TEST STAT + P-VALUE. A student can write the hypotheses and reach
// the decision and conclusion without ever showing the arithmetic - the section's own "the
// pieces are put together here" is exactly the step a plausible answer skips (the computation
// is the one category the others imply but never demand).
//
// CATEGORY PURITY: $sHypotheses states the pair and nothing else; $sConditions checks the
// conditions and nothing else; $sTestStat shows the arithmetic and nothing else; $sDecision
// states the decision and conclusion and nothing else.
$anstypes = array("choices", "multans", "choices")

$contexts = array(
  array("A researcher claims more than 30% of registered voters in the county voted in the primary election. A random sample of 200 voters finds 70 voted. Test at alpha = 0.05.",
        "`H_0: p = 0.30` vs `H_a: p > 0.30` (a right-tailed test).",
        "The sample is random; the population is at least 10 times 200; and the large-counts conditions hold since n times p_0 = 60 >= 10 and n times (1 - p_0) = 140 >= 10.",
        "p-hat = 70/200 = 0.35, SE = 0.0324, so z = (0.35 - 0.30)/0.0324 = 1.54. The p-value is 0.0618 (right-tailed).",
        "Since the p-value 0.0618 is greater than alpha = 0.05, we fail to reject `H_0`. There is NOT enough evidence at the 5% level to support the claim that the proportion of registered voters who voted is greater than 30%."),
  array("A health organization claims 9.5% of adults suffer from depression. A random sample of 300 adults finds 24 suffer from depression. Test at alpha = 0.05 whether the proportion differs from 9.5%.",
        "`H_0: p = 0.095` vs `H_a: p != 0.095` (a two-tailed test).",
        "The sample is random; the population is at least 10 times 300; and the large-counts conditions hold since n times p_0 = 28.5 >= 10 and n times (1 - p_0) = 271.5 >= 10.",
        "p-hat = 24/300 = 0.08, SE = 0.0169, so z = (0.08 - 0.095)/0.0169 = -0.89. The p-value is 0.3734 (two-tailed).",
        "Since the p-value 0.3734 is greater than alpha = 0.05, we fail to reject `H_0`. There is NOT enough evidence at the 5% level to support the claim that the proportion of adults who suffer from depression differs from 9.5%."),
  array("A survey claims the proportion of students who prefer online classes is less than 50%. A random sample of 80 students finds 32 prefer online classes. Test at alpha = 0.05.",
        "`H_0: p = 0.50` vs `H_a: p < 0.50` (a left-tailed test).",
        "The sample is random; the population is at least 10 times 80; and the large-counts conditions hold since n times p_0 = 40 >= 10 and n times (1 - p_0) = 40 >= 10.",
        "p-hat = 32/80 = 0.40, SE = 0.0559, so z = (0.40 - 0.50)/0.0559 = -1.79. The p-value is 0.0367 (left-tailed).",
        "Since the p-value 0.0367 is less than alpha = 0.05, we reject `H_0`. There is statistically significant evidence at the 5% level to support the claim that the proportion of students who prefer online classes is less than 50%.")
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i][0]
$sHypotheses = $contexts[$i][1]
$sConditions = $contexts[$i][2]
$sTestStat = $contexts[$i][3]
$sDecision = $contexts[$i][4]

$rFull    = $sHypotheses . ' ' . $sConditions . ' ' . $sTestStat . ' ' . $sDecision
$rNoTest  = $sHypotheses . ' ' . $sConditions . ' ' . $sDecision
$rNoCond  = $sHypotheses . ' ' . $sTestStat . ' ' . $sDecision
$rMinimal = $sHypotheses . ' ' . $sDecision

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoTest
$rC = $rNoCond
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoTest
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoCond
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noTestLabel = "B"
if ($pos == 1) { $noTestLabel = "A" }

$questions[1] = array(
  "Hypotheses (2 pts)",
  "Conditions (3 pts)",
  "Test stat + p-value (3 pts)",
  "Decision + Conclusion (2 pts)"
)
$answer[1] = "0,1,3"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The hypotheses can be written and the decision and conclusion reached without ever showing the arithmetic, so the test statistic and p-value have to be their own category.",
  "Yes. Once the hypotheses are written, the arithmetic follows automatically, so there is nothing separate to award.",
  "No, but only because the conditions are the hard part.",
  "Yes, as long as the decision is stated, the arithmetic does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope85 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope85 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope85 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope85 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope85 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope85 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope85 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope85 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope85 .row-colored { background:#fff9ea; }
  .qscope85 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope85 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope85">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Hypotheses<br>(2 pts)</b></td>
            <td>State `H_0` and `H_a` in symbols with the tail named.</td></tr>
          <tr><td style="text-align:center;"><b>Conditions<br>(3 pts)</b></td>
            <td>Verify the random sample, the population size, and the large-counts conditions.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Test stat + p-value<br>(3 pts)</b></td>
            <td>Compute the sample proportion, the standard error, the test statistic, and the p-value.</td></tr>
          <tr><td style="text-align:center;"><b>Decision + Conclusion<br>(2 pts)</b></td>
            <td>Compare the p-value to alpha, make the decision, and write the conclusion in context.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope85">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all four.</span> <b>Response ' . $fullLabel . '</b> writes the hypotheses, checks the conditions, shows the arithmetic, and states the decision and conclusion. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sHypotheses . ' ' . $sConditions . ' ' . $sTestStat . ' ' . $sDecision . '</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noTestLabel . ' line by line.</span></p>
      <ul>
        <li><b>Hypotheses &mdash; earned.</b> The pair is stated in symbols with the tail named.</li>
        <li><b>Conditions &mdash; earned.</b> The random sample, population size, and large-counts conditions are verified.</li>
        <li><b>Test stat + p-value &mdash; NOT earned.</b> The response never shows the arithmetic &mdash; no sample proportion, no standard error, no test statistic, no p-value.</li>
        <li><b>Decision + Conclusion &mdash; earned.</b> The p-value is compared to alpha and the conclusion is written in context.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the arithmetic is its own category.</span> The hypotheses and the decision-and-conclusion are both possible without ever showing the computation &mdash; the test statistic and p-value are the one category the others imply but never demand, and a response that skips them has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The arithmetic is the category most often skipped, because once the hypotheses are written the computation feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $ctx</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Set up the hypotheses, verify the conditions, compute the test statistic and p-value, make the decision at the stated significance level, and write a conclusion that connects the result back to the original claim.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 4px 0;"><b>Four students answered.</b></p>
    $responses
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response earns <b>full credit</b> on all four categories? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noTestLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is writing the hypotheses and reaching the decision and conclusion enough on its own to cover showing the test statistic and p-value? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
