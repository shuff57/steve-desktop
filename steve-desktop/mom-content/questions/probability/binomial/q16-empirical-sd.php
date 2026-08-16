// === NAME - DESCRIPTION: The Empirical Standard Deviation - s of the 30-group class data ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Organize-the-Data step. s of the fixed dataset ~ 1.4559 (divide by n-1).
// Invariant: ~ 1.4559 on every seed.

$anstypes = array("numfunc")

$answer[0] = 1.4559
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
      <p><span class="term-label">Step 1 &mdash; the sum of the squared values, weighted by frequency.</span></p>
      <p>`sum x^2 f = 0^2(2) + 1^2(5) + 2^2(9) + 3^2(7) + 4^2(4) + 5^2(2) + 6^2(1) = 0 + 5 + 36 + 63 + 64 + 50 + 36 = 254`</p>
      <p><span class="term-label">Step 2 &mdash; subtract the part explained by the mean.</span> With `n = 30` and `x-bar ~= 2.5333`:</p>
      <p>`s^2 = (sum x^2 f - n * x-bar^2) / (n - 1) = (254 - 30(2.5333)^2) / 29 ~= 61.4667 / 29 ~= 2.1195`</p>
      <p><span class="term-label">Step 3 &mdash; take the square root.</span></p>
      <p>`s = sqrt(2.1195) ~= 1.4559`</p>
      <p>Hold that beside the theoretical `sigma ~= 1.3693` you compute from the formula: the sample came out slightly more spread out than the model predicts, which is the ordinary behavior of 30 repetitions. Divide by `n - 1`, not `n`; this is a sample, not the whole population of every ten-draw run that could ever happen.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Using the same 30-group data (2 groups at 0, 5 at 1, 9 at 2, 7 at 3, 4 at 4, 2 at 5, 1 at 6), find the sample standard deviation `s`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `s =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
