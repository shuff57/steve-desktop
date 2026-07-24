// === NAME - DESCRIPTION: Residual Plot Good Fit - Identify whether a residual plot shows a good linear fit ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

// Randomly select one of three residual plot scenarios
$scenario = rand(0, 2)

if ($scenario == 0) {
  // Good fit: random scatter
  $plot_desc = "The residual plot shows points scattered randomly above and below the horizontal line at zero, with no clear pattern. About half the residuals are positive and half are negative."
  $answer = 0
  $correct_explain = "The residuals show <b>random scatter</b> with no pattern. This is exactly what we want to see — it indicates the linear model is a good fit for the data."
} elseif ($scenario == 1) {
  // Bad fit: U-shaped curve
  $plot_desc = "The residual plot shows a clear curved (U-shaped) pattern: residuals are positive on the left, negative in the middle, and positive again on the right."
  $answer = 1
  $correct_explain = "The <b>U-shaped pattern</b> in the residuals indicates the relationship between the variables is <b>nonlinear</b>. A straight line is not the best model — the data follows a curve."
} else {
  // Bad fit: fan shape (increasing spread)
  $plot_desc = "The residual plot shows residuals that are tightly clustered near zero on the left side but spread out more and more as you move to the right, forming a fan or cone shape."
  $answer = 2
  $correct_explain = "The <b>fan shape</b> indicates <b>non-constant variability</b> (heteroscedasticity). The predictions are more accurate for small x values and less accurate for large x values. This violates the equal-variance condition for linear regression."
}

$questions = array(
  "Yes — the residuals show random scatter with no pattern, so the linear model is appropriate.",
  "No — the residuals show a curved pattern, suggesting the relationship is nonlinear.",
  "No — the residuals show a fan shape (increasing spread), suggesting non-constant variability."
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
      <p>' . $correct_explain . '</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>What to look for in residual plots:</b><br>
        &bull; <b>Random scatter</b> around zero &rarr; good linear fit<br>
        &bull; <b>Curved pattern</b> (U-shape or S-shape) &rarr; nonlinear relationship<br>
        &bull; <b>Fan/cone shape</b> &rarr; non-constant variability
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A researcher fits a linear model and examines the residual plot:</p>
    <p style="margin:0; padding:12px; background:#f8f9fa; border-radius:8px; border:1px solid #e5e7eb;">$plot_desc</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Based on the residual plot, is a linear model a <b>good fit</b> for this data?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
