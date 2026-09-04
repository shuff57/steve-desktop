// === NAME - DESCRIPTION: Read the Significance Level - alpha as a decimal, and what it commits you to before the data arrive ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A test with a stated significance level. Parts: (a) numfunc - alpha as a decimal
// (b) choices - what that alpha commits you to.
// Invariant: (a) is the precomputed decimal exactly, (b) is constant across seeds.

$anstypes = array("numfunc", "choices")

$cases = array(
  array("A medical trial tests a new drug at the 1% significance level.",
        0.01),
  array("A factory quality-control test runs at the 5% significance level.",
        0.05),
  array("A school district evaluates a tutoring program at the 10% significance level.",
        0.10)
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$alpha = $cases[$i][1]

$answer[0] = $alpha
$abstolerance[0] = 0.005

$questions[1] = array(
  "The probability of rejecting `H_0` when it is in fact true: chosen BEFORE the data are collected.",
  "The probability that `H_0` is true.",
  "The probability of a Type II error."
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
      <p><span class="term-label">Part (a): alpha as a decimal.</span> "The level of significance is 1%" means `alpha = ' . $alpha . '`. If no level is given at all, the common standard is `alpha = 0.05`.</p>
      <p><span class="term-label">Part (b): what it commits you to.</span> `alpha` is the probability of rejecting `H_0` when it is in fact true, and it is preset: chosen before the sample data are collected. Choosing alpha after seeing the p-value would let you pick whichever threshold gives the answer you were hoping for, which is not a test of anything.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is `alpha` as a decimal?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does that alpha commit you to?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
