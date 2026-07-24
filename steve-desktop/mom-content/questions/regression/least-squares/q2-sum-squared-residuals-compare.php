// === NAME - DESCRIPTION: Sum of Squared Residuals - Compute SSR for a candidate line ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices")

// Four data points with a clear linear trend plus some noise
// Choose a "true" line y = b0 + b1*x, then perturb
$b1 = randfrom("2,3,4")
$b0 = rand(1, 5)

$x1 = 1; $x2 = 2; $x3 = 3; $x4 = 4
$e1 = randfrom("-1,1"); $e2 = randfrom("-1,0,1"); $e3 = randfrom("-1,0,1"); $e4 = randfrom("-1,1")
$y1 = $b0 + $b1 * $x1 + $e1
$y2 = $b0 + $b1 * $x2 + $e2
$y3 = $b0 + $b1 * $x3 + $e3
$y4 = $b0 + $b1 * $x4 + $e4

// Candidate line: the "true" line (without noise)
$a_cand = $b0
$b_cand = $b1

$yhat1 = $a_cand + $b_cand * $x1
$yhat2 = $a_cand + $b_cand * $x2
$yhat3 = $a_cand + $b_cand * $x3
$yhat4 = $a_cand + $b_cand * $x4

$r1 = $y1 - $yhat1
$r2 = $y2 - $yhat2
$r3 = $y3 - $yhat3
$r4 = $y4 - $yhat4

$ssr = $r1*$r1 + $r2*$r2 + $r3*$r3 + $r4*$r4

$answer[0] = $ssr
$reltolerance[0] = 0.01

$questions[1] = array(
  "The line with the smaller sum of squared residuals.",
  "The line with the larger sum of squared residuals.",
  "The line that passes through the most data points.",
  "The line with the steepest slope."
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
  .sol-wrap table { border-collapse:collapse; margin:0.5em 0; }
  .sol-wrap th, .sol-wrap td { border:1px solid #cbd5e1; padding:4px 10px; text-align:center; user-select:text; }
  .sol-wrap th { background:#eef2ff; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Step 1:</b> For each point, compute the predicted value `hat{y} = ' . $a_cand . ' + ' . $b_cand . 'x` and the residual `y - hat{y}`.</p>
      <table>
        <tr><th>x</th><th>y</th><th>&hat;y</th><th>residual</th><th>residual&sup2;</th></tr>
        <tr><td>' . $x1 . '</td><td>' . $y1 . '</td><td>' . $yhat1 . '</td><td>' . $r1 . '</td><td>' . ($r1*$r1) . '</td></tr>
        <tr><td>' . $x2 . '</td><td>' . $y2 . '</td><td>' . $yhat2 . '</td><td>' . $r2 . '</td><td>' . ($r2*$r2) . '</td></tr>
        <tr><td>' . $x3 . '</td><td>' . $y3 . '</td><td>' . $yhat3 . '</td><td>' . $r3 . '</td><td>' . ($r3*$r3) . '</td></tr>
        <tr><td>' . $x4 . '</td><td>' . $y4 . '</td><td>' . $yhat4 . '</td><td>' . $r4 . '</td><td>' . ($r4*$r4) . '</td></tr>
      </table>
      <p><b>Step 2:</b> Add the squared residuals:</p>
      <p>`"SSR" = ' . ($r1*$r1) . ' + ' . ($r2*$r2) . ' + ' . ($r3*$r3) . ' + ' . ($r4*$r4) . ' = ' . $ssr . '`</p>
      <p><b>Part b:</b> The least squares line is the one with the <b>smallest</b> sum of squared residuals among all possible lines. A competing line with a larger SSR fits the data worse on this criterion.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> SSR is a direct numerical measure of how badly a line misses the data. Lower is better.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A small dataset has four observations:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`(${x1}, ${y1}), \ (${x2}, ${y2}), \ (${x3}, ${y3}), \ (${x4}, ${y4})`</p>
    <p style="margin:0;">Consider the candidate line `hat{y} = $a_cand + {$b_cand}x`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the sum of squared residuals (SSR) for this line. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> If a second candidate line had SSR = `' . ($ssr + 10) . '`, which line fits the data better by the least squares criterion? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
