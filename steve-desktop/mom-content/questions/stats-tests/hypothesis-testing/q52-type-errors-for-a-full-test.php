// === NAME - DESCRIPTION: Type Errors for a Full Test - both errors stated in context from the stated hypotheses ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A full-test scenario with its hypotheses stated. Parts: (a) choices - the Type I error
// stated in context (b) choices - the Type II error stated in context.
// Invariant: both answers are constant per scenario and match the stated hypotheses.

$anstypes = array("choices", "choices")

$cases = array(
  array("A test of whether the mean hours of sleep for adults is less than 7 hours per night. The hypotheses are `H_0: mu >= 7` and `H_a: mu < 7`.",
        "Conclude the mean is less than 7 hours when in fact it is at least 7.",
        "Conclude the mean is at least 7 hours when in fact it is less than 7."),
  array("A test of whether the mean fill weight of cereal boxes is exactly 16 ounces. The hypotheses are `H_0: mu = 16` and `H_a: mu != 16`.",
        "Conclude the mean differs from 16 oz when in fact it is 16 oz.",
        "Conclude the mean is 16 oz when in fact it differs from 16 oz."),
  array("A test of whether the proportion of voters who support a measure is greater than 30%. The hypotheses are `H_0: p <= 0.30` and `H_a: p > 0.30`.",
        "Conclude the proportion is greater than 30% when in fact it is at most 30%.",
        "Conclude the proportion is at most 30% when in fact it is greater than 30%.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$type1 = $cases[$i][1]
$type2 = $cases[$i][2]

$questions[0] = array(
  $type1,
  $type2,
  "The sample was not random."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $type2,
  $type1,
  "The sample was not random."
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">Part (a) &mdash; the Type I error.</span> Rejecting `H_0` when it is true: ' . $type1 . '</p>
      <p><span class="term-label">Part (b) &mdash; the Type II error.</span> Failing to reject `H_0` when it is false: ' . $type2 . '</p>
      <p>The errors are tied to the decisions &mdash; a Type I error can only happen on a run where you rejected, and stating the null first matters because swap `H_0` and `H_a` and you swap the errors along with them.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the Type I error in this context?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the Type II error in this context?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
