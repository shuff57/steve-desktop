// === NAME - DESCRIPTION: Confidence interval interpretation (p) ===
// === SET QUESTION TYPE TO: multans ===

// === COMMON CONTROL (paste into Common Control) ===

$p = randfrom("90,95,99,98")
$low = rrand(.1,.15,.01)
$high = rrand(.2,.35,.01)
$n = rand(75,90)

$questions = array(
  "With `$p%` confidence, the proportion of all students who take notes is between `$low` and `$high`.",
  "There is a `$p%` chance that the proportion of notetakers in a sample of `n = $n` students will be between `$low` and `$high`.",
  "There is a `$p%` chance that the proportion of the population is between `$low` and `$high`.",
  "With `$p%` confidence, a randomly selected student takes notes in a proportion of their classes that is between `$low` and `$high`.",
  "The proportion of all students who take notes is between `$low` and `$high`, `$p%` of the time."
)

$answers = "0"

$noshuffle = "all"

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A student was asked to find a `$p%` confidence interval for the proportion of students who take notes using data from a random sample of size `n = $n`.</p>
    <p style="margin:0;">Which of the following is a correct interpretation of the interval `$low < p < $high`? <b>Check all that are correct.</b></p>
  </div>
</div>
