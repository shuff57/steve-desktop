// === NAME - DESCRIPTION: Interpret a CI for the Slope - Contains zero, evidence of relationship, alpha=0.05 decision ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Scenario randomization
$ctx_x      = array("study hours per week",   "advertising spend (hundreds of dollars)", "minutes of weekly exercise", "monthly income (thousands)", "outdoor temperature (&deg;F)")
$ctx_y      = array("exam score",             "weekly sales (dollars)",                  "resting heart rate (bpm)",   "monthly savings (dollars)",  "ice cream sales (dollars)")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xname = $picked_ctx[0]
$yname = $picked_ctx[1]

// CI cases: parallel arrays so the (case, low, high) triple stays consistent.
// "pos"  = entirely above 0, "neg" = entirely below 0, "zero" = brackets 0.
$cases = array("pos",  "neg",  "zero", "pos",  "zero", "neg")
$lows  = array(0.12,  -2.30, -0.18,   0.40,  -0.85,  -1.40)
$highs = array(0.85,  -0.30,  0.55,   1.10,   1.20,  -0.10)
$picked_ci = jointrandfrom($cases, $lows, $highs)
$case = $picked_ci[0]
$low  = $picked_ci[1]
$high = $picked_ci[2]

// Part a: does the CI contain 0?
$choices[0] = array("Yes, the interval contains 0", "No, the interval does not contain 0")
$displayformat[0] = "select"
$noshuffle[0] = "all"
if ($case == "zero") {
  $answer[0] = 0
  $contains_text = "contains 0"
} else {
  $answer[0] = 1
  $contains_text = "does not contain 0"
}

// Part b: what does that mean about the relationship?
$choices[1] = array(
  "There is convincing evidence of a positive linear relationship between `x` and `y` in the population.",
  "There is convincing evidence of a negative linear relationship between `x` and `y` in the population.",
  "There is not convincing evidence of a linear relationship between `x` and `y` in the population.",
  "We have proven there is no relationship between `x` and `y` in the population."
)
$noshuffle[1] = "all"

if ($case == "pos") {
  $answer[1] = 0
  $relate_text = "the entire interval lies above 0, so we have convincing evidence of a positive linear relationship"
} else if ($case == "neg") {
  $answer[1] = 1
  $relate_text = "the entire interval lies below 0, so we have convincing evidence of a negative linear relationship"
} else {
  $answer[1] = 2
  $relate_text = "0 is a plausible value for the slope, so we do not have convincing evidence of a linear relationship"
}

// Part c: at alpha = 0.05, reject H_0?
$choices[2] = array("Reject `H_0`", "Fail to reject `H_0`")
$displayformat[2] = "select"
$noshuffle[2] = "all"

if ($case == "zero") {
  $answer[2] = 1
  $decision_text = "fail to reject `H_0`"
  $why_text = "since 0 is inside the 95% CI for `beta_1`"
} else {
  $answer[2] = 0
  $decision_text = "reject `H_0`"
  $why_text = "since 0 is outside the 95% CI for `beta_1`"
}

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
      <p><b>(a) Does the CI contain 0?</b> The 95% CI for the slope is (' . $low . ', ' . $high . '), which ' . $contains_text . '.</p>
      <p><b>(b) What that means.</b> A CI for `beta_1` collects every plausible slope. Here ' . $relate_text . '.</p>
      <p><b>(c) Decision at `alpha = 0.05`.</b> A 95% CI matches a two-sided test at `alpha = 0.05`. We ' . $decision_text . ' ' . $why_text . '.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Quick rule: if 0 is in the 95% CI for `beta_1`, fail to reject `H_0: beta_1 = 0`. If 0 is outside, reject.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A regression of <b>$yname</b> on <b>$xname</b> produces this 95% confidence interval for the population slope `beta_1`:</p>
    <p style="text-align:center; font-size:18px; margin:0;"><b>($low, $high)</b></p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Does the interval contain 0? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Pick the best interpretation of this interval. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Using this CI, what is the decision for `H_0: beta_1 = 0` vs. `H_a: beta_1 ne 0` at `alpha = 0.05`? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
