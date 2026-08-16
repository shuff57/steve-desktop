// === NAME - DESCRIPTION: Mean, Sum, or Individual - which distribution each kind of question uses ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// One scenario per context, three questions about the SAME population: a single-value question,
// a sample-mean question, and a sample-sum question. Parts: (a) choices - single value
// (b) choices - sample mean (c) choices - sample sum.
// Invariant: all three answers are constant across seeds (only the context varies).

$anstypes = array("choices", "choices", "choices")

$contexts = array(
  "the excess time used by cell phone customers who exceed their contract, with mean 22 minutes and standard deviation 22 minutes",
  "the systolic blood pressure of females aged 18 to 24, with mean 114.8 and standard deviation 13.1",
  "the distance of fly balls hit to the outfield, with mean 250 feet and standard deviation 50 feet"
)
$ns = array(80, 40, 49)

$i = rand(0, 2)
$ctx = $contexts[$i]
$n = $ns[$i]

$questions[0] = array(
  "The distribution of the individual values (here: the population distribution itself &mdash; do NOT use the central limit theorem)",
  "The central limit theorem sampling distribution of `bar(x)`",
  "The central limit theorem distribution of `Sigma x`"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The distribution of the individual values",
  "The central limit theorem sampling distribution of `bar(x)`",
  "The central limit theorem distribution of `Sigma x`"
)
$answer[1] = 1
$noshuffle[1] = "all"

$questions[2] = array(
  "The distribution of the individual values",
  "The central limit theorem sampling distribution of `bar(x)`",
  "The central limit theorem distribution of `Sigma x`"
)
$answer[2] = 2
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
      <p><span class="term-label">The word before the number tells you which tool.</span> Scan the sentence for "average", "mean", "total", "sum", or "one randomly selected". Those five words decide the whole problem. Everything after that is typing.</p>
      <p><span class="term-label">Part (a) &mdash; one individual.</span> A question about one person, one part, one value is about `X`, the population distribution itself &mdash; the central limit theorem has nothing to say about it. That third case is where most of the lost points in this section live.</p>
      <p><span class="term-label">Part (b) &mdash; a sample mean.</span> A question about the average of a sample is about `bar(x)`, and the central limit theorem applies: `bar(X) ~ N(mu, sigma/sqrt(n))`.</p>
      <p><span class="term-label">Part (c) &mdash; a sample sum.</span> A question about a total or a sum is about `Sigma x`, and the central limit theorem for sums applies: `Sigma X ~ N(n*mu, sqrt(n)*sigma)`.</p>
      <p>The two numbers are not close to each other and they are not supposed to be. Averaging pulls values toward the center, which is exactly why a mean is far less likely to land out in a tail than any individual value is.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider $ctx. A random sample of size `n = $n` is taken. Three questions are asked about this situation; each uses a different distribution.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> A question about ONE randomly selected individual value uses which distribution?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> A question about the MEAN of the sample uses which distribution?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> A question about the TOTAL (SUM) of the sample uses which distribution?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
