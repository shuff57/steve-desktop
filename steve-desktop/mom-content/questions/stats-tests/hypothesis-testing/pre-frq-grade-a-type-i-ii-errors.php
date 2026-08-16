// === NAME - DESCRIPTION: Pre-FRQ Grade a Type I/II Errors - the scenario and grading checklist of the (authored-first) Type I/II errors FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Authored-first pre-FRQ (no Type I/II FRQ exists anywhere in questions/frq/ - the pre-FRQ
// defines the scenario and rubric a later FRQ should match).
// Categories: State Both Errors in Context (4) / Name the Probabilities (3) /
// Judge Which Error Costs More (3) = 10.
//
// The dropped category is NAME THE PROBABILITIES. A student can state both errors in context
// and judge their consequences without ever attaching the lettered probabilities - the
// section's own "the trick that never fails" is exactly the step a plausible answer skips.
//
// CATEGORY PURITY: $sErrors states both errors and nothing else; $sProbs names the
// probabilities and nothing else; $sJudge judges the consequences and nothing else.
$anstypes = array("choices", "multans", "choices")

$contexts = array(
  array("A medical test screens for a disease. The null hypothesis is `H_0`: the patient does NOT have the disease; the alternative is `H_a`: the patient HAS the disease.",
        "Conclude the patient has the disease when in fact the patient is healthy.",
        "Conclude the patient is healthy when in fact the patient has the disease.",
        "The Type I error is the false alarm &mdash; telling a healthy patient they are sick &mdash; and the Type II error is the missed diagnosis &mdash; telling a sick patient they are healthy. The Type II error costs more here, because a missed diagnosis means the patient does not get the treatment they need."),
  array("A smoke-detection system makes a decision each minute. The null hypothesis is `H_0`: there is NO fire; the alternative is `H_a`: there IS a fire.",
        "Sound the alarm when there is no fire.",
        "Stay silent when there is a fire.",
        "The Type I error is the false alarm &mdash; the alarm going off while you make toast &mdash; and the Type II error is the missed fire &mdash; the alarm staying silent during a real fire. The Type II error costs more here, because a silent alarm means the building burns."),
  array("The FDA reviews a new drug. The null hypothesis is `H_0`: the drug is unsafe; the alternative is `H_a`: the drug is safe.",
        "Approve the drug when in fact it is unsafe.",
        "Reject the drug when in fact it is safe.",
        "The Type I error is approving an unsafe drug, and the Type II error is rejecting a safe one. The Type I error costs more here, because approving an unsafe drug puts patients at risk.")
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i][0]
$type1 = $contexts[$i][1]
$type2 = $contexts[$i][2]
$sJudge = $contexts[$i][3]

$sErrors = "The Type I error is " . $type1 . " The Type II error is " . $type2
$sProbs = "Alpha is the probability of the Type I error, and beta is the probability of the Type II error."

$rFull    = $sErrors . ' ' . $sProbs . ' ' . $sJudge
$rNoProbs = $sErrors . ' ' . $sJudge
$rNoJudge = $sErrors . ' ' . $sProbs
$rMinimal = $sErrors . ' The errors are the two ways a test can be wrong.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoProbs
$rC = $rNoJudge
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoProbs
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoJudge
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noProbsLabel = "B"
if ($pos == 1) { $noProbsLabel = "A" }

$questions[1] = array(
  "State Both Errors in Context (4 pts)",
  "Name the Probabilities (3 pts)",
  "Judge Which Error Costs More (3 pts)"
)
$answer[1] = "0,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Both errors can be stated in context and their consequences judged without ever attaching the lettered probabilities, so naming alpha and beta has to be its own category.",
  "Yes. Once the errors are stated in context, the probabilities follow automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the errors are stated, the probabilities do not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope82 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope82 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope82 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope82 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope82 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope82 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope82 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope82 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope82 .row-colored { background:#fff9ea; }
  .qscope82 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope82 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope82">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State Both Errors in Context<br>(4 pts)</b></td>
            <td>State the Type I error and the Type II error in the wording of the problem, each tied to its decision.</td></tr>
          <tr><td style="text-align:center;"><b>Name the Probabilities<br>(3 pts)</b></td>
            <td>Name alpha as the probability of the Type I error and beta as the probability of the Type II error.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Judge Which Error Costs More<br>(3 pts)</b></td>
            <td>Say which error carries the heavier consequence in this scenario, with a one-sentence reason.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope82">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states both errors in context, names the probabilities, and judges which error costs more. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sErrors . ' ' . $sProbs . ' ' . $sJudge . '</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noProbsLabel . ' line by line.</span></p>
      <ul>
        <li><b>State Both Errors in Context &mdash; earned.</b> Both errors are stated in the wording of the problem, each tied to its decision.</li>
        <li><b>Name the Probabilities &mdash; NOT earned.</b> The response never attaches the lettered probabilities to the errors.</li>
        <li><b>Judge Which Error Costs More &mdash; earned.</b> The heavier consequence is named with a one-sentence reason.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the probabilities are their own category.</span> Stating the errors in context and judging their consequences are both possible without ever naming alpha and beta &mdash; the letters are the vocabulary that lets you talk about how often each error happens, and a response that skips them has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> This scenario has no FRQ yet &mdash; the pre-FRQ defines the scenario and rubric a later FRQ should match. The probabilities are the category most often skipped, because once the errors are stated the letters feel like decoration.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> ' . $ctx . '</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the Type I error and the Type II error in the wording of the problem, name the probability each one carries, and judge which error costs more in this scenario.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noProbsLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is stating both errors in context and judging their consequences enough on its own to cover naming the probabilities? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
