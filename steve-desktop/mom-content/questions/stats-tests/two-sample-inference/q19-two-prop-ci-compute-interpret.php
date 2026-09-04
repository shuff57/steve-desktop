// === NAME - DESCRIPTION: Two-proportion CI: compute bounds, does it contain 0, and conclusion ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("number","number","choices","choices")
$noshuffle[2] = "all"
$noshuffle[3] = "all"

$ci = rand(0, 3)
$contexts = array(
  array("students who prefer in-person class", "freshmen", "sophomores", 148, 200, 120, 180),
  array("adults who exercise regularly", "urban residents", "rural residents", 216, 300, 154, 250),
  array("employees who report high job satisfaction", "remote workers", "in-office workers", 170, 250, 126, 200),
  array("patients who show improvement", "treatment group", "control group", 168, 240, 105, 180)
)
$param  = $contexts[$ci][0]
$grp1   = $contexts[$ci][1]
$grp2   = $contexts[$ci][2]
$x1     = $contexts[$ci][3]
$n1     = $contexts[$ci][4]
$x2     = $contexts[$ci][5]
$n2     = $contexts[$ci][6]

$p1     = round($x1/$n1, 4)
$p2     = round($x2/$n2, 4)
$diff   = round($p1 - $p2, 4)
$se     = sqrt($p1*(1-$p1)/$n1 + $p2*(1-$p2)/$n2)
$z      = 1.96
$me     = $z * $se
$low    = round($diff - $me, 4)
$high   = round($diff + $me, 4)

$answer[0] = $low
$answer[1] = $high
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$contains_zero = ($low <= 0 && $high >= 0) ? 1 : 0
$choices[2] = array("Yes: 0 is inside the interval", "No: 0 is outside the interval")
$answer[2] = $contains_zero ? 0 : 1

if ($contains_zero) {
  $concl = "Fail to reject H₀: there is not sufficient evidence of a difference in the proportion of $param between $grp1 and $grp2."
  $answer[3] = 1
} else {
  $concl = "Reject H₀: there is sufficient evidence that the proportion of $param differs between $grp1 and $grp2."
  $answer[3] = 0
}
$choices[3] = array(
  "Reject H₀: there is evidence of a difference in the proportion of $param.",
  "Fail to reject H₀: there is not sufficient evidence of a difference."
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
      <p>`hat{p}_1 = ' . $p1 . '`, `hat{p}_2 = ' . $p2 . '`, difference `= ' . $diff . '`</p>
      <p>SE `= sqrt(' . $p1 . ' cdot ' . round(1-$p1,4) . '/' . $n1 . ' + ' . $p2 . ' cdot ' . round(1-$p2,4) . '/' . $n2 . ') = ' . round($se,4) . '`</p>
      <p>ME `= 1.96 cdot ' . round($se,4) . ' = ' . round($me,4) . '`</p>
      <p>95% CI: `(' . $low . ', ' . $high . ')`</p>
      <p>Since the CI ' . ($contains_zero ? "contains" : "does not contain") . ' 0, we <b>' . ($contains_zero ? "fail to reject H₀" : "reject H₀") . '</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <p style="margin:0;">A survey compared the proportion of <b>$param</b> between <b>$grp1</b> ($x1 out of $n1) and <b>$grp2</b> ($x2 out of $n2). Construct a <b>95% CI for `p_1 - p_2`</b> using `z^* = 1.96`.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">a–b.</span> Lower: $answerbox[0] &nbsp;&nbsp; Upper: $answerbox[1]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">c.</span> Does your confidence interval contain 0? $answerbox[2]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">d.</span> Based on your interval, what do you conclude about H₀: `p_1 = p_2`? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
