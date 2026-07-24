// === NAME - DESCRIPTION: Compute a Residual - Use regression equation to find residual from observed point ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices")

// Randomize the regression coefficients and observed point
$b0_options = array(array(41, 0.59, "possum head length (mm)", "total body length (cm)"),
                    array(12.2, 1.3, "weekly sales (thousands of dollars)", "advertising spend (hundreds of dollars)"),
                    array(25, 0.74, "final exam score (points)", "homework average (points)"))
$pick = $b0_options[rand(0, count($b0_options) - 1)]
$b0 = $pick[0]
$b1 = $pick[1]
$yunit = $pick[2]
$xunit = $pick[3]

$xval = rand(60, 95)
$yhat = round($b0 + $b1 * $xval, 1)
$offset = rand(-80, 80) / 10
$yobs = round($yhat + $offset, 1)
$residual = round($yobs - $yhat, 1)

$answer[0] = $residual
$reltolerance[0] = 0.02

if ($residual > 0) {
  $answer[1] = 0
} elseif ($residual < 0) {
  $answer[1] = 1
} else {
  $answer[1] = 2
}
$questions[1] = array(
  "The model underpredicted — the actual value was higher than predicted.",
  "The model overpredicted — the actual value was lower than predicted.",
  "The model predicted exactly — there is no error."
)

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
      <p><b>Step 1:</b> Compute the predicted value by plugging `x = ' . $xval . '` into the equation:</p>
      <p>`hat{y} = ' . $b0 . ' + ' . $b1 . '(' . $xval . ') = ' . $yhat . '`</p>
      <p><b>Step 2:</b> Compute the residual:</p>
      <p>`"residual" = y - hat{y} = ' . $yobs . ' - ' . $yhat . ' = ' . $residual . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Interpretation:</b> ' . ($residual > 0 ? 'The residual is positive, so the model <b>underpredicted</b> — the actual value was ' . abs($residual) . ' ' . explode(" (", $yunit)[0] . ' higher than predicted.' : ($residual < 0 ? 'The residual is negative, so the model <b>overpredicted</b> — the actual value was ' . abs($residual) . ' ' . explode(" (", $yunit)[0] . ' lower than predicted.' : 'The residual is zero — a perfect prediction!')) . '
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A regression model predicts $yunit from $xunit. The regression equation is:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`hat{y} = $b0 + {$b1}x`</p>
    <p style="margin:0;">One observation has `x = $xval` and an actual value of `y = $yobs`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the residual for this observation. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the sign of the residual tell you? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
