// === NAME - DESCRIPTION: Conditions for Least Squares Regression - Identify required assumptions ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

$questions = array(
  "Linearity, approximately constant variance of residuals, independence of observations, and approximately normal residuals.",
  "The explanatory variable must be normally distributed, and both variables must have the same mean.",
  "The sample size must be at least 100, and the correlation must be greater than 0.9.",
  "The slope must be positive, and the intercept must be non-zero."
)
$answer = 0

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
      <p>Least squares regression relies on four conditions, sometimes remembered as <b>L.I.N.E.</b>:</p>
      <ul>
        <li><b>Linearity</b>: the scatterplot and residual plot look roughly linear (no curved pattern).</li>
        <li><b>Independence</b>: observations are independent of one another (no clusters, no time-dependent trends).</li>
        <li><b>Normality</b>: residuals are approximately normally distributed.</li>
        <li><b>Equal variance</b>: the spread of residuals is roughly the same across all values of `x` (no fan/funnel shape).</li>
      </ul>
      <p>Neither the explanatory variable nor the response variable has to be normally distributed, and no rule requires a specific sample size, correlation strength, or sign of the slope.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Checking conditions:</b> plot the data, fit the line, and <b>look at the residual plot</b>: linearity, constant variance, and lack of patterns are easiest to judge there.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Which of the following best describes the conditions that should be checked before trusting a least squares regression model for inference and prediction?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
