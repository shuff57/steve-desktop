// === NAME - DESCRIPTION: CLT - compare the probability of a single observation X to the probability of a sample mean x-bar at the same cutoff, then choose the correct explanation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number","number","choices")
$displayformat = "select"
$answerboxsize = 14

// Each scenario gives mu, sigma, sample size n, and a cutoff a offset from mu.
// Part (a) is the single-observation probability; part (b) is the sample-mean probability;
// part (c) is the "why" — the sample mean is always more extreme because SE = sigma/sqrt(n) < sigma.
// Direction (< vs >) is randomized for parts (a) and (b).

// Scenario 0: SAT scores, mu=510, sigma=110, n=36, a=540
// Scenario 1: heart rate, mu=72, sigma=10, n=49, a=75
// Scenario 2: light bulb, mu=1200, sigma=100, n=25, a=1180  (population stated as normal so n<30 is OK)
// Scenario 3: protein,    mu=18, sigma=3, n=36, a=17.5
// Scenario 4: commute,    mu=28, sigma=8, n=64, a=30
// Scenario 5: delivery,   mu=22, sigma=5, n=40, a=23

$ctxs = array(
  "SAT math scores in a population are approximately normally distributed with mean `mu = 510` and standard deviation `sigma = 110`. A random sample of <b>`n = 36`</b> scores is taken.",
  "Adult resting heart rates have mean `mu = 72` bpm and standard deviation `sigma = 10`. A random sample of <b>`n = 49`</b> adults is taken.",
  "Light-bulb lifetimes are approximately normally distributed with mean `mu = 1200` hours and standard deviation `sigma = 100` hours. A random sample of <b>`n = 25`</b> bulbs is taken.",
  "Protein-bar protein content has mean `mu = 18` grams and standard deviation `sigma = 3` grams. A random sample of <b>`n = 36`</b> bars is taken.",
  "Daily commute times have mean `mu = 28` minutes and standard deviation `sigma = 8` minutes. A random sample of <b>`n = 64`</b> commuters is taken.",
  "Pizza delivery times have mean `mu = 22` minutes and standard deviation `sigma = 5` minutes. A random sample of <b>`n = 40`</b> deliveries is taken."
)

$mus    = array(510, 72,  1200, 18,   28, 22)
$sigmas = array(110, 10,  100,  3,    8,  5)
$ns     = array(36,  49,  25,   36,   64, 40)
$as     = array(540, 75,  1180, 17.5, 30, 23)

$picked = jointrandfrom($ctxs, $mus, $sigmas, $ns, $as)
$ctx   = $picked[0]
$mu    = $picked[1]
$sigma = $picked[2]
$n     = $picked[3]
$a     = $picked[4]

$se = $sigma / sqrt($n)
$zX    = ($a - $mu) / $sigma
$zxbar = ($a - $mu) / $se

$dir = rand(0, 1)
$prob_X_lt    = normalcdf($zX)
$prob_xbar_lt = normalcdf($zxbar)
if ($dir == 0) {
  $relsym = "<"
  $relword = "less than"
  $probX    = $prob_X_lt
  $probxbar = $prob_xbar_lt
} else {
  $relsym = ">"
  $relword = "greater than"
  $probX    = 1 - $prob_X_lt
  $probxbar = 1 - $prob_xbar_lt
}

$answer[0] = round($probX, 4)
$answer[1] = round($probxbar, 4)
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "(a), the single observation, is more extreme because individual values have more variability",
  "(b), the sample mean, is more extreme because the sample mean has a smaller standard error",
  "The two probabilities are equal because both come from the same population",
  "It depends on whether the cutoff is above or below the mean"
)
$noshuffle[2] = "all"
$answer[2] = 1

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
      <p><b>(a)</b> A <i>single</i> observation `X` uses the population standard deviation `sigma = ' . $sigma . '`:</p>
      <p style="text-align:center;">`P(X ' . $relsym . ' ' . $a . ') ~~ ' . round($probX, 4) . '`</p>
      <p><b>(b)</b> The <i>sample mean</i> `bar(x)` uses the standard error `SE = sigma/sqrt(n) = ' . $sigma . '/sqrt(' . $n . ') ~~ ' . round($se, 4) . '`:</p>
      <p style="text-align:center;">`P(bar(x) ' . $relsym . ' ' . $a . ') ~~ ' . round($probxbar, 4) . '`</p>
      <p><b>(c)</b> Because `SE < sigma`, the sampling distribution of `bar(x)` is <i>narrower</i> and more concentrated around `mu`. So the probability of getting a value as far from `mu` as `' . $a . '` is <b>smaller</b> for `bar(x)` than for `X`. The sample-mean probability is therefore "more extreme" (closer to 0 if `a > mu`, closer to ... wait, depends on direction). Concretely, `' . round($probxbar, 4) . '` is further from `0.5` than `' . round($probX, 4) . '`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>The big idea:</b> averaging across `n` observations <i>reduces variability</i>. The standard error of the mean is `sigma/sqrt(n)`, which shrinks as `n` grows. So sample means cluster more tightly around `mu` than individual observations do.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">Compare the probability for a <b>single observation</b> `X` to the probability for the <b>sample mean</b> `bar(x)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the probability that a single observation is <b>$relword</b> $a, that is, `P(X $relsym $a)`. (Round to 4 decimal places.)
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the probability that the sample mean is <b>$relword</b> $a, that is, `P(bar(x) $relsym $a)`. (Round to 4 decimal places.)
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which probability is "more extreme" (further from `0.5`), and why?
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
