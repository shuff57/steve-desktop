// === NAME - DESCRIPTION: Identify the null and alternative hypotheses (`p`) and determine which type of test to use (e.g., right-tailed). Not Random. Scaffolded (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

$anstypes = array("choices","choices","number","choices","choices","number","choices")
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[3] = "select"
$displayformat[4] = "select"
$displayformat[6] = "select"
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[3] = "all"
$noshuffle[4] = "all"
$noshuffle[6] = "all"
$answerboxsize[2] = 5
$answerboxsize[5] = 5

$param_choices = array("`\hat{p}`","`s`","`n`","`N`","`X`","`bar(x)`","`p`","`mu`","`sigma`")
$relops = array("`>=`","`<`","`=`","`!=`","`>`","`<=`")

$questions[0] = $param_choices
$answer[0] = 6

$questions[1] = $relops
$answer[1] = 2

$answer[2] = 0.35

$questions[3] = $param_choices
$answer[3] = 6

$questions[4] = $relops
$answer[4] = 1

$answer[5] = 0.35

$questions[6] = array("Two-tailed", "Left-tailed", "Right-tailed")
$answer[6] = 1

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Past studies estimated that the percentage of smokers in a population is about `35%`. Given new smoking-cessation programs, you now believe the percentage of smokers has <b>reduced</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;"><b>Null hypothesis:</b></p>
    <p style="margin:0;">`H_0` :<span style="margin-left:8px;">$answerbox[0]</span><span style="margin-left:8px;">$answerbox[1]</span><span style="margin-left:8px;">$answerbox[2]</span></p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;"><b>Alternative hypothesis:</b></p>
    <p style="margin:0;">`H_a` :<span style="margin-left:8px;">$answerbox[3]</span><span style="margin-left:8px;">$answerbox[4]</span><span style="margin-left:8px;">$answerbox[5]</span></p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">What kind of tail test will this be?<span style="margin-left:8px;">$answerbox[6]</span></p>
  </div>
</div>
