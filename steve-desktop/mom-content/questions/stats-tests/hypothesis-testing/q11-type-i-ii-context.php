// === NAME - DESCRIPTION: Type I and Type II errors in context - given a scenario with a null hypothesis, identify which outcome corresponds to a Type I error and which corresponds to a Type II error ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices")
$displayformat = "select"
$answerboxsize = 28

// Each scenario has a clear H0 / Ha pair. For each, students choose:
// (a) what a Type I error means in this context (reject H0 when H0 is true)
// (b) what a Type II error means in this context (fail to reject H0 when H0 is false)

// Scenario 0: medical test — H0: patient is healthy; Ha: patient has disease
// Scenario 1: factory — H0: bolt diameter = 10mm; Ha: bolt diameter ≠ 10mm
// Scenario 2: fire alarm — H0: no fire; Ha: there is a fire
// Scenario 3: drug trial — H0: drug has no effect; Ha: drug has an effect
// Scenario 4: pollution — H0: water is safe; Ha: water is contaminated

$ctxs = array(
  "A medical test screens for a disease. The null hypothesis is <b>`H_0`: the patient does NOT have the disease</b>; the alternative is <b>`H_a`: the patient HAS the disease</b>.",
  "A quality-control team tests whether bolts coming off a production line have the target mean diameter. The null hypothesis is <b>`H_0: mu = 10` mm</b>; the alternative is <b>`H_a: mu != 10` mm</b>.",
  "A smoke-detection system makes a decision each minute. The null hypothesis is <b>`H_0`: there is NO fire</b>; the alternative is <b>`H_a`: there IS a fire</b>.",
  "A drug trial tests whether a new medication reduces blood pressure. The null hypothesis is <b>`H_0`: the drug has no effect</b>; the alternative is <b>`H_a`: the drug lowers blood pressure</b>.",
  "An environmental study tests whether a river is contaminated. The null hypothesis is <b>`H_0`: the water is safe</b>; the alternative is <b>`H_a`: the water is contaminated</b>."
)

// Type I error options (4 choices). Correct = "reject H0 when H0 is true" worded in context.
$type1_opt = array(
  array(
    "Conclude the patient has the disease when in fact the patient is healthy.",
    "Conclude the patient is healthy when in fact the patient has the disease.",
    "Conclude the patient has the disease when in fact the patient has the disease.",
    "Conclude the patient is healthy when in fact the patient is healthy."
  ),
  array(
    "Conclude the mean diameter differs from 10mm when in fact it is 10mm.",
    "Conclude the mean diameter is 10mm when in fact it differs from 10mm.",
    "Conclude the mean diameter is 10mm when in fact it is 10mm.",
    "Conclude the mean diameter differs from 10mm when in fact it differs from 10mm."
  ),
  array(
    "Sound the alarm when there is no fire.",
    "Stay silent when there is a fire.",
    "Sound the alarm when there is a fire.",
    "Stay silent when there is no fire."
  ),
  array(
    "Conclude the drug works when in fact it has no effect.",
    "Conclude the drug has no effect when in fact it works.",
    "Conclude the drug works when in fact it works.",
    "Conclude the drug has no effect when in fact it has no effect."
  ),
  array(
    "Conclude the water is contaminated when in fact it is safe.",
    "Conclude the water is safe when in fact it is contaminated.",
    "Conclude the water is contaminated when in fact it is contaminated.",
    "Conclude the water is safe when in fact it is safe."
  )
)
// All correct Type I answers are index 0 in this layout.
$type1_correct = array(0, 0, 0, 0, 0)

// Type II error options (parallel, but correct = "fail to reject H0 when H0 is false")
$type2_opt = array(
  array(
    "Conclude the patient is healthy when in fact the patient has the disease.",
    "Conclude the patient has the disease when in fact the patient is healthy.",
    "Conclude the patient is healthy when in fact the patient is healthy.",
    "Conclude the patient has the disease when in fact the patient has the disease."
  ),
  array(
    "Conclude the mean diameter is 10mm when in fact it differs from 10mm.",
    "Conclude the mean diameter differs from 10mm when in fact it is 10mm.",
    "Conclude the mean diameter is 10mm when in fact it is 10mm.",
    "Conclude the mean diameter differs from 10mm when in fact it differs from 10mm."
  ),
  array(
    "Stay silent when there is a fire.",
    "Sound the alarm when there is no fire.",
    "Sound the alarm when there is a fire.",
    "Stay silent when there is no fire."
  ),
  array(
    "Conclude the drug has no effect when in fact it works.",
    "Conclude the drug works when in fact it has no effect.",
    "Conclude the drug works when in fact it works.",
    "Conclude the drug has no effect when in fact it has no effect."
  ),
  array(
    "Conclude the water is safe when in fact it is contaminated.",
    "Conclude the water is contaminated when in fact it is safe.",
    "Conclude the water is contaminated when in fact it is contaminated.",
    "Conclude the water is safe when in fact it is safe."
  )
)
$type2_correct = array(0, 0, 0, 0, 0)

$picked = jointrandfrom($ctxs, $type1_opt, $type1_correct, $type2_opt, $type2_correct)
$ctx = $picked[0]

$questions[0] = $picked[1]
$answer[0] = $picked[2]

$questions[1] = $picked[3]
$answer[1] = $picked[4]

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Type I error</b>: reject `H_0` when `H_0` is true. (False positive.)</p>
      <p><b>Type II error</b>: fail to reject `H_0` when `H_0` is false. (False negative.)</p>
      <p>To name each in context: write out the decision the test would make ("conclude `H_a`" vs "stick with `H_0`") and pair it with what is actually true.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Mnemonic:</b> Type I = "I rejected a true null" (the "I" in "rejected"). Type II = "Too tired to reject a false null".
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> In this context, a <b>Type I error</b> would be to:
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> In this context, a <b>Type II error</b> would be to:
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
