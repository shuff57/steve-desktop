// === NAME - DESCRIPTION: Sigma Known vs Unknown - which spread you were given, and the distribution that follows ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A mean test where the scenario either hands you sigma or hands you s. Parts: (a) choices -
// which spread you were given (b) choices - the distribution that follows.
// Invariant: (a) and (b) are constant per scenario and consistent.

$anstypes = array("choices", "choices")

$cases = array(
  array("A study tests whether the mean weight of 1-lb bags of coffee differs from 454 g. The population standard deviation is known to be 6.8 g.",
        "The population standard deviation `sigma`",
        "The normal distribution"),
  array("A study tests whether the mean daily fiber intake of college students differs from 25 g. The population standard deviation is not known; the sample standard deviation is 8.4 g.",
        "The sample standard deviation `s`",
        "Student's t distribution"),
  array("A factory tests whether the mean part length has dropped from 12.5 mm. The population standard deviation is known to be 0.28 mm.",
        "The population standard deviation `sigma`",
        "The normal distribution"),
  array("A nutritionist tests whether the mean daily sugar intake of clients is less than 35 g. The population standard deviation is not known; the sample standard deviation is 4.1 g.",
        "The sample standard deviation `s`",
        "Student's t distribution"),
  array("A logistics company tests whether mean delivery time exceeds 28 hours. The population standard deviation is known to be 5.2 hours.",
        "The population standard deviation `sigma`",
        "The normal distribution")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$spread = $cases[$i][1]
$dist = $cases[$i][2]

$questions[0] = array(
  $spread,
  "The sample size `n`",
  "The claimed value `mu_0`"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $dist,
  "The binomial distribution",
  "The chi-square distribution"
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
      <p><span class="term-label">Part (a) &mdash; which spread.</span> ' . $spread . '</p>
      <p><span class="term-label">Part (b) &mdash; the distribution.</span> ' . $dist . '</p>
      <p>When you estimate the spread from the same numbers you used to estimate the center, you have introduced a second source of uncertainty that a normal curve does not account for &mdash; that substitution is exactly what the t distribution exists for. The fewer degrees of freedom, the fatter its tails.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which spread were you given?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which distribution follows?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
