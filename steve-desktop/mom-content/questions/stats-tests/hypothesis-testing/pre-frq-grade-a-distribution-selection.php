// === NAME - DESCRIPTION: Pre-FRQ Grade a Distribution Selection - the scenario and grading checklist of the (authored-first) distribution-selection FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Authored-first pre-FRQ (no distribution-selection FRQ exists anywhere in questions/frq/ -
// the pre-FRQ defines the scenario and rubric a later FRQ should match).
// Categories: Name the Distribution (3) / State the Deciding Facts (4) /
// Check the Conditions (3) = 10.
//
// The dropped category is CHECK THE CONDITIONS. A student can name the distribution and the
// deciding facts without ever verifying the assumptions - the section's own "pick the wrong
// one and every number after it is measured against the wrong ruler" is exactly the step a
// plausible answer skips.
//
// CATEGORY PURITY: $sName names the distribution and nothing else; $sFacts states the deciding
// facts and nothing else; $sCheck checks the conditions and nothing else.
$anstypes = array("choices", "multans", "choices")

$contexts = array(
  array("A study tests whether the mean hours of sleep for adults is less than 7 hours per night. The population standard deviation is known to be 1.2 hours, and a simple random sample of 50 adults is taken.",
        "The normal distribution",
        "The population standard deviation was handed to you, so the sample mean follows a normal curve.",
        "The condition check: the sample is a simple random sample, and with sigma known the normal curve applies."),
  array("A study tests whether the mean daily fiber intake of college students differs from 25 g. The population standard deviation is not known, and a simple random sample of 30 students gives a sample standard deviation of 8.4 g.",
        "Student's t distribution",
        "The population standard deviation was withheld, so the sample standard deviation stands in and the t curve accounts for the extra uncertainty.",
        "The condition check: the sample is a simple random sample, and the population is approximately normal."),
  array("A survey tests whether the proportion of voters who support a measure is greater than 30%. A simple random sample of 200 voters is taken.",
        "The normal distribution for a proportion",
        "The parameter is a proportion, and for a large enough sample the sample proportion is approximately normal.",
        "The condition check: np0 = 60 and n(1 - p0) = 140, both well above 5, so the normal approximation is legitimate.")
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i][0]
$sName = $contexts[$i][1]
$sFacts = $contexts[$i][2]
$sCheck = $contexts[$i][3]

$rFull    = $sName . ' ' . $sFacts . ' ' . $sCheck
$rNoCheck = $sName . ' ' . $sFacts
$rNoFacts = $sName . ' ' . $sCheck
$rMinimal = $sName . ' The distribution is the first decision of the test.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoCheck
$rC = $rNoFacts
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoCheck
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoFacts
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noCheckLabel = "B"
if ($pos == 1) { $noCheckLabel = "A" }

$questions[1] = array(
  "Name the Distribution (3 pts)",
  "State the Deciding Facts (4 pts)",
  "Check the Conditions (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The distribution can be named and the deciding facts stated without ever verifying the assumptions, so checking the conditions has to be its own category.",
  "Yes. Once the distribution is named, the conditions follow automatically, so there is nothing separate to award.",
  "No, but only because the arithmetic is the hard part.",
  "Yes, as long as the distribution is named, the conditions do not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope83 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope83 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope83 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope83 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope83 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope83 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope83 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope83 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope83 .row-colored { background:#fff9ea; }
  .qscope83 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope83 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope83">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Name the Distribution<br>(3 pts)</b></td>
            <td>Name the curve the test runs on: normal, Student\'s t, or normal-for-a-proportion.</td></tr>
          <tr><td style="text-align:center;"><b>State the Deciding Facts<br>(4 pts)</b></td>
            <td>Say what in the problem statement chose the curve: sigma given, sigma withheld, or the parameter is a proportion.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Check the Conditions<br>(3 pts)</b></td>
            <td>Verify the assumptions the test needs before any arithmetic: the np/nq check, or the SRS and normality requirements.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope83">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> names the distribution, states the deciding facts, and checks the conditions. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sName . ' ' . $sFacts . ' ' . $sCheck . '</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noCheckLabel . ' line by line.</span></p>
      <ul>
        <li><b>Name the Distribution &mdash; earned.</b> The curve is named.</li>
        <li><b>State the Deciding Facts &mdash; earned.</b> What in the problem statement chose the curve is stated.</li>
        <li><b>Check the Conditions &mdash; NOT earned.</b> The response never verifies the assumptions the test needs.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the conditions are their own category.</span> Naming the distribution and the deciding facts are both possible without ever verifying the assumptions &mdash; the condition check is what makes the distribution legal in the first place, and a response that skips it has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> This scenario has no FRQ yet &mdash; the pre-FRQ defines the scenario and rubric a later FRQ should match. The conditions are the category most often skipped, because once the curve is named the assumptions feel like fine print.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $ctx</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Name the distribution the test runs on, state what in the problem statement chose that curve, and check the conditions the test needs before any arithmetic.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noCheckLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is naming the distribution and stating the deciding facts enough on its own to cover checking the conditions? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
