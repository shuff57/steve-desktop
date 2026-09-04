// === NAME - DESCRIPTION: Pre-FRQ Grade a Sum Interpretation - a sums scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for §6.2 sums. No sums FRQ exists to mirror (the CLT-reasoning mirror is claimed
// by §6.1's pre-FRQ), so the scenario and checklist are ORIGINAL and define the shape a later
// sums FRQ should match. See reference/pre-frq-template.md.
//
// Categories: State the Sum's Parameters (3) / Compute the Probability or Percentile (5) /
// Interpret in Context (4) = 12.
//
// The dropped category is STATE THE SUM'S PARAMETERS. A student can compute the probability and
// interpret it without ever writing Sigma X ~ N(n*mu, sqrt(n)*sigma): the section's own
// warning, "feeding in the original pair is the single most common mistake", is exactly the
// step a plausible answer skips.
//
// CATEGORY PURITY: $sParams states the two parameters and nothing else; $sCompute states the
// computation and nothing else; $sInterpret states the contextual reading and nothing else.
$anstypes = array("choices", "multans", "choices")

$sParams = "The sum has mean n*mu = 50(34) = 1700 years and standard deviation sqrt(n)*sigma = sqrt(50)(15) ~= 106.07 years, so Sigma X ~ N(1700, 106.07)."
$sCompute = "For the between-question, standardize both edges and subtract: P(1500 < Sigma x < 1800) ~= 0.7974."
$sInterpret = "There is about a 79.74% chance that the combined ages of a sample of 50 users fall between 1500 and 1800 years."

$rFull    = $sParams . ' ' . $sCompute . ' ' . $sInterpret
$rNoParams = $sCompute . ' ' . $sInterpret
$rNoCompute = $sParams . ' ' . $sInterpret
$rMinimal  = $sParams . ' The probability is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoParams
$rC = $rNoCompute
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoParams
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoCompute
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noParamsLabel = "B"
if ($pos == 1) { $noParamsLabel = "A" }

$questions[1] = array(
  "State the Sum's Parameters (3 pts)",
  "Compute the Probability or Percentile (5 pts)",
  "Interpret in Context (4 pts)"
)
$answer[1] = "1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The probability can be computed and interpreted without ever writing the sum's own parameters, so they have to be stated on their own: without them the computation has no licence.",
  "Yes. Once the probability is computed, the parameters are implied by the formula, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the answer is a probability, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope62 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope62 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope62 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope62 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope62 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope62 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope62 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope62 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope62 .row-colored { background:#fff9ea; }
  .qscope62 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope62 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope62">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Sum\'s Parameters<br>(3 pts)</b></td>
            <td>Write Sigma X ~ N(n*mu, sqrt(n)*sigma) with the numbers substituted.</td></tr>
          <tr><td style="text-align:center;"><b>Compute the Probability or Percentile<br>(5 pts)</b></td>
            <td>Run normalcdf/invNorm on the sum\'s own distribution and state the value.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Interpret in Context<br>(4 pts)</b></td>
            <td>Read the result as a statement about totals of size n, in the original units.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope62">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the sum\'s parameters, computes the probability, and interprets it in context. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sParams . ' ' . $sCompute . ' ' . $sInterpret . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noParamsLabel . ' line by line.</span></p>
      <ul>
        <li><b>State the Sum\'s Parameters: NOT earned.</b> The probability is computed and interpreted, but nowhere does the response state the sum\'s own mean and standard deviation.</li>
        <li><b>Compute the Probability or Percentile: earned.</b> The normalcdf computation is present with its value.</li>
        <li><b>Interpret in Context: earned.</b> The result is read as a statement about combined ages of a sample of 50.</li>
      </ul>
      <p><span class="term-label">Part (c): why the parameters are their own category.</span> The whole trap of this section is feeding the original mu and sigma into the calculator instead of the sum\'s own (n)(mu) and (sqrt(n))(sigma). Stating the two parameters first is what forces the substitution to happen, and a response that skips it has no licence for its computation.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the sums FRQ this scenario comes with a blank box and this same checklist. The parameters are the category most often skipped, because once the probability works the setup feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> The mean age of iPad users is 34 years, with a standard deviation of 15 years. A sample of 50 users is taken.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the mean and standard deviation of the sum of the 50 ages, find the probability that the sum is between 1500 and 1800 years, and interpret the result in context.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noParamsLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is computing the probability and interpreting it enough on its own to cover stating the sum's parameters? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
