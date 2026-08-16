// === NAME - DESCRIPTION: The Sample Size for a Mean - n = z^2 sigma^2 / EBM^2, rounded up ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// sigma, EBM, CL. Parts: (a) numfunc - the raw n = z^2 sigma^2 / EBM^2
// (b) numfunc - the sample size rounded UP.
// Invariant: (a) is the precomputed raw value, (b) = ceil(a) exactly on every seed.

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("the age of Foothill College students, in years", 15, 2, 0.95, 1.96),
  array("the height of high school basketball players, in inches", 3, 1, 0.95, 1.96),
  array("the height of young adult males, in inches", 2.5, 1, 0.93, 1.812)
)
// [ctx, sigma, ebm, cl, z]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$sigma = $contexts[$i][1]
$ebm = $contexts[$i][2]
$cl = $contexts[$i][3]
$z = $contexts[$i][4]

$clPct = round($cl * 100)

$raw = $z * $z * $sigma * $sigma / ($ebm * $ebm)
$n = ceil($raw)

$answer[0] = $raw
$abstolerance[0] = 0.005
$answer[1] = $n
$abstolerance[1] = 0.005

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
      <p><span class="term-label">Part (a) &mdash; the raw sample size.</span> The error bound formula links precision, confidence, and sample size. Solving it for n:</p>
      <p>`n = z^2 sigma^2 / EBM^2 = (' . $z . ')^2 (' . $sigma . ')^2 / (' . $ebm . ')^2 = ' . round($raw, 2) . '`</p>
      <p><span class="term-label">Part (b) &mdash; round UP.</span> The rounding rule is not a convention, it is arithmetic. Rounding down would leave the sample slightly too small, which makes the error bound slightly larger than the one the study promised. Rounding up costs one extra observation and keeps the promise:</p>
      <p>`n = ' . $n . '`</p>
      <p>Precision is expensive: the EBM is squared in the denominator, so halving the error bound multiplies the required sample size by four.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The population standard deviation for $ctx is `sigma = $sigma`. We want to be $clPct% confident that the sample mean is within `EBM = $ebm` of the true population mean. How many randomly selected individuals must be surveyed?</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The raw value of `n` before rounding. (Round to 2 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The sample size, rounded UP to the next whole number.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
