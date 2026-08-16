// === NAME - DESCRIPTION: Pre-FRQ Grade a z-Score Interpretation - the scenario and grading checklist of the z-score FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ mirror of frq/normal-distribution/q10-z-scores-and-normal-probability.php.
// Categories: Z-Score Calculation (4) / Z-Score Interpretation (3) / Unusual or Typical (3) = 10.
//
// The dropped category is UNUSUAL OR TYPICAL. A student can compute the z-score and interpret
// its position without ever judging whether the score is unusual — the section's own Context
// Pause ("the 1 in 20 rule of thumb") is exactly the step a plausible answer skips.
//
// CATEGORY PURITY: $sCalc states the arithmetic and nothing else; $sInterp states the position
// and nothing else; $sUnusual states the judgement and nothing else. Each sentence earns exactly
// one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$contexts = array(
  array("a standardized academic aptitude test", "test score", "student", "test-takers"),
  array("a physical fitness assessment", "fitness score", "participant", "assessment participants"),
  array("an employee performance evaluation", "performance rating", "employee", "evaluated employees")
)
$mus = array(100, 50, 70)
$sigmas = array(15, 10, 8)
$zs = array(2, 2, 3)
$xs = array(130, 70, 94)

$i = rand(0, 2)
$topic = $contexts[$i][0]
$score_label = $contexts[$i][1]
$subject_label = $contexts[$i][2]
$setting_label = $contexts[$i][3]
$mu = $mus[$i]
$sigma = $sigmas[$i]
$z = $zs[$i]
$x = $xs[$i]

$name = randname()

$sCalc = "The z-score formula is z = (x - mu)/sigma. Plugging in: z = (" . $x . " - " . $mu . ")/" . $sigma . " = " . $z . "."
$sInterp = "A z-score of " . $z . " means " . $name . " scored " . $z . " standard deviations above the mean " . $score_label . " for " . $setting_label . "."
$sUnusual = "A z-score of " . $z . " is beyond the common threshold of 2, so this score is clearly unusual."

$rFull    = $sCalc . ' ' . $sInterp . ' ' . $sUnusual
$rNoUnusual = $sCalc . ' ' . $sInterp
$rNoInterp = $sCalc . ' ' . $sUnusual
$rMinimal  = $sCalc . ' The result speaks for itself.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoUnusual
$rC = $rNoInterp
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoUnusual
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoInterp
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noUnusualLabel = "B"
if ($pos == 1) { $noUnusualLabel = "A" }

$questions[1] = array(
  "Z-Score Calculation (4 pts)",
  "Z-Score Interpretation (3 pts)",
  "Unusual or Typical (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The z-score can be computed and its position interpreted without ever judging whether the score is unusual, so the judgement has to be stated on its own.",
  "Yes. Once the z-score is computed, whether it is unusual follows automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the z-score is large, the judgement does not need its own sentence."
)
$answer[2] = 0

$css = '
<style>
  .qscope51 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope51 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope51 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope51 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope51 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope51 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope51 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope51 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope51 .row-colored { background:#fff9ea; }
  .qscope51 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope51 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope51">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Z-Score Calculation<br>(4 pts)</b></td>
            <td>Write the z-score formula, identify the values, and state the final z-score.</td></tr>
          <tr><td style="text-align:center;"><b>Z-Score Interpretation<br>(3 pts)</b></td>
            <td>Explain what the z-score means in the context of the scenario.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Unusual or Typical<br>(3 pts)</b></td>
            <td>State whether the score is unusual or typical, with reasoning.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope51">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> computes the z-score, interprets its position, and judges whether it is unusual. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sCalc . ' ' . $sInterp . ' ' . $sUnusual . '</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noUnusualLabel . ' line by line.</span></p>
      <ul>
        <li><b>Z-Score Calculation &mdash; earned.</b> The formula is written, the values are identified, and the z-score is stated.</li>
        <li><b>Z-Score Interpretation &mdash; earned.</b> The position of the score relative to the mean is explained in context.</li>
        <li><b>Unusual or Typical &mdash; NOT earned.</b> The response never says whether the score is unusual, so the judgement is missing.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the judgement is its own category.</span> Computing a z-score and reading its position answers "how far from the mean", not "is this surprising". The unusual-or-typical call is a separate judgement against a threshold, and a response that skips it has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The unusual-or-typical sentence is the category most often skipped, because once the arithmetic is done the judgement feels like commentary.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> Scores on $topic are normally distributed with a mean of $mu and a standard deviation of $sigma. One $subject_label, $name, receives a $score_label of $x.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Calculate the z-score for $name, interpret what it means in context, and explain whether this score would be considered unusual or typical.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noUnusualLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is computing the z-score and interpreting its position enough on its own to cover stating whether the score is unusual? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
