// === NAME - DESCRIPTION: Pre-FRQ Grade a Home Costs Interval - a home-sale-price scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for the 7.4 home-costs lab. No lab FRQ exists to mirror (the CI-interpretation,
// t-interval and proportion-interval pre-FRQs are claimed by §7.1/§7.2/§7.3), so the scenario
// and checklist are ORIGINAL and define the shape a later lab FRQ should match.
//
// Categories: State the Sample Summary (3) / Build the Interval (3) / Interpret in Context (4)
// = 10.
//
// The dropped category is BUILD THE INTERVAL. A student can report the summary statistics and
// interpret a given interval without ever computing the error bound: the lab's own text says
// "the arithmetic is easy and the interpretation is where almost everyone slips", so the
// computation is exactly the step a plausible answer skips. (This drop is distinct from the
// 4.4/4.6/5.3/6.4 lab pre-FRQs' drops and from §7.1/§7.2/§7.3's: the template forbids repeating
// a dropped category.)
//
// CATEGORY PURITY: $sSummary states the summary statistics and nothing else; $sBuild states the
// interval computation and nothing else; $sInterpret states the contextual reading and nothing
// else.
$anstypes = array("choices", "multans", "choices")

$sSummary = "The 35 prices give x-bar = $410,000, s_x ~= $113,006, and n = 35, with df = 34."
$sBuild = "The standard error is s_x/sqrt(n) ~= $19,101, so EBM = 1.6909 x 19,101 ~= $32,298 and the 90% interval is ($377,702, $442,298)."
$sInterpret = "We are 90% confident that the true mean sale price of all homes recently listed in Butte County lies between $377,702 and $442,298."

$rFull    = $sSummary . ' ' . $sBuild . ' ' . $sInterpret
$rNoBuild = $sSummary . ' ' . $sInterpret
$rNoSummary = $sBuild . ' ' . $sInterpret
$rMinimal  = $sSummary . ' The interval is where the answer lives.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoBuild
$rC = $rNoSummary
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoBuild
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoSummary
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noBuildLabel = "B"
if ($pos == 1) { $noBuildLabel = "A" }

$questions[1] = array(
  "State the Sample Summary (3 pts)",
  "Build the Interval (3 pts)",
  "Interpret in Context (4 pts)"
)
$answer[1] = "0,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The summary statistics can be reported and a given interval interpreted without ever computing the error bound, so the interval has to be built on its own: the arithmetic is where the numbers come from.",
  "Yes. Once the summary statistics are reported, the interval follows automatically, so there is nothing separate to award.",
  "No, but only because the interpretation is the hard part.",
  "Yes, as long as the interval is reported, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope74 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope74 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope74 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope74 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope74 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope74 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope74 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope74 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope74 .row-colored { background:#fff9ea; }
  .qscope74 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope74 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope74">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Sample Summary<br>(3 pts)</b></td>
            <td>Report x-bar, s_x, and n from the class\'s 35 prices.</td></tr>
          <tr><td style="text-align:center;"><b>Build the Interval<br>(3 pts)</b></td>
            <td>Compute the standard error, the error bound, and the interval at the stated confidence level.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Interpret in Context<br>(4 pts)</b></td>
            <td>Write the specific sentence naming the population, the quantity, and the two endpoints.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope74">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the summary statistics, builds the interval, and interprets it in context. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sSummary . ' ' . $sBuild . ' ' . $sInterpret . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noBuildLabel . ' line by line.</span></p>
      <ul>
        <li><b>State the Sample Summary: earned.</b> The summary statistics from the 35 prices are present.</li>
        <li><b>Build the Interval: NOT earned.</b> The response never computes the error bound or the interval, so the build is missing.</li>
        <li><b>Interpret in Context: earned.</b> The interval is interpreted with the population, the quantity, and the endpoints named.</li>
      </ul>
      <p><span class="term-label">Part (c): why the build is its own category.</span> The lab\'s own text says "the arithmetic is easy and the interpretation is where almost everyone slips": but the interval has to come from somewhere. Reporting the summary statistics and interpreting a given interval answers half the question; the error bound is the thing the other categories imply but never demand.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab this scenario comes with a blank box and this same checklist. The interval build is the category most often skipped, because once the summary statistics are written the computation feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> In the home-costs lab, one class collected 35 home sale prices from a newspaper. The sum of the prices is $14,350,000 and the sum of their squares is 6,317,692 x 10^6. The class built a 90% confidence interval for the mean sale price.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the sample summary statistics, build the 90% confidence interval for the mean sale price, and interpret the interval in context.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noBuildLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is reporting the summary statistics and interpreting a given interval enough on its own to cover building the interval? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
