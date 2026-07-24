// === NAME - DESCRIPTION: Hypothesis testing using confidence intervals for a proportion with randomized story contexts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("choices","choices","number","number","number","choices")
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[5] = "select"
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[5] = "all"
$answerboxsize = 6

// Randomized story contexts
$ci = rand(0,4)
$companies = array("Trydint","FreshBite","QuickCharge","GreenLeaf","SoundWave")
$products = array("bubble-gum","protein bar","phone charger","herbal tea","wireless earbud")
$rivals = array("Eklypse","PowerCrunch","VoltMax","PureBlend","BassBoost")

$company = $companies[$ci]
$product = $products[$ci]
$rival = $rivals[$ci]

$subp = rand(3,9)
$param = $subp/10
$n = rrand(50,500,10)
$subn = $param*$n+rand(-30,-2) where ($subn>0)
$p = $subn/$n

$c = randfrom("90,95,99")
$z = invnormalcdf($c/100 + .5*(1-$c/100))
$me = round(round($z,2)*sqrt($p*(1-$p)/$n),3)

$questions[0] = array(
  "`H_0: p >= $param`,  `H_a: p < $param`",
  "`H_0: p <= $param`,  `H_a: p > $param`",
  "`H_0: p = $param`,  `H_a: p != $param`",
  "`H_0: mu >= $param`,  `H_a: mu < $param`",
  "`H_0: mu <= $param`,  `H_a: mu > $param`",
  "`H_0: mu = $param`,  `H_a: mu != $param`"
)
$answer[0] = 2

$questions[1] = array(
  "The proportion of all people who prefer $company $product is `$param`.",
  "The proportion of all people who prefer $company $product is greater than `$param`.",
  "The proportion of all people who prefer $company $product is less than `$param`.",
  "The proportion of people in this sample who prefer $company $product is `$param`.",
  "The proportion of people in this sample who prefer $company $product is not `$param`.",
  "The average number of people who prefer $company $product is `$param`.",
  "The average number of people who prefer $company $product is not `$param`."
)
$answer[1] = 0

$questions[5] = array("Reject the null hypothesis","Fail to reject the null hypothesis")

$answer[2] = round($p,3)
$answer[3] = round($p-$me,3)
$answer[4] = round($p+$me,3)
$answer[5] = 0 if ($p+$me < $param)
$answer[5] = 1 if ($p+$me >= $param)

$abstolerance = .01

$decision_text = "reject the null hypothesis"
$decision_text = "fail to reject the null hypothesis" if ($p+$me >= $param)
$inside_text = "outside"
$inside_text = "inside" if ($p+$me >= $param)

$z_show = round($z, 3)
$p_show = round($p, 3)
$lo_show = round($p - $me, 3)
$hi_show = round($p + $me, 3)

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">"$company" claims that `$subp` out of `10` people prefer their $product to "$rival" brand. Test their claim at the `$c%` confidence level.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The null and alternative hypotheses in symbols are:
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The null hypothesis in words:
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">Based on a sample of `n = $n` people, `$subn` said they prefer "$company" $product to "$rival".</p>
    <p style="margin:0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The point estimate is `\hat{p} =` <span style="margin-left:8px;">$answerbox[2]</span> (to 3 decimals).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> The `$c%` confidence interval is from <span style="margin-left:8px;">$answerbox[3]</span> to <span style="margin-left:8px;">$answerbox[4]</span> (to 3 decimals).
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Based on the CI, our decision is:
    <div style="margin-top:12px;">$answerbox[5]</div>
  </div>
</div>

///

<b>Step 1: State the hypotheses.</b><br>
The company claims `$subp` out of `10` people prefer their product, so `p_0 = $param`. We test whether this claim is true with a two-tailed test:<br>
`H_0: p = $param` &nbsp;&nbsp; `H_a: p != $param`<br><br>

<b>Step 2: The null hypothesis in words.</b><br>
"The proportion of <i>all</i> people who prefer $company $product is `$param`."<br><br>

<b>Step 3: Compute the point estimate.</b><br>
`\hat{p} = $subn / $n ~~ $p_show`<br><br>

<b>Step 4: Construct the `$c%` confidence interval.</b><br>
`z^* ~~ $z_show`<br>
`ME = z^* cdot sqrt(\hat{p}(1-\hat{p})/n) ~~ $me`<br>
CI: `\hat{p} +- ME = $p_show +- $me`<br>
CI: `($lo_show, $hi_show)`<br><br>

<b>Step 5: Make a decision using the CI.</b><br>
Check whether `p_0 = $param` falls inside the confidence interval `($lo_show, $hi_show)`.<br>
Since `$param` is $inside_text the interval, we <b>$decision_text</b>.
