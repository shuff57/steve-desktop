// === NAME - DESCRIPTION: What a P-Value Measures - the correct meaning, what it is NOT, and the region it stands for ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A completed test with its p-value. Parts: (a) choices - the correct meaning of the p-value
// (b) choices - what the p-value is NOT (c) choices - the shaded region it stands for.
// Invariant: all three answers are constant across seeds.

$anstypes = array("choices", "choices", "choices")

$cases = array(
  array("A test of whether the mean hours of sleep for adults is less than 7 produces a p-value of 0.0142.",
        0.0142,
        "The probability of a sample result this extreme or more so, IF the null hypothesis were true.",
        "The probability that the null hypothesis is true.",
        "The tail(s) of the sampling distribution beyond the observed test statistic."),
  array("A test of whether the proportion of voters who support a measure differs from 0.30 produces a p-value of 0.165.",
        0.165,
        "The probability of a sample result this extreme or more so, IF the null hypothesis were true.",
        "The probability that the claim is false.",
        "The tail(s) of the sampling distribution beyond the observed test statistic."),
  array("A test of whether the mean daily fiber intake of college students differs from 25 g produces a p-value of 0.03.",
        0.03,
        "The probability of a sample result this extreme or more so, IF the null hypothesis were true.",
        "The probability that the alternative hypothesis is true.",
        "The tail(s) of the sampling distribution beyond the observed test statistic.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$pval = $cases[$i][1]

$questions[0] = array(
  "The probability of a sample result this extreme or more so, IF the null hypothesis were true.",
  "The probability that the null hypothesis is true.",
  "The probability that the sample was not random."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The probability that the null hypothesis is true: the p-value is computed assuming the null is true, so it cannot measure the null's probability.",
  "The probability of a sample result this extreme or more so, IF the null hypothesis were true.",
  "The probability that the sample mean equals the claimed value."
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "The tail(s) of the sampling distribution beyond the observed test statistic.",
  "The whole area under the curve.",
  "The area between the mean and the test statistic."
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
      <p><span class="term-label">Part (a): what the p-value measures.</span> The p-value is the probability of a sample result this extreme or more so, IF the null hypothesis were true. A p-value of ' . $pval . ' means that if the true value really were the claimed one, a sample result this far out would turn up about ' . round($pval * 100, 1) . '% of the time.</p>
      <p><span class="term-label">Part (b): what it is NOT.</span> The p-value is not the probability that the null is true: it is computed assuming the null is true, so it measures the surprise, not the truth.</p>
      <p><span class="term-label">Part (c): the region.</span> The p-value stands for the tail(s) of the sampling distribution beyond the observed test statistic: the picture is the p-value made visible.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What does the p-value measure?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the p-value NOT?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which region of the sampling distribution does it stand for?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
