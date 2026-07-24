// === NAME - DESCRIPTION: Reject vs Fail to Reject - nuanced contexts where the p-value is close to the significance level alpha; students must apply the rule rigidly and avoid the "accept the null" misconception ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")
$displayformat = "select"
$answerboxsize = 24

// Each scenario gives a p-value and a significance level alpha. Scenarios are tuned so
// the p-value is close to alpha (above or below). Part (a) compares p to alpha;
// (b) is the decision; (c) is the correct CONCLUSION wording — testing for the
// "accept the null" misconception.

// Scenarios (p, alpha, decision: 0=reject, 1=fail to reject):
// 0: p=0.048, alpha=0.05  -> just barely reject
// 1: p=0.052, alpha=0.05  -> just barely fail to reject (NOT "accept null")
// 2: p=0.011, alpha=0.01  -> just barely fail to reject
// 3: p=0.009, alpha=0.01  -> just barely reject
// 4: p=0.103, alpha=0.10  -> just barely fail to reject
// 5: p=0.025, alpha=0.05  -> reject (clearly under alpha but not lopsided)
// 6: p=0.150, alpha=0.05  -> clearly fail to reject

$ctxs = array(
  "A pharmacy claims that a new pain reliever works for more than 60% of patients. After a study, the p-value for the test is <b>0.048</b> and the significance level is <b>`alpha` = 0.05</b>.",
  "A teacher claims that the mean score on a state exam is higher than 70. A study produces a p-value of <b>0.052</b> at <b>`alpha` = 0.05</b>.",
  "A manufacturer claims its bolts have a mean diameter different from 10mm. A study produces a p-value of <b>0.011</b> at <b>`alpha` = 0.01</b>.",
  "A coffee shop claims more than 30% of customers buy the seasonal drink. A study produces a p-value of <b>0.009</b> at <b>`alpha` = 0.01</b>.",
  "A polling firm claims the proportion of voters in favor of a bill is less than 0.50. A poll produces a p-value of <b>0.103</b> at <b>`alpha` = 0.10</b>.",
  "A school nurse claims more than 15% of students miss a day due to illness in a typical week. A survey produces a p-value of <b>0.025</b> at <b>`alpha` = 0.05</b>.",
  "A nutrition study tests whether the mean sugar content of a cereal brand differs from 12g. The p-value is <b>0.150</b> at <b>`alpha` = 0.05</b>."
)

$ps     = array(0.048, 0.052, 0.011, 0.009, 0.103, 0.025, 0.150)
$alphas = array(0.05,  0.05,  0.01,  0.01,  0.10,  0.05,  0.05)
// 0 = p < alpha (reject), 1 = p > alpha (fail to reject)
$cmpAns = array(0,     1,     1,     0,     1,     0,     1)
// 0 = "Reject H_0", 1 = "Fail to reject H_0"
$decAns = array(0,     1,     1,     0,     1,     0,     1)
// final conclusion choices (see $questions[2] below) — index of correct option
// 0 = "There is statistically significant evidence to support the alternative"
// 1 = "There is NOT enough evidence to support the alternative; we do not have evidence to conclude the null is true"
// 2 = "We have proven the alternative is true"
// 3 = "We accept the null hypothesis as true"
$conclAns = array(0,   1,     1,     0,     1,     0,     1)

$picked = jointrandfrom($ctxs, $ps, $alphas, $cmpAns, $decAns, $conclAns)
$ctx     = $picked[0]
$p_val   = $picked[1]
$alpha_v = $picked[2]

$questions[0] = array("The p-value is LESS than `alpha`", "The p-value is GREATER than `alpha`")
$answer[0] = $picked[3]

$questions[1] = array("Reject the null hypothesis", "Fail to reject the null hypothesis")
$answer[1] = $picked[4]

$questions[2] = array(
  "There is statistically significant evidence to support the alternative hypothesis.",
  "There is NOT enough evidence to support the alternative hypothesis. We do not conclude the null is true; we just fail to find evidence against it.",
  "We have proven that the alternative hypothesis is true.",
  "We accept the null hypothesis as true."
)
$answer[2] = $picked[5]

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
      <p><b>Decision rule:</b></p>
      <ul>
        <li>If `p-text(value) <= alpha`, <b>reject `H_0`</b>.</li>
        <li>If `p-text(value) > alpha`, <b>fail to reject `H_0`</b> (we do NOT "accept" `H_0`).</li>
      </ul>
      <p><b>(a)</b> Compare `p = ' . $p_val . '` to `alpha = ' . $alpha_v . '`.</p>
      <p><b>(b)</b> Apply the rule.</p>
      <p><b>(c)</b> When we reject `H_0`, the correct conclusion is "there is statistically significant evidence for `H_a`". When we fail to reject `H_0`, the correct conclusion is "we do NOT have enough evidence to support `H_a`" — NOT "we accept `H_0`" and NOT "we have proven the null is true".</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff3e0; border-left:4px solid #ff9800; border-radius:0 8px 8px 0;">
        <b>Watch out:</b> "fail to reject" only means the evidence is not strong enough to overturn the null. The null might still be false — we just did not detect it in this study. Always speak in terms of evidence, never in terms of "proof".
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===
<style>select{max-width:100%;width:100%;box-sizing:border-box;display:block;margin:4px 0;}</style>

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How does the p-value compare to `alpha`?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the decision?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which of the following best describes the correct conclusion?
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
