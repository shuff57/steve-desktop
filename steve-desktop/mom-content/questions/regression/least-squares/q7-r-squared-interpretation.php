// === NAME - DESCRIPTION: R-squared Interpretation - Pick best interpretation in context ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

$ctx_options = array(
  array("square footage", "home sale prices"),
  array("study hours", "exam scores"),
  array("family income", "gift aid amounts"),
  array("age of a used car", "its selling price"),
  array("height", "arm span measurements")
)
$ctx = $ctx_options[rand(0, count($ctx_options) - 1)]
$xname = $ctx[0]
$yname = $ctx[1]

$r2_pct = rand(35, 85)
$r2 = $r2_pct / 100

$questions = array(
  "About $r2_pct% of the variability in $yname is explained by the linear model using $xname.",
  "About $r2_pct% of the data points fall exactly on the regression line.",
  "The regression model predicts $yname correctly $r2_pct% of the time.",
  "For each one-unit increase in $xname, $yname changes by $r2_pct%."
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
      <p>`R^2` measures the <b>proportion of variability in the response variable that is explained by the linear model</b>.</p>
      <p>With `R^2 = ' . $r2 . '`, we say about <b>' . $r2_pct . '% of the variability in ' . $yname . '</b> is accounted for by the linear relationship with ' . $xname . '. The remaining ' . (100 - $r2_pct) . '% is due to other factors or natural variation.</p>
      <p><b>Common wrong interpretations to avoid:</b></p>
      <ul>
        <li>`R^2` is not the proportion of data points that lie on the line.</li>
        <li>`R^2` is not the probability that a prediction is correct.</li>
        <li>`R^2` is not the slope, and has nothing to do with "per unit change".</li>
      </ul>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key phrase:</b> "`R^2`% of the variability in `y` is explained by the model using `x`."
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A least squares regression line is used to predict <b>$yname</b> from <b>$xname</b>. The model has `R^2 = $r2`.</p>
    <p style="margin:0;">Which statement is the best interpretation of `R^2` in this context?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
