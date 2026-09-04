// === NAME - DESCRIPTION: The Empirical Rule Bands - the 68%, 95%, and 99.7% intervals of N(mu, sigma) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// N(mu, sigma) with clean parameters. Parts: (a) the 68% band (b) the 95% band
// (c) the 99.7% band - each part takes TWO numfunc boxes, a lower and an upper, so
// six anstypes for three lettered parts. One entry per ANSWER BOX, not per part:
// three entries rendered only boxes 1-3, so (b) lost its upper box and (c) lost both,
// and the missing parts could not be graded, so it still scored full marks.
// Invariant: the bands are mu +/- k*sigma for k = 1, 2, 3 exactly on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc", "numfunc", "numfunc")

$contexts = array(
  "the scores on a college entrance exam, in points",
  "the heights of adult men, in inches",
  "the daily commute time, in minutes"
)
$mus = array(52, 70, 28)
$sigmas = array(11, 3, 6)

$i = rand(0, 2)
$ctx = $contexts[$i]
$mu = $mus[$i]
$sigma = $sigmas[$i]

$lo1 = $mu - $sigma
$hi1 = $mu + $sigma
$lo2 = $mu - 2*$sigma
$hi2 = $mu + 2*$sigma
$lo3 = $mu - 3*$sigma
$hi3 = $mu + 3*$sigma

$answer[0] = $lo1
$answer[1] = $hi1
$answer[2] = $lo2
$answer[3] = $hi2
$answer[4] = $lo3
$answer[5] = $hi3
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005
$abstolerance[3] = 0.005
$abstolerance[4] = 0.005
$abstolerance[5] = 0.005

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
      <p><span class="term-label">The rule.</span> For a normal distribution, about 68% of the values lie within one standard deviation of the mean, 95% within two, and 99.7% within three. Each band is centered at the mean.</p>
      <p><span class="term-label">Part (a): the 68% band.</span> `mu - sigma = ' . $mu . ' - ' . $sigma . ' = ' . $lo1 . '` and `mu + sigma = ' . $mu . ' + ' . $sigma . ' = ' . $hi1 . '`</p>
      <p><span class="term-label">Part (b): the 95% band.</span> `mu - 2sigma = ' . $mu . ' - ' . (2*$sigma) . ' = ' . $lo2 . '` and `mu + 2sigma = ' . $mu . ' + ' . (2*$sigma) . ' = ' . $hi2 . '`</p>
      <p><span class="term-label">Part (c): the 99.7% band.</span> `mu - 3sigma = ' . $mu . ' - ' . (3*$sigma) . ' = ' . $lo3 . '` and `mu + 3sigma = ' . $mu . ' + ' . (3*$sigma) . ' = ' . $hi3 . '`</p>
      <p>The percentages are not evenly spaced: the curve is tallest in the middle, so most of the area is already accounted for before you get far from the mean.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The distribution of $ctx is approximately normal with mean `mu = $mu` and standard deviation `sigma = $sigma`. Use the empirical rule (68-95-99.7).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> About 68% of the values lie between what two values? (Enter the lower value.) $answerbox[0] &nbsp; (Enter the upper value.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> About 95% of the values lie between what two values? (Enter the lower value.) $answerbox[2] &nbsp; (Enter the upper value.) $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> About 99.7% of the values lie between what two values? (Enter the lower value.) $answerbox[4] &nbsp; (Enter the upper value.) $answerbox[5]
  </div>
</div>

// === ANSWER ===

$solutionguide
