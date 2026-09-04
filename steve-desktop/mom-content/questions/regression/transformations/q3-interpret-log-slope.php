// === NAME - DESCRIPTION: Interpret a Log-Linear Slope - Each unit of x multiplies y by e^slope; percent change ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number")

// Scenario randomization
$ctx_y = array("home price (dollars)",  "annual income (dollars)", "company revenue (dollars)", "annual salary (dollars)", "city population")
$ctx_x = array("square footage (per 100 sqft)", "years of education", "advertising spend (per million dollars)", "years of work experience", "decade since 1900")
$picked = jointrandfrom($ctx_y, $ctx_x)
$yname = $picked[0]
$xname = $picked[1]

// Randomize intercept and slope of the log-linear fit: log(y) = b0 + b1*x
$intercept = round(rand(100, 500) / 100, 2)   // 1.00 to 5.00
$slope     = round(rand(10, 60) / 100, 2)     // 0.10 to 0.60

$mult = round(2.718281828^$slope, 4)                  // e^slope, multiplicative factor per unit of x
$pct  = round((2.718281828^$slope - 1) * 100, 1)      // approximate percent increase per unit of x

// Part a: multiplicative factor per unit increase in x
$answer[0] = $mult
$reltolerance[0] = 0.01

// Part b: corresponding percent increase
$answer[1] = $pct
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
      <p><b>Setup.</b> The fitted model on the log scale is:</p>
      <p style="text-align:center;">`ln(y) = ' . $intercept . ' + ' . $slope . ' cdot x`</p>
      <p>Exponentiate both sides to get the original-scale model:</p>
      <p style="text-align:center;">`y = e^{' . $intercept . '} cdot e^{' . $slope . ' x} = e^{' . $intercept . '} cdot (e^{' . $slope . '})^x`</p>
      <p><b>(a) Multiplicative factor.</b> Each one-unit increase in `x` multiplies `y` by `e^{' . $slope . '} approx ' . $mult . '`.</p>
      <p><b>(b) Percent change.</b> A multiplier of ' . $mult . ' is the same as a percent change of `(' . $mult . ' - 1) cdot 100% = ' . $pct . '%` per unit of `x`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Quick rule: in `ln(y) = b_0 + b_1 x`, a one-unit increase in `x` multiplies `y` by `e^{b_1}`, which is approximately a `(e^{b_1} - 1) cdot 100%` change.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A study models <b>{ $yname }</b> against <b>{ $xname }</b>. Because { $yname } is right-skewed, the analyst regresses `ln(` { $yname } `)` on { $xname } and reports:</p>
    <p style="text-align:center; font-size:18px; margin:0;"><b>`ln(y) = { $intercept } + { $slope } cdot x`</b></p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> A one-unit increase in `x` <b>multiplies</b> the predicted value of `y` by what factor? Round to 4 decimal places.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Express that factor as a <b>percent change</b> in the predicted value of `y` per one-unit increase in `x`. Give a number (in percent), rounded to 1 decimal place.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
