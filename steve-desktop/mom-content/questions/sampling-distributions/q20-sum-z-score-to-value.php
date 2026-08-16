// === NAME - DESCRIPTION: Recover a Sum from a z-Score - s = n*mu + z*sqrt(n)*sigma and its sign-and-size reading ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A z-score z for a sum from mu, sigma, n. Parts: (a) numfunc - s = n*mu + z*sqrt(n)*sigma
// (b) choices - the interpretation (|z| SDs of the sums above/below the mean of the sums).
// Invariant: (a) equals the precomputed sum exactly, (b) matches the sign of z on every seed.

$anstypes = array("numfunc", "choices")

$contexts = array(
  array("an unknown distribution", 90, 15, 80, 1.5),
  array("an unknown distribution", 80, 12, 95, -1.5),
  array("an unknown distribution", 12, 1, 25, -2.5)
)
// [ctx, mu, sigma, n, z]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$z = $contexts[$i][4]

$s = round($n * $mu + $z * sqrt($n) * $sigma, 1)

$answer[0] = $s
$abstolerance[0] = 0.005

$questions[1] = array(
  "The sum sits " . abs($z) . " standard deviations of the sums ABOVE the mean of the sums",
  "The sum sits " . abs($z) . " standard deviations of the sums BELOW the mean of the sums"
)
$answer[1] = ($z > 0) ? 0 : 1
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
      <p><span class="term-label">Part (a) &mdash; run the z-score backwards.</span> The z-score equation, solved for the sum, turns a location back into a total:</p>
      <p>`Sigma x = (n)(mu_X) + (z)(sqrt(n))(sigma_X) = (' . $n . ')(' . $mu . ') + (' . $z . ')(sqrt(' . $n . '))(' . $sigma . ') = ' . $s . '`</p>
      <p><span class="term-label">Part (b) &mdash; read the sign and the size.</span> A positive z-score puts the sum above the mean of the sums, a negative one below, and the size says how many standard deviations of the sums away. Both forms of the equation come up in the same problem often enough that it is worth writing them side by side once.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx with mean `mu = $mu` and standard deviation `sigma = $sigma`. A sample of size `n = $n` is drawn. A particular sample has a sum with z-score `z = $z`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the sum `Sigma x`? (Round to 1 decimal place.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does this z-score tell you about the sum?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
