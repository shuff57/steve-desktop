// === NAME - DESCRIPTION: Compute a z-Score from a Value - z = (x - mu) / sigma and its sign-and-size reading ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A value x from N(mu, sigma). Parts: (a) numfunc - z = (x - mu)/sigma
// (b) choices - the interpretation (|z| SDs right/left of the mean).
// Invariant: (a) equals the precomputed (x - mu)/sigma exactly, and (b)'s
// direction matches the sign of z on every seed.

$anstypes = array("numfunc", "choices")

$contexts = array(
  "the amount of weight lost in a month, in pounds",
  "the height of a 15- to 18-year-old male from Chile, in cm",
  "the score on a standardized test"
)
$mus = array(5, 170, 496)
$sigmas = array(2, 6.28, 114)
$xs = array(10, 160.58, 325)
$zs = array(2.5, -1.5, -1.5)
$dirs = array(0, 1, 1)

$i = rand(0, 2)
$ctx = $contexts[$i]
$mu = $mus[$i]
$sigma = $sigmas[$i]
$x = $xs[$i]
$z = $zs[$i]
$dir = $dirs[$i]

$answer[0] = $z
$abstolerance[0] = 0.005

$questions[1] = array(
  "The value is " . abs($z) . " standard deviations to the RIGHT of the mean",
  "The value is " . abs($z) . " standard deviations to the LEFT of the mean"
)
$answer[1] = $dir
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
      <p><span class="term-label">Part (a) &mdash; standardize.</span> `z = (x - mu)/sigma = (' . $x . ' - ' . $mu . ')/' . $sigma . ' = ' . $z . '`</p>
      <p><span class="term-label">Part (b) &mdash; read the sign and the size.</span> The numerator `x - mu` measures the distance from the center in the original units; dividing by `sigma` re-expresses it as a count of standard deviations. The sign says direction &mdash; positive is to the right of the mean, negative to the left &mdash; and the size says how far.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Suppose `X` = $ctx, and `X ~ N($mu, $sigma)`. A particular value is `x = $x`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the z-score of `x = $x`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does this z-score tell you about `x = $x`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
