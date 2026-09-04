// === NAME - DESCRIPTION: Read the Notation - mu, sigma, and the median of X ~ N(mu, sigma) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The section's notation reading: X ~ N(mu, sigma). Parts: (a) numfunc - mu
// (b) numfunc - sigma (c) numfunc - the median.
// Invariant: (a) and (b) are the stated parameters and (c) = mu on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$contexts = array(
  "the life of a wearable fitness device, in years",
  "the recovery time from a surgical procedure, in days",
  "the time to find a parking space at 9 A.M., in minutes"
)
$mus = array(4.1, 5.3, 5)
$sigmas = array(1.3, 2.1, 2)

$i = rand(0, 2)
$ctx = $contexts[$i]
$mu = $mus[$i]
$sigma = $sigmas[$i]

$answer[0] = $mu
$answer[1] = $sigma
$answer[2] = $mu
$abstolerance[0] = 0.005
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
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Reading the notation.</span> `X ~ N(mu, sigma)` names the center first and the spread second. The first number is the mean `mu`, the second is the standard deviation `sigma`.</p>
      <p><span class="term-label">Part (a): the mean.</span> `mu = ' . $mu . '`</p>
      <p><span class="term-label">Part (b): the standard deviation.</span> `sigma = ' . $sigma . '`</p>
      <p><span class="term-label">Part (c): the median.</span> A normal curve is symmetric about its mean, so half the area sits on each side and the median IS the mean: `median = mu = ' . $mu . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Let `X` = $ctx. The distribution of `X` is normal: `X ~ N($mu, $sigma)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the mean `mu`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the standard deviation `sigma`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the median?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
