// === NAME - DESCRIPTION: Extrapolation Warning - Judge whether a prediction is reliable ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

$ctx_options = array(
  array("family income (thousands of dollars)", "gift aid (thousands of dollars)", "family income", "gift aid"),
  array("study hours per week", "exam score (points)", "study time", "exam score"),
  array("age of a used car (years)", "selling price (thousands of dollars)", "car age", "selling price"),
  array("temperature (&deg;F)", "electricity usage (kWh)", "temperature", "electricity usage")
)
$ctx = $ctx_options[rand(0, count($ctx_options) - 1)]
$xname = $ctx[0]
$yname = $ctx[1]
$xshort = $ctx[2]
$yshort = $ctx[3]

$xmin = rand(10, 30)
$xmax = $xmin + rand(20, 50)

// Inside range for part a
$x_inside = rand($xmin + 3, $xmax - 3)
// Outside range for part b (well beyond)
$x_outside = $xmax + rand(15, 40)

$questions[0] = array(
  "Reliable — the prediction is for an `x` value inside the observed data range (interpolation).",
  "Unreliable — the prediction is extrapolation, because `x = $x_inside` is outside the observed range.",
  "Unreliable — because linear models can never be trusted for any prediction.",
  "Reliable — because all linear models work for any `x` value."
)
$answer[0] = 0

$questions[1] = array(
  "Unreliable — the prediction is extrapolation, because `x = $x_outside` is far beyond the observed data range.",
  "Reliable — extrapolation is always safe when the line was fit with least squares.",
  "Reliable — because the regression equation is a mathematical formula that works for any `x`.",
  "Unreliable — but only because `R^2` must be below 0.5."
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
      <p>The regression model was built using `x` values between `' . $xmin . '` and `' . $xmax . '`. Predictions inside that range (<b>interpolation</b>) are generally reliable; predictions outside that range (<b>extrapolation</b>) are not.</p>
      <p><b>Part a:</b> `x = ' . $x_inside . '` is <b>inside</b> the observed range, so the prediction is reliable interpolation.</p>
      <p><b>Part b:</b> `x = ' . $x_outside . '` is <b>far outside</b> the observed range. The linear pattern might not continue out there — the relationship could bend, flatten, or even reverse. This is extrapolation.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff4e5; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Caution:</b> Using a regression equation beyond the range of the data can produce nonsensical predictions (e.g., negative financial aid for a million-dollar family income).
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A least squares regression line predicts <b>$yname</b> from <b>$xname</b>. The observed `x` values in the dataset ranged from <b>$xmin</b> to <b>$xmax</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is a prediction at $xshort `= $x_inside` reliable? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Is a prediction at $xshort `= $x_outside` reliable? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
