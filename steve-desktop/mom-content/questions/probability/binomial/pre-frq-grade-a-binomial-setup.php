// === NAME - DESCRIPTION: Pre-FRQ Grade a Binomial Setup - A binomial scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 4.3. No binomial FRQ exists to mirror, so the scenario and checklist are
// ORIGINAL and define the shape a later binomial FRQ should match. See reference/pre-frq-template.md.
//
// Categories: Name the Parameters (2 pts) / State the Probability Question (5 pts) /
// Compute and Interpret (5 pts) = 12.
//
// The dropped category is NAME THE PARAMETERS. The probability question and its computation can
// both be right while nobody ever stated n and p, so nothing shows the model matches the scenario
// -- the section's "decide what one trial is, what counts as a success, how many trials there
// are" step, which the other categories imply but never demand.
//
// Not reused from an earlier assignment: 2.3 Percentile, 2.4 Contextual Interpretation, 2.5
// Outlier Impact, 2.6 Further Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2
// Distinguish the Two, 3.3 Second Factor, 3.4 State the Direction, 3.5 Draw the Structure, 4.1
// State the Values, 4.2 Verify the Sum: none is this.
//
// CATEGORY PURITY: $sParams states n and p and nothing else; $sQuestion states the probability
// question symbolically and nothing else; $sCompute evaluates and reads the result in context
// without re-listing the parameters. Each sentence earns exactly one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 2)
$scenario = $scenarios[$i]

$scenarios = array(
  "Approximately 70% of statistics students do their homework in time for it to be collected and graded. Each student does homework independently. In a statistics class of 50 students, what is the probability that at least 40 will do their homework on time?",
  "About 41% of adult workers have a high school diploma but do not pursue any further education. If 20 adult workers are randomly selected, what is the probability that at most 12 of them have a high school diploma but do not pursue any further education?",
  "A biased coin lands heads with probability 0.25. It is flipped 5 times. What is the probability of getting more than 3 heads?"
)

$sParams = array(
  "There are n = 50 trials and the success probability is p = 0.70, so X ~ B(50, 0.70).",
  "There are n = 20 trials and the success probability is p = 0.41, so X ~ B(20, 0.41).",
  "There are n = 5 trials and the success probability is p = 0.25, so X ~ B(5, 0.25)."
)

$sQuestion = array(
  "The question asks for P(X >= 40): at least 40 of the 50 students.",
  "The question asks for P(X <= 12): at most 12 of the 20 workers.",
  "The question asks for P(X > 3): strictly more than 3 heads."
)

$sCompute = array(
  "P(X >= 40) = 1 - P(X <= 39) ~= 1 - 0.9999 = 0.0001, so it is almost certain that fewer than 40 do it on time.",
  "P(X <= 12) = 0.9738, so about 97% of such samples have at most 12 workers with only a high school diploma.",
  "P(X > 3) = P(X = 4) + P(X = 5) = 0.0146 + 0.0010 = 0.0156, so more than 3 heads is rare."
)

$sP = $sParams[$i]
$sQ = $sQuestion[$i]
$sC = $sCompute[$i]

$rFull    = $sP . ' ' . $sQ . ' ' . $sC
$rNoParams = $sQ . ' ' . $sC
$rNoQuestion = $sP . ' ' . $sC
$rMinimal  = $sP . ' The model is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoParams
$rC = $rNoQuestion
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoParams
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoQuestion
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
  "Name the Parameters (2 pts)",
  "State the Probability Question (5 pts)",
  "Compute and Interpret (5 pts)"
)
$answer[1] = "1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The probability question and its computation can both be right while nobody ever stated n and p, so nothing shows the model matches the scenario: the parameters have to be named on their own.",
  "Yes. Once the probability is computed correctly, the parameters are implied by the arithmetic, so there is nothing separate to award.",
  "No, but only because the computation is the hard part.",
  "Yes, as long as the answer is a number between 0 and 1, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope43 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope43 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope43 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope43 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope43 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope43 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope43 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope43 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope43 .row-colored { background:#fff9ea; }
  .qscope43 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope43 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope43">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Name the Parameters<br>(2 pts)</b></td>
            <td>State n (the number of trials) and p (the success probability), and write `X ~ B(n, p)`.</td></tr>
          <tr><td style="text-align:center;"><b>State the Probability Question<br>(5 pts)</b></td>
            <td>Translate the English into a symbolic probability statement, e.g. `P(X >= 40)`.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Compute and Interpret<br>(5 pts)</b></td>
            <td>Evaluate the probability and read the result in context.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope43">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> names the parameters, states the probability question symbolically, and computes and interprets it. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sP . ' ' . $sQ . ' ' . $sC . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noParamsLabel . ' line by line.</span></p>
      <ul>
        <li><b>Name the Parameters: NOT earned.</b> The probability question and the computation use n and p, but nowhere does the response state them, so nothing shows the model matches the scenario.</li>
        <li><b>State the Probability Question: earned.</b> The English is translated into a symbolic statement.</li>
        <li><b>Compute and Interpret: earned.</b> The probability is evaluated and read in context.</li>
      </ul>
      <p><span class="term-label">Part (c): why the parameters are their own category.</span> Most of the work in a binomial problem happens before any arithmetic: decide what one trial is, what counts as a success, how many trials there are, and which inequality the English is asking for. A computation without the parameters is an answer looking for a question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The parameters are the category most often skipped, because once the arithmetic is down the setup feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $scenario</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Name the parameters of the binomial model, state the probability question symbolically, and compute and interpret the result.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is computing the probability correctly enough on its own to cover naming the parameters? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
