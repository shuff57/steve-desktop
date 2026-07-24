// === NAME - DESCRIPTION: Residual Concepts - Multiple choice on properties of residuals ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

// Randomize which conceptual question variant appears
$variant = rand(0, 2)

if ($variant == 0) {
  $stem = "Which of the following statements about residuals is <b>true</b>?"
  $questions = array(
    "A residual is the difference between the observed value and the predicted value: residual = y &minus; &#375;.",
    "A positive residual means the model overpredicted the actual value.",
    "Residuals are always positive because we square them.",
    "If all residuals are zero, the model is a poor fit."
  )
  $answer = 0
  $explain = '<p>The residual is defined as `"residual" = y - hat{y}`, the difference between the observed and predicted values.</p>
    <p><b>Why the others are wrong:</b></p>
    <ul>
      <li>A <i>positive</i> residual means `y > hat{y}`, so the model <i>under</i>predicted (not overpredicted).</li>
      <li>Residuals can be positive or negative. We square them in certain calculations (like finding `s`), but the residuals themselves have a sign.</li>
      <li>If all residuals are zero, the model fits the data perfectly &mdash; that would be an ideal (but unrealistic) fit!</li>
    </ul>'
} elseif ($variant == 1) {
  $stem = "Which of the following is <b>NOT</b> a use of residuals in regression analysis?"
  $questions = array(
    "Determining the sample size needed for a study.",
    "Assessing how well a linear model fits the data.",
    "Identifying outliers &mdash; observations with unusually large residuals.",
    "Checking whether a linear model is appropriate by looking for patterns in a residual plot."
  )
  $answer = 0
  $explain = '<p><b>Sample size planning</b> is not determined from residuals. Residuals are computed <i>after</i> fitting a model to existing data.</p>
    <p><b>The other three are valid uses:</b></p>
    <ul>
      <li>Small residuals overall &rarr; good model fit.</li>
      <li>Large individual residuals &rarr; potential outliers.</li>
      <li>Patterns in residual plots &rarr; linear model may not be appropriate.</li>
    </ul>'
} else {
  $stem = "A regression model produces the following residuals for five data points: &minus;3, +1, +5, &minus;2, &minus;1. Which statement is correct?"
  $questions = array(
    "The model overpredicts the first data point by 3 units.",
    "The model underpredicts the first data point by 3 units.",
    "The third data point falls below the regression line.",
    "All five points fall exactly on the regression line."
  )
  $answer = 0
  $explain = '<p>The first residual is <b>&minus;3</b>, meaning `y - hat{y} = -3`, so `y < hat{y}`. The observed value is 3 less than predicted &mdash; the model <b>overpredicted</b> by 3 units.</p>
    <p><b>Why the others are wrong:</b></p>
    <ul>
      <li>A negative residual means overprediction, not underprediction.</li>
      <li>The third residual is +5 (positive), so that point is <i>above</i> the line, not below.</li>
      <li>If all points were on the line, all residuals would be zero.</li>
    </ul>'
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
      ' . $explain . '
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Remember:</b> residual = observed &minus; predicted. The sign tells direction; the size tells magnitude.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$stem</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
