// === NAME - DESCRIPTION: Indicator Variable Interpretation - Slope/intercept with 0-1 predictor ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "number")

$ctx_options = array(
  array("selling price of a Mario Kart game (dollars)", "used", "new", "used games", "new games"),
  array("monthly electricity bill (dollars)", "without solar panels", "with solar panels", "homes without solar", "homes with solar"),
  array("response time to a survey (minutes)", "email invitation", "text message invitation", "email recipients", "text recipients"),
  array("weekly hours studied", "non-athlete", "student athlete", "non-athletes", "student athletes")
)
$ctx = $ctx_options[rand(0, count($ctx_options) - 1)]
$yname = $ctx[0]
$grp0 = $ctx[1]
$grp1 = $ctx[2]
$grp0_plural = $ctx[3]
$grp1_plural = $ctx[4]

$b0 = rand(20, 80)
$b1 = rand(-15, 25)
if ($b1 == 0) { $b1 = 8 }

$mean0 = $b0
$mean1 = $b0 + $b1

$questions[0] = array(
  "The mean $yname for $grp0_plural (the x = 0 group).",
  "The mean $yname for $grp1_plural (the x = 1 group).",
  "The overall average $yname across both groups.",
  "The difference between the two group means."
)
$answer[0] = 0

$questions[1] = array(
  "The difference in mean $yname between $grp1_plural and $grp0_plural.",
  "The average $yname for $grp0_plural.",
  "The proportion of observations that are $grp1_plural.",
  "The overall average $yname across both groups."
)
$answer[1] = 0

$answer[2] = $mean1
$reltolerance[2] = 0.01

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
      <p>When the predictor `x` is an indicator variable (0 or 1), the regression line connects the two group means.</p>
      <p><b>Part a — Intercept:</b> plug `x = 0` into `hat{y} = ' . $b0 . ' + ' . $b1 . 'x`:</p>
      <p>`hat{y}(0) = ' . $b0 . '`</p>
      <p>So the intercept is the mean $yname for $grp0_plural (the `x = 0` group).</p>
      <p><b>Part b — Slope:</b> the slope is the change in predicted `y` for a one-unit increase in `x`. Going from `x = 0` to `x = 1` means switching from $grp0_plural to $grp1_plural, so:</p>
      <p>`b_1 = "mean for group 1" - "mean for group 0" = ' . $b1 . '`</p>
      <p><b>Part c — Predicted value at `x = 1`:</b></p>
      <p>`hat{y}(1) = ' . $b0 . ' + ' . $b1 . '(1) = ' . $mean1 . '`</p>
      <p>This equals the mean $yname for $grp1_plural.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> with an indicator predictor, intercept = mean of the reference group (`x = 0`); slope = difference between group means; fitted values at `x = 0` and `x = 1` are the two group means.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A researcher fits a least squares regression to predict <b>$yname</b> using an indicator variable `x`, where `x = 0` for $grp0_plural and `x = 1` for $grp1_plural. The fitted equation is:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`hat{y} = $b0 + {$b1}x`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What does the intercept ($b0) represent? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the slope ($b1) represent? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the predicted $yname for $grp1_plural (`x = 1`)? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
