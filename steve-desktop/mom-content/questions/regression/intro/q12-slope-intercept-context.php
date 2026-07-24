// === NAME - DESCRIPTION: Slope and Intercept in Context - Plant growth over weeks ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$xval = rand(4, 10)
$yhat = 5.1 + 2.3 * $xval
$product = 2.3 * $xval

$anstypes = array("choices", "number")

$questions[0] = array(
  "For each additional week, the predicted plant height increases by 2.3 cm.",
  "For each additional week, the predicted plant height decreases by 2.3 cm.",
  "After 2.3 weeks the plant stops growing.",
  "The plant starts at 2.3 cm tall."
)
$answer[0] = 0

$answer[1] = $yhat
$reltolerance[1] = 0.01

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
      <p><b>Part a:</b> The slope (2.3) is the rate of change. It tells us that for each additional week, the predicted plant height increases by 2.3 cm.</p>
      <p><b>Part b:</b> Substitute `x = ' . $xval . '`:</p>
      <p>`hat{y} = 5.1 + 2.3(' . $xval . ') = 5.1 + ' . $product . ' = ' . $yhat . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        The slope means the plant grows about 2.3 cm per week.<br>
        <b>Predicted height at week ' . $xval . ':</b> ' . $yhat . ' cm
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A student tracks the height of a bean plant `y` (cm) over several weeks `x` after planting. The regression equation is:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`hat{y} = 5.1 + 2.3x`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the best interpretation of the <b>slope</b> (2.3)? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Predict the plant height at <b>week $xval</b>. $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
