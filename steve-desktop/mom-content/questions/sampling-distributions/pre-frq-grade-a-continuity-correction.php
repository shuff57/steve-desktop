// === NAME - DESCRIPTION: Pre-FRQ Grade a Continuity Correction - a binomial-approximation scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for §6.3's binomial approximation. No continuity-correction FRQ exists to mirror
// (the CLT-reasoning and sum-interpretation mirrors are claimed by §6.1/§6.2), so the scenario
// and checklist are ORIGINAL and define the shape a later FRQ should match.
//
// Categories: State the Binomial Setup (3) / Apply the Continuity Correction (5) /
// Compute and Interpret (4) = 12.
//
// The dropped category is APPLY THE CONTINUITY CORRECTION. A student can set up the binomial
// and compute a probability without ever moving the boundary: the section's own warning, "an
// answer computed without the correction still looks reasonable, still lands in the right
// decimal place", is exactly the step a plausible answer skips.
//
// CATEGORY PURITY: $sSetup states the binomial setup and nothing else; $sCorrection states the
// corrected boundary and nothing else; $sCompute states the computation and interpretation and
// nothing else.
$anstypes = array("choices", "multans", "choices")

$sSetup = "The count of supporters is X ~ B(500, 0.46) with mu = np = 230 and sigma = sqrt(npq) ~= 11.14; since np = 230 > 5 and nq = 270 > 5, the normal approximation is allowed."
$sCorrection = "At least 250 includes 250, so the boundary moves outward by half a unit: the probability is P(Y >= 249.5)."
$sCompute = "Standardizing gives z = (249.5 - 230)/11.14 ~= 1.75, so the probability is about 0.0401: only about a 4% chance that at least half the sample of 500 favors the incumbent."

$rFull    = $sSetup . ' ' . $sCorrection . ' ' . $sCompute
$rNoCorrection = $sSetup . ' ' . $sCompute
$rNoSetup = $sCorrection . ' ' . $sCompute
$rMinimal  = $sSetup . ' The probability is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoCorrection
$rC = $rNoSetup
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoCorrection
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoSetup
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noCorrectionLabel = "B"
if ($pos == 1) { $noCorrectionLabel = "A" }

$questions[1] = array(
  "State the Binomial Setup (3 pts)",
  "Apply the Continuity Correction (5 pts)",
  "Compute and Interpret (4 pts)"
)
$answer[1] = "0,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The binomial can be set up and a probability computed without ever moving the boundary, so the correction has to be applied on its own: without it the computation is wrong by about half a bar's worth of probability every time.",
  "Yes. Once the probability is computed, the correction is implied by the formula, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the answer is a probability, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope63 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope63 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope63 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope63 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope63 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope63 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope63 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope63 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope63 .row-colored { background:#fff9ea; }
  .qscope63 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope63 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope63">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Binomial Setup<br>(3 pts)</b></td>
            <td>Name X ~ B(n, p), compute mu = np and sigma = sqrt(npq), and check the conditions.</td></tr>
          <tr><td style="text-align:center;"><b>Apply the Continuity Correction<br>(5 pts)</b></td>
            <td>Move the boundary half a unit in the direction the inequality demands and state the corrected probability statement.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Compute and Interpret<br>(4 pts)</b></td>
            <td>Standardize, evaluate, and read the result in context.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope63">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the binomial setup, applies the continuity correction, and computes and interprets the probability. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sSetup . ' ' . $sCorrection . ' ' . $sCompute . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noCorrectionLabel . ' line by line.</span></p>
      <ul>
        <li><b>State the Binomial Setup: earned.</b> The binomial is named with its parameters and the conditions are checked.</li>
        <li><b>Apply the Continuity Correction: NOT earned.</b> The response never moves the boundary half a unit, so the correction is missing.</li>
        <li><b>Compute and Interpret: earned.</b> The standardized probability is evaluated and read in context.</li>
      </ul>
      <p><span class="term-label">Part (c): why the correction is its own category.</span> The approximation is not doing anything clever about which bars belong in your event: you decide that by reading the inequality. What the half-unit repairs is the mismatch between a bar that occupies real width on the number line and a curve that only has area. An answer computed without it still looks reasonable, still lands in the right decimal place, and is simply wrong by about half a bar\'s worth of probability every time.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The correction is the category most often skipped, because once the arithmetic works the half-unit feels like a rounding nicety.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> In a city, 46 percent of the population favor the incumbent, Dawn Morgan, for mayor. A simple random sample of 500 is taken.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the binomial setup for the number who favor the incumbent, apply the continuity correction to find the probability that at least 250 favor her, and interpret the result.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noCorrectionLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is stating the binomial setup and computing the probability enough on its own to cover applying the continuity correction? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
