// === NAME - DESCRIPTION: The Pilot Median and Quartiles - locations in the sorted 12-value dataset ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Describe-the-Data step on the fixed 12-value pilot dataset:
// 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1
// Parts: (a) numfunc - median = (128.9 + 129.4)/2 = 129.15
// (b) numfunc - Q1 = (127.1 + 127.8)/2 = 127.45
// (c) numfunc - Q3 = (130.8 + 131.5)/2 = 131.15
// Invariant: ~ 129.15, ~ 127.45, ~ 131.15 on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$answer[0] = 129.15
$answer[1] = 127.45
$answer[2] = 131.15
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

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
      <p><span class="term-label">Step 1 &mdash; the median.</span> There are twelve values, so the median sits between the 6th and 7th:</p>
      <p>`median = (128.9 + 129.4)/2 = 258.3/2 = 129.15`</p>
      <p><span class="term-label">Step 2 &mdash; the first quartile.</span> The lowest six values are 125.9 through 128.9, and their median is the average of the 3rd and 4th:</p>
      <p>`Q1 = (127.1 + 127.8)/2 = 254.9/2 = 127.45`</p>
      <p><span class="term-label">Step 3 &mdash; the third quartile.</span> The highest six values are 129.4 through 134.1, and their median is the average of the 9th and 10th values overall:</p>
      <p>`Q3 = (130.8 + 131.5)/2 = 262.3/2 = 131.15`</p>
      <p>A quartile is a location in the data, not a formula applied to it &mdash; every one of these comes from positions in the ordered list.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A group ran a short pilot before the lap-time lab and sampled twelve lap times, already sorted: 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1. Find the median and the quartiles.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Median.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> First quartile.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Third quartile.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
