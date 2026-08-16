// === NAME - DESCRIPTION: The t vs z Critical Values - why the t-value is larger ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A sample size n and a confidence level. Parts: (a) numfunc - t_(alpha/2) for df = n - 1
// (b) numfunc - the matching z_(alpha/2) (c) choices - why the t-value is larger.
// Invariant: (a) > (b) on every seed, (c) is constant.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices")

$contexts = array(
  array("a sample of 25 rechargeable batteries", 25, 0.95, 2.064),
  array("a sample of 15 acupuncture subjects", 15, 0.95, 2.145),
  array("a sample of 30 patients", 30, 0.95, 2.045),
  array("a sample of 100 subjects", 100, 0.95, 1.984)
)
// [ctx, n, cl, t]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$n = $contexts[$i][1]
$cl = $contexts[$i][2]
$t = $contexts[$i][3]

$clPct = round($cl * 100)
$df = $n - 1

$z = 1.96

$answer[0] = $t
$abstolerance[0] = 0.005
$answer[1] = $z
$abstolerance[1] = 0.005

$questions[2] = array(
  "The t-distribution has fatter tails because s is an estimate of sigma, so a bigger multiplier is needed to capture the same confidence",
  "The t-distribution is narrower than the normal, so a smaller multiplier is needed",
  "The t-value is larger because the sample mean is larger than the population mean"
)
$answer[2] = 0
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
      <p><span class="term-label">Part (a) &mdash; the t-critical value.</span> For a ' . round($cl * 100) . '% confidence interval with `df = ' . ($n - 1) . '`, the t-score with `alpha/2` area to its right is `t_(alpha/2) = ' . $t . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the matching z.</span> The matching z-score for the same confidence level is `z_(alpha/2) = ' . $z . '`.</p>
      <p><span class="term-label">Part (c) &mdash; why the t is larger.</span> The t-distribution has more probability out in its tails than the standard normal does, because the denominator is an estimate rather than a known constant. To capture the middle 95% of a t-curve with ' . ($n - 1) . ' degrees of freedom you have to go out ' . $t . ' standard errors from the center instead of ' . $z . ' &mdash; further, because the curve has pushed more of its probability into the tails and you need a wider net to fence in the same 95%. That is the price of not knowing sigma, and the price falls as the sample grows: at 30 degrees of freedom the number is 2.04, at 100 it is 1.98, and it keeps creeping toward 1.96 without ever quite getting there.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider $ctx. The population standard deviation is unknown, so a t-distribution is used. Compare the t-critical value to the matching z-critical value for a $clPct% confidence interval.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The t-critical value `t_(alpha/2)` for `df = $df`. (Round to 3 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The matching z-critical value `z_(alpha/2)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why is the t-value larger than the z-value?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
