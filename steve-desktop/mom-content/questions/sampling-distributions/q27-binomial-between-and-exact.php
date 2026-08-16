// === NAME - DESCRIPTION: Binomial Between and Exact with the Continuity Correction - strict strips and the half-unit ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A binomial scenario with n, p, a, b, and c (a < b, np > 5 and nq > 5).
// Parts: (a) numfunc - P(a < X < b) by the normal approximation (strict both ends)
// (b) numfunc - P(X = c) as the strip between c - 0.5 and c + 0.5 (c) choices - why the
// correction matters.
// Invariant: (a) and (b) are the precomputed standardized strip areas, and (c) is constant.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices")

$contexts = array(
  array("favor a charter school for grades K through 5", 300, 0.53, 140, 160, 150),
  array("favor the incumbent for mayor", 500, 0.46, 220, 240, 230),
  array("pass a certain exam", 400, 0.25, 90, 110, 100)
)
// [ctx, n, p, a, b, c]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$n = $contexts[$i][1]
$p = $contexts[$i][2]
$a = $contexts[$i][3]
$b = $contexts[$i][4]
$c = $contexts[$i][5]

$pPct = $p * 100

$mu = $n * $p
$sigma = sqrt($n * $p * (1 - $p))

$loBound = $a + 0.5
$hiBound = $b - 0.5
$zlo = ($loBound - $mu) / $sigma
$zhi = ($hiBound - $mu) / $sigma
$probBetween = normalcdf($zhi) - normalcdf($zlo)

$zLoExact = ($c - 0.5 - $mu) / $sigma
$zHiExact = ($c + 0.5 - $mu) / $sigma
$probExact = normalcdf($zHiExact) - normalcdf($zLoExact)

$answer[0] = $probBetween
$reltolerance[0] = 0.02
$abstolerance[0] = 0.003
$answer[1] = $probExact
$reltolerance[1] = 0.02
$abstolerance[1] = 0.003

$questions[2] = array(
  "A binomial bar has real width on the number line, so the boundary slides half a unit to cover it — without the correction the answer is wrong by about half a bar's worth of probability every time",
  "The correction makes the normal curve taller",
  "The correction changes which bars belong in the event",
  "The correction is only needed when n is smaller than 30"
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
      <p><span class="term-label">The setup.</span> `X ~ B(' . $n . ', ' . $p . ')` with `mu = np = ' . $mu . '` and `sigma = sqrt(npq) = ' . round($sigma, 4) . '`.</p>
      <p><span class="term-label">Part (a) &mdash; the strict between.</span> "Between ' . $a . ' and ' . $b . '" excludes both endpoints, so the lower boundary moves IN by 0.5 and the upper boundary moves IN by 0.5:</p>
      <p>`P(' . $a . ' < X < ' . $b . ')` becomes `P(' . $loBound . ' < Y < ' . $hiBound . ')`, giving `z_lo ~= ' . round($zlo, 3) . '` and `z_hi ~= ' . round($zhi, 3) . '`, so the probability is `~= ' . round($probBetween, 4) . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the exact value.</span> An exact value becomes the strip half a unit wide on each side:</p>
      <p>`P(X = ' . $c . ')` becomes `P(' . ($c - 0.5) . ' < Y < ' . ($c + 0.5) . ')`, giving `~= ' . round($probExact, 4) . '` — the only reason a continuous distribution can answer an "exactly" question at all.</p>
      <p><span class="term-label">Part (c) &mdash; why the correction matters.</span> The bar sitting over the whole number ' . $c . ' stretches from ' . ($c - 0.5) . ' to ' . ($c + 0.5) . ', so if that outcome belongs in your event, the shaded region under the curve has to cover that whole bar. The error without the correction is never dramatic, which is exactly what makes it worth guarding against: the answer still looks reasonable, still lands in the right decimal place, and is simply wrong by about half a bar\'s worth of probability every time.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In a survey of `n = $n` people, $pPct% $ctx. Let `X` = the number who do, so `X ~ B($n, $p)`. Use the normal approximation with the continuity correction.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P($a < X < $b)`, the probability that the count is between $a and $b (strict, both ends excluded). (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(X = $c)`, the probability that exactly $c $ctx. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why does the continuity correction matter?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
