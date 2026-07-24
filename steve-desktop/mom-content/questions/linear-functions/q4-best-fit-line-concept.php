// === NAME - DESCRIPTION: Best-Fit Line Concept - Compute yhat, residual, and interpret sign for a candidate regression line ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "number", "choices")
$displayformat[2] = "select"
$noshuffle[2] = "all"

/* ---------- 1. Randomization ---------- */
// Each scenario: [m, b, x_pred, y_pred_check, x_resid, y_actual, residual]
// Constructed so yhat and residual are clean integers.
// yhat = m * x_pred + b (integer by construction)
// residual = y_actual - yhat (integer by construction)
// residual_sign: 0 = positive (line below point), 1 = negative (line above point)

// Scenario 1: m=2, b=1; x_pred=3 -> yhat=7; resid point x=5, y_actual=9, yhat=11, resid=-2 (neg)
// Scenario 2: m=3, b=-1; x_pred=4 -> yhat=11; resid point x=2, y_actual=8, yhat=5, resid=3 (pos)
// Scenario 3: m=1, b=4; x_pred=5 -> yhat=9; resid point x=7, y_actual=12, yhat=11, resid=1 (pos)
// Scenario 4: m=2, b=3; x_pred=6 -> yhat=15; resid point x=4, y_actual=10, yhat=11, resid=-1 (neg)
// Scenario 5: m=4, b=-2; x_pred=3 -> yhat=10; resid point x=5, y_actual=17, yhat=18, resid=-1 (neg)
// Scenario 6: m=1, b=2; x_pred=8 -> yhat=10; resid point x=3, y_actual=7, yhat=5, resid=2 (pos)

// Six scenarios with fully precomputed values in if-blocks.
// Avoids all variable-index array lookups ($arr[$var]) which fail in IMathAS.
// Default: Scenario 0: m=2,b=1, xpred=3->yhat=7, resid at (5,9): resid=-2, rsign=1
$m = 2
$b = 1
$xpred = 3
$yhat = 7
$xresid = 5
$yactual = 9
$resid = -2
$rsign = 1
$p1x = 1
$p1y = 4
$p2x = 3
$p2y = 7
$p3x = 5
$p3y = 9
$p4x = 7
$p4y = 16
$si = rand(1, 5)
if ($si == 1) {
  $m = 3
  $b = -1
  $xpred = 4
  $yhat = 11
  $xresid = 2
  $yactual = 8
  $resid = 3
  $rsign = 0
  $p1x = 1
  $p1y = 2
  $p2x = 2
  $p2y = 8
  $p3x = 4
  $p3y = 11
  $p4x = 6
  $p4y = 19
}
if ($si == 2) {
  $m = 1
  $b = 4
  $xpred = 5
  $yhat = 9
  $xresid = 7
  $yactual = 12
  $resid = 1
  $rsign = 0
  $p1x = 2
  $p1y = 7
  $p2x = 5
  $p2y = 12
  $p3x = 7
  $p3y = 12
  $p4x = 9
  $p4y = 14
}
if ($si == 3) {
  $m = 2
  $b = 3
  $xpred = 6
  $yhat = 15
  $xresid = 4
  $yactual = 10
  $resid = -1
  $rsign = 1
  $p1x = 1
  $p1y = 5
  $p2x = 3
  $p2y = 9
  $p3x = 4
  $p3y = 10
  $p4x = 6
  $p4y = 15
}
if ($si == 4) {
  $m = 4
  $b = -2
  $xpred = 3
  $yhat = 10
  $xresid = 5
  $yactual = 17
  $resid = -1
  $rsign = 1
  $p1x = 1
  $p1y = 3
  $p2x = 3
  $p2y = 10
  $p3x = 5
  $p3y = 17
  $p4x = 6
  $p4y = 22
}
if ($si == 5) {
  $m = 1
  $b = 2
  $xpred = 8
  $yhat = 10
  $xresid = 3
  $yactual = 7
  $resid = 2
  $rsign = 0
  $p1x = 1
  $p1y = 4
  $p2x = 3
  $p2y = 7
  $p3x = 6
  $p3y = 9
  $p4x = 8
  $p4y = 11
}

$answer[0] = $yhat
$answer[1] = $resid
$answer[2] = $rsign

$abstolerance[0] = 0.01
$abstolerance[1] = 0.01

// Display the line equation
$b_disp = " + " . $b
if ($b < 0) {
  $b_disp = " - " . abs($b)
}

$line_eq = "hat{y} = " . $m . "x" . $b_disp

// choices for part c
$choices[2] = array("Positive (the line is below the point)", "Negative (the line is above the point)")

// Residual computation display for solution
$yhat_at_resid_pt = $m * $xresid + $b
$resid_check = $yactual - $yhat_at_resid_pt

// Precompute display strings for ternary-free solution
$mx_a = $m * $xpred
$mx_r = $m * $xresid
$sign_interp = "negative. The actual point is below the line (line overestimates)."
if ($resid > 0) {
  $sign_interp = "positive. The actual point is above the line (line underestimates)."
}
$sign_word = "Negative"
if ($resid > 0) { $sign_word = "Positive" }

/* ---------- 2. Solution guide ---------- */
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
      <p><b>Part a: Compute hat{y} at x = ' . $xpred . '</b></p>
      <p style="margin-left:1.5em;">hat{y} = ' . $m . ' &times; ' . $xpred . ' ' . $b_disp . ' = ' . $mx_a . ' ' . $b_disp . ' = ' . $yhat . '</p>
      <p><b>Part b: Residual at (' . $xresid . ', ' . $yactual . ')</b></p>
      <p style="margin-left:1.5em;">hat{y} at x = ' . $xresid . ': ' . $m . ' &times; ' . $xresid . ' ' . $b_disp . ' = ' . $mx_r . ' ' . $b_disp . ' = ' . $yhat_at_resid_pt . '</p>
      <p style="margin-left:1.5em;">Residual = y - hat{y} = ' . $yactual . ' - ' . $yhat_at_resid_pt . ' = ' . $resid . '</p>
      <p><b>Part c: Sign interpretation</b></p>
      <p style="margin-left:1.5em;">The residual is <b>' . $resid . '</b>, which is ' . $sign_interp . '</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) hat{y} = ' . $yhat . ' &nbsp;&bull;&nbsp; (b) residual = ' . $resid . ' &nbsp;&bull;&nbsp; (c) ' . $sign_word . '
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 8px 0;">A researcher fits a candidate best-fit line to the four data points below.</p>
<div style="border-radius:12px;overflow:hidden;box-shadow:0 4px 6px -1px rgba(0,0,0,0.08),0 2px 4px -2px rgba(0,0,0,0.05);border:1px solid #e5e7eb;display:inline-block;">
<table style="border-collapse:collapse; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:15px;">
<tr style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3;">
<th style="padding:8px 18px;">x</th><th style="padding:8px 18px; border-left:1px solid #e5e7eb;">y</th>
</tr>
<tr><td style="padding:7px 18px; text-align:center;">$p1x</td><td style="padding:7px 18px; text-align:center; border-left:1px solid #e5e7eb;">$p1y</td></tr>
<tr style="background:#f7f9fa;"><td style="padding:7px 18px; text-align:center;">$p2x</td><td style="padding:7px 18px; text-align:center; border-left:1px solid #e5e7eb;">$p2y</td></tr>
<tr><td style="padding:7px 18px; text-align:center;">$p3x</td><td style="padding:7px 18px; text-align:center; border-left:1px solid #e5e7eb;">$p3y</td></tr>
<tr style="background:#f7f9fa;"><td style="padding:7px 18px; text-align:center;">$p4x</td><td style="padding:7px 18px; text-align:center; border-left:1px solid #e5e7eb;">$p4y</td></tr>
</table>
</div>
<p style="margin:10px 0 0 0;">The candidate line is: <b>`$line_eq`</b></p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Use the candidate line to compute the <b>predicted value</b> `hat{y}` when `x = $xpred`. <span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the <b>residual</b> `y - hat{y}` for the data point `($xresid, $yactual)`. <span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is the residual from part (b) positive or negative? What does that tell you about the line? <span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
