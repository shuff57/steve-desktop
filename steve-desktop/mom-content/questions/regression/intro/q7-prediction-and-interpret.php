// === NAME - DESCRIPTION: Prediction and Interpret - Used car price above or below the line ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$xval = rand(3, 10)
$yhat = 28 - 1.5 * $xval

// Observed value: randomly above or below the line
$above = rand(0, 1)
if ($above == 1) {
  $offset = rand(1, 4)
  $yobs = $yhat + $offset
  $answer[1] = 0
  $abovebelow = "above"
  $compare = "greater than"
} else {
  $offset = rand(1, 4)
  $yobs = $yhat - $offset
  $answer[1] = 1
  $abovebelow = "below"
  $compare = "less than"
}

$anstypes = array("number", "choices")
$answer[0] = $yhat
$reltolerance[0] = 0.01

$questions[1] = array("Above the line", "Below the line")
$noshuffle[1] = "all"

$product = 1.5 * $xval

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
      <p><b>Part a:</b> Substitute `x = ' . $xval . '` into the equation:</p>
      <p>`hat{y} = 28 - 1.5(' . $xval . ') = 28 - ' . $product . ' = ' . $yhat . '`</p>
      <p><b>Part b:</b> The actual price is ' . $yobs . ' and the predicted price is ' . $yhat . '. Since ' . $yobs . ' is ' . $compare . ' ' . $yhat . ', the point falls <b>' . $abovebelow . '</b> the regression line.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Predicted: ' . $yhat . ' thousand dollars<br>
        Observed: ' . $yobs . ' thousand dollars<br>
        The point is <b>' . $abovebelow . '</b> the line.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A study of used cars models the relationship between a car's age `x` (years) and its price `y` (thousands of dollars). The regression equation is:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`hat{y} = 28 - 1.5x`</p>
    <p style="margin:0;">A $xval-year-old car has an actual selling price of <b>$yobs thousand dollars</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>predicted</b> price when `x = $xval`? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Is the actual selling price <b>above</b> or <b>below</b> the regression line? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
