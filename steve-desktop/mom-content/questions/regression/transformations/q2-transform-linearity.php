// === NAME - DESCRIPTION: Log-Log Linearity - Identify a power relationship and read off the exponent ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number")

// Scenario randomization
$ctx_x = array("body length",        "side length",        "ad spend",        "city radius",          "tree diameter")
$ctx_y = array("metabolic rate",     "volume",             "weekly sales",    "population density",   "tree biomass")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xname = $picked_ctx[0]
$yname = $picked_ctx[1]

// Randomize the slope of the log(y) vs log(x) line. This IS the exponent b in y = a * x^b.
$bs = array(0.5, 1.5, 2, 2.5, 3, -0.5, -1.5)
$b  = $bs[rand(0, count($bs) - 1)]

// Part a: what is the original relationship between y and x?
$choices[0] = array(
  "A power relationship: `y = a cdot x^b`",
  "An exponential relationship: `y = a cdot b^x`",
  "A quadratic relationship: `y = a + bx + cx^2`",
  "A linear relationship: `y = a + b cdot x`"
)
$noshuffle[0] = "all"
$answer[0] = 0

// Part b: exponent in y = a * x^b
$answer[1] = $b
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
      <p><b>(a) Why a power relationship.</b> Start from `y = a cdot x^b` and take the log of both sides:</p>
      <p style="text-align:center;">`ln(y) = ln(a) + b cdot ln(x)`</p>
      <p>This is a straight line in `ln(y)` versus `ln(x)` with slope `b` and intercept `ln(a)`. So if a log-log plot is straight, the original relationship has the form `y = a cdot x^b`. (An exponential relationship straightens under `ln(y)` versus `x`, not log-log.)</p>
      <p><b>(b) Reading off the exponent.</b> The slope of the log-log line equals the exponent: `b = ' . $b . '`. So the original power model is `y = a cdot x^{' . $b . '}` for some positive constant `a`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Mnemonic: log-log line straight `=>` power model; semi-log line straight (only `ln(y)`) `=>` exponential model.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A scatterplot of <b>{ $yname }</b> vs. <b>{ $xname }</b> shows a clear curve: the cloud of points bends, so a straight line is not a good fit on the original scale.</p>
    <p style="margin:0 0 10px 0;">When the analyst plots `ln(` { $yname } `)` against `ln(` { $xname } `)`, the points fall on a straight line with slope <b>{ $b }</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which form best describes the original relationship between `y` and `x`? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Read off the value of the exponent `b` in `y = a cdot x^b`.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
