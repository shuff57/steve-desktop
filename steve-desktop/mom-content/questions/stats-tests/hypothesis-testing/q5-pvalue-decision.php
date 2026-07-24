// === NAME - DESCRIPTION: P-Value is low/high, identify conclusion (Carey Smith) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

$anstypes = array("choices","choices")
$displayformat[0] = "select"
$displayformat[1] = "select"
$noshuffle[0] = "all"
$noshuffle[1] = "all"

$alpha = 0.05
$Pvalue = round((rand(1,9)/90),3)

$questions[0] = array("Reject `H_0`","Fail to reject `H_0`")
$reject = 1
$reject = 0 if ($Pvalue < $alpha)
$reject = 1 if ($Pvalue >= $alpha)
$answer[0] = $reject

$questions[1] = array(
  "There IS sufficient evidence to reject the null hypothesis and support the alternative.",
  "There is NOT sufficient evidence to reject the null hypothesis."
)
$answer[1] = $reject

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">For a hypothesis test at significance level `alpha = $alpha`, the computed p-value is `p = $Pvalue`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Should `H_0` be rejected? <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the correct conclusion?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
</div>
