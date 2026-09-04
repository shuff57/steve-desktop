// === NAME - DESCRIPTION: Paired t-test: compute test statistic from summary data, decide, conclude ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("number","choices","choices")
$noshuffle[1] = "all"
$noshuffle[2] = "all"

$ci = rand(0, 3)
$contexts = array(
  array("before and after a 6-week fitness program", "resting heart rate (bpm)", "the fitness program reduces resting heart rate", 35, 4.8, 2.1),
  array("before and after a tutoring intervention", "quiz score (points)", "the tutoring intervention improves quiz scores", 28, 5.2, 2.4),
  array("without and with noise-canceling headphones", "reading comprehension score", "headphones improve reading comprehension", 22, 3.6, 1.8),
  array("before and after a mindfulness program", "reported stress level (1-10)", "the mindfulness program reduces stress", 40, 1.9, 0.9)
)
$setting  = $contexts[$ci][0]
$measure  = $contexts[$ci][1]
$claim    = $contexts[$ci][2]
$n        = $contexts[$ci][3]
$dbar     = $contexts[$ci][4]
$sd       = $contexts[$ci][5]
$alpha    = "0.05"

$t_stat   = round($dbar / ($sd / sqrt($n)), 3)
$df       = $n - 1
$pval_approx = round(1 - tcdf($t_stat, $df), 4)
$decision_idx = ($pval_approx < 0.05) ? 0 : 1
$decision_text = ($decision_idx == 0) ? "Reject H₀" : "Fail to reject H₀"
$concl_yes = "There IS sufficient evidence to support the claim that $claim."
$concl_no  = "There is NOT sufficient evidence to support the claim that $claim."
$concl_text = ($decision_idx == 0) ? $concl_yes : $concl_no

$answer[0] = $t_stat
$reltolerance[0] = 0.02
$answer[1] = $decision_idx
$answer[2] = $decision_idx

$choices[1] = array("Reject H₀: the data provide sufficient evidence.", "Fail to reject H₀: the data do not provide sufficient evidence.")
$choices[2] = array($concl_yes, $concl_no)

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
      <p><b>Part a: Test statistic:</b> `t = bar{d}/(s_d/sqrt(n)) = ' . $dbar . '/(' . $sd . '/sqrt(' . $n . ')) = ' . $t_stat . '`, df = ' . $df . '</p>
      <p><b>Part b: Decision:</b> p-value &asymp; ' . $pval_approx . ' ' . ($pval_approx < 0.05 ? '&lt;' : '&ge;') . ' &alpha; = ' . $alpha . '. <b>' . $decision_text . '</b>.</p>
      <p><b>Part c: Conclusion:</b> ' . $concl_text . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <p style="margin:0 0 8px 0;">Researchers measured <b>$measure</b> for <b>$n participants</b> $setting. The differences (before − after) had <b>mean `bar{d} = $dbar`</b> and <b>standard deviation `s_d = $sd`</b>.</p>
    <p style="margin:0;">H₀: `mu_d = 0` &nbsp;&nbsp; H_a: `mu_d > 0` &nbsp;&nbsp; (&alpha; = $alpha)</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">a.</span> Compute the paired t test statistic. (Round to 3 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">b.</span> What is your decision at &alpha; = $alpha? $answerbox[1]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">c.</span> Select the correct conclusion in context. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
