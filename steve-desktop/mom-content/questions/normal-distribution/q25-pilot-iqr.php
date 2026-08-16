// === NAME - DESCRIPTION: The Pilot IQR - Q3 - Q1 of the 12-value pilot ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Describe-the-Data step on the fixed 12-value pilot dataset:
// 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1
// Part: (a) numfunc - IQR = 131.15 - 127.45 = 3.70.
// Invariant: ~ 3.70 on every seed.

$anstypes = array("numfunc")

$answer[0] = 3.70
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
      <p><span class="term-label">The IQR is a width, not a location.</span> From the ordered list, `Q1 = 127.45` (the median of the lowest six) and `Q3 = 131.15` (the median of the highest six), so:</p>
      <p>`IQR = Q3 - Q1 = 131.15 - 127.45 = 3.70`</p>
      <p>This is the empirical number the theoretical model will be held against: the model insists on its own IQR, and the gap between the two is the finding of the lab.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A group ran a short pilot before the lap-time lab and sampled twelve lap times, already sorted: 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1. The quartiles are `Q1 = 127.45` and `Q3 = 131.15`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the interquartile range `IQR = Q3 - Q1`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
