// === NAME - DESCRIPTION: Pre-FRQ Grade a CI Interpretation - the scenario and grading checklist of the single-mean CI FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ mirror of frq/inference-for-means/q3-single-mean-interpreting-confidence-interval.php.
// Categories: CI Interpretation (4) / Confidence Level Meaning (3) / Assessing the Claim (3) = 10.
//
// The dropped category is CONFIDENCE LEVEL MEANING. A student can interpret the interval and
// assess the claim without ever explaining what the confidence level means in repeated sampling
// — the section's own "the interval moves, not the truth" is exactly the step a plausible
// answer skips.
//
// CATEGORY PURITY: $sInterp states the interval interpretation and nothing else; $sLevel states
// what the confidence level means and nothing else; $sClaim assesses the claim and nothing else.
$anstypes = array("choices", "multans", "choices")

$sInterp = "We are 95% confident that the true mean weekly study time for all students at the school is between 11.8 and 14.6 hours per week."
$sLevel = "The 95% confidence level describes the long-run performance of this method: if we repeatedly took random samples of 50 students and built confidence intervals the same way, about 95% of those intervals would capture the true population mean."
$sClaim = "The claimed value mu0 = 15 is outside the interval (11.8, 14.6), so the claim is not plausible based on this sample."

$rFull    = $sInterp . ' ' . $sLevel . ' ' . $sClaim
$rNoLevel = $sInterp . ' ' . $sClaim
$rNoClaim = $sInterp . ' ' . $sLevel
$rMinimal  = $sInterp . ' The interval is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoLevel
$rC = $rNoClaim
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoLevel
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoClaim
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noLevelLabel = "B"
if ($pos == 1) { $noLevelLabel = "A" }

$questions[1] = array(
  "CI Interpretation (4 pts)",
  "Confidence Level Meaning (3 pts)",
  "Assessing the Claim (3 pts)"
)
$answer[1] = "0,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The interval can be interpreted and the claim assessed without ever explaining what the confidence level means in repeated sampling, so the level has to be explained on its own.",
  "Yes. Once the interval is interpreted, what the confidence level means follows automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the interval is reported, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope71 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope71 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope71 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope71 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope71 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope71 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope71 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope71 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope71 .row-colored { background:#fff9ea; }
  .qscope71 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope71 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope71">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>CI Interpretation<br>(4 pts)</b></td>
            <td>Interpret the interval in context, naming the true population mean, the population, and the bounds with units.</td></tr>
          <tr><td style="text-align:center;"><b>Confidence Level Meaning<br>(3 pts)</b></td>
            <td>Explain what the confidence level means in terms of the long-run behavior of the interval method.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Assessing the Claim<br>(3 pts)</b></td>
            <td>Use whether the claimed value falls inside or outside the interval to decide if the claim is plausible.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope71">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> interprets the interval, explains the confidence level, and assesses the claim. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sInterp . ' ' . $sLevel . ' ' . $sClaim . '</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noLevelLabel . ' line by line.</span></p>
      <ul>
        <li><b>CI Interpretation &mdash; earned.</b> The interval is interpreted in context with the population and the bounds named.</li>
        <li><b>Confidence Level Meaning &mdash; NOT earned.</b> The response never explains what the confidence level means in repeated sampling, so the level is missing.</li>
        <li><b>Assessing the Claim &mdash; earned.</b> The claimed value is checked against the interval and judged not plausible.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the level is its own category.</span> The interval moves, not the truth: the population mean is one fixed number that never budges, and it is the interval that jumps around from sample to sample. Explaining what the confidence level means &mdash; the method captures the true mean 95% of the time &mdash; is a separate judgement from reading the interval, and a response that skips it has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The confidence level meaning is the category most often skipped, because once the interval is written the level feels like commentary.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> A school counselor is studying weekly study time for all students at the school. A random sample of n = 50 gives a sample mean of x-bar = 13.2 hours per week. A 95% confidence interval for the true population mean is (11.8, 14.6) hours per week. The claim being evaluated is mu0 = 15 hours per week.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Interpret the confidence interval in context, explain what "95% confident" means, and use the interval to evaluate whether the claim about the population mean is plausible.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noLevelLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is interpreting the interval and assessing the claim enough on its own to cover explaining what the confidence level means? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
