// === NAME - DESCRIPTION: Sum Notation - the mean and standard deviation of Sigma X ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// X ~ N(mu, sigma) and a sample of size n. Parts: (a) numfunc - the mean of the sums n*mu
// (b) numfunc - the standard deviation of the sums sqrt(n)*sigma (c) choices - why the mean
// scales by n but the spread only by sqrt(n).
// Invariant: (a) = n*mu exactly, (b) = sqrt(n)*sigma exactly, (c) is constant on every seed.

$anstypes = array("numfunc", "numfunc", "choices")

$contexts = array(
  array("an unknown distribution", 20, 4, 36),
  array("the mean age of iPad users, in years", 35, 10, 39),
  array("the amount of sugar in a can of soda", 39.01, 0.5, 100),
  array("an unknown distribution", 12, 1, 25)
)
// [ctx, mu, sigma, n]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]

$muSum = $n * $mu
$sigmaSum = sqrt($n) * $sigma

$answer[0] = $muSum
$abstolerance[0] = 0.005
$answer[1] = $sigmaSum
$abstolerance[1] = 0.005

$questions[2] = array(
  "Adding more values pushes the total up in a straight line, but high draws and low draws partly cancel each other on the way to the total",
  "The sum is divided by the sample size before it is standardized",
  "The standard deviation of the sum equals the population standard deviation"
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
      <p><span class="term-label">Part (a): the mean of the sums.</span> The mean multiplies by `n`: `mu_SigmaX = (n)(mu_X) = (' . $n . ')(' . $mu . ') = ' . $muSum . '`.</p>
      <p><span class="term-label">Part (b): the standard deviation of the sums.</span> The standard deviation multiplies by `sqrt(n)`, NOT by `n`: `sigma_SigmaX = (sqrt(n))(sigma_X) = (sqrt(' . $n . '))(' . $sigma . ') = ' . round($sigmaSum, 4) . '`.</p>
      <p><span class="term-label">Part (c): why the two scale differently.</span> Adding more values pushes the total up in a straight line, but it spreads the total out much more slowly than that, because high draws and low draws partly cancel each other on the way to the total. Ten shopping trips cost about ten times one trip, but the ups and downs do not all line up, a cheap week offsets an expensive one, so the spread only grows by `sqrt(10) ~= 3.16`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx with mean `mu = $mu` and standard deviation `sigma = $sigma`. A sample of size `n = $n` is drawn. Let `Sigma x` = the sum of the $n values.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the mean of `Sigma X`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the standard deviation of `Sigma X`? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why does the mean multiply by `n` but the standard deviation only by `sqrt(n)`?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
