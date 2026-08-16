// === NAME - DESCRIPTION: The Population Mean and SD - x-bar and s of the 30 pocket-change amounts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Collect-the-Data step on the fixed 30-value class dataset (Table 6.4.2):
// total $21.00, sum of squares 22.3092.
// Parts: (a) numfunc - x-bar = 21.00/30 = 0.70 (b) numfunc - s ~ 0.5122 (divide by n-1).
// Invariant: ~ 0.70 and ~ 0.5122 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 0.70
$answer[1] = 0.5122
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

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
      <p><span class="term-label">Part (a) &mdash; the sample mean.</span> The 30 amounts total $21.00, so</p>
      <p>`x-bar = 21.00/30 = 0.70`</p>
      <p><span class="term-label">Part (b) &mdash; the sample standard deviation.</span> The squares total 22.3092, so</p>
      <p>`s^2 = (sum x^2 - n*x-bar^2)/(n - 1) = (22.3092 - 30(0.70)^2)/29 = 7.6092/29 ~= 0.26238`</p>
      <p>`s = sqrt(0.26238) ~= 0.5122`</p>
      <p>Divide by `n - 1` rather than `n` &mdash; these 30 are a sample of a much larger population of pockets, not the whole of it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">One class surveyed 30 people and recorded the pocket-change amounts in Table 6.4.2. The 30 values total $21.00 and the squares of the values total 22.3092. Calculate the sample mean and the sample standard deviation.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `x-bar =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> `s =`
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
