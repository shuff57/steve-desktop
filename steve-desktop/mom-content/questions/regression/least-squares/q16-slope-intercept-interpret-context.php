// === NAME - DESCRIPTION: Interpret slope and intercept in context (TOP-MISS drill for HW 8.2 q6) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")

// Three scenarios. Each gives a regression equation in context, x-range, and asks for
//   (a) correct slope interpretation
//   (b) correct intercept interpretation
//   (c) whether the intercept is a meaningful estimate or extrapolation outside observed x

// Scenario 0: Plant growth. x = days since seeded, y = height (cm). yhat = 2.3 + 0.85 x.
//   x observed range: 7 to 60 days. Intercept at x=0 is OUTSIDE the observed range (and physical).
// Scenario 1: Used car. x = age (years), y = price ($1000). yhat = 21.4 - 1.6 x.
//   x range: 1 to 12 years. Intercept at x=0 (new car) is OUTSIDE observed but plausible to state.
// Scenario 2: Study time. x = hours studied per week, y = test score (0-100). yhat = 58 + 3.2 x.
//   x range: 2 to 20 hours. Intercept at x=0 is OUTSIDE observed range.

$ctxs = array(
  array("intro" => "A biologist records seedling height (cm) over time. Data ranges from 7 to 60 days after seeding.", "yvar" => "predicted height (cm)", "xvar" => "day since seeded", "intercept" => 2.3, "slope" => 0.85, "yhat" => "hat(y) = 2.3 + 0.85 x", "xrange_low" => 7, "xrange_high" => 60, "xrange_text" => "7 to 60 days", "slope_unit" => "centimeters", "intercept_unit" => "centimeters", "intercept_inside" => false),
  array("intro" => "A used-car dealer fits a regression of price ($1000) on vehicle age (years). Data ranges from 1 to 12 years old.", "yvar" => "predicted price (thousands of dollars)", "xvar" => "year of age", "intercept" => 21.4, "slope" => -1.6, "yhat" => "hat(y) = 21.4 - 1.6 x", "xrange_low" => 1, "xrange_high" => 12, "xrange_text" => "1 to 12 years", "slope_unit" => "thousands of dollars", "intercept_unit" => "thousands of dollars", "intercept_inside" => false),
  array("intro" => "A teacher fits a regression of test score on weekly study hours. Data ranges from 2 to 20 hours per week.", "yvar" => "predicted test score", "xvar" => "additional hour of study per week", "intercept" => 58, "slope" => 3.2, "yhat" => "hat(y) = 58 + 3.2 x", "xrange_low" => 2, "xrange_high" => 20, "xrange_text" => "2 to 20 hours", "slope_unit" => "points", "intercept_unit" => "points", "intercept_inside" => false)
)

$picked = jointrandfrom($ctxs)
$s = $picked[0]

$intro      = $s["intro"]
$yvar       = $s["yvar"]
$xvar       = $s["xvar"]
$intercept  = $s["intercept"]
$slope      = $s["slope"]
$yhat       = $s["yhat"]
$xlo        = $s["xrange_low"]
$xhi        = $s["xrange_high"]
$xrange     = $s["xrange_text"]
$slope_unit = $s["slope_unit"]
$icpt_unit  = $s["intercept_unit"]

// Slope direction word
$slope_dir = "increases"
$slope_mag = $slope
if ($slope < 0) { $slope_dir = "decreases"; $slope_mag = abs($slope) }

// Part a: slope interpretation
$choices[0] = array(
  "For each additional " . $xvar . ", the " . $yvar . " " . $slope_dir . " by about " . $slope_mag . " " . $slope_unit . ".",
  "When " . $xvar . " equals zero, the " . $yvar . " is about " . $slope_mag . " " . $slope_unit . ".",
  "There is a " . $slope_mag . "% chance the " . $yvar . " will change.",
  "The " . $yvar . " causes " . $xvar . " to change by " . $slope_mag . " units."
)
$answer[0] = 0
$noshuffle[0] = "all"

// Part b: intercept interpretation
$choices[1] = array(
  "When " . $xvar . " equals zero, the model predicts a " . $yvar . " of about " . $intercept . " " . $icpt_unit . ".",
  "For each additional " . $xvar . ", the " . $yvar . " increases by " . $intercept . " " . $icpt_unit . ".",
  "There is a " . $intercept . "% probability the model is correct.",
  "The intercept is the average value of x in the data."
)
$answer[1] = 0
$noshuffle[1] = "all"

// Part c: meaningful vs extrapolation
// In all three scenarios, x=0 is outside the observed range, so the intercept is extrapolation.
$choices[2] = array(
  "The intercept is a meaningful estimate because the regression equation is always valid for any x.",
  "The intercept is an extrapolation because `x = 0` falls outside the observed range (" . $xrange . "), so we should not trust the predicted value at `x = 0`.",
  "The intercept is meaningless because intercepts in regression never have interpretation.",
  "The intercept is exact because least squares minimizes the residual at `x = 0`."
)
$answer[2] = 1
$noshuffle[2] = "all"

$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"

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
      <p><b>Slope.</b> The slope is the rate of change in the predicted response per one-unit increase in the explanatory variable. Here slope `= ' . $slope . '`, so for each additional ' . $xvar . ', the ' . $yvar . ' ' . $slope_dir . ' by ' . $slope_mag . ' ' . $slope_unit . '.</p>
      <p><b>Intercept.</b> The intercept is the predicted response when the explanatory variable equals zero. Here intercept `= ' . $intercept . '`, so the model predicts a ' . $yvar . ' of ' . $intercept . ' ' . $icpt_unit . ' when ' . $xvar . ' is zero.</p>
      <p><b>Is the intercept meaningful?</b> The data only cover x from ' . $xlo . ' to ' . $xhi . '. Since `x = 0` is outside this range, the intercept is an <b>extrapolation</b>. The number is correct algebraically, but we should not interpret it as a trustworthy prediction without data near `x = 0`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff8e1; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Common trap:</b> Always check whether `x = 0` is inside the observed range before interpreting the intercept as a real-world value.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===
<style>select{max-width:100%;width:100%;box-sizing:border-box;display:block;margin:4px 0;}</style>

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$intro The fitted regression line is:</p>
    <p style="margin:0 0 8px 0; text-align:center;">`$yhat`</p>
    <p style="margin:0;">Use this equation to answer the questions below.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which is the best interpretation of the <b>slope</b>?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which is the best interpretation of the <b>y-intercept</b>?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is the intercept a meaningful prediction in this context?
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
