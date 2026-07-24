// === NAME - DESCRIPTION: Residual Sign Interpretation - Determine over/underprediction from residual value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

// Randomize the residual value
$resid_abs = rand(15, 85) / 10
$sign = rand(0, 1)
if ($sign == 0) {
  $residual = $resid_abs
  $direction = "above"
} else {
  $residual = -1 * $resid_abs
  $direction = "below"
}

// Part a: above or below the line
$questions[0] = array("Above the regression line", "Below the regression line", "Exactly on the regression line")
if ($sign == 0) {
  $answer[0] = 0
} else {
  $answer[0] = 1
}
$noshuffle[0] = "all"

// Part b: over or underpredicted
$questions[1] = array(
  "The model underpredicted the actual value.",
  "The model overpredicted the actual value.",
  "The model predicted exactly correctly."
)
if ($sign == 0) {
  $answer[1] = 0
} else {
  $answer[1] = 1
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
      <p>The residual is `"residual" = y - hat{y} = ' . $residual . '`.</p>
      <p><b>Part a:</b> Since the residual is ' . ($sign == 0 ? 'positive' : 'negative') . ', the observed value `y` is ' . ($sign == 0 ? 'greater' : 'less') . ' than the predicted value `hat{y}`. This means the data point is <b>' . $direction . '</b> the regression line.</p>
      <p><b>Part b:</b> ' . ($sign == 0 ? 'A positive residual means `y > hat{y}`, so the model <b>underpredicted</b> — it guessed too low.' : 'A negative residual means `y < hat{y}`, so the model <b>overpredicted</b> — it guessed too high.') . '</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Remember:</b> residual = observed &minus; predicted. Positive &rarr; above the line (underpredicted). Negative &rarr; below the line (overpredicted).
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A data point in a regression analysis has a residual of <b>$residual</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is this data point <b>above</b> or <b>below</b> the regression line? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Did the model overpredict or underpredict the actual value? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
