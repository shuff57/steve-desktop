// === NAME - DESCRIPTION: Probability That a Sum Is Less Than a Value - P(Sigma x < c) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// mu, sigma, n, and a cutoff c. Parts: (a) numfunc - the mean of the sums
// (b) numfunc - P(Sigma x < c).
// Invariant: (a) = n*mu, (b) = normalcdf((c - n*mu)/(sqrt(n)*sigma)) to 4 decimals.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("an unknown distribution", 80, 12, 95, 7400),
  array("the distribution of results from a cholesterol test", 180, 20, 40, 7000),
  array("the amount of sugar in a can of soda", 39.01, 0.5, 100, 3900),
  array("an unknown distribution", 100, 100, 100, 9000)
)
// [ctx, mu, sigma, n, c]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$c = $contexts[$i][4]

$muSum = $n * $mu
$sigmaSum = sqrt($n) * $sigma
$z = ($c - $muSum) / $sigmaSum
$prob = normalcdf($z)

$answer[0] = $muSum
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
      <p><span class="term-label">Part (a) &mdash; the mean of the sums.</span> `mu_SigmaX = (n)(mu_X) = (' . $n . ')(' . $mu . ') = ' . $muSum . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the left tail.</span> "Less than ' . $c . '" shades the area below ' . $c . '. The standard deviation of the sums is `sigma_SigmaX = (sqrt(' . $n . '))(' . $sigma . ') ~= ' . round($sigmaSum, 4) . '`. Standardize:</p>
      <p>`z = (' . $c . ' - ' . $muSum . ')/' . round($sigmaSum, 4) . ' ~= ' . round($z, 3) . '`</p>
      <p>`P(Sigma x < ' . $c . ') = P(Z < ' . round($z, 3) . ') ~= ' . round($prob, 4) . '`</p>
      <p>The left tail is a direct `normalcdf` &mdash; no complement needed. Before you type anything, sketch the curve and shade what the sentence is asking for; the sketch is what stops you from swapping the bounds, and a swapped pair returns a negative area with no error message.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx with mean `mu = $mu` and standard deviation `sigma = $sigma`. A sample of size `n = $n` is drawn. Let `Sigma x` = the sum of the $n values.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the mean of `Sigma X`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(Sigma x < $c)`, the probability that the sum is less than $c. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
