// === NAME - DESCRIPTION: The At-Least Percentile - invNorm needs the complement of a right-hand area ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// N(mu, sigma) and a right-hand percentage p. Parts: (a) numfunc - the value k with
// P(X >= k) = p (b) choices - the area you must feed invNorm (the complement 1 - p).
// Invariant: (a) is mu + invnormalcdf(1 - p) * sigma and (b) is constant on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "choices")

$contexts = array(
  array("the ages of smartphone users, in years", 36.9, 13.9, 0.40),
  array("the scores on a college entrance exam, in points", 52, 11, 0.30),
  array("the time to find a parking space, in minutes", 5, 2, 0.70),
  array("the recovery time from a surgical procedure, in days", 5.3, 2.1, 0.60)
)
// [ctx, mu, sigma, p (right-hand area)]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$p = $contexts[$i][3]

$leftArea = 1 - $p
$z = invnormalcdf($leftArea)
$k = $mu + $z * $sigma

$pPct = $p * 100
$leftPct = $leftArea * 100

$answer[0] = $k
$reltolerance[0] = 0.02
$abstolerance[0] = 0.5

$questions[1] = array(
  "The complement, " . $leftPct . "% &mdash; invNorm only accepts an area to the left",
  "The given " . $pPct . "% &mdash; the area to the right is what invNorm takes"
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
      <p><span class="term-label">Translate the words.</span> "At least" means `x >= k`, which is the area to the RIGHT of `k`. The problem hands you a right-hand area of ' . $pPct . '%, but `invNorm` only accepts an area to the left.</p>
      <p><span class="term-label">Part (b) &mdash; subtract first.</span> The area to the left is `1 - ' . $p . ' = ' . $leftArea . '` (' . $leftPct . '%). That is the number you type.</p>
      <p><span class="term-label">Part (a) &mdash; the value.</span> `z = invnormalcdf(' . $leftArea . ') ~= ' . round($z, 3) . '`, so `k = mu + z*sigma = ' . $mu . ' + (' . round($z, 3) . ')(' . $sigma . ') ~= ' . round($k, 2) . '`.</p>
      <p>Skipping the subtraction is the single most common error in this subsection, and it fails quietly: you still get a plausible-looking value back, just the wrong one. A sanity check: an area to the left below 0.5 has to return a value below the mean, and above 0.5 a value above it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx are normally distributed with mean `mu = $mu` and standard deviation `sigma = $sigma`. Find the value `k` such that ' . $pPct . '% of the values are <b>at least</b> `k`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `k`. (Round to 1 decimal place.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which area must you feed to `invNorm`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
