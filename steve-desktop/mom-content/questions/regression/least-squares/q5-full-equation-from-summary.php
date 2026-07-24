// === NAME - DESCRIPTION: Full Equation from Summary Statistics - Compute slope, intercept, make prediction ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

$ctx_options = array(
  array("family income (thousands of dollars)", "gift aid (thousands of dollars)", "family income", "gift aid"),
  array("shoe size", "height (inches)", "shoe size", "height"),
  array("hours of exercise per week", "resting heart rate (bpm)", "hours of exercise", "heart rate"),
  array("midterm score (points)", "final exam score (points)", "midterm score", "final exam score")
)
$ctx = $ctx_options[rand(0, count($ctx_options) - 1)]
$xname = $ctx[0]
$yname = $ctx[1]
$xshort = $ctx[2]
$yshort = $ctx[3]

$xbar = rand(400, 1200) / 10
$ybar = rand(300, 900) / 10
$sx = rand(50, 200) / 10
$sy = rand(40, 150) / 10
$r = rand(-80, 80) / 100
if (abs($r) < 0.2) { $r = 0.5 }

$b1 = round($r * $sy / $sx, 4)
$b0 = round($ybar - $b1 * $xbar, 3)

// Prediction at x = xbar + 1 std dev for a clean follow-up
$xpred = round($xbar + $sx, 1)
$ypred = round($b0 + $b1 * $xpred, 2)

$answer[0] = $b1
$reltolerance[0] = 0.02
$answer[1] = $b0
$reltolerance[1] = 0.02
$answer[2] = $ypred
$reltolerance[2] = 0.02

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
      <p><b>Part a — Slope:</b> Use `b_1 = r cdot s_y/s_x`.</p>
      <p>`b_1 = (' . $r . ')(' . $sy . ' -: ' . $sx . ') = ' . $b1 . '`</p>
      <p><b>Part b — Intercept:</b> Use `b_0 = bar{y} - b_1 cdot bar{x}`.</p>
      <p>`b_0 = ' . $ybar . ' - (' . $b1 . ')(' . $xbar . ') = ' . $b0 . '`</p>
      <p>The full regression equation is:</p>
      <p style="text-align:center;">`hat{y} = ' . $b0 . ' + ' . $b1 . 'x`</p>
      <p><b>Part c — Prediction at x = ' . $xpred . ':</b></p>
      <p>`hat{y} = ' . $b0 . ' + (' . $b1 . ')(' . $xpred . ') = ' . $ypred . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Workflow:</b> summary stats &rarr; slope `b_1 = r(s_y/s_x)` &rarr; intercept `b_0 = bar{y} - b_1 bar{x}` &rarr; equation &rarr; prediction.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A least squares regression line predicts <b>$yname</b> from <b>$xname</b>. The summary statistics are:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`bar{x} = $xbar, \ s_x = $sx, \ bar{y} = $ybar, \ s_y = $sy, \ r = $r`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the slope `b_1`. (Round to 4 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the intercept `b_0`. (Round to 3 decimal places.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Use your regression equation to predict $yshort when $xshort `= $xpred`. (Round to 2 decimal places.) $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
