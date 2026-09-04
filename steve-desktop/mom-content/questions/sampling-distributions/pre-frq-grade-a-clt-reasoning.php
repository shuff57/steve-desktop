// === NAME - DESCRIPTION: Pre-FRQ Grade a CLT Reasoning - the scenario and grading checklist of the sampling-distribution FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ mirror of frq/sampling-distributions/q1-sampling-distribution-reasoning.php.
// Categories: Parameter vs Statistic (3) / Conditions for CLT (3) / Standard Error (4) = 10.
//
// The dropped category is CONDITIONS FOR CLT. A student can name the parameter and statistic and
// compute the standard error without ever verifying that the sampling distribution is
// approximately normal: the section's own "word before the number" rule is exactly the step a
// plausible answer skips.
//
// CATEGORY PURITY: $sParam names the parameter and statistic and nothing else; $sConditions
// states the three conditions and nothing else; $sSE computes and interprets the standard error
// and nothing else. Each sentence earns exactly one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$sParam = "The population parameter is mu = 28 minutes, the mean commute time for all adults in the metro area; the sample statistic is bar(x), the mean of one random sample of 50 commuters, which varies from sample to sample."
$sConditions = "The sample is random, the population is far larger than 10 times the sample size, and n = 50 is at least 30, so the central limit theorem says the sampling distribution of bar(x) is approximately normal even though the population is right-skewed."
$sSE = "The standard error is SE = sigma/sqrt(n) = 8/sqrt(50) ~= 1.13 minutes, the typical distance between a sample mean from a random sample of 50 and the population mean."

$rFull    = $sParam . ' ' . $sConditions . ' ' . $sSE
$rNoConditions = $sParam . ' ' . $sSE
$rNoParam = $sConditions . ' ' . $sSE
$rMinimal  = $sParam . ' The standard error is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoConditions
$rC = $rNoParam
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoConditions
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoParam
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noCondLabel = "B"
if ($pos == 1) { $noCondLabel = "A" }

$questions[1] = array(
  "Parameter vs Statistic (3 pts)",
  "Conditions for CLT (3 pts)",
  "Standard Error (4 pts)"
)
$answer[1] = "0,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The parameter and statistic can be named and the standard error computed without ever verifying that the sampling distribution is approximately normal, so the conditions have to be stated on their own.",
  "Yes. Once the sample size is at least 30, the conditions follow automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the standard error is computed, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope61 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope61 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope61 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope61 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope61 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope61 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope61 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope61 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope61 .row-colored { background:#fff9ea; }
  .qscope61 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope61 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope61">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Parameter vs Statistic<br>(3 pts)</b></td>
            <td>Identify the population parameter (mu) in context and the sample statistic (bar(x)), and explain that the statistic varies from sample to sample.</td></tr>
          <tr><td style="text-align:center;"><b>Conditions for CLT<br>(3 pts)</b></td>
            <td>Verify the sample is random, observations are approximately independent, and the sampling distribution is approximately normal (population shape OR n >= 30).</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Standard Error<br>(4 pts)</b></td>
            <td>Compute SE = sigma/sqrt(n) and interpret it as the typical distance of bar(x) from mu.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope61">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> names the parameter and statistic, verifies the conditions, and computes and interprets the standard error. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sParam . ' ' . $sConditions . ' ' . $sSE . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noCondLabel . ' line by line.</span></p>
      <ul>
        <li><b>Parameter vs Statistic: earned.</b> The population parameter and the sample statistic are named, and the statistic is explained as varying from sample to sample.</li>
        <li><b>Conditions for CLT: NOT earned.</b> The response never verifies that the sampling distribution is approximately normal, so the conditions are missing.</li>
        <li><b>Standard Error: earned.</b> The standard error is computed and interpreted as the typical distance of the sample mean from the population mean.</li>
      </ul>
      <p><span class="term-label">Part (c): why the conditions are their own category.</span> Computing the standard error assumes the sampling distribution is approximately normal in the first place. Without the conditions check, the computation has no licence: the CLT only applies when the sample is random, independent, and large enough (or the population is normal).</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The conditions are the category most often skipped, because once the arithmetic works the verification feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> Commute times for adults in a metro area have mean 28 minutes and standard deviation 8 minutes (right-skewed). A transportation study takes a random sample of 50 commuters.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Describe the sampling distribution of the sample mean bar(x). Identify the population parameter and the sample statistic, verify the conditions for the sampling distribution to be approximately normal, and compute and interpret the standard error.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noCondLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is naming the parameter and statistic and computing the standard error enough on its own to cover verifying the conditions for the CLT? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
