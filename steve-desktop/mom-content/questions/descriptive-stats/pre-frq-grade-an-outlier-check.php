// === NAME - DESCRIPTION: Pre-FRQ Grade an Outlier Check - The scenario and grading checklist of the five-number-summary FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 2.4, matching the chapter 1 ones: the SAME scenario and the SAME grading checklist
// as frq/descriptive-statistics/q8-five-number-summary-and-outliers, with the writing replaced by
// grading. The dropped category here is the contextual interpretation -- students compute the fence,
// declare the verdict, and stop, because the arithmetic feels like the whole job.
//
// The five numbers are generated and the fence is computed from them, so the arithmetic quoted inside
// each sample response is always right for the seed on screen.
$anstypes = array("choices", "multans", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $setting = "the times, in minutes, that patients waited in an emergency room one evening"
  $unitWord = "minutes"
  $meaningText = "one patient waited far longer than anyone else, which may point to a triage problem or a single unusually complex case"
  $actionText = "it is worth checking that patient's record before deciding whether the evening ran normally"
}
else {
  $setting = "the amounts, in dollars, spent by customers at a hardware store in one hour"
  $unitWord = "dollars"
  $meaningText = "one customer spent far more than anyone else, which may be a contractor buying in bulk rather than a typical shopper"
  $actionText = "it is worth checking that sale before using this hour to describe normal customer spending"
}

$q1 = 4 * rand(5, 9)
$iqr = 4 * rand(3, 6)
$q3 = $q1 + $iqr
$med = $q1 + $iqr / 2
$minV = $q1 - 2 * rand(2, 5)
$half = $iqr / 2
$reach = $iqr + $half
$upFence = $q3 + $reach
// The maximum is placed clearly beyond the fence, so the verdict is never a judgement call.
$maxV = $upFence + 2 * rand(3, 9)
$over = $maxV - $upFence

$sCalc = "The IQR is Q3 minus Q1, " . $q3 . " - " . $q1 . " = " . $iqr . " " . $unitWord . ". The upper fence is Q3 + 1.5(IQR) = " . $q3 . " + " . $reach . " = " . $upFence . " " . $unitWord . "."
$sClass = "The maximum of " . $maxV . " is above that fence, by " . $over . " " . $unitWord . ", so it does qualify as an outlier by the 1.5 IQR rule."
$sInterp = "In context that means " . $meaningText . ", so " . $actionText . "."

$rFull = $sCalc . " " . $sClass . " " . $sInterp
$rNoInterp = $sCalc . " " . $sClass . " The calculation is therefore complete."
$rNoClass = $sCalc . " " . $sInterp
$rCalcOnly = $sCalc . " Those are the two values the 1.5 IQR rule needs."

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoInterp
$rC = $rNoClass
$rD = $rCalcOnly
if ($pos == 1) {
  $rA = $rNoInterp
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoClass
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rCalcOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noInterpLabel = "B"
if ($pos == 1) { $noInterpLabel = "A" }

$questions[1] = array(
  "IQR and Upper Fence (4 pts)",
  "Outlier Classification (3 pts)",
  "Contextual Interpretation (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Flagging a value as an outlier is a statistical verdict; the rubric also asks what that value might MEAN here and whether it should be looked into. Those are separate points because they are separate thinking.",
  "Yes. Once a value has been classified as an outlier, its meaning follows automatically.",
  "No, but only because the response was too short. Recomputing the fence would have earned it.",
  "Yes, as long as the fence was calculated correctly."
)
$answer[2] = 0

$css = '
<style>
  .qscope8 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope8 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope8 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope8 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope8 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope8 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope8 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope8 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope8 .row-colored { background:#fff9ea; }
  .qscope8 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope8 .resp b { color:#1865f2; }
  .qscope8 .fivenum { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .qscope8 .fivenum td, .qscope8 .fivenum th { border:1px solid #d1d5db; padding:6px 16px; text-align:center; }
  .qscope8 .fivenum th { background:#f0f4ff; }
</style>'

$rubric = $css . '
<div class="qscope8">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>IQR &amp; Upper Fence<br>(4 pts)</b></td>
            <td>Calculate the IQR from Q1 and Q3, then calculate the upper fence using the 1.5(IQR) rule.</td></tr>
          <tr><td style="text-align:center;"><b>Outlier Classification<br>(3 pts)</b></td>
            <td>Compare the maximum with the upper fence and state whether it qualifies as an outlier.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Contextual Interpretation<br>(3 pts)</b></td>
            <td>Explain what the outlier might suggest in context, and whether it should be investigated further.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$fiveTable = '
<div class="qscope8">
  <table class="fivenum">
    <tr><th>Minimum</th><th>Q1</th><th>Median</th><th>Q3</th><th>Maximum</th></tr>
    <tr><td>' . $minV . '</td><td>' . $q1 . '</td><td>' . $med . '</td><td>' . $q3 . '</td><td>' . $maxV . '</td></tr>
  </table>
</div>'

$responses = '
<div class="qscope8">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> computes the IQR and the fence, compares the maximum against the fence and states the verdict, and then says what the outlier might mean and whether to look into it. Each of the other three drops a whole category.</p>
      <p><span class="term-label">The arithmetic.</span> `"IQR" = ' . $q3 . ' - ' . $q1 . ' = ' . $iqr . '`, and the upper fence is `' . $q3 . ' + 1.5(' . $iqr . ') = ' . $q3 . ' + ' . $reach . ' = ' . $upFence . '` ' . $unitWord . '. The maximum of ' . $maxV . ' clears the fence by ' . $over . ', so it IS an outlier by the 1.5 IQR rule.</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noInterpLabel . ' line by line.</span></p>
      <ul>
        <li><b>IQR &amp; Upper Fence &mdash; earned.</b> Both values are computed and both are correct.</li>
        <li><b>Outlier Classification &mdash; earned.</b> It compares the maximum with the fence and gives the verdict.</li>
        <li><b>Contextual Interpretation &mdash; NOT earned.</b> It ends with "the calculation is therefore complete", which is a statement about the arithmetic, not about ' . $setting . '. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the interpretation is separate.</span> "It is an outlier" is a statistical verdict about a number. The rubric also asks what that number might be TELLING you: here, that ' . $meaningText . ', and that ' . $actionText . '. An outlier is not automatically an error to delete &mdash; it can be the most informative value in the set, and deciding which requires looking at the context rather than the fence.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. Interpretation is the category most often missing here, because once the fence is computed and the verdict given the work FEELS finished &mdash; and the last three points are the ones still on the table.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> A five-number summary was produced for $setting.</p>
    $fiveTable
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Find the IQR and the upper fence, decide whether the maximum is an outlier, and interpret the result in context.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noInterpLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is flagging the value as an outlier enough on its own to cover the interpretation? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
