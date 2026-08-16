// === NAME - DESCRIPTION: Pre-FRQ Grade a t-Interval - the scenario and grading checklist of the single-mean CI FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ mirror of frq/inference-for-means/q3-single-mean-interpreting-confidence-interval.php.
// Categories: CI Interpretation (4) / Confidence Level Meaning (3) / Assessing the Claim (3) = 10.
//
// The dropped category is ASSESSING THE CLAIM. A student can interpret the interval and explain
// the confidence level without ever checking whether a claimed value falls inside it — the
// section's own "the interval either contains mu or it does not" is exactly the step a plausible
// answer skips. This is DIFFERENT from §7.1's pre-FRQ (dropped: Confidence Level Meaning) — the
// two pre-FRQs must not teach the same lesson.
//
// CATEGORY PURITY: $sInterp states the interval interpretation and nothing else; $sLevel states
// what the confidence level means and nothing else; $sClaim assesses the claim and nothing else.
$anstypes = array("choices", "multans", "choices")

$sInterp = "We are 90% confident that the true mean one-way commute time for all employees at the company is between 23.1 and 29.5 minutes."
$sLevel = "The 90% confidence level describes the method, not the single fixed mean: if we repeatedly sampled 40 employees and built intervals this way, about 90% of those intervals would contain the true population mean."
$sClaim = "The claimed value mu0 = 25 is inside the interval (23.1, 29.5), so the claim is plausible based on this sample."

$rFull    = $sInterp . ' ' . $sLevel . ' ' . $sClaim
$rNoClaim = $sInterp . ' ' . $sLevel
$rNoLevel = $sInterp . ' ' . $sClaim
$rMinimal  = $sInterp . ' The interval is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoClaim
$rC = $rNoLevel
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoClaim
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoLevel
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noClaimLabel = "B"
if ($pos == 1) { $noClaimLabel = "A" }

$questions[1] = array(
  "CI Interpretation (4 pts)",
  "Confidence Level Meaning (3 pts)",
  "Assessing the Claim (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The interval can be interpreted and the confidence level explained without ever checking whether a claimed value falls inside it, so the claim has to be assessed on its own.",
  "Yes. Once the interval is interpreted, whether the claim is plausible follows automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the interval is reported, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope72 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope72 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope72 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope72 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope72 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope72 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope72 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope72 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope72 .row-colored { background:#fff9ea; }
  .qscope72 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope72 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope72">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>CI Interpretation<br>(4 pts)</b></td>
            <td>Interpret the interval in context, naming the true population mean, the population, and the bounds with units.</td></tr>
          <tr><td style="text-align:center;"><b>Confidence Level Meaning<br>(3 pts)</b></td>
            <td>Explain what the confidence level means in terms of the long-run behavior of the interval method.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Assessing the Claim<br>(3 pts)</b></td>
            <td>Use whether the claimed value falls inside or outside the interval to decide if the claim is plausible.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope72">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> interprets the interval, explains the confidence level, and assesses the claim. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sInterp . ' ' . $sLevel . ' ' . $sClaim . '</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noClaimLabel . ' line by line.</span></p>
      <ul>
        <li><b>CI Interpretation &mdash; earned.</b> The interval is interpreted in context with the population and the bounds named.</li>
        <li><b>Confidence Level Meaning &mdash; earned.</b> The confidence level is explained in repeated-sampling language.</li>
        <li><b>Assessing the Claim &mdash; NOT earned.</b> The response never checks whether the claimed value falls inside the interval, so the claim is missing.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the claim is its own category.</span> The interval either contains mu or it does not — there is no chance left in it once the numbers are on the page. Deciding whether a claimed value is plausible is a separate judgement from reading the interval, and a response that skips it has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the FRQ this scenario comes with a blank box and this same checklist. The claim assessment is the category most often skipped, because once the interval is written the check feels like an afterthought.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> A transportation analyst is studying one-way commute time for all employees at the company. A random sample of n = 40 gives a sample mean of x-bar = 26.3 minutes. A 90% confidence interval for the true population mean is (23.1, 29.5) minutes. The claim being evaluated is mu0 = 25 minutes.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Interpret the confidence interval in context, explain what "90% confident" means, and use the interval to evaluate whether the claim about the population mean is plausible.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noClaimLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is interpreting the interval and explaining the confidence level enough on its own to cover assessing the claim? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
