// === NAME - DESCRIPTION: Probability That a Sum Falls Between Two Values - P(lo < Sigma x < hi) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// mu, sigma, n, and cutoffs lo and hi. Parts: (a) numfunc - the standard deviation of the sums
// (b) numfunc - P(lo < Sigma x < hi).
// Invariant: (a) = sqrt(n)*sigma, (b) = normalcdf((hi - n*mu)/(sqrt(n)*sigma)) - normalcdf((lo - n*mu)/(sqrt(n)*sigma)) to 4 decimals.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("the mean age of iPad users, in years", 35, 10, 39, 1400, 1500),
  array("the mean age of iPad users, in years", 34, 15, 50, 1500, 1800),
  array("an unknown distribution", 80, 12, 95, 7400, 7650)
)
// [ctx, mu, sigma, n, lo, hi]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$lo = $contexts[$i][4]
$hi = $contexts[$i][5]

$muSum = $n * $mu
$sigmaSum = sqrt($n) * $sigma
$zlo = ($lo - $muSum) / $sigmaSum
$zhi = ($hi - $muSum) / $sigmaSum
$prob = normalcdf($zhi) - normalcdf($zlo)

$answer[0] = $sigmaSum
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
      <p><span class="term-label">Part (a): the standard deviation of the sums.</span> `sigma_SigmaX = (sqrt(n))(sigma_X) = (sqrt(' . $n . '))(' . $sigma . ') ~= ' . round($sigmaSum, 4) . '`.</p>
      <p><span class="term-label">Part (b): the strip.</span> The sum has mean `mu_SigmaX = (' . $n . ')(' . $mu . ') = ' . $muSum . '`. Standardize both edges:</p>
      <p>`z_lo = (' . $lo . ' - ' . $muSum . ')/' . round($sigmaSum, 4) . ' ~= ' . round($zlo, 3) . '` and `z_hi = (' . $hi . ' - ' . $muSum . ')/' . round($sigmaSum, 4) . ' ~= ' . round($zhi, 3) . '`</p>
      <p>`P(' . $lo . ' < Sigma x < ' . $hi . ') = P(Z < ' . round($zhi, 3) . ') - P(Z < ' . round($zlo, 3) . ') ~= ' . round($prob, 4) . '`</p>
      <p>A between-question is the easiest of the three shapes: the thinking happens before you type, deciding which number is the lower edge of the strip and which is the upper, because a swapped pair returns a negative area with no warning.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx with mean `mu = $mu` and standard deviation `sigma = $sigma`. A sample of size `n = $n` is drawn. Let `Sigma x` = the sum of the $n values.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the standard deviation of `Sigma X`? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P($lo < Sigma x < $hi)`, the probability that the sum is between $lo and $hi. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
