// === NAME - DESCRIPTION: CI for the Slope - SE, margin of error, lower and upper bounds ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number")

// Scenario randomization
$ctx_x      = array("study hours per week",   "advertising spend (hundreds of dollars)", "minutes of weekly exercise", "monthly income (thousands)", "outdoor temperature (&deg;F)")
$ctx_y      = array("exam score",             "weekly sales (dollars)",                  "resting heart rate (bpm)",   "monthly savings (dollars)",  "ice cream sales (dollars)")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xname = $picked_ctx[0]
$yname = $picked_ctx[1]

// (df, t*) for a 95% CI — pick parallel pairs so they line up
$dfs    = array(20,    30,    50,    100,   200)
$tstars = array(2.086, 2.042, 2.009, 1.984, 1.972)
$picked_df = jointrandfrom($dfs, $tstars)
$df = $picked_df[0]
$tstar = $picked_df[1]

// Slope estimate and SE
$b  = round(rand(35, 195) / 100, 2)   // 0.35 to 1.95
$se = round(rand(10, 30)  / 100, 2)   // 0.10 to 0.30
$me    = round($tstar * $se, 3)
$lower = round($b - $me, 3)
$upper = round($b + $me, 3)

$answer[0] = $se
$reltolerance[0] = 0.02

$answer[1] = $me
$reltolerance[1] = 0.02

$answer[2] = $lower
$reltolerance[2] = 0.02

$answer[3] = $upper
$reltolerance[3] = 0.02

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
      <p>A confidence interval for the slope follows the usual estimate `pm` margin pattern:</p>
      <p style="text-align:center;">`b_1 \pm t^{star} cdot SE_{b_1}`</p>
      <p><b>(a) Standard error.</b> Read directly from the output: `SE = ' . $se . '`.</p>
      <p><b>(b) Margin of error.</b> `ME = t^{star} cdot SE = ' . $tstar . ' cdot ' . $se . ' = ' . $me . '`.</p>
      <p><b>(c) Lower bound.</b> `' . $b . ' - ' . $me . ' = ' . $lower . '`.</p>
      <p><b>(d) Upper bound.</b> `' . $b . ' + ' . $me . ' = ' . $upper . '`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        95% CI for the slope: (' . $lower . ', ' . $upper . ').
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A regression of <b>$yname</b> on <b>$xname</b> reports a slope estimate of `b_1 = $b` with standard error `SE = $se`. The residual degrees of freedom are `df = $df`, and the corresponding 95% critical value is `t^{star} = $tstar`.</p>
    <p style="margin:0;">Build a 95% confidence interval for the population slope `beta_1`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the standard error of the slope?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the margin of error `ME = t^{star} cdot SE`. Round to 3 decimal places.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the lower bound of the 95% CI?
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What is the upper bound of the 95% CI?
    <div style="margin-top:12px;text-align:center;">$answerbox[3]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
