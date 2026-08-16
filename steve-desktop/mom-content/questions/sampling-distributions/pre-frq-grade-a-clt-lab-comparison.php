// === NAME - DESCRIPTION: Pre-FRQ Grade a CLT Lab Comparison - a pocket-change sampling scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for the 6.4 pocket-change lab. No lab FRQ exists to mirror (the CLT-reasoning,
// sum-interpretation and continuity-correction pre-FRQs are claimed by §6.1/§6.2/§6.3), so the
// scenario and checklist are ORIGINAL and define the shape a later lab FRQ should match.
//
// Categories: State the Empirical Value (3) / State the Theoretical Value (3) /
// Compare and Explain the Gap (4) = 10.
//
// The dropped category is COMPARE AND EXPLAIN THE GAP. A student can report the empirical value
// and the theoretical prediction without ever putting them side by side — the lab's own
// Discussion Questions say to point at your own three histograms rather than at what the
// theorem says ought to have happened, so the comparison is exactly the step a plausible answer
// skips. (This drop is distinct from 4.4/4.6's State the Theoretical Value and 5.3's State the
// Empirical Value — the 6.4 lab teaches the CLT mechanism, not the empirical-vs-theoretical
// pair.)
//
// CATEGORY PURITY: $sEmp states the counted value and nothing else; $sTheory states the
// prediction and nothing else; $sCompare states the comparison and nothing else.
$anstypes = array("choices", "multans", "choices")

$sEmp = "The class data gave x-bar = 0.70 and s ~= 0.5122 for the 30 individual pocket-change amounts."
$sTheory = "The central limit theorem predicts the averages of groups of five will be approximately N(0.70, 0.2291) with the same center as the population."
$sCompare = "The empirical and theoretical centers match to two decimals and the empirical spread of the group averages lands within a few hundredths of the predicted standard error, which is agreement."

$rFull    = $sEmp . ' ' . $sTheory . ' ' . $sCompare
$rNoCompare = $sEmp . ' ' . $sTheory
$rNoTheory = $sEmp . ' ' . $sCompare
$rMinimal  = $sEmp . ' The comparison is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoCompare
$rC = $rNoTheory
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoCompare
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

$noCompareLabel = "B"
if ($pos == 1) { $noCompareLabel = "A" }

$questions[1] = array(
  "State the Empirical Value (3 pts)",
  "State the Theoretical Value (3 pts)",
  "Compare and Explain the Gap (4 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The empirical value and the theoretical prediction can both be reported without ever putting them side by side, so the comparison has to be stated on its own — the gap between the two is the finding of the lab.",
  "Yes. Once the two values are reported, the comparison follows automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the two numbers are close, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope64 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope64 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope64 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope64 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope64 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope64 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope64 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope64 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope64 .row-colored { background:#fff9ea; }
  .qscope64 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope64 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope64">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Empirical Value<br>(3 pts)</b></td>
            <td>Report the statistic the class data actually produced.</td></tr>
          <tr><td style="text-align:center;"><b>State the Theoretical Value<br>(3 pts)</b></td>
            <td>Report what the central limit theorem predicts.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Compare and Explain the Gap<br>(4 pts)</b></td>
            <td>Put the two side by side and say how close they are and why they are not exact.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope64">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the empirical value, the theoretical prediction, and the comparison with its explanation. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sEmp . ' ' . $sTheory . ' ' . $sCompare . '</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noCompareLabel . ' line by line.</span></p>
      <ul>
        <li><b>State the Empirical Value &mdash; earned.</b> The statistic from the class data is present.</li>
        <li><b>State the Theoretical Value &mdash; earned.</b> The central limit theorem prediction is present.</li>
        <li><b>Compare and Explain the Gap &mdash; NOT earned.</b> The response never puts the two side by side, so the comparison is missing.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the comparison is its own category.</span> The lab\'s Discussion Questions say to point at your own three histograms rather than at what the theorem says ought to have happened. The gap between the empirical and the theoretical is the finding &mdash; when the two land close together the normal model is doing honest work, and when they land far apart the model does not fit. Reporting both values without the comparison answers half the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab this comparison comes with a blank box and this same checklist. The comparison is the category most often skipped, because once both numbers are written down the side-by-side feels like repetition.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> In the pocket-change lab, one class surveyed 30 people and computed x-bar = $0.70 and s ~= $0.51 for the individual amounts. The class then averaged groups of five and compared the results to the central limit theorem prediction, N(0.70, 0.2291).</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the empirical values from the class data, state the theoretical prediction from the central limit theorem, and compare the two, explaining any gap.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noCompareLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is reporting the empirical value and the theoretical prediction enough on its own to cover comparing them and explaining the gap? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
