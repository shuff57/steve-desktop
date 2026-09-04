// === NAME - DESCRIPTION: p-value interpretation: two scenarios, pick the correct statement ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("choices","choices")
$noshuffle[0] = "all"
$noshuffle[1] = "all"

// Scenario A: one-proportion HT with a given p-value
$ci_a = rand(0, 3)
$contexts_a = array(
  array("A school claims 70% of students graduate on time. A sample of 200 shows 126 graduating on time.", "0.055", "0.70", "graduation rate", "students"),
  array("A company claims 40% of users click their ad. A sample of 300 shows 108 clicks.", "0.031", "0.40", "click rate", "users"),
  array("A clinic claims 80% of patients recover fully. A sample of 150 shows 114 recovering.", "0.083", "0.80", "recovery rate", "patients"),
  array("A researcher claims 55% of adults prefer brand A. A sample of 250 shows 146 preferring brand A.", "0.042", "0.55", "preference rate", "adults")
)
$ctx_a   = $contexts_a[$ci_a][0]
$pval_a  = $contexts_a[$ci_a][1]
$p0_a    = $contexts_a[$ci_a][2]
$param_a = $contexts_a[$ci_a][3]
$grp_a   = $contexts_a[$ci_a][4]
$conf_wrong = round((1 - $pval_a) * 100, 1)

$choices[0] = array(
  "If H₀ is true (true $param_a = $p0_a), there is a $pval_a probability of observing a sample result this extreme or more extreme.",
  "There is a $pval_a probability that H₀ is true.",
  "The true $param_a is $pval_a away from $p0_a.",
  "We can be $conf_wrong% confident H₀ is true."
)
$answer[0] = 0

// Scenario B: two-tailed test, decision
$ci_b = rand(0, 2)
$pvals_b   = array("0.003", "0.072", "0.041")
$alphas_b  = array("0.05", "0.05", "0.01")
$params_b  = array("mean delivery time", "mean exam score", "mean daily steps")
$claims_b  = array("The true mean delivery time differs from the advertised 30 minutes.",
                   "The true mean exam score differs from the national average of 500.",
                   "The true mean daily steps differs from the recommended 10,000.")
$pval_b    = $pvals_b[$ci_b]
$alpha_b   = $alphas_b[$ci_b]
$claim_b   = $claims_b[$ci_b]

$decision_b_text = "Reject H₀"
$conclude_b = "There IS sufficient evidence that $claim_b"
if ($pval_b >= $alpha_b) {
  $decision_b_text = "Fail to reject H₀"
  $conclude_b = "There is NOT sufficient evidence that $claim_b"
}

$choices[1] = array(
  "$decision_b_text. $conclude_b.",
  "Reject H₀ only if the p-value is exactly 0.",
  "Fail to reject H₀ because the p-value is always too small.",
  "Accept H₀. The claim is proven true."
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
      <p><b>Part a:</b> The p-value is the probability of obtaining a sample statistic as extreme as the one observed, <em>assuming H₀ is true</em>. It is NOT the probability that H₀ is true.</p>
      <p><b>Part b:</b> Compare p-value (' . $pval_b . ') to &alpha; = ' . $alpha_b . '. Since p ' . ($pval_b < $alpha_b ? "&lt;" : "&ge;") . ' &alpha;, we <b>' . $decision_b_text . '</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <p style="margin:0 0 8px 0;"><b>Scenario A:</b> $ctx_a A hypothesis test gives a p-value of <b>$pval_a</b>.</p>
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">a.</span> Select the correct interpretation of the p-value. $answerbox[0]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <p style="margin:0 0 8px 0;"><b>Scenario B:</b> A two-tailed hypothesis test yields p-value = <b>$pval_b</b> at &alpha; = $alpha_b.</p>
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">b.</span> Select the correct decision and conclusion. $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
