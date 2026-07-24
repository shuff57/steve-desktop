// === NAME - DESCRIPTION: Goodness-of-Fit Test Statistic and Decision - Compute chi-square statistic, identify degrees of freedom, state conclusion at alpha=0.05 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices")

// Each scenario: observed + expected counts, precomputed chi^2, df, decision.
// Decision: 0 = reject H0, 1 = fail to reject H0.
// Critical values at alpha=0.05: df=3->7.815, df=4->9.488, df=5->11.070, df=11->19.675.

// Scenario 0: M&M colors. n=150, k=5. O:40,25,50,20,15. E:30,30,45,22.5,22.5. chi^2=7.50, df=4, fail to reject.
// Scenario 1: Birth months. n=120, k=12. O:6,14,8,12,10,13,7,11,9,15,5,10. E:10 each. chi^2=11.00, df=11, fail to reject.
// Scenario 2: Voting preference. n=200, k=4. O:70,70,35,25. E:90,60,30,20. chi^2=8.19, df=3, reject.
// Scenario 3: Ice cream flavors. n=100, k=4. O:30,20,35,15. E:25 each. chi^2=10.00, df=3, reject.

$ctxs = array(
  "A candy company claims its bags contain <b>20% red, 20% orange, 30% yellow, 15% green, 15% brown</b>. A bag of <b>150</b> candies shows <b>40, 25, 50, 20, 15</b>. Under the claim, expected counts are <b>30, 30, 45, 22.5, 22.5</b>.",
  "A study tests whether births are equally likely in each month. Over <b>120</b> births the counts for Jan&ndash;Dec are <b>6, 14, 8, 12, 10, 13, 7, 11, 9, 15, 5, 10</b>. Under equal likelihood, each month expects <b>10</b> births.",
  "A survey claims voter preference splits <b>45% party A, 30% party B, 15% party C, 10% other</b>. A sample of <b>200</b> voters shows <b>70, 70, 35, 25</b>. The expected counts under the claim are <b>90, 60, 30, 20</b>.",
  "An ice cream shop claims all four flavors are equally popular. A sample of <b>100</b> customers orders <b>30 chocolate, 20 vanilla, 35 strawberry, 15 mint</b>. Under equal popularity, each flavor expects <b>25</b>."
)

$chisq = array(7.50, 11.00, 8.19, 10.00)
$dfs   = array(4,    11,    3,    3)
$dec   = array(1,    1,     0,    0)
$decreason = array(
  "fail to reject H_0 because the observed counts are close to expected, so the bag matches the claimed distribution",
  "fail to reject H_0 because the observed counts are close to expected, so births appear equally distributed across months",
  "reject H_0 because the observed counts are far from expected, so voter preferences do not match the claimed split",
  "reject H_0 because the observed counts are far from expected, so the flavors are not equally popular"
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

$crit = 7.815
if ($answer[1] == 4) { $crit = 9.488 }
if ($answer[1] == 5) { $crit = 11.070 }
if ($answer[1] == 11) { $crit = 19.675 }

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
