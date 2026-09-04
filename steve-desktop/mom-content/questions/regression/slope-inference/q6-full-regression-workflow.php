// === NAME - DESCRIPTION: Full Regression Workflow - Check conditions, state hypotheses, compute t and CI, decide, conclude ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "number", "number", "choices")

// Scenario randomization
$ctx_x      = array("weekly study hours",       "advertising budget (hundreds of dollars)", "morning commute time (min)", "square footage of a home (hundreds)", "daily screen time (hours)")
$ctx_y      = array("final exam score",         "monthly revenue (thousands)",              "afternoon energy level",     "listing price (thousands)",           "sleep quality score")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xname = $picked_ctx[0]
$yname = $picked_ctx[1]

// Choose (slope_b, target_t, p_val, direction) bundles
// direction: 0 = positive slope, 1 = negative slope
$slope_opts  = array(1.24, 0.87, 1.55, 0.63, 2.10, 0.48)
$t_opts      = array(3.12, 2.68, 4.05, 1.89, 5.23, 1.45)
$p_opts      = array(0.004, 0.011, 0.0002, 0.067, 0.00001, 0.155)
$dir_opts    = array(0,     0,     0,      0,     0,        1)
$picked_btp = jointrandfrom($slope_opts, $t_opts, $p_opts, $dir_opts)
$b1    = $picked_btp[0]
$test_t = $picked_btp[1]
$p_val  = $picked_btp[2]
$dir    = $picked_btp[3]
$se_b1  = round($b1 / $test_t, 4)

// For negative-slope scenarios, flip the sign
if ($dir == 1) {
  $b1 = -1 * $b1
  $se_b1 = round((-1 * $b1) / $test_t, 4)
}

$n_samples = array(22, 28, 35, 42, 55)
$n = $n_samples[rand(0, count($n_samples) - 1)]

$alpha = 0.05

// (df, t*) for 95% CI: parallel pairs
$dfs_ci   = array(20,    30,    40,    50,    60)
$tstars_ci = array(2.086, 2.042, 2.021, 2.009, 1.999)
$picked_df = jointrandfrom($dfs_ci, $tstars_ci)
$df_ci = $picked_df[0]
$tstar = $picked_df[1]

// Use n-2 for df in the test
$df_test = $n - 2

// Compute CI
$me    = round($tstar * $se_b1, 4)
$lower = round($b1 - $me, 4)
$upper = round($b1 + $me, 4)

// Part a: LINE conditions
$line_choices = array(
  "All four LINE conditions appear reasonably met: the scatterplot is linear, observations are independent, residuals are roughly normal, and the residual plot shows constant spread.",
  "The Linearity condition is violated: the scatterplot shows a clear curve.",
  "The Independence condition is violated: the data were collected from the same subjects over time without checking for serial correlation.",
  "The Equal variance condition is violated: the residual plot shows a fan shape."
)
$choices[0] = $line_choices
$noshuffle[0] = "all"
$answer[0] = 0

// Part b: Hypotheses
if ($dir == 0) {
  // positive slope: two-sided test
  $h0_text = "`H_0: beta_1 = 0`"
  $ha_text = "`H_a: beta_1 \\ne 0`"
  $hyp_choices = array(
    "`H_0: beta_1 = 0` vs. `H_a: beta_1 \\ne 0`",
    "`H_0: beta_1 = 0` vs. `H_a: beta_1 > 0`",
    "`H_0: b_1 = 0` vs. `H_a: b_1 > 0`",
    "`H_0: beta_1 \\ne 0` vs. `H_a: beta_1 = 0`"
  )
  $answer[1] = 0
  $ha_label = "`H_a: beta_1 \\ne 0`"
} else {
  // negative slope: one-sided test
  $h0_text = "`H_0: beta_1 = 0`"
  $ha_text = "`H_a: beta_1 < 0`"
  $hyp_choices = array(
    "`H_0: beta_1 = 0` vs. `H_a: beta_1 < 0`",
    "`H_0: beta_1 = 0` vs. `H_a: beta_1 \\ne 0`",
    "`H_0: b_1 = 0` vs. `H_a: b_1 < 0`",
    "`H_0: beta_1 \\ge 0` vs. `H_a: beta_1 < 0`"
  )
  $answer[1] = 0
  $ha_label = "`H_a: beta_1 < 0`"
}
$choices[1] = $hyp_choices
$noshuffle[1] = "all"

// Part c: t-statistic
$answer[2] = $test_t
$reltolerance[2] = 0.02

// Part d: 95% CI lower bound
$answer[3] = $lower
$reltolerance[3] = 0.02

// Part e: Decision and conclusion
$contains_zero = ($lower < 0 && $upper > 0)

if ($p_val < $alpha) {
  $answer[4] = 0
  $decision_label = "reject `H_0`"
  $comparison_text = "less than `alpha = 0.05`"
  $reject_conclusion = "there is convincing evidence of a linear relationship between $xname and $yname in the population."
  $fail_conclusion = "the data do not provide convincing evidence... (contradicts rejecting `H_0`)."
  $sol_conclusion = "There is convincing evidence of a linear relationship between $xname and $yname in the population."
} else {
  $answer[4] = 1
  $decision_label = "fail to reject `H_0`"
  $comparison_text = "greater than `alpha = 0.05`"
  $fail_conclusion = "the data do not provide convincing evidence of a linear relationship between $xname and $yname in the population."
  $reject_conclusion = "there is convincing evidence... (contradicts failing to reject `H_0`)."
  $sol_conclusion = "The data do not provide convincing evidence of a linear relationship between $xname and $yname in the population."
}

$conclusion_choices = array(
  "Since `p < 0.05`, we reject `H_0`. " . $reject_conclusion,
  "Since `p > 0.05`, we fail to reject `H_0`. " . $fail_conclusion,
  "Since the CI contains 0, there is no relationship between the variables.",
  "Since `r` is large, the relationship is definitely causal."
)
// Fix: for p < alpha, the first option is correct (index 0)
// For p >= alpha, the second option is correct (index 1): already set above
$choices[4] = $conclusion_choices
$noshuffle[4] = "all"

// Build CI interpretation text
if ($contains_zero) {
  $ci_interp = "The 95% CI (`$lower`, `$upper`) contains 0, consistent with failing to reject `H_0`."
} else {
  $ci_interp = "The 95% CI (`$lower`, `$upper`) does not contain 0, consistent with rejecting `H_0`."
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
      <p><b>(a) LINE conditions.</b> The problem states the scatterplot looks linear, observations are independent, the residual plot shows no fan shape, and the residual distribution is roughly symmetric. All four conditions are reasonably satisfied.</p>
      <p><b>(b) Hypotheses.</b> ' . $h0_text . ' vs. ' . $ha_text . '. We are testing whether the population slope differs from zero (the null of no linear relationship).</p>
      <p><b>(c) Test statistic.</b> `t = b_1 / SE = ' . $b1 . ' / ' . $se_b1 . ' = ' . $test_t . '`.</p>
      <p><b>(d) 95% CI lower bound.</b> `ME = t^{star} cdot SE = ' . $tstar . ' cdot ' . $se_b1 . ' = ' . $me . '`. Lower bound: `' . $b1 . ' - ' . $me . ' = ' . $lower . '`.</p>
      <p><b>(e) Decision.</b> p-value = ' . $p_val . ' is ' . $comparison_text . ', so we ' . $decision_label . '. ' . $sol_conclusion . '</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>CI check:</b> ' . $ci_interp . '
      </div>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff4e5; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Common trap:</b> Failing to reject `H_0` does not mean "there is no relationship." It means the current data are not strong enough to rule out zero.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A researcher fits a least-squares regression of <b>$yname</b> on <b>$xname</b> using <b>$n</b> observations. The scatterplot shows a linear trend, the observations are independent, and the residual plot shows no fan shape or curve. The software reports the following for the slope:</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 4px 6px -1px rgba(0,0,0,0.08),0 2px 4px -2px rgba(0,0,0,0.05);border:1px solid #e5e7eb;display:inline-block;margin:8px 0;">
      <table style="border-collapse:collapse; font-size:15px;">
        <tr style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3;">
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb;">&nbsp;</td>
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb;">Estimate</td>
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb;">Std. Error</td>
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb;">t value</td>
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb;">p-value</td>
        </tr>
        <tr>
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb; font-weight:600;">$xname</td>
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb;">$b1</td>
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb;">$se_b1</td>
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb;">$test_t</td>
          <td style="padding:8px 14px; border-left:1px solid #e5e7eb;">$p_val</td>
        </tr>
      </table>
    </div>
    <p style="margin:10px 0 0 0;">Use this output to carry out a complete inference procedure for the population slope.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Are the LINE conditions for slope inference reasonably met? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Choose the correct hypotheses for testing whether there is a linear relationship in the population. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Compute the test statistic `T = b_1 / SE`. Round to 2 decimal places.
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Compute the lower bound of the 95% confidence interval for `beta_1`. Use `t^{star} = $tstar` and `df = $df_ci`. Round to 4 decimal places.
    <div style="margin-top:12px;text-align:center;">$answerbox[3]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Choose the correct decision and conclusion at `alpha = 0.05`. $answerbox[4]
  </div>
</div>


// === ANSWER ===

$solutionguide