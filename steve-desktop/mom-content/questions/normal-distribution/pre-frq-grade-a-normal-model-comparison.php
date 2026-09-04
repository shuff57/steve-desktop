// === NAME - DESCRIPTION: Pre-FRQ Grade a Normal Model Comparison - a theoretical-vs-empirical comparison with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for the 5.3 lab. No lab FRQ exists to mirror, so the scenario and checklist are
// ORIGINAL and define the shape a later lab FRQ should match. See reference/pre-frq-template.md.
//
// Categories: State the Empirical Value (3) / State the Theoretical Value (3) /
// Compare and Explain the Gap (4) = 10.
//
// The dropped category is STATE THE EMPIRICAL VALUE. A student can report the theoretical value
// and compare it without ever doing the counting: the section's own text says the theoretical
// model "genuinely outperforms counting", so the empirical step is the one a plausible answer
// skips; the comparison then has no evidence behind it. This is DIFFERENT from the 4.4/4.6 lab
// pre-FRQs' dropped category (State the Theoretical Value): the 4.4/4.6 pair shares a drop
// deliberately, but a third repeat would teach the same lesson again.
//
// CATEGORY PURITY: $sEmp states the counted value and nothing else; $sTheory states the model's
// value and nothing else; $sCompare states the gap and why it exists without re-stating either
// number. Each sentence earns exactly one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$sEmp = "The pilot's empirical median is 129.15 seconds."
$sTheory = "The theoretical model N(129.42, 2.52) predicts a median of 129.42 seconds."
$sCompare = "The two medians are 0.27 seconds apart, which is agreement: a sample of twelve lands near the model, not on it."

$rFull    = $sEmp . ' ' . $sTheory . ' ' . $sCompare
$rNoEmp   = $sTheory . ' ' . $sCompare
$rNoTheory = $sEmp . ' ' . $sCompare
$rMinimal  = $sEmp . ' The comparison is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoEmp
$rC = $rNoTheory
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoEmp
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoTheory
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noEmpLabel = "B"
if ($pos == 1) { $noEmpLabel = "A" }

$questions[1] = array(
  "State the Empirical Value (3 pts)",
  "State the Theoretical Value (3 pts)",
  "Compare and Explain the Gap (4 pts)"
)
$answer[1] = "1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The theoretical value can be reported and compared without ever doing the counting, so the empirical value has to be stated on its own: the comparison then has evidence behind it.",
  "Yes. Once the theoretical value is reported, the empirical one is implied by the data, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the two numbers are close, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope53 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope53 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope53 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope53 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope53 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope53 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope53 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope53 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope53 .row-colored { background:#fff9ea; }
  .qscope53 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope53 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope53">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Empirical Value<br>(3 pts)</b></td>
            <td>State the statistic the sampled data actually produced.</td></tr>
          <tr><td style="text-align:center;"><b>State the Theoretical Value<br>(3 pts)</b></td>
            <td>State the value the normal model predicts.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Compare and Explain the Gap<br>(4 pts)</b></td>
            <td>Say how far apart the two are and why they are not exact.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope53">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the empirical value, the theoretical value, and the gap with its explanation. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sEmp . ' ' . $sTheory . ' ' . $sCompare . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noEmpLabel . ' line by line.</span></p>
      <ul>
        <li><b>State the Empirical Value: NOT earned.</b> The theoretical value is reported and compared, but nowhere does the response state what the sampled data actually produced, so the comparison has no evidence behind it.</li>
        <li><b>State the Theoretical Value: earned.</b> The value the model predicts is present.</li>
        <li><b>Compare and Explain the Gap: earned.</b> The gap is measured and explained as the ordinary wobble of a sample.</li>
      </ul>
      <p><span class="term-label">Part (c): why the empirical value is its own category.</span> The whole point of the lab is holding a measurement up against the thing it is measuring. Without the empirical column, "close" and "far" have no evidence behind them: the theoretical value is just a number with nothing to compare it to.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab this comparison comes with a blank box and this same checklist. The empirical value is the category most often skipped, because once the model is fitted the counting feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> In the lap-time lab, a pilot group sampled twelve lap times and computed an empirical median of 129.15 seconds. Their theoretical model, `N(129.42, 2.52)`, predicts a median of 129.42 seconds.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the empirical median from the pilot data, state the theoretical median from the model, and compare the two, explaining any gap.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noEmpLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is reporting the theoretical value and comparing it enough on its own to cover stating the empirical value? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
