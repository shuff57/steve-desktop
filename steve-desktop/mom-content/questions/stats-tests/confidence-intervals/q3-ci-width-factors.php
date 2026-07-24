// === NAME - DESCRIPTION: Widest/Narrowest Confidence Interval / Given confidence and sample size - PG ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL (paste into Common Control) ===

$ciSize = array("widest","narrowest")
$wideNarrow = randfrom($ciSize)

$questions = array(
  "A sample size of `n = 30` with `99%` confidence.",
  "A sample size of `n = 100` with `95%` confidence.",
  "A sample size of `n = 30` with `95%` confidence.",
  "A sample size of `n = 100` with `99%` confidence."
)

$answer = 0
$answer = 1 if ($wideNarrow == "narrowest")

$noshuffle = "all"

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Which of the following would result in the <b>$wideNarrow</b> confidence interval for a population proportion?</p>
    <div style="margin-top:12px;">$answerbox</div>
  </div>
</div>
