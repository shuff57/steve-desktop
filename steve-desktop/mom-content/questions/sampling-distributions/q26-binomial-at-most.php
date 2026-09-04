// === NAME - DESCRIPTION: Binomial At Most with the Continuity Correction - P(X <= a) via the corrected boundary ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A binomial scenario with n, p, and a (np > 5 and nq > 5). Parts: (a) numfunc - the corrected
// boundary a + 0.5 (b) numfunc - P(X <= a) by the normal approximation.
// Invariant: (a) = a + 0.5 exactly and (b) = normalcdf((a + 0.5 - mu)/sigma) to 4 decimals
// on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("favor a charter school for grades K through 5", 300, 0.53, 160),
  array("have a defective part in a shipment", 200, 0.10, 15),
  array("pass a certain exam", 400, 0.25, 120)
)
// [ctx, n, p, a]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$n = $contexts[$i][1]
$p = $contexts[$i][2]
$a = $contexts[$i][3]

$pPct = $p * 100

$mu = $n * $p
$sigma = sqrt($n * $p * (1 - $p))
$boundary = $a + 0.5
$z = ($boundary - $mu) / $sigma
$prob = normalcdf($z)

$answer[0] = $boundary
$abstolerance[0] = 0.005
$answer[1] = $prob
$reltolerance[1] = 0.02
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
      <p><span class="term-label">The setup.</span> `X ~ B(' . $n . ', ' . $p . ')` with `mu = np = ' . $mu . '` and `sigma = sqrt(npq) = ' . round($sigma, 4) . '`. Both `np` and `nq` are greater than 5, so the normal approximation is allowed.</p>
      <p><span class="term-label">Part (a): the continuity correction.</span> "At most ' . $a . '" includes ' . $a . ', so the bar over ' . $a . ' has to be inside the shaded region and the boundary moves outward by 0.5:</p>
      <p>`P(X <= ' . $a . ')` becomes `P(Y <= ' . $boundary . ')`.</p>
      <p><span class="term-label">Part (b): the probability.</span> Standardize: `z = (' . $boundary . ' - ' . $mu . ')/' . round($sigma, 4) . ' ~= ' . round($z, 3) . '`, so</p>
      <p>`P(X <= ' . $a . ') = P(Z < ' . round($z, 3) . ') ~= ' . round($prob, 4) . '`.</p>
      <p>The left tail is a direct normalcdf: no complement needed. Getting the direction of the half-unit backwards shifts the answer by roughly one bar\'s worth of probability, which is small but consistently wrong, so it is worth reading the inequality out loud before typing.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In a survey of `n = $n` people, $pPct% $ctx. Let `X` = the number who do, so `X ~ B($n, $p)`. Find the probability that at most $a $ctx, using the normal approximation with the continuity correction.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the corrected boundary? (The value you standardize.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(X <= $a)`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
