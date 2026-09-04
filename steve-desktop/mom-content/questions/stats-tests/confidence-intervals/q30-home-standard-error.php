// === NAME - DESCRIPTION: The Home Standard Error - s/sqrt(n) ~ 19101 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's standard error (Try It Now 7.5 step 1): s/sqrt(n) = 113006/sqrt(35) ~ 19101.
// Part: (a) numfunc - ~ 19101.
// Invariant: ~ 19101 on every seed.

$anstypes = array("numfunc")

$answer[0] = 19101
$abstolerance[0] = 0.5

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
      <p><span class="term-label">The standard error.</span> The standard error of the mean is `s_x/sqrt(n)`:</p>
      <p>`SE = 113,006/sqrt(35) = 113,006/5.9161 ~= 19,101`</p>
      <p><span class="term-label">Why compute it once.</span> The standard error measures how much the sample mean varies from sample to sample, and it depends only on the data: not on the confidence level. Computing it once first will save you arithmetic all the way through the four-level table: everything the confidence level touches is packed into the single multiplier `t_(alpha/2)`, so the last part of the lab can fill an entire table of intervals without ever recomputing `x-bar` or `s_x`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Using `x-bar = $410,000`, `s_x = $113,006`, and `n = 35` from the demonstration data, find the standard error of the mean.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The standard error `SE = s_x/sqrt(n)`. (Round to the nearest dollar.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
