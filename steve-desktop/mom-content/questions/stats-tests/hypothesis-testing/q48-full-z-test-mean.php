// === NAME - DESCRIPTION: Full Z-Test for a Mean - z, p-value, decision, and conclusion when sigma is known ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A one-mean test with sigma KNOWN. Parts: (a) numfunc - the test statistic z
// (b) numfunc - the p-value (c) choices - the decision at alpha (d) choices - the conclusion.
// Invariant: (a) and (b) are the precomputed values exactly, (c) matches the p-vs-alpha
// comparison, (d) matches the decision.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices", "choices")

$cases = array(
  array("A class of 30 students reports an average of 3.2 hours of television per day. The claim on trial is that Americans watch 4 hours per day, and sigma = 2 is known. Test at alpha = 0.05 whether the average for students is lower.",
        4, 2, 30, 3.2, 0.05, "left",
        "At the 5% significance level there is sufficient evidence to conclude that the mean hours of television watched per day by students in this class is less than four."),
  array("A factory claims the mean fill weight of its bottles is 16 fluid ounces. A sample of 40 bottles has a mean of 15.7 oz, and sigma = 0.8 is known. Test at alpha = 0.05 whether the mean differs from 16 oz.",
        16, 0.8, 40, 15.7, 0.05, "two",
        "At the 5% significance level there is sufficient evidence to conclude that the mean fill weight differs from 16 oz."),
  array("A logistics company advertises mean delivery time of 28 hours. A sample of 36 deliveries has a mean of 30.1 hours, and sigma = 5.2 is known. Test at alpha = 0.05 whether the mean exceeds 28 hours.",
        28, 5.2, 36, 30.1, 0.05, "right",
        "At the 5% significance level there is sufficient evidence to conclude that the mean delivery time exceeds 28 hours.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$mu0 = $cases[$i][1]
$sigma = $cases[$i][2]
$n = $cases[$i][3]
$xbar = $cases[$i][4]
$alpha = $cases[$i][5]
$tail = $cases[$i][6]
$conclusion = $cases[$i][7]

$se = $sigma / sqrt($n)
$z = ($xbar - $mu0) / $se

$pOne = normalcdf($z)
$pOne = 1 - $pOne if ($tail == "right")
$p = $pOne
$p = 2 * $pOne if ($tail == "two")

$reject = 0
$reject = 1 if ($p < $alpha)

$answer[0] = $z
$answer[1] = $p
$reltolerance[0] = 0.01
$abstolerance[0] = 0.02
$abstolerance[1] = 0.005

$questions[2] = array(
  "Reject `H_0`: the p-value is below alpha.",
  "Fail to reject `H_0`: the p-value is not below alpha."
)
$answer[2] = $reject
$noshuffle[2] = "all"

$questions[3] = array(
  $conclusion,
  "The claim is false.",
  "We accept `H_0`."
)
$answer[3] = 0
$noshuffle[3] = "all"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Part (a): the test statistic.</span> With sigma given the test statistic is a z-score built from the standard error: `z = (bar(x) - mu_0)/(sigma/sqrt(n)) = (' . $xbar . ' - ' . $mu0 . ')/(' . $sigma . '/sqrt(' . $n . ')) = ' . round($z, 3) . '`.</p>
      <p><span class="term-label">Part (b): the p-value.</span> ' . ($tail == "left" ? "Left-tailed: P(Z < " . round($z, 3) . ") = " . round($p, 4) : ($tail == "right" ? "Right-tailed: P(Z > " . round($z, 3) . ") = " . round($p, 4) : "Two-tailed: 2 * P(Z > |" . round($z, 3) . "|) = " . round($p, 4))) . '</p>
      <p><span class="term-label">Part (c): the decision.</span> ' . ($reject == 1 ? "The p-value " . round($p, 4) . " is below alpha = " . $alpha . ", so we reject `H_0`." : "The p-value " . round($p, 4) . " is not below alpha = " . $alpha . ", so we fail to reject `H_0`.") . '</p>
      <p><span class="term-label">Part (d): the conclusion.</span> ' . $conclusion . '</p>
      <p>The `sqrt(n)` in the denominator converts "how far below" into "how surprising": which is what makes a test different from eyeballing an average.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the test statistic `z`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the p-value.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the decision at `alpha = $alpha`?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What is the conclusion?
    <span style="margin-left:8px;">$answerbox[3]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
