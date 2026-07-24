// === NAME - DESCRIPTION: Chi-square Goodness of Fit - Full workflow with written conclusion in context ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","numfunc","choices","choices")

// Three scenarios. Each provides observed and expected counts, precomputed chi^2 and df.
// Decision at alpha = 0.05. Critical values: df=3 -> 7.815, df=4 -> 9.488, df=5 -> 11.070.

// Scenario 0: six-sided die fairness. n=120, k=6. Expected 20 each. Observed 18,22,16,24,20,20.
//   chi^2 = 2.0, df=5, FAIL TO REJECT.
// Scenario 1: M&M color distribution claim 30/25/25/20. n=200, observed 65,48,47,40, expected 60,50,50,40.
//   chi^2 = 0.68, df=3, FAIL TO REJECT.
// Scenario 2: Restaurant entrees claim 40/30/20/10. n=200, observed 60,80,35,25, expected 80,60,40,20.
//   chi^2 = 13.54, df=3, REJECT.

$ctxs = array(
  "A casino manager rolls a six-sided die <b>120</b> times to test whether it is fair (each face equally likely). Observed counts for faces 1-6 are <b>18, 22, 16, 24, 20, 20</b>; expected counts are <b>20</b> each.",
  "A candy company claims bags contain <b>30% red, 25% blue, 25% green, 20% yellow</b>. A consumer counts <b>200</b> candies and observes <b>65 red, 48 blue, 47 green, 40 yellow</b>; expected counts are <b>60, 50, 50, 40</b>.",
  "A restaurant claims its entree mix is <b>40% pasta, 30% pizza, 20% salad, 10% sandwich</b>. A random sample of <b>200</b> orders gives <b>60 pasta, 80 pizza, 35 salad, 25 sandwich</b>; expected counts are <b>80, 60, 40, 20</b>."
)

$chiVals = array(2.00, 0.68, 13.54)
$dfVals  = array(5, 3, 3)
$reject  = array(0, 0, 1)
$ctxName = array("die",          "M&M bag",                     "restaurant menu")
$claimText = array("the die is fair", "the bags match the claimed color distribution", "the restaurant's entree mix matches the claim")
$crits = array(11.070, 7.815, 7.815)

$picked = jointrandfrom($ctxs, $chiVals, $dfVals, $reject, $ctxName, $claimText, $crits)
$ctx      = $picked[0]
$answer[1] = $picked[1]
$dfval    = $picked[2]
$rj       = $picked[3]
$item     = $picked[4]
$claim    = $picked[5]
$crit     = $picked[6]
$reltolerance[1] = 0.05

// Part a: state H0/Ha
$choices[0] = array(
  "`H_0`: the population proportions match the claimed distribution. `H_a`: at least one population proportion differs from the claimed distribution.",
  "`H_0`: at least one population proportion differs. `H_a`: all population proportions match the claim.",
  "`H_0`: the category means are equal. `H_a`: the category means differ.",
  "`H_0`: there is no association between two variables. `H_a`: the variables are associated."
)
$answer[0] = 0
$noshuffle[0] = "all"

// Part c: decision at alpha = 0.05
$choices[2] = array(
  "Reject `H_0`",
  "Fail to reject `H_0`"
)
$answer[2] = 1 - $rj
$noshuffle[2] = "all"

// Part d: conclusion in context
$concl_reject     = "At `alpha = 0.05`, there is sufficient evidence to conclude that " . $claim . " is NOT supported. The observed counts depart significantly from the expected counts."
$concl_fail       = "At `alpha = 0.05`, there is not sufficient evidence to conclude that " . $claim . " is incorrect. The observed counts are consistent with the expected counts."
$concl_wrong1     = "The data prove that " . $claim . " is exactly correct."
$concl_wrong2     = "Because the chi-square statistic is positive, we automatically reject `H_0` regardless of its size."

$choices[3] = array(
  $concl_reject,
  $concl_fail,
  $concl_wrong1,
  $concl_wrong2
)
$answer[3] = 1
if ($rj == 1) { $answer[3] = 0 }
$noshuffle[3] = "all"

$displayformat[0] = "select"
$displayformat[2] = "select"
$displayformat[3] = "select"

$decision_word = "fail to reject"
if ($rj == 1) { $decision_word = "reject" }
$claim_word = "is consistent with"
if ($rj == 1) { $claim_word = "is not consistent with" }

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
      <p><b>Part a.</b> For a chi-square goodness-of-fit test, `H_0` says the population matches the claimed distribution; `H_a` says at least one proportion differs. Chi-square tests are one-sided in chi-square (always upper tail), so the alternative only says "differs," not direction-specific.</p>
      <p><b>Part b.</b> Compute `chi^2 = sum (O - E)^2 / E` across all categories. Here `chi^2 approx ' . $answer[1] . '`.</p>
      <p><b>Part c.</b> Critical value at `alpha = 0.05` with `"df" = ' . $dfval . '` is about `' . $crit . '`. Compare: `chi^2 = ' . $answer[1] . '` vs `' . $crit . '`, so ' . $decision_word . ' `H_0`.</p>
      <p><b>Part d.</b> In context, the observed counts ' . $claim_word . ' the claim that ' . $claim . '.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Workflow:</b> state `H_0`/`H_a` &rarr; compute `chi^2 = sum (O-E)^2/E` &rarr; `"df" = k - 1` &rarr; compare to critical value &rarr; write conclusion in plain English referencing the original claim.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">Use a chi-square goodness-of-fit test at significance level `alpha = 0.05`. The degrees of freedom for this test is `"df" = $dfval`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> State the null and alternative hypotheses.
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the chi-square test statistic. Round to two decimal places. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Decision at `alpha = 0.05`:
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Which conclusion best fits the result in context?
    <div style="margin-top:12px;">$answerbox[3]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
