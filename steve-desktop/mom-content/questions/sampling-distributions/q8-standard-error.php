// === NAME - DESCRIPTION: The Standard Error - SE, the z-score of a sample mean, and what quadrupling n does ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// mu, sigma, n with sqrt(n) exact. Parts: (a) numfunc - SE = sigma/sqrt(n)
// (b) numfunc - z = (bar(x) - mu)/SE (c) choices - what happens to the SE when n is quadrupled.
// Invariant: (a) = sigma/sqrt(n) exactly, (b) = (bar(x) - mu)/SE exactly, (c) is constant.

$anstypes = array("numfunc", "numfunc", "choices")

$contexts = array(
  array("the scores on a college entrance exam, in points", 52, 11, 25, 56),
  array("the amount of time to complete one review, in hours", 4, 1.2, 16, 4.6),
  array("the mean age of tablet users, in years", 34, 15, 100, 36)
)
// [ctx, mu, sigma, n, bar(x)]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]
$xbar = $contexts[$i][4]

$se = $sigma / sqrt($n)
$z = ($xbar - $mu) / $se

$answer[0] = $se
$abstolerance[0] = 0.005
$answer[1] = $z
$abstolerance[1] = 0.005

$questions[2] = array(
  "It is cut in half",
  "It is cut to a quarter",
  "It is unchanged",
  "It doubles"
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
      <p><span class="term-label">Part (a) &mdash; the standard error.</span> `SE = sigma/sqrt(n) = ' . $sigma . '/sqrt(' . $n . ') = ' . round($se, 4) . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the z-score of a sample mean.</span> The numerator still measures distance from the center, but the denominator is now the standard error rather than the population standard deviation:</p>
      <p>`z = (bar(x) - mu)/SE = (' . $xbar . ' - ' . $mu . ')/' . round($se, 4) . ' = ' . round($z, 4) . '`</p>
      <p><span class="term-label">Part (c) &mdash; quadrupling n.</span> The n sits under a square root in the denominator, so `sqrt(4n) = 2*sqrt(n)` and the SE is cut in half. Dividing by `n` instead of `sqrt(n)` is the single most common slip in this section, and using sigma where the SE belongs makes a very unusual sample mean look ordinary.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A population is described: $ctx, with `mu = $mu` and `sigma = $sigma`. A random sample of size `n = $n` is taken. A particular sample has mean `bar(x) = $xbar`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the standard error of the mean? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the z-score of `bar(x) = $xbar`? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What happens to the standard error if the sample size is quadrupled (from `n` to `4n`)?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
