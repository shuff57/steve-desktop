// === NAME - DESCRIPTION: Test Statistic to Decision - t, p-value from tcdf, and the decision at alpha ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A t-test with the raw statistics given. Parts: (a) numfunc - the test statistic t
// (b) numfunc - the p-value from tcdf (c) choices - the decision.
// Invariant: (a) and (b) are the precomputed values exactly, (c) matches the comparison.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices")

$cases = array(
  array("A study tests whether the mean number of sick days an employee takes per year differs from 10. A sample of 15 employees gives a mean of 9.4 days with a standard deviation of 2.1 days. Test at alpha = 0.05.",
        10, 15, 9.4, 2.1, 0.05, "two"),
  array("A study tests whether the mean lifespan of a brand of tires is less than 50,000 miles. A sample of 20 tires gives a mean of 48,500 miles with a standard deviation of 2,900 miles. Test at alpha = 0.05.",
        50000, 20, 48500, 2900, 0.05, "left"),
  array("A study tests whether the mean number of bubbles per blow of bubble gum is less than 22. A sample of 10 blows gives a mean of 19.5 bubbles with a standard deviation of 2.9 bubbles. Test at alpha = 0.05.",
        22, 10, 19.5, 2.9, 0.05, "left")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$mu0 = $cases[$i][1]
$n = $cases[$i][2]
$xbar = $cases[$i][3]
$s = $cases[$i][4]
$alpha = $cases[$i][5]
$tail = $cases[$i][6]

$df = $n - 1
$se = $s / sqrt($n)
$t = ($xbar - $mu0) / $se

$pOne = tcdf($t, $df)
$pOne = 1 - $pOne if ($tail == "right")
$p = $pOne
$p = 2 * $pOne if ($tail == "two")

$reject = 0
$reject = 1 if ($p < $alpha)

$answer[0] = $t
$answer[1] = $p
$reltolerance[0] = 0.01
$abstolerance[0] = 0.02
$abstolerance[1] = 0.005

$questions[2] = array(
  "Reject `H_0` &mdash; the p-value is below alpha.",
  "Fail to reject `H_0` &mdash; the p-value is not below alpha."
)
$answer[2] = $reject
$noshuffle[2] = "all"

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
      <p><span class="term-label">Part (a) &mdash; the test statistic.</span> `t = (bar(x) - mu_0)/(s/sqrt(n)) = (' . $xbar . ' - ' . $mu0 . ')/(' . $s . '/sqrt(' . $n . ')) = ' . round($t, 3) . '` with `df = ' . $df . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the p-value.</span> ' . ($tail == "left" ? "Left-tailed: P(T_" . $df . " < " . round($t, 3) . ") = " . round($p, 4) : ($tail == "right" ? "Right-tailed: P(T_" . $df . " > " . round($t, 3) . ") = " . round($p, 4) : "Two-tailed: 2 * P(T_" . $df . " > |" . round($t, 3) . "|) = " . round($p, 4))) . '</p>
      <p><span class="term-label">Part (c) &mdash; the decision.</span> ' . ($reject == 1 ? "The p-value " . round($p, 4) . " is below alpha = " . $alpha . ", so we reject `H_0`." : "The p-value " . round($p, 4) . " is not below alpha = " . $alpha . ", so we fail to reject `H_0`.") . '</p>
      <p>The t distribution accounts for estimating sigma with s &mdash; with few degrees of freedom its fatter tails mean a bigger p-value for the same test statistic, and reading the t-score against a normal table only ever fails in the direction that manufactures findings.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the test statistic `t`.
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
</div>

// === ANSWER ===

$solutionguide
