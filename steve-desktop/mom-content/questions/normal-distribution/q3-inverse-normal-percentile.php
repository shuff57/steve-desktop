// === NAME - DESCRIPTION: Inverse Normal - Find the cutoff value x for a given percentile of N(mu, sigma), and find both endpoints of a centered middle-p% interval ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "numfunc")

// Scenario provides mu, sigma, a one-tail percentile p, and a centered middle percent c%.
$cases = array(
  array("annual rainfall (in)", 38, 6, 0.90, 0.80),    // 90th percentile, middle 80%
  array("battery lifetimes (hrs)", 220, 25, 0.95, 0.90),
  array("scores on a state exam", 510, 80, 0.85, 0.95),
  array("daily commute times (min)", 28, 6, 0.75, 0.50),
  array("baby weights (lb)", 7.5, 1.0, 0.99, 0.95)
)
// [context, mu, sigma, p (one-tail percentile), c (centered %)]

$i = rand(0, count($cases)-1)
$ctx   = $cases[$i][0]
$mu    = $cases[$i][1]
$sigma = $cases[$i][2]
$p     = $cases[$i][3]
$c     = $cases[$i][4]

$zp = invnormalcdf($p)
$xp = $mu + $zp * $sigma

$tail = (1 - $c) / 2
$zlo = invnormalcdf($tail)
$zhi = invnormalcdf(1 - $tail)
$xlo = $mu + $zlo * $sigma
$xhi = $mu + $zhi * $sigma

$pPct = $p * 100
$cPct = $c * 100

$answer[0] = $xp
$answer[1] = $xlo
$answer[2] = $xhi
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
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Part a:</b> Look up the `z` that has area ' . $p . ' below it: `z ~~ ' . round($zp, 3) . '`. Then `x = mu + z*sigma = ' . $mu . ' + (' . round($zp, 3) . ')(' . $sigma . ') ~~ ' . round($xp, 2) . '`.</p>
      <p><b>Part b:</b> The middle ' . $cPct . '% leaves tail ' . round($tail, 4) . ' on each side. `z_(lo) ~~ ' . round($zlo, 3) . '`, so `x_(lo) ~~ ' . round($xlo, 2) . '`.</p>
      <p><b>Part c:</b> `z_(hi) ~~ ' . round($zhi, 3) . '`, so `x_(hi) ~~ ' . round($xhi, 2) . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A population of <b>$ctx</b> is normally distributed with mean `mu = $mu` and standard deviation `sigma = $sigma`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the value of x that is the <b>$pPct th percentile</b>. (Round to 2 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>lower endpoint</b> that, together with the upper endpoint in part (c), encloses the middle <b>$cPct%</b> of the distribution. (Round to 2 dp.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the <b>upper endpoint</b> of the middle <b>$cPct%</b> of the distribution. (Round to 2 dp.) $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
