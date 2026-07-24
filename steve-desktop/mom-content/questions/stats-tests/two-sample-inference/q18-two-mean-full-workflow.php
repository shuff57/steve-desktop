// === NAME - DESCRIPTION: Two-sample t - Full hypothesis-test workflow with Welch t and conclusion ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","numfunc","numfunc","choices")

// Three scenarios. Each gives two-sample summary stats.
// Hypothesize mu_1 != mu_2 (two-sided) at alpha = 0.05. Use Welch df = min(n) - 1.

$intros = array(
  "A nutritionist tests whether mean caloric intake differs between two diet plans. Plan A (n_1 = 25): mean = 1980 cal, s = 230 cal. Plan B (n_2 = 25): mean = 2160 cal, s = 280 cal.",
  "A psychologist tests whether mean reaction time differs between two groups. Group 1 (n_1 = 30): mean = 415 ms, s = 58 ms. Group 2 (n_2 = 30): mean = 442 ms, s = 64 ms.",
  "A coach tests whether mean vertical jump differs between two training programs. Program X (n_1 = 20): mean = 24.1 in, s = 3.2 in. Program Y (n_2 = 22): mean = 22.4 in, s = 3.5 in."
)
$xb1s = array(1980, 415,  24.1)
$xb2s = array(2160, 442,  22.4)
$sd1s = array(230,   58,   3.2)
$sd2s = array(280,   64,   3.5)
$n1s  = array(25,    30,   20)
$n2s  = array(25,    30,   22)
$dfs  = array(24,    29,   19)
$picked = jointrandfrom($intros, $xb1s, $xb2s, $sd1s, $sd2s, $n1s, $n2s, $dfs)
$intro = $picked[0]
$xb1 = $picked[1]; $xb2 = $picked[2]
$sd1 = $picked[3]; $sd2 = $picked[4]
$n1  = $picked[5]; $n2  = $picked[6]
$df  = $picked[7]

$se = round( sqrt( ($sd1*$sd1)/$n1 + ($sd2*$sd2)/$n2 ), 4 )
$t  = round( ($xb1 - $xb2) / $se , 3 )

// approximate two-sided critical at alpha=0.05 for df = 19, 24, 29
$tcrit = 2.093
if ($df == 24) { $tcrit = 2.064 }
if ($df == 29) { $tcrit = 2.045 }
$reject = 0
if (abs($t) > $tcrit) { $reject = 1 }

// Part a: hypotheses
$choices[0] = array(
  "`H_0: mu_1 = mu_2`; `H_a: mu_1 != mu_2`",
  "`H_0: mu_1 != mu_2`; `H_a: mu_1 = mu_2`",
  "`H_0: bar(x)_1 = bar(x)_2`; `H_a: bar(x)_1 != bar(x)_2`",
  "`H_0: mu_d = 0`; `H_a: mu_d != 0`"
)
$answer[0] = 0
$noshuffle[0] = "all"

// Part b: SE
$answer[1] = $se
$abstolerance[1] = 0.10

// Part c: t
$answer[2] = $t
$abstolerance[2] = 0.05

// Part d: conclusion
$choices[3] = array(
  "Reject `H_0`: there is sufficient evidence at `alpha = 0.05` that the population means differ.",
  "Fail to reject `H_0`: there is not sufficient evidence at `alpha = 0.05` that the population means differ."
)
$answer[3] = 1 - $reject
$noshuffle[3] = "all"
$displayformat[0] = "select"
$displayformat[3] = "select"

$decision_word = "fail to reject"
if ($reject == 1) { $decision_word = "reject" }

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
      <p><b>Part a.</b> Two-sample hypotheses test means in the population: `H_0: mu_1 = mu_2` vs `H_a: mu_1 != mu_2`. The hypotheses are statements about parameters, not sample means.</p>
      <p><b>Part b.</b> `SE = sqrt(s_1^2/n_1 + s_2^2/n_2) = sqrt(' . $sd1 . '^2/' . $n1 . ' + ' . $sd2 . '^2/' . $n2 . ') approx ' . $se . '`</p>
      <p><b>Part c.</b> `t = (bar(x)_1 - bar(x)_2) / SE = (' . $xb1 . ' - ' . $xb2 . ') / ' . $se . ' approx ' . $t . '`</p>
      <p><b>Part d.</b> At `alpha = 0.05` two-sided with conservative `df = ' . $df . '` (smaller of `n_1 - 1` and `n_2 - 1`), critical `t approx +- ' . $tcrit . '`. Since `|t| = ' . abs($t) . '`, we ' . $decision_word . ' `H_0`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    <p style="margin:8px 0 0 0;">Use a two-sided two-sample t-test at `alpha = 0.05` (do not assume equal variances). Conservative `df = $df`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> State `H_0` and `H_a`:
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Standard error: $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Test statistic `t`: $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Decision at `alpha = 0.05`:
    <div style="margin-top:12px;">$answerbox[3]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
