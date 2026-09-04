// === NAME - DESCRIPTION: The Sampling Distribution under H0 - the standard error, and the normal curve centered at the null value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A one-mean test with sigma known. Parts: (a) numfunc - the standard error sigma/sqrt(n)
// (b) choices - the sampling distribution of X-bar under H0.
// Invariant: (a) is the precomputed value exactly, (b) is constant per scenario.

$anstypes = array("numfunc", "choices")

$cases = array(
  array("A test of whether the mean jail time of first-time convicted burglars is 2.5 years. The population standard deviation is 1.5 years and a sample of 26 burglars is taken.",
        1.5, 26, 2.5),
  array("A test of whether the mean hours of television watched per day is 4 hours. The population standard deviation is 2 hours and a sample of 30 students is taken.",
        2, 30, 4),
  array("A test of whether the mean commute time of city residents is 28 minutes. The population standard deviation is 5 minutes and a sample of 40 residents is taken.",
        5, 40, 28)
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$sigma = $cases[$i][1]
$n = $cases[$i][2]
$mu0 = $cases[$i][3]

$se = $sigma / sqrt($n)

$answer[0] = $se
$abstolerance[0] = 0.005

$questions[1] = array(
  "A normal distribution centered at the null value `mu_0 = ' . $mu0 . '` with standard error `sigma/sqrt(n) = ' . round($se, 4) . '`.",
  "A t distribution centered at the sample mean.",
  "A binomial distribution centered at the claimed proportion."
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
      <p><span class="term-label">Part (a): the standard error.</span> `sigma/sqrt(n) = ' . $sigma . '/sqrt(' . $n . ') = ' . round($se, 4) . '`.</p>
      <p><span class="term-label">Part (b): the sampling distribution under `H_0`.</span> The distribution in the table is not the distribution of the raw data: it is the sampling distribution of the point estimate, the pattern you would see if you took sample after sample and plotted the estimate each time. Under `H_0` it is centered at the claimed value `mu_0 = ' . $mu0 . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the standard error `sigma/sqrt(n)`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the sampling distribution of `X-bar` under `H_0`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
