// === NAME - DESCRIPTION: The t-Interval Width Dials - confidence level, sample size, and the EBM at 4n ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// One scenario, three questions about the SAME t-interval. Parts: (a) choices - what happens to
// the EBM when the confidence level rises (b) choices - what happens when the sample size rises
// (c) numfunc - the EBM at the same CL for a quadrupled sample size (half the original EBM).
// Invariant: (a) and (b) are constant, (c) = original EBM / 2 exactly on every seed.

$anstypes = array("choices", "choices", "numfunc")

$contexts = array(
  array("the mean number of hours slept per night", 8.9833, 1.2904, 12, 0.95, 2.201),
  array("the mean sensory rate for acupuncture subjects", 8.2267, 1.6722, 15, 0.95, 2.145),
  array("the mean number of hours of television per week", 6.133, 5.514, 15, 0.98, 2.624)
)
// [ctx, xbar, s, n, cl, t]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$xbar = $contexts[$i][1]
$s = $contexts[$i][2]
$n = $contexts[$i][3]
$cl = $contexts[$i][4]
$t = $contexts[$i][5]

$clPct = round($cl * 100)
$n4 = 4 * $n

$ebm = $t * $s / sqrt($n)
$ebm4 = $t * $s / sqrt(4 * $n)

$questions[0] = array(
  "It increases &mdash; raising the confidence level means demanding more area under the middle of the t-curve, which forces the boundaries further out into the tails",
  "It decreases &mdash; raising the confidence level means the interval can be tighter",
  "It stays the same &mdash; the confidence level does not affect the error bound"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "It decreases &mdash; the standard error s/sqrt(n) shrinks as n grows",
  "It increases &mdash; more data means more variability",
  "It stays the same &mdash; the sample size does not affect the error bound"
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
      <p><span class="term-label">The standard error is the part that does not change.</span> The error bound is `EBM = t_(alpha/2) * s/sqrt(n)`. The standard error `s/sqrt(n)` depends only on the data &mdash; not on the confidence level. Everything the confidence level touches is packed into the single multiplier `t_(alpha/2)`.</p>
      <p><span class="term-label">Part (a) &mdash; the confidence level.</span> Raising the confidence level means demanding more area under the middle of the t-curve, which forces the boundaries further out into the tails and makes `t_(alpha/2)` larger. The EBM increases and the interval widens.</p>
      <p><span class="term-label">Part (b) &mdash; the sample size.</span> The n sits under a square root in the denominator, so more data shrinks the standard error and narrows the interval.</p>
      <p><span class="term-label">Part (c) &mdash; the EBM at 4n.</span> The original EBM is `' . round($ebm, 4) . '`. Quadrupling the sample size multiplies the denominator by `sqrt(4) = 2`, so the new EBM is half of it:</p>
      <p>`EBM_4n = t * s/sqrt(4n) = ' . round($ebm4, 4) . '`</p>
      <p>Certainty and precision pull against each other &mdash; the same trade the z-interval section described, with the t-multiplier standing in for the z-multiplier.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher is studying $ctx. A random sample of `n = $n` gives a sample mean of `bar(x) = $xbar` and a sample standard deviation of `s = $s`. A $clPct% t-interval has been built.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What happens to the error bound if the confidence level is raised (everything else fixed)?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What happens to the error bound if the sample size is raised (everything else fixed)?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the error bound if the sample size is quadrupled to `4n = $n4` at the same confidence level? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
