// === NAME - DESCRIPTION: t from Output - Compute t = Estimate / SE and decide at alpha = 0.05 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices")

// Scenario randomization
$ctx_x      = array("study_hours",         "ad_spend",                 "exercise_min",        "income",                 "temperature")
$ctx_y      = array("exam score",          "weekly sales (dollars)",   "resting heart rate",  "monthly savings (dollars)", "ice cream sales (dollars)")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xvar = $picked_ctx[0]
$yname = $picked_ctx[1]

// Pick a (target_t, p) pair so the displayed p-value lines up with the t-statistic
$target_ts = array(4.85, 3.10, 2.62, 2.18, 1.92, 1.42, 0.85)
$pvals     = array(0.001, 0.005, 0.012, 0.038, 0.072, 0.164, 0.405)
$picked_tp = jointrandfrom($target_ts, $pvals)
$target_t = $picked_tp[0]
$slope_p = $picked_tp[1]

// Pick a slope estimate, then back out SE so estimate / SE rounds back to a clean t
$slope_est_options = array(0.42, 0.78, 1.15, 0.31, 0.95, 1.40, 2.05)
$slope_est = $slope_est_options[rand(0, count($slope_est_options) - 1)]
$slope_se  = round($slope_est / $target_t, 3)
$true_t    = round($slope_est / $slope_se, 2)

$alpha = 0.05

$answer[0] = $true_t
$reltolerance[0] = 0.02

$choices[1] = array(
  "Reject `H_0: beta_1 = 0` at `alpha = 0.05`",
  "Fail to reject `H_0: beta_1 = 0` at `alpha = 0.05`"
)
$noshuffle[1] = "all"

if ($slope_p < $alpha) {
  $answer[1] = 0
  $compare_text = "less than `alpha = 0.05`"
  $decision_text = "reject the null hypothesis"
  $sig_summary = "There is convincing evidence that the slope is not zero."
} else {
  $answer[1] = 1
  $compare_text = "greater than `alpha = 0.05`"
  $decision_text = "fail to reject the null hypothesis"
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
      <p><b>(a) Compute the t-statistic.</b> The test statistic for the slope is the estimate divided by its standard error:</p>
      <p style="text-align:center;">`t = "Estimate" / "SE" = ' . $slope_est . ' / ' . $slope_se . ' = ' . $true_t . '`</p>
      <p><b>(b) Compare the p-value to `alpha`.</b> The reported p-value is `' . $slope_p . '`, which is ' . $compare_text . '. So we ' . $decision_text . ' that the slope equals zero.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        ' . $sig_summary . '
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Software fits a regression of <b>{ $yname }</b> on <b>{ $xvar }</b> and reports the slope row of the output:</p>
    <ul style="margin:0; padding-left:24px;">
      <li>Estimate: <b>{ $slope_est }</b></li>
      <li>Standard Error: <b>{ $slope_se }</b></li>
      <li>p-value: <b>{ $slope_p }</b></li>
    </ul>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the t-statistic for the slope. Round to 2 decimal places.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the decision at `alpha = 0.05`? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
