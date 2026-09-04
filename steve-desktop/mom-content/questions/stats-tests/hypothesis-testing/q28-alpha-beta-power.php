// === NAME - DESCRIPTION: Alpha, Beta, and Power - what each probability measures, and what the power of the test is ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A test with stated alpha. Parts: (a) choices - what alpha is the probability of
// (b) choices - what beta is the probability of (c) choices - what the power measures.
// Invariant: all three answers are constant across seeds.

$anstypes = array("choices", "choices", "choices")

$contexts = array(
  "A medical trial tests a new drug at the 5% significance level.",
  "A factory quality-control test runs at the 1% significance level.",
  "A school district evaluates a tutoring program at the 10% significance level."
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i]

$questions[0] = array(
  "The probability of a Type I error: rejecting `H_0` when it is in fact true.",
  "The probability of a Type II error: failing to reject `H_0` when it is false.",
  "The probability that `H_0` is true."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The probability of a Type II error: failing to reject `H_0` when it is false.",
  "The probability of a Type I error: rejecting `H_0` when it is in fact true.",
  "The probability that `H_a` is true."
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "The probability of rejecting `H_0` when it is false: the chance the test catches a real effect.",
  "The probability of rejecting `H_0` when it is true.",
  "The probability that the sample is representative."
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
      <p><span class="term-label">Part (a): alpha.</span> `alpha` is the probability of a Type I error: rejecting `H_0` when it is in fact true. It is preset: chosen before the data are collected.</p>
      <p><span class="term-label">Part (b): beta.</span> `beta` is the probability of a Type II error: failing to reject `H_0` when it is false.</p>
      <p><span class="term-label">Part (c): power.</span> The power of the test is `1 - beta`: the probability of rejecting `H_0` when it is false. It is the chance the test catches a real effect: at power 0.40, twelve of twenty real effects are missed.</p>
      <p>`alpha` and `beta` are the two ways a test can be wrong, and power is the complement of the second one.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is `alpha` the probability of?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is `beta` the probability of?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What does the power of the test measure?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
