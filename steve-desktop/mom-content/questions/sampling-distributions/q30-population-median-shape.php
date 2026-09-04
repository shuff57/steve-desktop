// === NAME - DESCRIPTION: The Population Median and Shape - 0.585, mean above median, right-skewed ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's shape analysis (Try It Now 6.2) on the fixed 30-value class dataset.
// Parts: (a) numfunc - median = (0.56 + 0.61)/2 = 0.585 (b) choices - mean vs median
// (c) choices - the shape.
// Invariant: ~ 0.585, (b) and (c) constant on every seed.

$anstypes = array("numfunc", "choices", "choices")

$answer[0] = 0.585
$abstolerance[0] = 0.005

$questions[1] = array(
  "The mean sits ABOVE the median: the handful of people carrying more than a dollar drag the mean upward while leaving the middle value alone",
  "The mean sits BELOW the median",
  "The mean and median are equal"
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "Right-skewed: bunched against zero on the left (nobody carries a negative amount of change) and trailing off to the right (no upper limit)",
  "Approximately normal: a symmetric bell",
  "Uniform: every amount equally likely",
  "Left-skewed: trailing off to the left"
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
      <p><span class="term-label">Part (a): the median.</span> With 30 sorted values the median averages positions 15 and 16, which hold $0.56 and $0.61:</p>
      <p>`median = (0.56 + 0.61)/2 = 0.585`</p>
      <p><span class="term-label">Part (b): mean vs median.</span> The mean is 0.70, about twelve cents ABOVE the median 0.585. That is the arithmetic signature of a right skew: the handful of people carrying more than a dollar drag the mean upward while leaving the middle value alone. Eighteen of the 30 people carry less than the mean.</p>
      <p><span class="term-label">Part (c): the shape.</span> The curve is bunched up against zero on the left, because nobody can carry a negative amount of change, and it trails off to the right, because there is no upper limit on how much someone might have. Pocket change is lopsided on purpose: a population that was already bell-shaped would prove nothing.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">One class surveyed 30 people and recorded the pocket-change amounts in Table 6.4.2 (sorted: 0.05, 0.11, 0.13, 0.17, 0.22, 0.25, 0.28, 0.31, 0.34, 0.36, 0.42, 0.45, 0.47, 0.53, 0.56, 0.61, 0.63, 0.68, 0.72, 0.79, 0.85, 0.91, 0.97, 1.06, 1.14, 1.23, 1.38, 1.52, 1.76, 2.10). The mean is $0.70 and s ~ $0.51.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the median.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How does the mean compare to the median, and what does that say about the distribution?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the approximate shape of the population distribution?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
