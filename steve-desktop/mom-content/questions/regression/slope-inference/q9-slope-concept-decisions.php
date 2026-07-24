// === NAME - DESCRIPTION: Slope inference - concept and decisions (TOP-MISS drill for IND q10 / group q14) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices","choices")

// Three scenarios. Each provides a slope estimate, t-statistic, and p-value from a slope hypothesis test.
// Parts:
//   (a) What does H0: beta = 0 mean in context?
//   (b) What does the slope estimate tell us about the sample?
//   (c) Decision at alpha = 0.05
//   (d) What does the decision mean for the relationship between x and y in the population?

$ctxs = array(
  array("x" => "years of education", "y" => "annual salary (thousands of dollars)", "slope" => 4.2, "tstat" => 5.81, "p" => 0.0001, "alpha" => 0.05),
  array("x" => "hours of weekly exercise", "y" => "resting heart rate (bpm)", "slope" => -1.8, "tstat" => -2.45, "p" => 0.018, "alpha" => 0.05),
  array("x" => "average daily caffeine (mg)", "y" => "hours of sleep per night", "slope" => -0.004, "tstat" => -1.32, "p" => 0.193, "alpha" => 0.05)
)

$picked = jointrandfrom($ctxs)
$s = $picked[0]

$xvar  = $s["x"]
$yvar  = $s["y"]
$slope = $s["slope"]
$tstat = $s["tstat"]
$pval  = $s["p"]
$alpha = $s["alpha"]

$reject = 0
if ($pval < $alpha) { $reject = 1 }

// Part a: meaning of H0
$choices[0] = array(
  "`H_0`: there is no linear relationship between " . $xvar . " and " . $yvar . " in the population (the true slope is zero). `H_a`: there is a linear relationship (the true slope is not zero).",
  "`H_0`: the slope of the regression line is exactly equal to the sample slope. `H_a`: the slope is different.",
  "`H_0`: the correlation `r` is zero. `H_a`: the correlation is one.",
  "`H_0`: the residuals are zero. `H_a`: the residuals are not zero."
)
$answer[0] = 0
$noshuffle[0] = "all"

// Part b: what does the sample slope tell us
$slope_dir = "positive"
if ($slope < 0) { $slope_dir = "negative" }
$choices[1] = array(
  "The sample slope is " . $slope . ", which shows a " . $slope_dir . " linear relationship in this sample. Whether the relationship exists in the population is what the hypothesis test will decide.",
  "The sample slope of " . $slope . " proves the relationship in the population.",
  "The sample slope is meaningless without a y-intercept.",
  "The sample slope equals the true population slope exactly."
)
$answer[1] = 0
$noshuffle[1] = "all"

// Part c: decision at alpha = 0.05
$choices[2] = array(
  "Reject `H_0`",
  "Fail to reject `H_0`"
)
$answer[2] = 1 - $reject
$noshuffle[2] = "all"

// Part d: what does the decision mean
$d_reject = "At alpha = 0.05, we have sufficient evidence to conclude that there is a linear relationship between " . $xvar . " and " . $yvar . " in the population. The slope is significantly different from zero."
$d_fail   = "At alpha = 0.05, we do not have sufficient evidence to conclude a linear relationship between " . $xvar . " and " . $yvar . " in the population. The data are consistent with a true slope of zero."
$d_wrong1 = "The result proves the slope is exactly " . $slope . " in the population."
$d_wrong2 = "Because t is " . $tstat . ", the relationship must be causal."

$choices[3] = array(
  $d_reject,
  $d_fail,
  $d_wrong1,
  $d_wrong2
)
$answer[3] = 1
if ($reject == 1) { $answer[3] = 0 }
$noshuffle[3] = "all"

$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"
$displayformat[3] = "select"

$decision_text = "fail to reject"
if ($reject == 1) { $decision_text = "reject" }

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
      <p><b>Part a.</b> In a slope hypothesis test, `H_0: beta = 0` says the population slope is zero, meaning no linear relationship between ' . $xvar . ' and ' . $yvar . ' in the population. `H_a: beta != 0` says there is a linear relationship.</p>
      <p><b>Part b.</b> The sample slope is `b = ' . $slope . '`. The sample slope describes the trend in <i>this</i> sample only. The hypothesis test asks whether that pattern is strong enough evidence of a population-level relationship, or whether it could plausibly come from random variation around a true slope of zero.</p>
      <p><b>Part c.</b> Compare `p = ' . $pval . '` to `alpha = ' . $alpha . '`. Since ' . ($reject==1 ? "p &lt; alpha" : "p &gt;= alpha") . ', we ' . $decision_text . ' `H_0`.</p>
      <p><b>Part d.</b> In context, this decision ' . ($reject==1 ? "supports the conclusion that there is a linear relationship" : "does not let us conclude there is a linear relationship") . ' between ' . $xvar . ' and ' . $yvar . ' in the population. Remember: even when we reject, the test never proves causation, and it does not pinpoint the exact value of the population slope.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff8e1; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Common trap:</b> "the slope is significant" only means it is significantly different from zero. It does not mean the slope <i>equals</i> the sample estimate in the population, and it never implies causation by itself.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===
<style>select{max-width:100%;width:100%;box-sizing:border-box;display:block;margin:4px 0;}</style>

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A researcher fits a least-squares regression of <b>$yvar</b> on <b>$xvar</b>. The estimated slope is `b = $slope`, the test statistic is `t = $tstat`, and the p-value is `$pval`. Use significance level `alpha = $alpha`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What do `H_0` and `H_a` say in context?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the sample slope tell us?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Decision at `alpha = $alpha`:
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What does this decision mean for the population?
    <div style="margin-top:12px;">$answerbox[3]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
