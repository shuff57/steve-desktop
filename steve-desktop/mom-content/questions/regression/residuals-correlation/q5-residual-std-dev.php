// === NAME - DESCRIPTION: Estimate Residual Standard Deviation - Use 95% rule to estimate s from residual plot bounds ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices")

// Randomize the 95% bounds
$s_true = rand(2, 12)
$bound = 2 * $s_true

// Context options
$ctx_options = array(
  array("predicting home sale price (thousands of dollars) from square footage", "thousand dollars"),
  array("predicting exam score (points) from study hours", "points"),
  array("predicting crop yield (bushels per acre) from rainfall (inches)", "bushels per acre"),
  array("predicting heart rate (bpm) from exercise duration (minutes)", "bpm")
)
$ctx = $ctx_options[rand(0, count($ctx_options) - 1)]
$context = $ctx[0]
$units = $ctx[1]

$answer[0] = $s_true
$reltolerance[0] = 0.01

// Part b: interpretation
$questions[1] = array(
  "The typical prediction error using this model is about $s_true $units.",
  "About $s_true% of the predictions will be correct.",
  "The model explains $s_true% of the variation in the response variable.",
  "The residuals have $s_true outliers."
)
$answer[1] = 0

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
      <p><b>Part a:</b> Using the 68-95 rule, approximately 95% of residuals fall within `pm 2s`.</p>
      <p>Since 95% of residuals fall between `{-' . $bound . '}` and `' . $bound . '`, we have:</p>
      <p>`2s = ' . $bound . '`, so `s = ' . $bound . ' -: 2 = ' . $s_true . '`</p>
      <p><b>Part b:</b> The standard deviation of residuals `s` measures the <b>typical prediction error</b>. Here, `s = ' . $s_true . '` means the model\'s predictions are typically off by about ' . $s_true . ' ' . $units . '.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> The residual standard deviation `s` tells you the typical size of prediction errors. Smaller `s` means more accurate predictions.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A linear model is used for $context. A residual plot shows that approximately 95% of the residuals fall between <b>&minus;$bound</b> and <b>$bound</b> $units.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Estimate the standard deviation of the residuals, `s`. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which statement best interprets the value of `s`? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
