// === NAME - DESCRIPTION: The Pilot Standard Deviation - s of the 12-value dataset ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Collect-the-Data step. s of the fixed pilot dataset ~ 0.3078 (divide by n-1).
// Invariant: ~ 0.3078 on every seed.

$anstypes = array("numfunc")

$answer[0] = 0.3078
$abstolerance[0] = 0.005

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
      <p><span class="term-label">Subtract the part explained by the mean.</span> With `n = 12` and `x-bar ~= 0.4980`, and the sum of the squares `sum x^2 = 4.0176696`:</p>
      <p>`s^2 = (sum x^2 - n * x-bar^2) / (n - 1) ~= (4.0176696 - 12(0.49798)^2) / 11 ~= 1.0419/11 ~= 0.0947`</p>
      <p><span class="term-label">Take the square root.</span></p>
      <p>`s = sqrt(0.0947) ~= 0.3078`</p>
      <p>Hold that beside the theoretical `sigma ~= 0.2887`: the sample came out about 7% more spread out than the model predicts. Divide by `n - 1`, not `n`; these twelve values are a sample, not every value the generator could ever produce. And notice that `s` drifted further from `sigma` than `x-bar` did from `mu`: a standard deviation is built from squared distances, so a single value out near an end moves it more than it moves the mean.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Using the same twelve pilot values, find the sample standard deviation `s`. Their sum is 5.9758 and the sum of their squares is 4.0176696.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `s =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
