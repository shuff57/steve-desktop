// === NAME - DESCRIPTION: The Complement Tail - P(X > x) = 1 - P(X < x) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// N(mu, sigma) with a cutoff x. Parts: (a) numfunc - P(X < x)
// (b) numfunc - P(X > x).
// Invariant: (a) + (b) = 1 exactly on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("the golf scores for a school team", 68, 3, 65),
  array("the final exam scores in a statistics class", 63, 5, 65),
  array("the ages of smartphone users, in years", 36.9, 13.9, 27)
)
// [ctx, mu, sigma, x]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$x = $contexts[$i][3]

$z = ($x - $mu) / $sigma
$probA = normalcdf($z)
$probB = 1 - $probA

$answer[0] = $probA
$answer[1] = $probB
$reltolerance[0] = 0.02
$reltolerance[1] = 0.02
$abstolerance[0] = 0.003
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
      <p><span class="term-label">Part (a) &mdash; the left tail.</span> Standardize: `z = (x - mu)/sigma = (' . $x . ' - ' . $mu . ')/' . $sigma . ' ~= ' . round($z, 3) . '`. Then `P(X < ' . $x . ') = P(Z < ' . round($z, 3) . ') ~= ' . round($probA, 4) . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the right tail is whatever is left over.</span> The two areas fill the curve, so `P(X > ' . $x . ') = 1 - P(X < ' . $x . ') = 1 - ' . round($probA, 4) . ' = ' . round($probB, 4) . '`.</p>
      <p>You never have to compute a right tail directly &mdash; drawing the line through `x` slices the curve into exactly two pieces, and whichever piece the calculator hands you, subtracting from 1 gets you the other.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx are normally distributed with mean `mu = $mu` and standard deviation `sigma = $sigma`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P(X < $x)`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(X > $x)`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
