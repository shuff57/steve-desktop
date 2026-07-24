// === NAME - DESCRIPTION: Empirical Rule (68-95-99.7) - Apply the empirical rule to estimate percentages within k SDs of the mean and a tail percentage on one side ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Scenarios: bell-shaped distribution with mean mu, SD sigma. Ask:
//  a) approx % of data within 1 SD, 2 SDs, or 3 SDs (depending on k)
//  b) approx % below mu - k*sigma (one-sided tail)
//  c) approx % between mu and mu + k*sigma (centered upper half)
// Pre-compute for k=1,2,3.

// Encode each scenario with:
//  ctx, mu, sigma, k (number of SDs)
$cases = array(
  array("test scores", 75, 8, 1),  // 68% within, ~16% below mu-sigma, ~34% between mu and mu+sigma
  array("heights of adult men (in)", 70, 3, 2), // 95% within, ~2.5% below mu-2sigma, ~47.5% between mu and mu+2sigma
  array("daily steps", 8000, 1500, 3), // 99.7% within, ~0.15% below mu-3sigma, ~49.85% between mu and mu+3sigma
  array("baby weights (lb)", 7.5, 1.0, 1),
  array("commute times (min)", 28, 6, 2)
)

$i = rand(0, count($cases)-1)
$ctx   = $cases[$i][0]
$mu    = $cases[$i][1]
$sigma = $cases[$i][2]
$k     = $cases[$i][3]

// Map k to expected percentages
$withinPct = array(68, 95, 99.7)
$tailLowPct = array(16, 2.5, 0.15)
$halfPct = array(34, 47.5, 49.85)

$within = $withinPct[$k - 1]
$tailLow = $tailLowPct[$k - 1]
$half = $halfPct[$k - 1]

$lowEdge = $mu - $k * $sigma
$highEdge = $mu + $k * $sigma

// Choices: a) percent within k SDs of the mean
$choices[0] = array("about 68%", "about 95%", "about 99.7%")
$answer[0] = $k - 1

// b) percent below mu - k*sigma
$choices[1] = array("about 16%", "about 2.5%", "about 0.15%")
$answer[1] = $k - 1

// c) percent between mu and mu + k*sigma
$choices[2] = array("about 34%", "about 47.5%", "about 49.85%")
$answer[2] = $k - 1

$noshuffle[0] = "all"
$noshuffle[1] = "all"
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
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>The empirical rule says about <b>68% within 1 SD</b>, <b>95% within 2 SD</b>, and <b>99.7% within 3 SD</b> for a bell-shaped distribution.</p>
      <p><b>Part a:</b> About ' . $within . '% of the data fall within ' . $k . ' SD of the mean (i.e., between ' . $lowEdge . ' and ' . $highEdge . ').</p>
      <p><b>Part b:</b> The remaining ' . (100 - $within) . '% is split equally between the two tails, so about ' . $tailLow . '% lie below ' . $lowEdge . '.</p>
      <p><b>Part c:</b> Half of ' . $within . '% lies between the mean and ' . $highEdge . ', so about ' . $half . '%.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A roughly bell-shaped distribution of <b>$ctx</b> has mean `mu = $mu` and standard deviation `sigma = $sigma`. Use the <b>empirical rule</b> (68-95-99.7).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Approximately what percent of the data fall within <b>$k</b> standard deviations of the mean (between $lowEdge and $highEdge)? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Approximately what percent fall <b>below</b> $lowEdge? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Approximately what percent fall <b>between the mean and $highEdge</b>? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
