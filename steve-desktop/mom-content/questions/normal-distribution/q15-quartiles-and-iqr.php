// === NAME - DESCRIPTION: Quartiles and the IQR of a Normal Distribution - Q1, Q3, and Q3 - Q1 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// N(mu, sigma). Parts: (a) numfunc - Q1 = mu + invnormalcdf(0.25) * sigma
// (b) numfunc - Q3 = mu + invnormalcdf(0.75) * sigma (c) numfunc - the IQR = Q3 - Q1.
// Invariant: (a) < (b), and (c) = (b) - (a) exactly, on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "numfunc")

$contexts = array(
  array("the scores on an exam, in points", 81, 15),
  array("the ages of smartphone users, in years", 36.9, 13.9),
  array("the time to find a parking space, in minutes", 5, 2),
  array("the recovery time from a surgical procedure, in days", 5.3, 2.1)
)
// [ctx, mu, sigma]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]

$z1 = invnormalcdf(0.25)
$z3 = invnormalcdf(0.75)
$q1 = $mu + $z1 * $sigma
$q3 = $mu + $z3 * $sigma
$iqr = $q3 - $q1

$answer[0] = $q1
$answer[1] = $q3
$answer[2] = $iqr
$reltolerance[0] = 0.02
$reltolerance[1] = 0.02
$reltolerance[2] = 0.02
$abstolerance[0] = 0.5
$abstolerance[1] = 0.5
$abstolerance[2] = 0.5

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
      <p><span class="term-label">Quartiles are percentiles in disguise.</span> Q1 is the 25th percentile and Q3 is the 75th, so each is an invNorm call.</p>
      <p><span class="term-label">Part (a): Q1.</span> `z ~= ' . round($z1, 3) . '`, so `Q1 = mu + z*sigma = ' . $mu . ' + (' . round($z1, 3) . ')(' . $sigma . ') ~= ' . round($q1, 2) . '`.</p>
      <p><span class="term-label">Part (b): Q3.</span> `z ~= ' . round($z3, 3) . '`, so `Q3 = mu + z*sigma = ' . $mu . ' + (' . round($z3, 3) . ')(' . $sigma . ') ~= ' . round($q3, 2) . '`.</p>
      <p><span class="term-label">Part (c): the IQR.</span> `IQR = Q3 - Q1 = ' . round($q3, 2) . ' - ' . round($q1, 2) . ' ~= ' . round($iqr, 2) . '`.</p>
      <p>The IQR is a width, not a location: Q1 and Q3 are places on the axis, and the distance between them is a spread.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx are normally distributed with mean `mu = $mu` and standard deviation `sigma = $sigma`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the first quartile `Q1`. (Round to 1 decimal place.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the third quartile `Q3`. (Round to 1 decimal place.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the interquartile range `IQR = Q3 - Q1`. (Round to 1 decimal place.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
