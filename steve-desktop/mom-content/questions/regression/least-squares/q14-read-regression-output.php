// === NAME - DESCRIPTION: Read Regression Output - Pull slope estimate and p-value from a software table ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

// Scenario randomization — variable names
$ctx_x      = array("study_hours",   "ad_spend",   "exercise_min",      "income",        "temperature")
$ctx_y      = array("exam score",    "weekly sales", "resting heart rate", "monthly savings", "ice cream sales")
$ctx_xlabel = array("study hours per week", "advertising spend (hundreds of dollars)", "weekly exercise minutes", "monthly income (thousands)", "outdoor temperature (&deg;F)")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y, $ctx_xlabel)
$xvar = $picked_ctx[0]
$yname = $picked_ctx[1]
$xlabel = $picked_ctx[2]

// Pick a (t, p) pair so the output is internally consistent
$tvals = array(4.85, 3.10, 2.62, 2.18, 1.92, 1.42)
$pvals = array(0.001, 0.005, 0.012, 0.038, 0.072, 0.164)
$picked_tp = jointrandfrom($tvals, $pvals)
$slope_t = $picked_tp[0]
$slope_p = $picked_tp[1]

// Randomize slope estimate, then back out SE so estimate / SE = t
$slope_est_options = array(0.42, 0.78, 1.15, 0.31, 0.95)
$slope_est = $slope_est_options[rand(0, count($slope_est_options) - 1)]
$slope_se = round($slope_est / $slope_t, 4)

// Intercept row (cosmetic — not asked about)
$int_est = round(rand(20, 80) / 10, 2)
$int_se  = round(rand(5, 22) / 10, 2)
$int_t   = round($int_est / $int_se, 2)
$int_p   = 0.001

$alpha = 0.05

$answer[0] = $slope_est
$reltolerance[0] = 0.02

$answer[1] = $slope_p
$reltolerance[1] = 0.05

$choices[2] = array(
  "Yes, the slope is statistically significant at `alpha = 0.05`.",
  "No, the slope is not statistically significant at `alpha = 0.05`."
)
$noshuffle[2] = "all"

if ($slope_p < $alpha) {
  $answer[2] = 0
  $compare_text = "is less than `alpha = 0.05`"
  $decision_text = "reject the null hypothesis"
  $sig_summary = "is statistically significant"
} else {
  $answer[2] = 1
  $compare_text = "is greater than `alpha = 0.05`"
  $decision_text = "fail to reject the null hypothesis"
  $sig_summary = "is not statistically significant"
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
      <p><b>(a) Slope estimate.</b> Read straight off the row for ' . $xvar . ' in the Estimate column: <b>' . $slope_est . '</b>.</p>
      <p><b>(b) p-value.</b> Same row, p-value column: <b>' . $slope_p . '</b>.</p>
      <p><b>(c) Decision at `alpha = 0.05`.</b> The p-value `' . $slope_p . '` ' . $compare_text . ', so we ' . $decision_text . '. The slope ' . $sig_summary . '.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Quick rule: if `p < alpha`, reject `H_0: beta_1 = 0` and call the slope significant. Otherwise fail to reject.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A statistician fits a regression model predicting <b>{ $yname }</b> from <b>{ $xlabel }</b>, coded as <code>{ $xvar }</code>. The software output is shown below.</p>
    <table style="width:100%; border-collapse:collapse; margin:8px 0; font-family:Menlo,Consolas,monospace; font-size:14px;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="text-align:left; padding:6px 10px; border:1px solid #e5e7eb;">Term</th>
          <th style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">Estimate</th>
          <th style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">Std. Error</th>
          <th style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">t</th>
          <th style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">p-value</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="padding:6px 10px; border:1px solid #e5e7eb;">(Intercept)</td>
          <td style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">{ $int_est }</td>
          <td style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">{ $int_se }</td>
          <td style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">{ $int_t }</td>
          <td style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">{ $int_p }</td>
        </tr>
        <tr>
          <td style="padding:6px 10px; border:1px solid #e5e7eb;">{ $xvar }</td>
          <td style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">{ $slope_est }</td>
          <td style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">{ $slope_se }</td>
          <td style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">{ $slope_t }</td>
          <td style="text-align:right; padding:6px 10px; border:1px solid #e5e7eb;">{ $slope_p }</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>slope estimate</b>?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>p-value</b> for the slope?
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> At `alpha = 0.05`, is the slope statistically significant? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
