// === NAME - DESCRIPTION: The Two Width Dials - confidence level and sample size, and the EBM at 4n ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// One scenario, three questions about the SAME interval. Parts: (a) choices - what happens to
// the width when the confidence level rises (b) choices - what happens when the sample size
// rises (c) numfunc - the EBM at the original CL for a quadrupled sample size (half the
// original EBM).
// Invariant: (a) and (b) are constant, (c) = original EBM / 2 exactly on every seed.

$anstypes = array("choices", "choices", "numfunc")

$contexts = array(
  array("the mean score on a statistics exam, in points", 68, 3, 36, 0.90, 1.645),
  array("the mean delivery time of a pizza chain, in minutes", 36, 6, 28, 0.90, 1.645),
  array("the mean age of tablet users, in years", 34, 15, 100, 0.95, 1.96)
)
// [ctx, xbar, sigma, n, cl, z]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$xbar = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$cl = $contexts[$i][4]
$z = $contexts[$i][5]

$ebm = $z * $sigma / sqrt($n)
$ebm4 = $z * $sigma / sqrt(4 * $n)

$questions[0] = array(
  "It widens &mdash; being surer means reaching further out into the tails",
  "It narrows &mdash; being surer means the interval can be tighter",
  "It stays the same &mdash; the confidence level does not affect the width"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "It narrows &mdash; more data means a tighter sampling distribution",
  "It widens &mdash; more data means more variability",
  "It stays the same &mdash; the sample size does not affect the width"
)
$answer[1] = 0
$noshuffle[1] = "all"

$answer[2] = $ebm4
$abstolerance[2] = 0.005

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
      <p><span class="term-label">Two dials control the width.</span> Raising the confidence level widens the interval, because being surer means reaching further out. Raising the sample size narrows it, because more data means a tighter sampling distribution. Each one pulls in a predictable direction.</p>
      <p><span class="term-label">Part (a) &mdash; the confidence level.</span> Capturing an area of 0.95 takes more room than capturing an area of 0.90, so to be more confident that the interval really does contain mu, the interval has to be wider.</p>
      <p><span class="term-label">Part (b) &mdash; the sample size.</span> The n sits under a square root in the denominator, so more data tightens the sampling distribution and narrows the interval.</p>
      <p><span class="term-label">Part (c) &mdash; the EBM at 4n.</span> The original EBM is `' . round($ebm, 4) . '`. Quadrupling the sample size multiplies the denominator by `sqrt(4) = 2`, so the new EBM is half of it:</p>
      <p>`EBM_4n = z * sigma/sqrt(4n) = ' . round($ebm4, 4) . '`</p>
      <p>Note that nearly tripling the sample size from 36 to 100 only cut the error bound by about 40% &mdash; the `sqrt(n)` makes precision expensive.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher is studying $ctx. A random sample of `n = $n` gives a sample mean of `bar(x) = $xbar`. The population standard deviation is known: `sigma = $sigma`. A ' . round($cl * 100) . '% confidence interval has been built.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What happens to the width of the interval if the confidence level is raised (everything else fixed)?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What happens to the width of the interval if the sample size is raised (everything else fixed)?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the error bound if the sample size is quadrupled to `4n = ' . (4 * $n) . '` at the same confidence level? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
