// === NAME - DESCRIPTION: Normal Distribution Probabilities (Context) - For a normal model N(mu, sigma), compute P(X<a), P(X>b), and P(a<X<b) using standardization and the standard-normal CDF ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "numfunc")

// Fix mu, sigma per scenario; pick three thresholds; let normalcdf compute probabilities.
$cases = array(
  array("heights of adult males (in inches)", 70, 3, 66, 74, 68, 73),
  array("battery life of a phone model (in hours)", 18, 2.5, 15, 21, 16, 20),
  array("annual rainfall (in inches) in a region", 42, 8, 30, 55, 35, 50),
  array("checkout times at a store (in minutes)", 4.5, 1.2, 3, 6, 3.5, 5.5),
  array("section scores on a placement exam", 520, 110, 400, 680, 450, 650)
)
// [context, mu, sigma, a (for P(X<a)), b (for P(X>b)), lo (a<X<b), hi (a<X<b)]

$i = rand(0, count($cases)-1)
$ctx   = $cases[$i][0]
$mu    = $cases[$i][1]
$sigma = $cases[$i][2]
$a     = $cases[$i][3]
$b     = $cases[$i][4]
$lo    = $cases[$i][5]
$hi    = $cases[$i][6]

$za = ($a - $mu) / $sigma
$zb = ($b - $mu) / $sigma
$zlo = ($lo - $mu) / $sigma
$zhi = ($hi - $mu) / $sigma

$probA = normalcdf($za)
$probB = 1 - normalcdf($zb)
$probMid = normalcdf($zhi) - normalcdf($zlo)

$answer[0] = $probA
$answer[1] = $probB
$answer[2] = $probMid
$reltolerance[0] = 0.02
$reltolerance[1] = 0.02
$reltolerance[2] = 0.02
$abstolerance[0] = 0.003
$abstolerance[1] = 0.003
$abstolerance[2] = 0.003

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
      <p><b>Part a:</b> Standardize: `z = (' . $a . ' - ' . $mu . ') / ' . $sigma . ' &approx; ' . round($za, 3) . '`. Then `P(X < ' . $a . ') = P(Z < ' . round($za, 3) . ') &approx; ' . round($probA, 4) . '`.</p>
      <p><b>Part b:</b> Standardize: `z = (' . $b . ' - ' . $mu . ') / ' . $sigma . ' &approx; ' . round($zb, 3) . '`. Then `P(X > ' . $b . ') = 1 - P(Z < ' . round($zb, 3) . ') &approx; ' . round($probB, 4) . '`.</p>
      <p><b>Part c:</b> Standardize both: z_lo &approx; ' . round($zlo, 3) . ', z_hi &approx; ' . round($zhi, 3) . '. Then `P(' . $lo . ' < X < ' . $hi . ') = P(Z < z_hi) - P(Z < z_lo) &approx; ' . round($probMid, 4) . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A population of <b>$ctx</b> is normally distributed with mean `mu = $mu` and standard deviation `sigma = $sigma`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P(X < $a)`. (Round to 4 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(X > $b)`. (Round to 4 decimal places.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find `P($lo < X < $hi)`. (Round to 4 decimal places.) $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
