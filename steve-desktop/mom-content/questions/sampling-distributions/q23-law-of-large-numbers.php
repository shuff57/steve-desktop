// === NAME - DESCRIPTION: The Law of Large Numbers - bigger samples, tighter sample means ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// mu, sigma, one sample size n with sqrt(n) and sqrt(4n) exact.
// Parts: (a) numfunc - SE for size n (b) numfunc - SE for size 4n (c) choices - what the
// comparison shows.
// Invariant: (a) = sigma/sqrt(n), (b) = (a)/2 exactly, (c) is constant on every seed.

$anstypes = array("numfunc", "numfunc", "choices")

$contexts = array(
  array("a population with mu = 50 and sigma = 12", 50, 12, 9),
  array("a population with mu = 100 and sigma = 20", 100, 20, 25),
  array("a population with mu = 30 and sigma = 8", 30, 8, 16)
)
// [ctx, mu, sigma, n]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$n = $contexts[$i][3]

$n4 = 4 * $n

$se1 = $sigma / sqrt($n)
$se2 = $sigma / sqrt(4 * $n)

$answer[0] = $se1
$abstolerance[0] = 0.005
$answer[1] = $se2
$abstolerance[1] = 0.005

$questions[2] = array(
  "Four times the data only halves the spread of the sample mean &mdash; the sample mean gets closer to the population mean as more data is collected",
  "Four times the data quarters the spread of the sample mean",
  "The sample size has no effect on the spread of the sample mean",
  "Bigger samples move the population mean closer to the sample mean"
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
      <p><span class="term-label">Part (a) &mdash; the SE for size n.</span> `SE = sigma/sqrt(n) = ' . $sigma . '/sqrt(' . $n . ') = ' . round($se1, 4) . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the SE for size 4n.</span> `SE = sigma/sqrt(4n) = ' . $sigma . '/sqrt(' . (4 * $n) . ') = ' . round($se2, 4) . '`.</p>
      <p><span class="term-label">Part (c) &mdash; the law of large numbers.</span> The n sits under a square root in the denominator, so as n grows the curve of sample means squeezes in tighter around mu &mdash; the sample mean gets closer to the population mean, and it gets closer as you collect more data. But square roots grow lazily: to cut the spread in half you need four times the data; to cut it to a tenth you need a hundred times the data. That is why survey sample sizes plateau in the low thousands rather than climbing forever.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider $ctx. Find the standard deviation of the sample mean `bar(x)` for samples of size `n = $n` and for samples of size `4n = $n4`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The standard deviation of `bar(x)` for samples of size `n = $n`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The standard deviation of `bar(x)` for samples of size `4n = $n4`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What do the two answers show?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
