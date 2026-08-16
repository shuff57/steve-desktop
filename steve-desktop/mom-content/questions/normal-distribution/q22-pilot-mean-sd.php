// === NAME - DESCRIPTION: The Pilot Mean and Standard Deviation - x-bar and s of the 12-value pilot ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Collect-the-Data step on the fixed 12-value pilot dataset:
// 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1
// Parts: (a) numfunc - x-bar = 1553.0/12 ~ 129.42 (b) numfunc - s ~ 2.52 (divide by n-1).
// Invariant: ~ 129.42 and ~ 2.52 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 129.42
$answer[1] = 2.52
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
      <p><span class="term-label">Part (a) &mdash; the sample mean.</span> Add the twelve values and divide by twelve:</p>
      <p>`x-bar = 1553.0/12 = 129.4167 ~= 129.42`</p>
      <p><span class="term-label">Part (b) &mdash; the sample standard deviation.</span> Find how far each value sits from the mean, square each distance, add the squares, divide by `n - 1 = 11` (this is a sample, not a population), and take the square root:</p>
      <p>`s = sqrt(69.896/11) ~= 2.5207 ~= 2.52`</p>
      <p>Notice how small the standard deviation is next to the mean &mdash; a spread of barely over two seconds around a lap that takes more than two minutes. A racer that consistent is exactly the kind of process a normal model has a chance of describing well.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A group ran a short pilot before the lap-time lab and sampled twelve lap times, already sorted: 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1. Calculate the sample mean and the sample standard deviation, carrying your answers to two decimal places.</p>
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
