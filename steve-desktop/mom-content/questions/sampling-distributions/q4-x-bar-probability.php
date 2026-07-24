// === NAME - DESCRIPTION: Sampling distribution of x-bar - compute the mean, standard error, and a less-than or greater-than probability for a sample mean ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number","number","number")

// Each scenario gives population mean mu, population standard deviation sigma, sample size n,
// and a cutoff c. Conditions for a normal sampling distribution are pre-checked
// (population stated as normal, or n >= 30). Direction (< vs >) is randomized.

// Scenario 0: SAT scores,         mu=510, sigma=110, n=36, c=540, pop normal
// Scenario 1: commute times,      mu=28,  sigma=8,   n=50, c=30,  CLT (skewed pop)
// Scenario 2: light-bulb life,    mu=1200,sigma=100, n=25, c=1180, pop normal
// Scenario 3: pizza delivery,     mu=22,  sigma=5,   n=40, c=23,  CLT (skewed pop)
// Scenario 4: protein per serving,mu=18,  sigma=3,   n=32, c=17.4, CLT
// Scenario 5: heart rate,         mu=72,  sigma=10,  n=49, c=75,  CLT

$ctxs = array(
  "SAT math scores in a population are approximately normally distributed with mean `mu = 510` and standard deviation `sigma = 110`. A random sample of <b>`n = 36`</b> scores is taken.",
  "Daily commute times in a metro area have mean `mu = 28` minutes and standard deviation `sigma = 8` minutes (right-skewed). A random sample of <b>`n = 50`</b> commuters is taken.",
  "Light-bulb lifetimes are approximately normally distributed with mean `mu = 1200` hours and standard deviation `sigma = 100` hours. A random sample of <b>`n = 25`</b> bulbs is taken.",
  "Pizza delivery times have mean `mu = 22` minutes and standard deviation `sigma = 5` minutes (right-skewed). A random sample of <b>`n = 40`</b> deliveries is taken.",
  "Protein-bar protein content per serving has mean `mu = 18` grams and standard deviation `sigma = 3` grams. A random sample of <b>`n = 32`</b> bars is taken.",
  "Adult resting heart rates have mean `mu = 72` beats per minute and standard deviation `sigma = 10`. A random sample of <b>`n = 49`</b> adults is taken."
)

$mus    = array(510,  28,    1200, 22,   18,   72)
$sigmas = array(110,  8,     100,  5,    3,    10)
$ns     = array(36,   50,    25,   40,   32,   49)
$cs     = array(540,  30,    1180, 23,   17.4, 75)

$picked = jointrandfrom($ctxs, $mus, $sigmas, $ns, $cs)
$ctx   = $picked[0]
$mu    = $picked[1]
$sigma = $picked[2]
$n     = $picked[3]
$c     = $picked[4]

$se = $sigma / sqrt($n)
$z = ($c - $mu) / $se

$dir = rand(0, 1)
$prob_lt = normalcdf($z)
if ($dir == 0) {
  $relsym = "<"
  $relword = "less than"
  $prob = $prob_lt
} else {
  $relsym = ">"
  $relword = "greater than"
  $prob = 1 - $prob_lt
}

$answer[0] = $mu
$answer[1] = round($se, 4)
$answer[2] = round($prob, 4)
$abstolerance[0] = 0.01
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

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
      <p><b>(a)</b> Mean of the sampling distribution of `bar(x)` is `mu = ' . $mu . '`.</p>
      <p><b>(b)</b> Standard error is `SE = sigma/sqrt(n) = ' . $sigma . '/sqrt(' . $n . ') ~~ ' . round($se, 4) . '`.</p>
      <p><b>(c)</b> We want `P(bar(x) ' . $relsym . ' ' . $c . ')`. Compute the z-score:</p>
      <p style="text-align:center;">`z = (' . $c . ' - ' . $mu . ') / ' . round($se, 4) . ' ~~ ' . round($z, 3) . '`</p>
      <p>Using technology with `mu = ' . $mu . '` and `SE = ' . round($se, 4) . '`:</p>
      <p style="text-align:center;">`P(bar(x) ' . $relsym . ' ' . $c . ') ~~ ' . round($prob, 4) . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff3e0; border-left:4px solid #ff9800; border-radius:0 8px 8px 0;">
        <b>Common slip:</b> using `sigma` (population SD) instead of `SE = sigma/sqrt(n)` when computing the probability for the <i>sample mean</i>. `bar(x)` has a much smaller spread than a single observation.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">Assume the conditions for the sampling distribution of `bar(x)` to be approximately normal are met.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>mean</b> of the sampling distribution of `bar(x)`?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>standard error</b> of `bar(x)`? (Round to 4 decimal places.)
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find `P(bar(x) $relsym $c)`, the probability that the sample mean is <b>$relword</b> $c. (Round to 4 decimal places.)
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
