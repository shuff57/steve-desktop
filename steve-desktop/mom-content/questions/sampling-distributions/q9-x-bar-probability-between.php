// === NAME - DESCRIPTION: Probability That a Sample Mean Falls Between Two Values - P(lo < bar(x) < hi) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// mu, sigma, n, and cutoffs lo and hi. Parts: (a) numfunc - the standard error
// (b) numfunc - P(lo < bar(x) < hi).
// Invariant: (a) = sigma/sqrt(n) and (b) = normalcdf((hi-mu)/SE) - normalcdf((lo-mu)/SE),
// computed by standardization, on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("an unknown distribution", 45, 8, 30, 42, 50),
  array("the length of time taken on the SAT, in hours", 2.5, 0.25, 60, 2, 3),
  array("the length of time to play one soccer match, in hours", 2, 0.5, 50, 1.8, 2.3),
  array("the mean age of tablet users, in years", 34, 15, 100, 30, 38)
)
// [ctx, mu, sigma, n, lo, hi]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$lo = $contexts[$i][4]
$hi = $contexts[$i][5]

$se = $sigma / sqrt($n)
$zlo = ($lo - $mu) / $se
$zhi = ($hi - $mu) / $se
$prob = normalcdf($zhi) - normalcdf($zlo)

$answer[0] = $se
$abstolerance[0] = 0.005
$answer[1] = $prob
$reltolerance[1] = 0.02
$abstolerance[1] = 0.003

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
      <p><span class="term-label">Part (a): the standard error.</span> `SE = sigma/sqrt(n) = ' . $sigma . '/sqrt(' . $n . ') = ' . round($se, 4) . '`.</p>
      <p><span class="term-label">Part (b): the probability.</span> Sketch the curve and shade the strip between ' . $lo . ' and ' . $hi . '. Standardize both boundaries against the sampling distribution:</p>
      <p>`z_lo = (' . $lo . ' - ' . $mu . ')/' . round($se, 4) . ' ~= ' . round($zlo, 3) . '` and `z_hi = (' . $hi . ' - ' . $mu . ')/' . round($se, 4) . ' ~= ' . round($zhi, 3) . '`</p>
      <p>`P(' . $lo . ' < bar(x) < ' . $hi . ') = P(Z < ' . round($zhi, 3) . ') - P(Z < ' . round($zlo, 3) . ') ~= ' . round($prob, 4) . '`</p>
      <p>The one substitution that matters: the mean and standard deviation you feed the calculator are the ones from the ORIGINAL distribution, with the sample size converting the second into a standard error. Forgetting to divide by `sqrt(n)` answers a question about one value rather than an average.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx has mean `mu = $mu` and standard deviation `sigma = $sigma`. A random sample of size `n = $n` is drawn.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the standard error of the mean? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the probability that the sample mean is between `$lo` and `$hi`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
