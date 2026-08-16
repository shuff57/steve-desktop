// === NAME - DESCRIPTION: Pre-FRQ Grade an EV Decision - The scenario and grading checklist of the expected-value FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 4.1. Mirrors questions/frq/probability/q1-expected-value-decision.php: same
// three scenarios, same rubric voice. The rubric adds the category the FRQ checklist implies but
// never states. See reference/pre-frq-template.md.
//
// Categories: State the Values and Probabilities (2 pts) / Compute Expected Value (6 pts) /
// Decision and Interpretation (4 pts) = 12, matching the slot.
//
// The dropped category is STATE THE VALUES AND PROBABILITIES. The arithmetic and the decision can
// both be right while the pairing of X with its probabilities was never stated, so nothing shows
// whether the calculation matches the scenario. Not reused from an earlier assignment: 2.3 dropped
// Percentile, 2.4 Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7
// Practical Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the
// Direction, 3.5 Draw the Structure -- none is this.
//
// CATEGORY PURITY: $sValues states the pairing and nothing else; $sCompute shows the arithmetic
// (products, not a pairing statement) and nothing else; $sDecision states the verdict and the
// long-run reading without re-deriving the calculation. Each sentence earns exactly one rubric
// line and no other.
$anstypes = array("choices", "multans", "choices")

$scenarios = array(
  "You are considering a roulette bet at a casino. You bet $5 on red: if the ball lands on red (18 out of 38 spins), you win $5 net; if it lands on black or green, you lose your $5. Let `X` be your net profit in dollars.",
  "You are deciding whether to buy a state lottery ticket. The ticket costs $2 and has a 1 in 1,000,000 chance of winning the $500,000 jackpot; otherwise you lose the $2. Let `X` be your net profit in dollars.",
  "You are at a carnival ring-toss booth. It costs $1 to play: you win a $15 prize with probability 0.05 and win nothing otherwise. Let `X` be your net profit in dollars."
)

$i = rand(0, 2)
$scenario = $scenarios[$i]

$sValues = array(
  "The net profit `X` takes the value 5 with probability 18/38 and the value -5 with probability 20/38.",
  "The net profit `X` takes the value 499,998 with probability 1/1,000,000 and the value -2 with probability 999,999/1,000,000.",
  "The net profit `X` takes the value 14 with probability 0.05 and the value -1 with probability 0.95."
)

$sCompute = array(
  "`E(X) = 5(18/38) + (-5)(20/38) = 90/38 - 100/38 = -10/38 ~= -0.26`.",
  "`E(X) = 499,998(1/1,000,000) + (-2)(999,999/1,000,000) ~= 0.50 - 2.00 = -1.50`.",
  "`E(X) = 14(0.05) + (-1)(0.95) = 0.70 - 0.95 = -0.25`."
)

$sDecision = array(
  "Since `E(X)` is negative, the bet loses about $0.26 per play on average, so it should not be repeated.",
  "Since `E(X)` is negative, the ticket loses about $1.50 per play on average, so buying it is a bad bet in expected value.",
  "Since `E(X)` is negative, the game loses about $0.25 per play on average, so it should not be played for profit."
)

$sV = $sValues[$i]
$sC = $sCompute[$i]
$sD = $sDecision[$i]

$rFull    = $sV . ' ' . $sC . ' ' . $sD
$rNoValues = $sC . ' ' . $sD
$rNoDecision = $sV . ' ' . $sC
$rMinimal = $sV . ' The value of `E(X)` is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoValues
$rC = $rNoDecision
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoValues
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoDecision
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noValuesLabel = "B"
if ($pos == 1) { $noValuesLabel = "A" }

$questions[1] = array(
  "State the Values and Probabilities (2 pts)",
  "Compute Expected Value (6 pts)",
  "Decision and Interpretation (4 pts)"
)
$answer[1] = "1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The arithmetic and the decision can both be right while the pairing of `X` with its probabilities was never stated, so nothing shows whether the calculation matches the scenario — the pairing has to be judged on its own.",
  "Yes. Once `E(X)` is computed correctly, the values are implied by the arithmetic, so there is nothing separate to award.",
  "No, but only because the calculation is the hard part.",
  "Yes, as long as the decision matches the sign of `E(X)`, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope41 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope41 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope41 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope41 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope41 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope41 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope41 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope41 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope41 .row-colored { background:#fff9ea; }
  .qscope41 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope41 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope41">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Values and Probabilities<br>(2 pts)</b></td>
            <td>Identify the net outcomes `X` can take and the probability of each.</td></tr>
          <tr><td style="text-align:center;"><b>Compute Expected Value<br>(6 pts)</b></td>
            <td>Show the `E(X) = sum x cdot P(X = x)` calculation step by step, with the correct sign and units.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Decision and Interpretation<br>(4 pts)</b></td>
            <td>State whether to play or pass based on `E(X)`, and read it as a long-run average.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope41">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> pairs each value of `X` with its probability, shows the `E(X)` arithmetic, and gives the decision with the long-run reading. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sV . ' ' . $sC . ' So ' . $sD . '</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noValuesLabel . ' line by line.</span></p>
      <ul>
        <li><b>State the Values and Probabilities &mdash; NOT earned.</b> The arithmetic uses the numbers, but nowhere does the response pair each value of `X` with its probability, so nothing shows the calculation matches the scenario.</li>
        <li><b>Compute Expected Value &mdash; earned.</b> The step-by-step calculation is present and correct.</li>
        <li><b>Decision and Interpretation &mdash; earned.</b> The verdict follows from the sign of `E(X)` and reads it as a long-run average.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the pairing is its own category.</span> The arithmetic can be right while the values were never stated, and then a grader cannot tell whether the calculation belongs to this scenario at all. Stating the pairing is what makes the arithmetic auditable.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The pairing is the category most often skipped, because once the numbers are in the formula the setup feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $scenario</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the values `X` can take and their probabilities, compute the expected value of one play, and decide whether to play or buy, interpreting `E(X)` as a long-run average.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noValuesLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is computing `E(X)` correctly enough on its own to cover stating the values and probabilities? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
