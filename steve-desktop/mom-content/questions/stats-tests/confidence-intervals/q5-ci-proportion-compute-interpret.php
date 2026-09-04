// === NAME - DESCRIPTION: CI for one proportion: compute lower and upper bounds, then select correct interpretation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("number","number","choices")
$noshuffle[2] = "all"

$ci = rand(0, 4)
$contexts = array(
  array("city residents", "support the new park", 420, 600),
  array("students", "prefer online homework", 168, 240),
  array("adults", "exercise at least 3 days per week", 315, 500),
  array("customers", "are satisfied with the service", 273, 350),
  array("voters", "plan to vote in the upcoming election", 396, 550)
)
$grp    = $contexts[$ci][0]
$act    = $contexts[$ci][1]
$x      = $contexts[$ci][2]
$n      = $contexts[$ci][3]
$phat   = round($x / $n, 4)
$conf   = randfrom("90,95,98,99")
$z      = invnormalcdf($conf/100 + 0.5*(1-$conf/100))
$se     = sqrt($phat*(1-$phat)/$n)
$me     = $z * $se

$answer[0] = $phat - $me
$answer[1] = $phat + $me
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$answer[2] = 0   // index 0 = the correct CI interpretation

$low  = round($phat - $me, 4)
$high = round($phat + $me, 4)
$zr   = round($z, 3)
$mer  = round($me, 4)

$choices[2] = array(
  "We are $conf% confident the true proportion of $grp who $act is between $low and $high.",
  "There is a $conf% chance that $phat of $grp $act.",
  "$conf% of $grp in this sample $act.",
  "We are $conf% confident that $phat is a good estimate."
)

$solutionguide = '
<style>
  .sol-wrap details{width:100%;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;}
  .sol-wrap summary{cursor:pointer;display:block;width:100%;background:#f0f4ff;color:#21242c;padding:0.5em 0.75em;font-weight:700;font-size:15px;border-bottom:1px solid #e5e7eb;list-style:none;}
  .sol-wrap summary::-webkit-details-marker{display:none;}
  .sol-arrow-open{display:none;}
  .sol-wrap details[open] .sol-arrow-closed{display:none;}
  .sol-wrap details[open] .sol-arrow-open{display:inline;}
  .sol-body{padding:0.75em;background:#fafafa;}
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;margin:1em 0;">
  <details>
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><b>Parts a &amp; b: Compute the CI:</b></p>
      <p>`hat{p} = ' . $x . '/' . $n . ' = ' . $phat . '`</p>
      <p>Critical value `z^* = ' . $zr . '` for ' . $conf . '% confidence.</p>
      <p>ME `= z^* sqrt(hat{p}(1-hat{p})/n) = ' . $zr . ' cdot ' . round($se,4) . ' = ' . $mer . '`</p>
      <p>CI: `(' . $low . ', ' . $high . ')`</p>
      <p><b>Part c: Interpretation:</b> The correct template is: "We are [confidence]% confident the TRUE PROPORTION is between [lower] and [upper]." The CI is about the parameter, not the sample statistic.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <p style="margin:0;">A survey of <b>$n $grp</b> found that <b>$x</b> of them $act. Construct a <b>$conf% confidence interval</b> for the true proportion of $grp who $act.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">a–b.</span> Lower bound: $answerbox[0] &nbsp;&nbsp; Upper bound: $answerbox[1]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">c.</span> Select the correct interpretation of your confidence interval. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
