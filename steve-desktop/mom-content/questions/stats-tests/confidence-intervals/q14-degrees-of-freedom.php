// === NAME - DESCRIPTION: Degrees of Freedom - df = n - 1 and why ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A sample size n. Parts: (a) numfunc - df = n - 1 (b) choices - why it is n - 1.
// Invariant: (a) = n - 1 exactly, (b) is constant on every seed.

$anstypes = array("numfunc", "choices")

$contexts = array(
  array("a quality inspector tests a random sample of 25 rechargeable batteries", 25),
  array("a study of hypnotherapy measures hours of sleep for 12 subjects", 12),
  array("a study of acupuncture measures sensory rates for 15 subjects", 15),
  array("a survey of 70 patients measures emergency room wait times", 70)
)
// [ctx, n]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$n = $contexts[$i][1]

$df = $n - 1

$answer[0] = $df
$abstolerance[0] = 0.005

$questions[1] = array(
  "The n deviations from the sample mean must sum to zero, so the last one is determined once the other n - 1 are known",
  "The sample standard deviation divides by n, so there are n degrees of freedom",
  "The t-distribution needs one fewer degree of freedom than the normal distribution"
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
      <p><span class="term-label">Part (a): the degrees of freedom.</span> The degrees of freedom is always one less than the sample size:</p>
      <p>`df = n - 1 = ' . $n . ' - 1 = ' . $df . '`</p>
      <p><span class="term-label">Part (b): why.</span> The degrees of freedom come from the calculation of the sample standard deviation s, which requires the n deviations `x - bar(x)`. Because those deviations must sum to zero, the last one is determined once the other n - 1 are known. Only n - 1 of them can vary freely, and that count is the degrees of freedom.</p>
      <p>The subscript is doing real work: it names which member of the t-family you are using, so writing `t_' . $df . '` instead of just `t` is the difference between a specific curve and a vague gesture at a family of them.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx. The population standard deviation is unknown.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many degrees of freedom does the t-distribution for this sample have?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Why is the degrees of freedom `n - 1` rather than `n`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
