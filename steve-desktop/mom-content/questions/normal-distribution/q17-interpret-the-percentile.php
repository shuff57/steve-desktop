// === NAME - DESCRIPTION: Interpret the Percentile - the value, the interpretation sentence, and the complement ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// N(mu, sigma) and a percentile p. Parts: (a) numfunc - the value k
// (b) choices - the correct interpretation sentence (c) choices - the complementary reading.
// Invariant: (a) is mu + invnormalcdf(p) * sigma, (b) and (c) are constant, and the two
// sentences describe the same computation from opposite sides, on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "choices", "choices")

$contexts = array(
  array("the ages of smartphone users, in years", 36.9, 13.9, 0.30, "smartphone users in the 13 to 55+ age range", "years"),
  array("the diameters of mandarin oranges, in cm", 5.85, 0.24, 0.16, "mandarin oranges from this farm", "cm"),
  array("the scores on a college entrance exam, in points", 52, 11, 0.90, "students who took the exam", "points")
)
// [ctx, mu, sigma, p, population, unit]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$p = $contexts[$i][3]
$pop = $contexts[$i][4]
$unit = $contexts[$i][5]

$z = invnormalcdf($p)
$k = $mu + $z * $sigma

$pPct = $p * 100
$restPct = 100 - $pPct

$answer[0] = $k
$reltolerance[0] = 0.02
$abstolerance[0] = 0.5

$questions[1] = array(
  $pPct . "% of " . $pop . " are " . round($k, 2) . " " . $unit . " or less",
  round($k, 2) . "% of " . $pop . " are " . $pPct . " " . $unit . " or less",
  $pPct . " of " . $pop . " are " . round($k, 2) . " " . $unit . " or more"
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "The remaining " . $restPct . "% of " . $pop . " are more than " . round($k, 2) . " " . $unit,
  "The remaining " . $restPct . "% of " . $pop . " are less than " . round($k, 2) . " " . $unit,
  "Exactly " . $restPct . " of " . $pop . " are " . round($k, 2) . " " . $unit
)
$answer[2] = 0
$noshuffle[2] = "all"

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
      <p><span class="term-label">Part (a) &mdash; the value.</span> A percentile is an `invNorm` question: `z ~= ' . round($z, 3) . '`, so `k = mu + z*sigma = ' . $mu . ' + (' . round($z, 3) . ')(' . $sigma . ') ~= ' . round($k, 2) . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the interpretation sentence.</span> The ' . $pPct . 'th percentile is ' . round($k, 2) . ' ' . $unit . ': ' . $pPct . '% of ' . $pop . ' are ' . round($k, 2) . ' ' . $unit . ' or less.</p>
      <p><span class="term-label">Part (c) &mdash; the other side of the same cut.</span> The same value read the other way describes the rest: the remaining ' . $restPct . '% of ' . $pop . ' are more than ' . round($k, 2) . ' ' . $unit . '. Both sentences are true and describe the same computation.</p>
      <p>A percentile is a rank, not a score: being at the ' . $pPct . 'th percentile does not mean you got ' . $pPct . '% of something right. It means ' . $pPct . '% of the group is at or below you.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx are normally distributed with mean `mu = $mu` and standard deviation `sigma = $sigma`. Find the ' . $pPct . 'th percentile and interpret it.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the ' . $pPct . 'th percentile `k`. (Round to 1 decimal place.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which sentence correctly interprets the ' . $pPct . 'th percentile?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which sentence states the complementary reading of the same value?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
