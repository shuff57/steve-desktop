// === NAME - DESCRIPTION: Paired t-test full workflow from data table - mean diff, SE, t, conclusion ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc","numfunc","numfunc","choices")

// Three scenarios. Each gives paired data summarized as dbar (mean of differences), sd (SD of differences), n.
// Pretest-Posttest pairing on same subjects.
// Part a: mean of differences (given, must report)  -- nope, instead:
// Part a: SE of dbar
// Part b: t statistic
// Part c: degrees of freedom
// Part d: decision at alpha = 0.05 (two-sided)

$intros = array(
  "A fitness trainer measures resting heart rate (bpm) for <b>15</b> clients before and after a 6-week program. The mean of the within-subject differences (before - after) is `bar(d) = 4.2` bpm with sample standard deviation `s_d = 5.8` bpm.",
  "A teacher records reading-comprehension scores (0-100) for <b>22</b> students before and after a 4-week intervention. The mean of the differences (after - before) is `bar(d) = 6.4` points with sample standard deviation `s_d = 12.1` points.",
  "A nutrition study measures fasting blood glucose (mg/dL) for <b>18</b> participants before and after a 12-week diet. The mean of the differences (before - after) is `bar(d) = 5.1` mg/dL with sample standard deviation `s_d = 9.3` mg/dL."
)
$dbars = array(4.2, 6.4, 5.1)
$sds   = array(5.8, 12.1, 9.3)
$ns    = array(15, 22, 18)
$picked = jointrandfrom($intros, $dbars, $sds, $ns)
$intro = $picked[0]
$dbar  = $picked[1]
$sd    = $picked[2]
$n     = $picked[3]

$se = round( $sd / sqrt($n) , 4 )
$t  = round( $dbar / $se , 3 )
$df = $n - 1

$answer[0] = $se
$answer[1] = $t
$answer[2] = $df

$abstolerance[0] = 0.01
$abstolerance[1] = 0.05
$abstolerance[2] = 0.5

// Decide reject vs fail at alpha=0.05 two-sided
// Approximate critical t values
$tcrit = 2.131
if ($df >= 16 && $df <= 20) { $tcrit = 2.110 }
if ($df >= 21 && $df <= 24) { $tcrit = 2.080 }
if ($df >= 25) { $tcrit = 2.064 }

$reject = 0
if (abs($t) > $tcrit) { $reject = 1 }

$choices[3] = array(
  "Reject `H_0`: there is sufficient evidence at `alpha = 0.05` that the population mean difference is not zero.",
  "Fail to reject `H_0`: there is not sufficient evidence at `alpha = 0.05` that the population mean difference is not zero."
)
$answer[3] = 1 - $reject
$noshuffle[3] = "all"
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
      <p><b>Setup.</b> For paired data, work with the differences `d_i`. Hypotheses: `H_0: mu_d = 0` vs `H_a: mu_d != 0`.</p>
      <p><b>Part a.</b> `SE = s_d / sqrt(n) = ' . $sd . ' / sqrt(' . $n . ') approx ' . $se . '`</p>
      <p><b>Part b.</b> `t = bar(d) / SE = ' . $dbar . ' / ' . $se . ' approx ' . $t . '`</p>
      <p><b>Part c.</b> `df = n - 1 = ' . $df . '`</p>
      <p><b>Part d.</b> Critical `t` at `alpha = 0.05` (two-sided) for `df = ' . $df . '` is about `' . $tcrit . '`. Since `|t| = ' . abs($t) . '`, we ' . $decision_word . ' `H_0`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===
<style>select{max-width:100%;width:100%;box-sizing:border-box;display:block;margin:4px 0;}</style>

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    <p style="margin:8px 0 0 0;">Run a paired t-test of `H_0: mu_d = 0` vs `H_a: mu_d != 0` at `alpha = 0.05`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Standard error of `bar(d)`: $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Test statistic `t`: $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Degrees of freedom: $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Decision at `alpha = 0.05`:
    <div style="margin-top:12px;">$answerbox[3]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
