// === NAME - DESCRIPTION: Distracted Eating CI - Students construct a 95% confidence interval for the difference in mean food intake between a distracted (solitaire) group and a control group, using conservative df and interpreting the result in context. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("choices","number","number","choices")
$abstolerance[1] = 0.06
$abstolerance[2] = 0.06

/* ---------- 1. Randomized Scenario ---------- */

// Sample sizes (equal groups, total 30–60)
$n1 = rrand(18, 28, 2)
$n2 = $n1
$ntotal = $n1 + $n2

// Treatment group (distracted): higher mean food intake
$xbar1 = rrand(45.0, 60.0, 0.1)
$s1 = rrand(35.0, 50.0, 0.1)

// Control group: lower mean food intake
$xbar2 = rrand(22.0, 35.0, 0.1)
$s2 = rrand(20.0, 32.0, 0.1)

// Difference (treatment - control)
$diff = round($xbar1 - $xbar2, 2)

// Conservative degrees of freedom
$df = $n1 - 1

// 95% CI: t critical value with conservative df
$t = invtcdf(0.975, $df)

// Standard error of the difference
$se = sqrt($s1^2/$n1 + $s2^2/$n2)

// Margin of error
$me = round($t * $se, 2)

// CI bounds
$lower = round($diff - $me, 2)
$upper = round($diff + $me, 2)

// Part 0: "the average"
$choices[0] = array("individual", "the average", "the sample", "the sample mean")
$answer[0] = 1
$noshuffle[0] = "all"

// Part 1: lower bound
$answer[1] = $lower

// Part 2: upper bound
$answer[2] = $upper

// Part 3: direction: treatment group always has higher mean by construction
$choices[3] = array("less", "more")
$answer[3] = 1
$noshuffle[3] = "all"

// Display values for solution guide
$diff_show = round($diff, 2)
$se_show = round($se, 2)
$t_show = round($t, 3)
$me_show = round($me, 2)

// === QUESTION TEXT ===

<p>A group of researchers are interested in the possible effects of distracting stimuli during eating, such as an increase or decrease in the amount of food consumption. To test this hypothesis, they monitored food intake for a group of $ntotal patients who were randomized into two equal groups of $n1 each. The treatment group ate lunch while playing solitaire, and the control group ate lunch without any added distractions. Patients in the treatment group ate $xbar1 grams of biscuits, with a standard deviation of $s1 grams, and patients in the control group ate $xbar2 grams of biscuits, with a standard deviation of $s2 grams. Do these data provide convincing evidence that the average food intake (measured in amount of biscuits consumed) is more for individuals playing solitaire? Use the <b>conservative degrees of freedom</b> (the smaller of n&#8321;&minus;1 and n&#8322;&minus;1). Compute a 95% confidence interval for the average difference in food intake.
<br /><br />
We can be 95% confident that $answerbox[0] food intake is between $answerbox[1] grams and $answerbox[2] grams $answerbox[3] for individuals eating while playing solitaire than individuals not playing solitaire. <strong>Round to 2 decimals.</strong></p>

///

<b>Step 1: Identify the procedure.</b><br>
We want a confidence interval for the difference of two independent population means, `mu_1 - mu_2`, with population SDs unknown. Use a <b>two-sample t-interval</b> with the conservative degrees of freedom equal to the smaller of n&#8321;&minus;1 and n&#8322;&minus;1, so df = $df.<br><br>

<b>Step 2: Compute the point estimate.</b><br>
`bar x_1 - bar x_2 = $xbar1 - $xbar2 =` <b>$diff_show</b> grams<br><br>

<b>Step 3: Find the critical t-value.</b><br>
For a 95% CI with df = $df, the critical value is `t* =` <b>$t_show</b>.<br>
Use the invT function on your calculator with cumulative area 0.975, or look up the t-table column for two-tailed `alpha` = 0.05.<br><br>

<b>Step 4: Compute the standard error.</b><br>
`SE = sqrt(s_1^2/n_1 + s_2^2/n_2)`<br>
`SE = sqrt($s1^2/$n1 + $s2^2/$n2)` &approx; <b>$se_show</b><br><br>

<b>Step 5: Compute the margin of error.</b><br>
`E = t* xx SE = $t_show xx $se_show` &approx; <b>$me_show</b><br><br>

<b>Step 6: Construct the interval.</b><br>
`(bar x_1 - bar x_2) +- E = $diff_show +- $me_show`<br>
Lower bound: $diff_show &minus; $me_show &approx; <b>$lower</b><br>
Upper bound: $diff_show + $me_show &approx; <b>$upper</b><br><br>

<b>Conclusion:</b> We are 95% confident that the average food intake is between <b>$lower</b> grams and <b>$upper</b> grams more for individuals eating while playing solitaire than for individuals not playing solitaire. Because the entire interval is positive, the data provide convincing evidence that distracted solitaire eaters consume more on average.
