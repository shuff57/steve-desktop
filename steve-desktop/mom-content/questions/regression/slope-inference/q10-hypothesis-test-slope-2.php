// === NAME - DESCRIPTION: Hypothesis Test for the Slope - State null and alternative hypotheses, interpret p-value at alpha=0.05 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// New randomized contexts: no overlap with q2-hypothesis-test-slope.php
$ctx_x = array("hours of sleep per night",     "distance from campus (miles)",    "number of employees",      "pages in the textbook",  "commute distance (miles)")
$ctx_y = array("GPA",                          "monthly rent (dollars)",          "annual revenue (millions)", "price (dollars)",        "weekly gas cost (dollars)")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xname = $picked_ctx[0]
$yname = $picked_ctx[1]

$target_ts = array(4.85, 3.10, 2.62, 2.18, 1.92, 1.42, 0.85)
$pvals     = array(0.001, 0.005, 0.012, 0.038, 0.072, 0.164, 0.405)
$picked_tp = jointrandfrom($target_ts, $pvals)
$target_t = $picked_tp[0]
$slope_p = $picked_tp[1]

$slope_est_options = array(0.42, 0.78, 1.15, 0.31, 0.95, 1.40)
$slope_est = $slope_est_options[rand(0, count($slope_est_options) - 1)]
$slope_se  = round($slope_est / $target_t, 3)

$alpha = 0.05

// Part a: H0
$choices[0] = array("`H_0: beta_1 = 0`", "`H_0: beta_1 \\ne 0`", "`H_0: b_1 = beta_1`")
$noshuffle[0] = "all"
$answer[0] = 0

// Part b: Ha
$choices[1] = array("`H_a: beta_1 \\ne 0`", "`H_a: beta_1 = 0`", "`H_a: beta_1 > b_1`")
$noshuffle[1] = "all"
$answer[1] = 0

// Part c: p-value interpretation
$choices[2] = array(
  "The p-value is below `alpha`, so we reject `H_0` and conclude there is evidence of a linear relationship between `x` and `y` in the population.",
  "The p-value is above `alpha`, so we fail to reject `H_0`; the data do not give convincing evidence of a linear relationship.",
  "The p-value is the probability that `H_0` is true, so a small p-value means `H_0` is false.",
  "The p-value tells us how often `b_1` would equal `beta_1` exactly under repeated sampling."
)
$noshuffle[2] = "all"

if ($slope_p < $alpha) {
  $answer[2] = 0
  $compare_text = "less than `alpha = 0.05`"
  $decision_text = "reject `H_0`"
  $sig_summary = "There is convincing evidence that the slope is not zero in the population."
} else {
  $answer[2] = 1
  $compare_text = "greater than `alpha = 0.05`"
  $decision_text = "fail to reject `H_0`"
  $sig_summary = "There is not enough evidence to conclude the slope differs from zero."
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
      <p><b>(a) Null hypothesis.</b> The standard test asks whether the population slope is zero: `H_0: beta_1 = 0`.</p>
      <p><b>(b) Alternative hypothesis.</b> The two-sided alternative is `H_a: beta_1 ne 0` (some linear relationship exists).</p>
      <p><b>(c) p-value decision.</b> The p-value `' . $slope_p . '` is ' . $compare_text . ', so we ' . $decision_text . '. ' . $sig_summary . '</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff4e5; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Common trap:</b> the p-value is NOT the probability that `H_0` is true. It is the probability of seeing data this extreme (or more) if `H_0` were true.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A regression of <b>$yname</b> on <b>$xname</b> produces this slope row:</p>
    <ul style="margin:0; padding-left:24px;">
      <li>Estimate: <b>$slope_est</b></li>
      <li>Std. Error: <b>$slope_se</b></li>
      <li>p-value: <b>$slope_p</b></li>
    </ul>
    <p style="margin:10px 0 0 0;">Use this output to test whether there is a linear relationship in the population.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Choose the correct null hypothesis. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Choose the correct (two-sided) alternative hypothesis. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Choose the best interpretation of the p-value at `alpha = 0.05`. $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
