// === NAME - DESCRIPTION: Goodness-of-Fit Test Statistic and Decision - Compute chi-square statistic, identify df, decide at alpha=0.05 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices")

// Each scenario: observed counts + expected counts (already aligned), precomputed chi^2, df, and decision.
// Decision index: 0 = reject H0, 1 = fail to reject H0.
// Critical values at alpha = 0.05: df=3 -> 7.815, df=4 -> 9.488, df=5 -> 11.070, df=6 -> 12.592.

// Scenario 0: Fair die test. n=120, k=6. Observed 18,22,16,24,20,20. E=20 each. chi^2 = 2.0, df=5, fail to reject.
// Scenario 1: Tax-prep methods. n=200, k=4. Observed 60,50,50,40. E=80,60,40,20. chi^2 = 29.17, df=3, reject.
// Scenario 2: 4-sided die. n=80, k=4. Observed 35,15,18,12. E=20 each. chi^2 = 15.9, df=3, reject.
// Scenario 3: Candy bag colors. n=200, k=4. Observed 70,40,50,40. E=60,50,50,40. chi^2 = 3.67, df=3, fail to reject.

$ctxs = array(
  "A casino manager tests a six-sided die by rolling it <b>120</b> times. The observed counts for faces 1 through 6 are <b>18, 22, 16, 24, 20, 20</b>. Each face is expected <b>20</b> times under the fair-die claim.",
  "A tax office claims that returns split <b>40% standard, 30% itemized, 20% business, 10% other</b>. A sample of <b>200</b> returns shows <b>60, 50, 50, 40</b> in those categories. The expected counts under the claim are <b>80, 60, 40, 20</b>.",
  "A four-sided gaming die is rolled <b>80</b> times with observed counts <b>35, 15, 18, 12</b>. Under the fair-die claim, each face is expected <b>20</b> times.",
  "A candy company claims bags contain <b>30% red, 25% blue, 25% green, 20% yellow</b>. A bag of <b>200</b> candies shows <b>70, 40, 50, 40</b>. The expected counts are <b>60, 50, 50, 40</b>."
)

$chisq = array(2.00,  29.17, 15.90, 3.67)
$dfs   = array(5,     3,     3,     3)
$dec   = array(1,     0,     0,     1)
$decreason = array(
  "fail to reject H_0 because the observed counts are close to expected, so the die appears fair",
  "reject H_0 because the observed counts are far from expected, so returns do not match the claimed split",
  "reject H_0 because the observed counts are far from expected, so the die does not appear fair",
  "fail to reject H_0 because the observed counts are close to expected, so the bag matches the claim"
)

$picked = jointrandfrom($ctxs, $chisq, $dfs, $dec, $decreason)
$ctx     = $picked[0]
$answer[0] = $picked[1]
$answer[1] = $picked[2]
$answer[2] = $picked[3]
$decwhy  = $picked[4]
$reltolerance[0] = 0.02
$abstolerance[1] = 0.5

$choices[2] = array(
  "Reject H_0. The data do not match the claimed distribution.",
  "Fail to reject H_0. The data are consistent with the claimed distribution."
)

// Critical value for the solution narrative
$crit = 7.815
if ($answer[1] == 4) { $crit = 9.488 }
if ($answer[1] == 5) { $crit = 11.070 }
if ($answer[1] == 6) { $crit = 12.592 }

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
      <p><b>Part a:</b> Use `chi^2 = sum (O - E)^2 / E` across all categories.</p>
      <p>Adding `(O-E)^2/E` over the categories gives <b>`chi^2 approx ' . $answer[0] . '`</b>.</p>
      <p><b>Part b:</b> For goodness of fit, `df = k - 1` where `k` is the number of categories. Here <b>df = ' . $answer[1] . '</b>.</p>
      <p><b>Part c:</b> Critical value at `alpha = 0.05` with df = ' . $answer[1] . ' is about <b>' . $crit . '</b>.</p>
      <p>Compare: `chi^2 = ' . $answer[0] . '` vs critical `' . $crit . '`. So we ' . $decwhy . '.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Workflow:</b> compute `(O-E)^2/E` per cell &rarr; sum &rarr; find df = k-1 &rarr; compare to critical value at `alpha`.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the chi-square test statistic. (Round to 2 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Identify the degrees of freedom. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Using `alpha = 0.05`, state the conclusion.
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
