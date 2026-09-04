// === NAME - DESCRIPTION: CI from Technology Output - Read software output, construct slope CI, decide significance ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

// Scenario randomization
$ctx_x = array("study hours per week",  "advertising spend (hundreds of dollars)", "minutes of weekly exercise", "monthly income (thousands)")
$ctx_y = array("exam score",            "weekly sales (dollars)",                  "resting heart rate (bpm)",   "monthly savings (dollars)")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xname = $picked_ctx[0]
$yname = $picked_ctx[1]

// (df, t*) for 95% CI: pick parallel pairs
$dfs    = array(20,    30,    50,    100,   200)
$tstars = array(2.086, 2.042, 2.009, 1.984, 1.972)
$picked_df = jointrandfrom($dfs, $tstars)
$df = $picked_df[0]
$tstar = $picked_df[1]

// Slope estimate and SE: chosen so the CI ALWAYS excludes 0 (significant case).
// max ME = 2.086 * 0.30 = 0.626; min lower = 0.80 - 0.626 = 0.174 > 0.
$b  = round(rand(80, 200) / 100, 2)
$se = round(rand(15, 30)  / 100, 2)

$me    = round($tstar * $se, 3)
$lower = round($b - $me, 3)
$upper = round($b + $me, 3)

$answer[0] = $tstar
$reltolerance[0] = 0.01

$answer[1] = $lower
$reltolerance[1] = 0.02

$answer[2] = $upper
$reltolerance[2] = 0.02

// Part d: significance interpretation. Because lower > 0 by construction, CI excludes 0.
$choices[3] = array(
  "The interval excludes 0, so there is evidence the predictor is associated with the response (reject `H_0: beta_1 = 0` at `alpha = 0.05`).",
  "The interval contains 0, so we do not have evidence that the predictor is associated with the response (fail to reject `H_0: beta_1 = 0` at `alpha = 0.05`).",
  "The interval is positive, so the predictor causes the response to increase.",
  "A CI cannot be used to make a decision about `H_0`; we would need a p-value."
)
$noshuffle[3] = "all"
$answer[3] = 0

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
      <p>The 95% CI for a slope follows the standard estimate `pm` margin pattern using the technology output:</p>
      <p style="text-align:center;">`b_1 \pm t^{star} cdot SE_{b_1}`</p>
      <p><b>(a) Critical value.</b> For `df = ' . $df . '` and a 95% CI, `t^{star} = ' . $tstar . '` (from the t-table or calculator).</p>
      <p><b>(b) Lower bound.</b> `b_1 - t^{star} cdot SE = ' . $b . ' - ' . $tstar . ' cdot ' . $se . ' = ' . $b . ' - ' . $me . ' = ' . $lower . '`.</p>
      <p><b>(c) Upper bound.</b> `b_1 + t^{star} cdot SE = ' . $b . ' + ' . $me . ' = ' . $upper . '`.</p>
      <p><b>(d) Conclusion.</b> The 95% CI is `(' . $lower . ', ' . $upper . ')`. Because every value in the interval is positive, 0 is not a plausible value for the population slope `beta_1`. We have evidence the predictor is associated with the response: equivalent to rejecting `H_0: beta_1 = 0` at `alpha = 0.05`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Decision rule from a CI: if 0 is NOT in the interval &rarr; reject `H_0: beta_1 = 0`. If 0 IS in the interval &rarr; fail to reject.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Software output for a regression of <b>$yname</b> on <b>$xname</b>:</p>
    <table style="border-collapse:collapse; width:100%; max-width:480px; margin:8px 0;">
      <tr style="background:#f0f4ff;"><th style="border:1px solid #ccc; padding:6px; text-align:left;">Term</th><th style="border:1px solid #ccc; padding:6px; text-align:right;">Estimate</th><th style="border:1px solid #ccc; padding:6px; text-align:right;">Std Error</th></tr>
      <tr><td style="border:1px solid #ccc; padding:6px;">Slope</td><td style="border:1px solid #ccc; padding:6px; text-align:right;">$b</td><td style="border:1px solid #ccc; padding:6px; text-align:right;">$se</td></tr>
    </table>
    <p style="margin:8px 0 0 0;">Residual degrees of freedom: `df = $df`. Use a 95% confidence level.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the critical value `t^{star}` for a 95% CI with `df = $df`?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the lower bound of the 95% CI for the population slope. Round to 3 decimal places.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Compute the upper bound of the 95% CI for the population slope. Round to 3 decimal places.
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Based on this CI, what conclusion do you reach about `H_0: beta_1 = 0`? $answerbox[3]
  </div>
</div>


// === ANSWER ===

$solutionguide
