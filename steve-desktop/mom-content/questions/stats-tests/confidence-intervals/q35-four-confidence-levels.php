// === NAME - DESCRIPTION: The Four Confidence Levels - the EBM at 50%, 80%, 95%, and 99% ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's four-level table (Try It Now 7.9): EBM = t x 19101 for t = 0.6818, 1.3070, 2.0322,
// 2.7284 at 34 df.
// Parts: (a) numfunc - EBM at 50% ~ 13023 (b) numfunc - EBM at 80% ~ 24965
// (c) numfunc - EBM at 95% ~ 38817 (d) numfunc - EBM at 99% ~ 52115.
// Invariant: ~ 13023, ~ 24965, ~ 38817, ~ 52115 on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

$answer[0] = 13023
$answer[1] = 24965
$answer[2] = 38817
$answer[3] = 52115
$abstolerance[0] = 0.5
$abstolerance[1] = 0.5
$abstolerance[2] = 0.5
$abstolerance[3] = 0.5

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
      <p><span class="term-label">The four rows share everything except one number.</span> Your `x-bar`, your `s_x`, and your `n` are fixed by the sample, so the standard error `s_x/sqrt(n) ~= $19,101` is the same in every row &mdash; compute it once, write it in the margin, and reuse it. All that changes down the column is `t_(alpha/2)`.</p>
      <p><span class="term-label">Part (a) &mdash; 50%.</span> `EBM = 0.6818 x 19,101 ~= 13,023`</p>
      <p><span class="term-label">Part (b) &mdash; 80%.</span> `EBM = 1.3070 x 19,101 ~= 24,965`</p>
      <p><span class="term-label">Part (c) &mdash; 95%.</span> `EBM = 2.0322 x 19,101 ~= 38,817`</p>
      <p><span class="term-label">Part (d) &mdash; 99%.</span> `EBM = 2.7284 x 19,101 ~= 52,115`</p>
      <p>Filling this table is really just multiplying one fixed number by four different multipliers, and seeing that plainly is the point of the exercise.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Using the demonstration data (standard error ~ $19,101) and the t-values `t_(0.25) = 0.6818`, `t_(0.10) = 1.3070`, `t_(0.025) = 2.0322`, and `t_(0.005) = 2.7284`, all with 34 degrees of freedom, find the error bound at each confidence level.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The error bound at 50% confidence. (Round to the nearest dollar.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The error bound at 80% confidence. (Round to the nearest dollar.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The error bound at 95% confidence. (Round to the nearest dollar.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> The error bound at 99% confidence. (Round to the nearest dollar.)
    <span style="margin-left:8px;">$answerbox[3]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
