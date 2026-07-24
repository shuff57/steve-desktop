// === NAME - DESCRIPTION: Read a Regression Output Table - Pull slope, intercept, slope SE, t-stat, p-value from a software summary; decide whether the slope is significant ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "numfunc", "choices")

// Output: predictor name, response name, intercept b0, slope b1, SE_b1, n, alpha
$cases = array(
  array("hours studied", "exam score", 51.2, 4.85, 0.92, 30, 0.05),
  array("daily steps (1000s)", "resting heart rate (bpm)", 78.4, -1.34, 0.42, 25, 0.05),
  array("advertising spend ($1000s)", "weekly sales ($1000s)", 12.4, 2.07, 0.31, 22, 0.05),
  array("years of experience", "salary ($1000s)", 41.5, 3.20, 1.10, 18, 0.05),
  array("temperature (F)", "ice cream sales ($)", -85, 18.6, 4.4, 40, 0.01)
)

$i = rand(0, count($cases)-1)
$xname  = $cases[$i][0]
$yname  = $cases[$i][1]
$b0     = $cases[$i][2]
$b1     = $cases[$i][3]
$sebslope = $cases[$i][4]
$n      = $cases[$i][5]
$alpha  = $cases[$i][6]

$df = $n - 2
$tstat = $b1 / $sebslope
$pval = 2 * (1 - tcdf(abs($tstat), $df))

$answer[0] = $tstat
$answer[1] = $pval
$answer[2] = $b1   // direct read from output
$answer[3] = $pval < $alpha ? 0 : 1

$reltolerance[0] = 0.02
$reltolerance[1] = 0.05
$reltolerance[2] = 0.01
$abstolerance[0] = 0.02
$abstolerance[1] = 0.005
$abstolerance[2] = 0.01

// 4 options: 2 real decisions (context-anchored) + 2 misdirects
$choices[3] = array(
  "Reject `H_0: beta_1 = 0`: at `alpha = " . $alpha . "` the slope is significantly different from 0, so " . $xname . " is a useful linear predictor of " . $yname . ".",
  "Fail to reject `H_0: beta_1 = 0`: at `alpha = " . $alpha . "` the slope is not significantly different from 0, so " . $xname . " is not a useful linear predictor of " . $yname . ".",
  "Reject `H_0: beta_1 = 0`: the estimated slope is not zero, so it must be statistically significant.",
  "Fail to reject `H_0: beta_1 = 0`: a nonsignificant slope proves the true slope is exactly 0."
)
$noshuffle[3] = "all"

$tableHtml = '<table style="border-collapse:collapse;margin:0.5em 0;font-family:Arial;font-size:14px;">
<tr style="background:#f2f2f2"><th style="border:1px solid #ccc;padding:6px 10px;">Predictor</th><th style="border:1px solid #ccc;padding:6px 10px;">Coefficient</th><th style="border:1px solid #ccc;padding:6px 10px;">Std. Error</th><th style="border:1px solid #ccc;padding:6px 10px;">t</th><th style="border:1px solid #ccc;padding:6px 10px;">p-value</th></tr>
<tr><td style="border:1px solid #ccc;padding:6px 10px;">(Intercept)</td><td style="border:1px solid #ccc;padding:6px 10px;text-align:right;">' . $b0 . '</td><td style="border:1px solid #ccc;padding:6px 10px;text-align:right;">-</td><td style="border:1px solid #ccc;padding:6px 10px;text-align:right;">-</td><td style="border:1px solid #ccc;padding:6px 10px;text-align:right;">-</td></tr>
<tr><td style="border:1px solid #ccc;padding:6px 10px;">' . $xname . '</td><td style="border:1px solid #ccc;padding:6px 10px;text-align:right;">' . $b1 . '</td><td style="border:1px solid #ccc;padding:6px 10px;text-align:right;">' . $sebslope . '</td><td style="border:1px solid #ccc;padding:6px 10px;text-align:right;">?</td><td style="border:1px solid #ccc;padding:6px 10px;text-align:right;">?</td></tr>
</table>
<p style="margin:0.4em 0;">`n = ' . $n . '`, so `"df" = ' . $df . '`.</p>'

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
      <p><b>Part a:</b> `t = b_1 / "SE"(b_1) = ' . $b1 . ' / ' . $sebslope . ' ~~ ' . round($tstat, 3) . '`.</p>
      <p><b>Part b:</b> Two-tailed p-value with `"df" = ' . $df . '`: `~~ ' . round($pval, 4) . '`.</p>
      <p><b>Part c:</b> The slope is read directly from the output: `b_1 = ' . $b1 . '`.</p>
      <p><b>Part d:</b> ' . ($answer[3] == 0 ? "Since `p < alpha`, REJECT `H_0: beta_1 = 0`." : "Since `p >= alpha`, FAIL TO REJECT `H_0: beta_1 = 0`.") . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">A least-squares regression of <b>$yname</b> on <b>$xname</b> produced this partial output (the t and p-value cells are blank):</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the <b>t-statistic</b> for the slope. (Round to 3 dp.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the <b>two-tailed p-value</b>. (Round to 4 dp.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the <b>estimated slope</b>? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Test `H_0: beta_1 = 0` at `alpha = $alpha`. $answerbox[3]
  </div>
</div>


// === ANSWER ===

$solutionguide
