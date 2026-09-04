// === NAME - DESCRIPTION: The Middle Percent - the two endpoints that enclose the middle c% ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// N(mu, sigma) and a middle percentage c. Parts: (a) numfunc - the lower endpoint k1
// (b) numfunc - the upper endpoint k2.
// Invariant: the tail area is (1 - c)/2 on each side, k1 = mu + invnormalcdf((1-c)/2) * sigma,
// k2 = mu + invnormalcdf(1 - (1-c)/2) * sigma, and k1 < k2, on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("the diameters of mandarin oranges, in cm", 5.85, 0.24, 0.40),
  array("the IQs of a population", 100, 15, 0.50),
  array("the scores on a college entrance exam, in points", 52, 11, 0.80),
  array("the time to find a parking space, in minutes", 5, 2, 0.90)
)
// [ctx, mu, sigma, c (middle percent as a decimal)]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$c = $contexts[$i][3]

$tail = (1 - $c) / 2
$zlo = invnormalcdf($tail)
$zhi = invnormalcdf(1 - $tail)
$k1 = $mu + $zlo * $sigma
$k2 = $mu + $zhi * $sigma

$cPct = $c * 100
$tailPct = $tail * 100

$answer[0] = $k1
$answer[1] = $k2
$reltolerance[0] = 0.02
$reltolerance[1] = 0.02
$abstolerance[0] = 0.5
$abstolerance[1] = 0.5

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
      <p><span class="term-label">Split the leftover area between the tails.</span> Outside the middle ' . $cPct . '% there is `1 - ' . $c . ' = ' . (1 - $c) . '` of the area, and the normal distribution is symmetric, so each tail carries `' . $tail . '` (' . $tailPct . '%).</p>
      <p><span class="term-label">Part (a): the lower endpoint.</span> The lower boundary is the ' . $tailPct . 'th percentile: `z ~= ' . round($zlo, 3) . '`, so `k1 = mu + z*sigma = ' . $mu . ' + (' . round($zlo, 3) . ')(' . $sigma . ') ~= ' . round($k1, 2) . '`.</p>
      <p><span class="term-label">Part (b): the upper endpoint.</span> The upper boundary is the ' . (100 - $tailPct) . 'th percentile: `z ~= ' . round($zhi, 3) . '`, so `k2 = mu + z*sigma = ' . $mu . ' + (' . round($zhi, 3) . ')(' . $sigma . ') ~= ' . round($k2, 2) . '`.</p>
      <p>Do this on paper the first few times rather than memorizing a formula: the same reasoning handles the middle 50%, the middle 90%, and the confidence intervals waiting in a later chapter.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx are normally distributed with mean `mu = $mu` and standard deviation `sigma = $sigma`. The middle $cPct% of the values lie between what two values?</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The lower value. (Round to 2 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The upper value. (Round to 2 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
