// === NAME - DESCRIPTION: Percentile of the Sampling Distribution - k for a sample mean, and its average-focused interpretation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// mu, sigma, n, and a percentile p. Parts: (a) numfunc - k = mu + invnormalcdf(p)*(sigma/sqrt(n))
// (b) choices - the correct interpretation (a statement about AVERAGES, not individuals).
// Invariant: (a) is the precomputed percentile, (b) is constant on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "choices")

$contexts = array(
  array("the mean age of tablet users, in years", 34, 15, 100, 0.95),
  array("the mean number of minutes for app engagement by a tablet user", 8.2, 1, 60, 0.90),
  array("the mean time to complete one review, in hours", 4, 1.2, 16, 0.95),
  array("the mean amount of weight lost in a month, in pounds", 5, 2, 25, 0.90)
)
// [ctx, mu, sigma, n, p]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$p = $contexts[$i][4]

$se = $sigma / sqrt($n)
$z = invnormalcdf($p)
$k = $mu + $z * $se

$pPct = $p * 100

$answer[0] = $k
$reltolerance[0] = 0.02
$abstolerance[0] = 0.5

$questions[1] = array(
  $pPct . "% of samples of size " . $n . " have a sample mean below " . round($k, 2) . " &mdash; a statement about averages, not individuals",
  $pPct . "% of individual values are below " . round($k, 2),
  "The sample mean is " . round($k, 2) . " with probability " . $pPct . "%"
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
      <p><span class="term-label">Part (a) &mdash; the percentile.</span> A percentile question runs the other way from a probability question: you are handed an area and asked for the value on the axis that cuts it off. The command is invNorm, and the only adjustment for sample means is that the standard deviation you type is the standard error:</p>
      <p>`SE = sigma/sqrt(n) = ' . $sigma . '/sqrt(' . $n . ') = ' . round($se, 4) . '`</p>
      <p>`k = mu + invnormalcdf(' . $p . ') * SE = ' . $mu . ' + (' . round($z, 3) . ')(' . round($se, 4) . ') ~= ' . round($k, 2) . '`</p>
      <p><span class="term-label">Part (b) &mdash; the interpretation.</span> A percentile of the SAMPLING distribution is a statement about averages, not about individuals. When you find that the ' . $pPct . 'th percentile of the sample mean is ' . round($k, 2) . ', you have not said that ' . $pPct . '% of users are younger than that. You have said that ' . $pPct . '% of SAMPLES of size ' . $n . ' would produce an average below ' . round($k, 2) . ' &mdash; a much narrower claim, because averages hug the center far more tightly than individuals do. That one word &mdash; "average" &mdash; is usually the difference between a correct interpretation and a plausible-sounding wrong one.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx, with `mu = $mu` and `sigma = $sigma`. A random sample of size `n = $n` is taken.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the {$pPct}th percentile for the sample mean. (Round to 1 decimal place.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which sentence correctly interprets the {$pPct}th percentile?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
