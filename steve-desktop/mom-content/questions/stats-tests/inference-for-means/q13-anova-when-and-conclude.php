// === NAME - DESCRIPTION: ANOVA: when to apply (conceptual) + interpret conclusion from output ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("choices","choices")
$noshuffle[0] = "all"
$noshuffle[1] = "all"

// Part a: 4 scenarios: only one is ANOVA (comparing 3+ group means)
$ci_a = rand(0, 3)
$scenarios_a = array(
  "A researcher compares the mean GPA of students in four different majors: Business, Engineering, Arts, and Sciences.",
  "A scientist tests whether a new drug changes cholesterol levels by measuring each patient before and after treatment.",
  "A pollster checks whether the proportion of voters who support a ballot measure differs between males and females.",
  "A teacher tests whether a student's final grade is related to the number of hours studied, using a regression model."
)
$correct_a = 0  // scenario 0 is always ANOVA (compare 3+ means)
// Shuffle scenarios so correct is not always first
$order = array($ci_a, ($ci_a+1)%4, ($ci_a+2)%4, ($ci_a+3)%4)
$choices_a = array($scenarios_a[$order[0]], $scenarios_a[$order[1]], $scenarios_a[$order[2]], $scenarios_a[$order[3]])
// Find which position has the ANOVA scenario (scenario 0)
$answer[0] = 0
if ($order[1] == 0) {$answer[0] = 1}
if ($order[2] == 0) {$answer[0] = 2}
if ($order[3] == 0) {$answer[0] = 3}
$choices[0] = $choices_a

// Part b: interpret ANOVA output: 3 groups, given F and p-value
$ci_b = rand(0, 2)
$group_sets  = array(
  array("three teaching methods (lecture, flipped, online)", "student final exam scores"),
  array("four fertilizer treatments (none, low, medium, high)", "average crop yield (bushels/acre)"),
  array("three exercise frequencies (daily, weekly, rarely)", "resting heart rate (bpm)")
)
$F_vals  = array("8.43", "12.71", "5.96")
$pvals_b = array("0.0004", "< 0.001", "0.003")
$grps_b  = $group_sets[$ci_b][0]
$resp_b  = $group_sets[$ci_b][1]
$F_b     = $F_vals[$ci_b]
$pval_b  = $pvals_b[$ci_b]
$alpha_b = "0.05"

$choices[1] = array(
  "Reject H₀. At least one group mean for $resp_b is significantly different from the others.",
  "Fail to reject H₀. There is no evidence of a difference in $resp_b across groups.",
  "Accept H₀. All group means are proven equal.",
  "Cannot conclude: ANOVA requires equal group sizes."
)
$answer[1] = 0

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
      <p><b>Part a:</b> ANOVA is used to compare means across <b>three or more independent groups</b>. The scenario comparing GPAs across 4 majors fits this exactly. Paired measurements → paired t-test. Proportions → z-test. Regression → slope test.</p>
      <p><b>Part b:</b> F = ' . $F_b . ', p-value = ' . $pval_b . ' &lt; &alpha; = ' . $alpha_b . '. <b>Reject H₀.</b> ANOVA\'s H₀ is "all group means are equal." Rejecting means at least one group differs: not that all are different.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">a.</span> Which scenario below calls for a one-way ANOVA? $answerbox[0]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <p style="margin:0 0 8px 0;">Researchers compared $resp_b across $grps_b. ANOVA output: <b>F = $F_b</b>, p-value = <b>$pval_b</b>, &alpha; = $alpha_b.</p>
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">b.</span> Select the correct decision and conclusion. $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
