// === NAME - DESCRIPTION: Pre-FRQ Grade a Single-Mean Hypotheses - the scenario and grading checklist of the single-mean concept-and-hypotheses FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ mirror of frq/inference-for-means/q1-single-mean-concept-and-hypotheses.php.
// Categories: Purpose of the Test (3) / Real-World Example (3) / Hypotheses (4) = 10.
//
// The dropped category is REAL-WORLD EXAMPLE. A student can state the purpose and write the
// hypotheses without ever applying them to a concrete scenario: the section's own "get the
// pair wrong and every calculation after it answers the wrong question" is exactly the step a
// plausible answer skips.
//
// CATEGORY PURITY: $sPurpose states the purpose and nothing else; $sExample applies the
// scenario and nothing else; $sHypotheses states the pair and nothing else.
$anstypes = array("choices", "multans", "choices")

$contexts = array(
  array("a fitness magazine claims adults sleep an average of 7.0 hours per night",
        "all adults", "hours of sleep per night", "7.0", "hours per night", "45",
        "randomly select 45 adults and record each person's sleep time"),
  array("a university reports first-year students study an average of 15 hours per week",
        "all first-year students at the university", "hours studied per week", "15", "hours per week", "60",
        "randomly select 60 first-year students and record each student's weekly study time"),
  array("a health organization claims teenagers average 5.0 hours of daily screen time",
        "all teenagers", "hours of daily screen time", "5.0", "hours per day", "35",
        "randomly select 35 teenagers and record each teen's daily screen time")
)

$i = rand(0, count($contexts)-1)
$topic = $contexts[$i][0]
$population = $contexts[$i][1]
$characteristic = $contexts[$i][2]
$claimed_value = $contexts[$i][3]
$unit = $contexts[$i][4]
$sample_size = $contexts[$i][5]
$sample_action = $contexts[$i][6]

$sPurpose = "A hypothesis test for a single mean determines whether sample evidence is strong enough to conclude the true population mean differs from a specific claimed value."
$sExample = "For this scenario, we would " . $sample_action . ". The population is " . $population . ", the variable is " . $characteristic . ", and the sample mean from n = " . $sample_size . " can be compared to the claim of " . $claimed_value . " " . $unit . "."
$sHypotheses = "The hypotheses are H&#8320;: &#956; = " . $claimed_value . " and H&#8321;: &#956; &#8800; " . $claimed_value . ", so we start by assuming the claimed mean is correct for " . $population . "."

$rFull    = $sPurpose . ' ' . $sExample . ' ' . $sHypotheses
$rNoExample = $sPurpose . ' ' . $sHypotheses
$rNoHypo  = $sPurpose . ' ' . $sExample
$rMinimal = $sPurpose . ' The hypotheses are the heart of the test.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoExample
$rC = $rNoHypo
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoExample
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoHypo
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noExampleLabel = "B"
if ($pos == 1) { $noExampleLabel = "A" }

$questions[1] = array(
  "Purpose of the Test (3 pts)",
  "Real-World Example (3 pts)",
  "Hypotheses (4 pts)"
)
$answer[1] = "0,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The purpose can be stated and the hypotheses written without ever applying them to a concrete scenario, so the example has to be its own category.",
  "Yes. Once the purpose is stated, the example follows automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the hypotheses are written, the example does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope81 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope81 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope81 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope81 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope81 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope81 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope81 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope81 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope81 .row-colored { background:#fff9ea; }
  .qscope81 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope81 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope81">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Purpose of the Test<br>(3 pts)</b></td>
            <td>Explain what question a hypothesis test for a single population mean is designed to answer.</td></tr>
          <tr><td style="text-align:center;"><b>Real-World Example<br>(3 pts)</b></td>
            <td>Use the given scenario as an example, identifying the population, the variable, and the sample action.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Hypotheses<br>(4 pts)</b></td>
            <td>State both H&#8320; and H&#8321; correctly using &#956; notation, and explain in plain language what H&#8320; assumes.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope81">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the purpose, applies the scenario, and writes the hypotheses. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sPurpose . ' ' . $sExample . ' ' . $sHypotheses . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noExampleLabel . ' line by line.</span></p>
      <ul>
        <li><b>Purpose of the Test: earned.</b> The purpose is stated: the test decides whether sample evidence is strong enough to conclude the true mean differs from the claimed value.</li>
        <li><b>Real-World Example: NOT earned.</b> The response never applies the test to the scenario: no population, no variable, no sample action.</li>
        <li><b>Hypotheses: earned.</b> The pair is written in &#956; notation and the null\'s assumption is explained.</li>
      </ul>
      <p><span class="term-label">Part (c): why the example is its own category.</span> Writing the pair correctly is the section\'s headline skill, and the purpose can be stated in one sentence: but neither one forces the student to connect the machinery to an actual study. The example is the step that makes the hypotheses mean something, and a response that skips it has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The real-world example is the category most often skipped, because once the purpose and the pair are written the scenario feels like decoration.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> Suppose $topic. A researcher takes a random sample of size n = $sample_size to evaluate this claim.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Explain what a hypothesis test for a single population mean is trying to answer. Then use this scenario as your example to identify the population, the variable being measured, and what sample data would be collected. Finally, state the null and alternative hypotheses using &#956; notation and explain what H&#8320; assumes in plain language.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noExampleLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is stating the purpose and writing the hypotheses enough on its own to cover applying the test to a concrete scenario? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
