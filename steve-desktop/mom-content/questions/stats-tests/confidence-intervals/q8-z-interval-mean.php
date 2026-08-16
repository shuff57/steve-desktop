// === NAME - DESCRIPTION: The z-Interval for a Mean - SE, EBM, and the upper endpoint when sigma is known ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// bar(x), sigma, n, CL with sqrt(n) exact. Parts: (a) numfunc - SE = sigma/sqrt(n)
// (b) numfunc - EBM = z_(alpha/2) * SE (c) numfunc - the upper endpoint bar(x) + EBM.
// Invariant: (a) = sigma/sqrt(n) exactly, (b) = z*SE exactly, (c) = bar(x) + EBM exactly.

$anstypes = array("numfunc", "numfunc", "numfunc")

$contexts = array(
  array("the mean score on a statistics exam, in points", 68, 3, 36, 0.90, 1.645),
  array("the mean delivery time of a pizza chain, in minutes", 36, 6, 28, 0.90, 1.645),
  array("the mean SAR level of cell phones, in watts per kilogram", 1.024, 0.337, 30, 0.98, 2.326),
  array("the mean age of tablet users, in years", 34, 15, 100, 0.95, 1.96)
)
// [ctx, xbar, sigma, n, cl, z]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$xbar = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$cl = $contexts[$i][4]
$z = $contexts[$i][5]

$se = $sigma / sqrt($n)
$ebm = $z * $se
$hi = $xbar + $ebm
$clPct = round($cl * 100)

$answer[0] = $se
$abstolerance[0] = 0.005
$answer[1] = $ebm
$abstolerance[1] = 0.005
$answer[2] = $hi
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
      <p><span class="term-label">Part (a) &mdash; the standard error.</span> The standard deviation has to be the one that applies to the thing being estimated. We are estimating a MEAN, so we need the standard deviation of sample means, which the central limit theorem tells us is `sigma/sqrt(n)`:</p>
      <p>`SE = sigma/sqrt(n) = ' . $sigma . '/sqrt(' . $n . ') = ' . round($se, 4) . '`</p>
      <p><span class="term-label">Part (b) &mdash; the error bound.</span> `EBM = z_(alpha/2) * sigma/sqrt(n) = ' . $z . ' * ' . round($se, 4) . ' = ' . round($ebm, 4) . '`</p>
      <p><span class="term-label">Part (c) &mdash; the upper endpoint.</span> The interval is the point estimate plus or minus the error bound:</p>
      <p>`bar(x) + EBM = ' . $xbar . ' + ' . round($ebm, 4) . ' = ' . round($hi, 4) . '`</p>
      <p>Getting the standard deviation wrong &mdash; using `sigma` by itself instead of `sigma/sqrt(n)` &mdash; is the most common way to wreck this calculation.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher is studying $ctx. A random sample of `n = $n` gives a sample mean of `bar(x) = $xbar`. The population standard deviation is known: `sigma = $sigma`. Build a $clPct% confidence interval for the population mean.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the standard error of the mean? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the error bound `EBM`? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the UPPER endpoint of the interval? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
