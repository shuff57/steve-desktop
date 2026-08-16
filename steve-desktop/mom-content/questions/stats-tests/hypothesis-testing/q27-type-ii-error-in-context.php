// === NAME - DESCRIPTION: Type II Error in Context - fail to reject a false null, worded about the claim, and the decision it is tied to ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A scenario with a null hypothesis. Parts: (a) choices - the Type II error stated in context
// (b) choices - which decision the Type II error is tied to.
// Invariant: both answers are constant per scenario.

$anstypes = array("choices", "choices")

$cases = array(
  array("A medical test screens for a disease. The null hypothesis is `H_0`: the patient does NOT have the disease; the alternative is `H_a`: the patient HAS the disease.",
        "Conclude the patient is healthy when in fact the patient has the disease.",
        "Do not reject `H_0` &mdash; a Type II error can only happen on a run where you did not reject."),
  array("A quality-control team tests whether bolts coming off a production line have the target mean diameter. The null hypothesis is `H_0: mu = 10` mm; the alternative is `H_a: mu != 10` mm.",
        "Conclude the mean diameter is 10 mm when in fact it differs from 10 mm.",
        "Do not reject `H_0` &mdash; a Type II error can only happen on a run where you did not reject."),
  array("A smoke-detection system makes a decision each minute. The null hypothesis is `H_0`: there is NO fire; the alternative is `H_a`: there IS a fire.",
        "Stay silent when there is a fire.",
        "Do not reject `H_0` &mdash; a Type II error can only happen on a run where you did not reject."),
  array("A drug trial tests whether a new medication reduces blood pressure. The null hypothesis is `H_0`: the drug has no effect; the alternative is `H_a`: the drug lowers blood pressure.",
        "Conclude the drug has no effect when in fact it works.",
        "Do not reject `H_0` &mdash; a Type II error can only happen on a run where you did not reject."),
  array("An environmental study tests whether a river is contaminated. The null hypothesis is `H_0`: the water is safe; the alternative is `H_a`: the water is contaminated.",
        "Conclude the water is safe when in fact it is contaminated.",
        "Do not reject `H_0` &mdash; a Type II error can only happen on a run where you did not reject.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$type2 = $cases[$i][1]
$tied = $cases[$i][2]

$questions[0] = array(
  $type2,
  "Conclude the claim is true when in fact it is false.",
  "Conclude the claim is false when in fact it is true."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $tied,
  "Reject `H_0` &mdash; a Type II error can only happen on a run where you rejected.",
  "Either decision &mdash; the error is not tied to the decision."
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
      <p><span class="term-label">Part (a) &mdash; the error in context.</span> The trick that never fails: write down what `H_0` says, then say out loud "I did not reject it, and it was false." Here that is: ' . $type2 . '</p>
      <p><span class="term-label">Part (b) &mdash; the decision it is tied to.</span> ' . $tied . ' A Type II error is a real effect the test walked past, and beta is how often you miss it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the Type II error in this context?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which decision is the Type II error tied to?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
