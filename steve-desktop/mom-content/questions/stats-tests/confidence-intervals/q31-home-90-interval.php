// === NAME - DESCRIPTION: The Home 90% Interval - EBM, lower and upper endpoints ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's 90% interval (Try It Now 7.5): EBM = 1.6909 x 19101 ~ 32298,
// lower 377702, upper 442298.
// Parts: (a) numfunc - EBM (b) numfunc - lower endpoint (c) numfunc - upper endpoint.
// Invariant: ~ 32298, ~ 377702, ~ 442298 on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$answer[0] = 32298
$answer[1] = 377702
$answer[2] = 442298
$abstolerance[0] = 0.5
$abstolerance[1] = 0.5
$abstolerance[2] = 0.5

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
      <p><span class="term-label">Part (a) &mdash; the error bound.</span> With `t_(0.05) = 1.6909` at 34 degrees of freedom:</p>
      <p>`EBM = t_(alpha/2) * s_x/sqrt(n) = 1.6909 x 19,101 ~= 32,298`</p>
      <p><span class="term-label">Parts (b) and (c) &mdash; the interval.</span> Lay that distance off in both directions from the sample mean:</p>
      <p>`410,000 - 32,298 = 377,702` and `410,000 + 32,298 = 442,298`</p>
      <p>Notice how wide that is &mdash; roughly $65,000 from end to end &mdash; and notice where the width came from. It is not sloppiness in the sample. It is the $113,006 standard deviation divided by the square root of only 35 homes. To halve the width the class would need four times as many listings, because the `sqrt(n)` in the denominator means precision improves with the square root of effort, not with effort itself.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Using `x-bar = $410,000`, `s_x = $113,006`, and `n = 35` from the demonstration data, find the error bound and the 90% confidence interval for the mean sale price. Use `t_(0.05) = 1.6909` with 34 degrees of freedom.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The error bound `EBM`. (Round to the nearest dollar.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The LOWER endpoint of the interval.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The UPPER endpoint of the interval.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
