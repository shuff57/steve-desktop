// === NAME - DESCRIPTION: Compare Two Distributions by z-Score - equal z-scores from differently scaled normals ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Two distributions N(mu1, sigma1) and N(mu2, sigma2) with DIFFERENT parameters, and
// values x and y chosen so their z-scores are EQUAL. Parts: (a) numfunc - z for x
// (b) numfunc - z for y.
// Invariant: (a) = (b) exactly, and the raw values differ, on every seed.

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("the height of a 15- to 18-year-old male from Chile in 2009-2010, in cm", "the height of a 15- to 18-year-old male from Chile in 1984-1985, in cm", 170, 6.28, 160.58, 172.36, 6.34, 162.85, -1.5),
  array("the score on the math section of the SAT in one year", "the score on the math section of the SAT in a different year", 520, 115, 750, 514, 117, 748, 2),
  array("the weight of an 80 cm girl in the reference population, in kg", "the weight of an 80 cm girl in a second reference population, in kg", 10.2, 0.8, 11, 10.5, 0.8, 11.3, 1)
)
// [ctxX, ctxY, mu1, sigma1, x, mu2, sigma2, y, z]

$i = rand(0, 2)
$ctxX = $contexts[$i][0]
$ctxY = $contexts[$i][1]
$mu1 = $contexts[$i][2]
$sigma1 = $contexts[$i][3]
$x = $contexts[$i][4]
$mu2 = $contexts[$i][5]
$sigma2 = $contexts[$i][6]
$y = $contexts[$i][7]
$z = $contexts[$i][8]

$answer[0] = $z
$answer[1] = $z
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

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
      <p><span class="term-label">Part (a) &mdash; standardize x against its own distribution.</span> `z = (x - mu1)/sigma1 = (' . $x . ' - ' . $mu1 . ')/' . $sigma1 . ' = ' . $z . '`</p>
      <p><span class="term-label">Part (b) &mdash; standardize y against its own distribution.</span> `z = (y - mu2)/sigma2 = (' . $y . ' - ' . $mu2 . ')/' . $sigma2 . ' = ' . $z . '`</p>
      <p><span class="term-label">The point.</span> The raw values `' . $x . '` and `' . $y . '` are not comparable &mdash; the two distributions have different centers and different spreads. But once standardized, both come out to `z = ' . $z . '`: relative to the population each was drawn from, the two values sit at the same place. That is the whole reason z-scores exist.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Let `X` = $ctxX, with `X ~ N($mu1, $sigma1)`. Let `Y` = $ctxY, with `Y ~ N($mu2, $sigma2)`. A particular value is `x = $x` and a particular value is `y = $y`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the z-score of `x = $x`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the z-score of `y = $y`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
