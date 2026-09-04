// === NAME - DESCRIPTION: Full hypothesis test for one population proportion (p-value approach) with randomized story contexts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

$anstypes = array("choices","choices","number","number","choices")
$dir = rand(0,2)

// Randomized story contexts
$ci = rand(0,4)
$subjects = array("people who own cats","adults who exercise regularly","voters who support the measure","customers who are satisfied","students who prefer online classes")
$dirtext = listtoarray("smaller,larger,significantly different")

$subject = $subjects[$ci]

$p = rrand(.1,.9,.1)
$phat = $p + nonzerorrand(-0.09,.09,.01) if ($dir==2)
$phat = $p + (2*$dir-1)*rrand(.01,.09,.01) if ($dir!=2)

$n = rrand(100,800,100)

$alpha = randfrom("0.005,0.01,0.025,0.05,0.10")
$alpha = $alpha*2 if ($dir==2)

$mu = $p

$questions[0] = array(
    "`H_0: p = $p`<br>`H_1: p < $p`",
    "`H_0: p = $p`<br>`H_1: p > $p`",
    "`H_0: p = $p`<br>`H_1: p &ne; $p`",
    "`H_0: mu = $mu`<br>`H_1: mu < $mu`",
    "`H_0: mu = $mu`<br>`H_1: mu > $mu`",
    "`H_0: mu = $mu`<br>`H_1: mu &ne; $mu`"
)

$questions[1] = array("left-tailed","right-tailed","two-tailed")
$questions[4] = array("Reject the null hypothesis","Fail to reject the null hypothesis")

$pper = $p*100
$phatper = $phat*100

$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[4] = "select"
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[4] = "all"

$pos = "positive" if ($dir==2)

loadlibrary("stats")
$abstolerance = .01

$teststat = ($phat - $p)/sqrt($p*(1-$p)/$n)

$areatoleft = normalcdf($teststat)

$answer[0] = $dir
$answer[1] = $dir
$answer[2] = $teststat
$answer[3] = $areatoleft if ($dir==0)
$answer[3] = (1-$areatoleft) if ($dir==1)
$answer[3] = min(2*$areatoleft,2*(1-$areatoleft)) if ($dir==2)
$answer[4] = 1
$answer[4] = 0 if ($answer[3] < $alpha)

// Precompute scalars used in QT and Solution: IMathAS text interpolation cannot resolve $arr[$varIndex].
$dirtext_picked = $dirtext[$dir]
$tail_picked = $questions[1][$dir]
$decision_picked = $questions[4][$answer[4]]
$ha_sign_picked = "<" if ($dir==0)
$ha_sign_picked = ">" if ($dir==1)
$ha_sign_picked = "&ne;" if ($dir==2)

$pcompare = "is less than or equal to"
$pcompare = "is greater than" if ($answer[4] == 1)

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Test the claim that the proportion of $subject is <b>$dirtext_picked</b> than `$pper%` at the `alpha = $alpha` significance level. Based on a sample of `n = $n` people, `$phatper%` were $subject.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The null and alternative hypotheses are:
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> This is a:<span style="margin-left:8px;">$answerbox[1]</span> test.
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The test statistic is `z =` <span style="margin-left:8px;">$answerbox[2]</span> (to 2 decimals).
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> The p-value is <span style="margin-left:8px;">$answerbox[3]</span> (to 2 decimals).
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Based on this, we:
    <div style="margin-top:12px;">$answerbox[4]</div>
  </div>
</div>

///

<b>Step 1: State the hypotheses.</b><br>
Since the claim is about a population <i>proportion</i>, we use `p` (not `mu`). The claim says the proportion is <b>$dirtext_picked</b> than `$pper%`, so:<br>
`H_0: p = $p` &nbsp;&nbsp; `H_1: p $ha_sign_picked $p`<br>
This is a <b>$tail_picked</b> test.<br><br>

<b>Step 2: Compute the test statistic.</b><br>
A z-test for proportions:<br>
`z = (\hat{p} - p_0) / sqrt(p_0(1-p_0)/n)`<br>
`z =` <b>$answer[2]</b><br><br>

<b>Step 3: Find the p-value.</b><br>
Using the standard normal distribution, `p text(-value) =` <b>$answer[3]</b>.<br><br>

<b>Step 4: Make a decision.</b><br>
Compare `p text(-value) = $answer[3]` to `alpha = $alpha`. Since the p-value $pcompare `alpha`, we <b>$decision_picked</b>.
