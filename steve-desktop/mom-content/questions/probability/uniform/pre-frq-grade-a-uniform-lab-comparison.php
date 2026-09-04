// === NAME - DESCRIPTION: Pre-FRQ Grade a Uniform Lab Comparison - A theoretical-vs-empirical comparison with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for the 4.6 lab. No lab FRQ exists to mirror, so the scenario and checklist are
// ORIGINAL and define the shape a later lab FRQ should match. See reference/pre-frq-template.md.
//
// Categories: State the Theoretical Value (3 pts) / State the Empirical Value (3 pts) /
// Compare and Explain the Gap (4 pts) = 10.
//
// The dropped category is STATE THE THEORETICAL VALUE. A student can report the empirical
// statistic and compare it without ever stating what the theory predicts: the comparison then
// has no standard. This is deliberately the SAME dropped category as the 4.4 lab pre-FRQ: the two
// labs are a matched pair teaching the same empirical-vs-theoretical lesson.
//
// CATEGORY PURITY: $sTheory states the theoretical value and nothing else; $sEmp states the
// empirical value and nothing else; $sCompare states the gap and why it exists without
// re-stating either number. Each sentence earns exactly one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$sTheory = "The theoretical mean of U(0, 1) is mu = 0.5."
$sEmp = "The pilot's sample mean is x-bar = 0.4980."
$sCompare = "The sample mean is 0.0020 below the theoretical one, which is the ordinary wobble of twelve values: a sample lands near the theory, not on it."

$rFull    = $sTheory . ' ' . $sEmp . ' ' . $sCompare
$rNoTheory = $sEmp . ' ' . $sCompare
$rNoEmp   = $sTheory . ' ' . $sCompare
$rMinimal  = $sTheory . ' The comparison is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoTheory
$rC = $rNoEmp
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoTheory
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoEmp
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noTheoryLabel = "B"
if ($pos == 1) { $noTheoryLabel = "A" }

$questions[1] = array(
  "State the Theoretical Value (3 pts)",
  "State the Empirical Value (3 pts)",
  "Compare and Explain the Gap (4 pts)"
)
$answer[1] = "1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The empirical value can be reported and compared without ever stating what the theory predicts, so the comparison has no standard: the theoretical value has to be stated on its own.",
  "Yes. Once the empirical value is reported, the theoretical one is implied by the formula, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the two numbers are close, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope46 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope46 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope46 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope46 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope46 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope46 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope46 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope46 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope46 .row-colored { background:#fff9ea; }
  .qscope46 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope46 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope46">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Theoretical Value<br>(3 pts)</b></td>
            <td>State the parameter the distribution predicts.</td></tr>
          <tr><td style="text-align:center;"><b>State the Empirical Value<br>(3 pts)</b></td>
            <td>State the statistic the generated data actually produced.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Compare and Explain the Gap<br>(4 pts)</b></td>
            <td>Say how far apart the two are and why they are not exact.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope46">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the theoretical value, the empirical value, and the gap with its explanation. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sTheory . ' ' . $sEmp . ' ' . $sCompare . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noTheoryLabel . ' line by line.</span></p>
      <ul>
        <li><b>State the Theoretical Value: NOT earned.</b> The empirical value is reported and compared, but nowhere does the response state what the distribution predicts, so the comparison has no standard.</li>
        <li><b>State the Empirical Value: earned.</b> The statistic from the generated data is present.</li>
        <li><b>Compare and Explain the Gap: earned.</b> The gap is measured and explained as the ordinary wobble of a sample.</li>
      </ul>
      <p><span class="term-label">Part (c): why the theoretical value is its own category.</span> The whole point of the lab is holding a measurement up against the thing it is measuring. Without the theoretical column, "close" and "far" have no meaning: the empirical value is just a number.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab this comparison comes with a blank box and this same checklist. The theoretical value is the category most often skipped, because once the data is tallied the formula feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> In the continuous-distribution lab, a pilot group generated twelve values from U(0,1) and computed a sample mean of 0.4980.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the theoretical mean of the distribution, state the empirical mean from the pilot data, and compare the two, explaining any gap.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noTheoryLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is reporting the empirical value and comparing it enough on its own to cover stating the theoretical value? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
