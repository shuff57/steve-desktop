// === NAME - DESCRIPTION: Percentile of the Sum - k for a sum, and its interpretation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// mu, sigma, n, and a percentile p. Parts: (a) numfunc - k = n*mu + invnormalcdf(p)*sqrt(n)*sigma
// (b) choices - the interpretation (p% of the sums of size n are at or below k).
// Invariant: (a) is the precomputed percentile, (b) is constant on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "choices")

$contexts = array(
  array("the mean age of iPad users, in years", 35, 10, 39, 0.90),
  array("the mean age of iPad users, in years", 34, 15, 50, 0.80),
  array("the amount of sugar in a can of soda", 39.01, 0.5, 100, 0.95)
)
// [ctx, mu, sigma, n, p]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$p = $contexts[$i][4]

$muSum = $n * $mu
$sigmaSum = sqrt($n) * $sigma
$z = invnormalcdf($p)
$k = $muSum + $z * $sigmaSum

$pPct = $p * 100

$answer[0] = $k
$reltolerance[0] = 0.02
$abstolerance[0] = 0.5

$questions[1] = array(
  $pPct . "% of the sums of size " . $n . " are at or below " . round($k, 1),
  $pPct . "% of individual values are at or below " . round($k, 1),
  "The sum is " . round($k, 1) . " with probability " . $pPct . "%"
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">Part (a) &mdash; the percentile.</span> The other direction gives you an area and asks for the total that cuts it off. The sum has mean `mu_SigmaX = (' . $n . ')(' . $mu . ') = ' . $muSum . '` and standard deviation `sigma_SigmaX = (sqrt(' . $n . '))(' . $sigma . ') ~= ' . round($sigmaSum, 4) . '`:</p>
      <p>`k = mu_SigmaX + invnormalcdf(' . $p . ') * sigma_SigmaX = ' . $muSum . ' + (' . round($z, 3) . ')(' . round($sigmaSum, 4) . ') ~= ' . round($k, 1) . '`</p>
      <p><span class="term-label">Part (b) &mdash; the interpretation.</span> The ' . $pPct . 'th percentile of the sums is the total with ' . $pPct . '% of all possible totals of size ' . $n . ' at or below it. The answer carries the original units &mdash; years, minutes, dollars &mdash; and `invNorm` always wants the area to the LEFT. Getting an answer of 0.83 when the question asked for a number of years is the clearest possible sign you reached for the wrong command.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx with mean `mu = $mu` and standard deviation `sigma = $sigma`. A sample of size `n = $n` is drawn. Let `Sigma x` = the sum of the $n values.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the ' . $pPct . 'th percentile for the sum. (Round to 1 decimal place.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which sentence correctly interprets the ' . $pPct . 'th percentile?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
