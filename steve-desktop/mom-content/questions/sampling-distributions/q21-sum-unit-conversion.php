// === NAME - DESCRIPTION: Unit Conversion for a Sum - minutes versus hours, and why it matters ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A mean in minutes and a total in hours. Parts: (a) numfunc - P(Sigma x < 10 hours) computed
// from the converted cutoff (b) choices - why the unit conversion matters.
// Invariant: (a) is computed from the converted cutoff, (b) is constant on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "choices")

$contexts = array(
  array("the mean number of minutes for app engagement by a tablet user", 8.2, 1, 70, 600),
  array("the mean number of minutes for app engagement by a tablet user", 8.2, 1, 60, 480),
  array("the mean length of a song in a music collection, in minutes", 3, 0.5, 40, 115)
)
// [ctx, mu, sigma, n, cutoffMinutes]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$cut = $contexts[$i][4]

$muSum = $n * $mu
$sigmaSum = sqrt($n) * $sigma
$z = ($cut - $muSum) / $sigmaSum
$prob = normalcdf($z)

$answer[0] = $prob
$reltolerance[0] = 0.02
$abstolerance[0] = 0.003

$questions[1] = array(
  "Converting everything to one unit before touching the calculator is the arithmetic that is trivial to forget, and the answer must carry the right unit",
  "The calculator cannot handle numbers larger than 1000",
  "Hours and minutes are the same unit, so no conversion is needed",
  "The conversion only matters for sums, never for means"
)
$answer[1] = 0
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
      <p><span class="term-label">Convert to one unit first.</span> The cutoff of ten hours is ' . $cut . ' minutes. The sum has mean `mu_SigmaX = (' . $n . ')(' . $mu . ') = ' . $muSum . '` minutes and standard deviation `sigma_SigmaX = (sqrt(' . $n . '))(' . $sigma . ') ~= ' . round($sigmaSum, 4) . '` minutes.</p>
      <p><span class="term-label">Part (a): the probability.</span> Standardize: `z = (' . $cut . ' - ' . $muSum . ')/' . round($sigmaSum, 4) . ' ~= ' . round($z, 3) . '`, so `P(Sigma x < ' . $cut . ') = P(Z < ' . round($z, 3) . ') ~= ' . round($prob, 4) . '`.</p>
      <p><span class="term-label">Part (b): why the conversion matters.</span> A problem hands you a mean in minutes and then asks about "ten hours". The arithmetic is trivial but forgetting it is not: convert everything to one unit before you touch the calculator, and state which unit your answer is in when you write it down.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx, with `mu = $mu` minutes and `sigma = $sigma` minute. A sample of size `n = $n` is taken. Let `Sigma x` = the total of the $n values, in minutes.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the probability that the total is less than 10 hours. (Convert to minutes first; round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Why does the unit conversion matter?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
