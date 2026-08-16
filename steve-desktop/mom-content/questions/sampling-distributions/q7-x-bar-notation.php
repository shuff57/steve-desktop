// === NAME - DESCRIPTION: Sampling Distribution Notation - mu, the standard error, and what the SE measures for bar(x) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A population with mu and sigma and a sample of size n. Parts: (a) numfunc - the mean of the
// sampling distribution (b) numfunc - the standard error sigma/sqrt(n) (c) choices - what the
// SE measures.
// Invariant: (a) = mu, (b) = sigma/sqrt(n) exactly, (c) is constant on every seed.

$anstypes = array("numfunc", "numfunc", "choices")

$contexts = array(
  array("the mean age of tablet users, in years", 34, 15, 100),
  array("the mean number of minutes for app engagement by a tablet user", 8.2, 1, 60),
  array("the mean time to complete one employee review, in hours", 4, 1.2, 16)
)
// [ctx, mu, sigma, n]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]

$se = $sigma / sqrt($n)

$answer[0] = $mu
$abstolerance[0] = 0.005
$answer[1] = $se
$abstolerance[1] = 0.005

$questions[2] = array(
  "How far, on average, a sample mean falls from the population mean in repeated random samples of size " . $n,
  "How far, on average, an individual value falls from the population mean",
  "The spread of the original population"
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
      <p><span class="term-label">Part (a) &mdash; the mean of the sampling distribution.</span> Averaging does not push the answer up or down; it just makes the answer more reliable. So `mu_bar(x) = mu = ' . $mu . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the standard error.</span> The standard deviation of the sampling distribution is the population standard deviation divided by the square root of the sample size:</p>
      <p>`SE = sigma/sqrt(n) = ' . $sigma . '/sqrt(' . $n . ') = ' . round($se, 4) . '`</p>
      <p><span class="term-label">Part (c) &mdash; what it measures.</span> The standard error describes how far, on average, a sample mean will fall from the population mean in repeated random samples of size ' . $n . '. Sample means cluster tighter around the center than individual values do, and the bigger the sample, the tighter they cluster.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A population is described: $ctx, with `mu = $mu` and `sigma = $sigma`. A random sample of size `n = $n` is taken. Let `bar(x)` = the mean of the sample.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the mean of the sampling distribution of `bar(x)`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the standard error of `bar(x)`? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What does the standard error measure?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
