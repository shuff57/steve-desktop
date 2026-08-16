// === NAME - DESCRIPTION: Bar Widths and Expected Counts - 8 bars over [0,1] for 50 values ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Organize-the-Data step. Parts: (a) numfunc - width of each of 8 bars = 1/8 = 0.125
// (b) numfunc - expected count per bar for 50 values = 50 * 0.125 = 6.25.
// Invariant: (a) = 0.125 and (b) = 6.25 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 0.125
$answer[1] = 6.25
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
      <p><span class="term-label">Part (a) &mdash; the bar width.</span> The interval from 0 to 1 has length 1, split into 8 equal pieces:</p>
      <p>`1/8 = 0.125`</p>
      <p><span class="term-label">Part (b) &mdash; the expected count.</span> Because every value between 0 and 1 is equally likely, the chance of landing in a bar is exactly that bar\'s width, so the expected count is 50 times the width:</p>
      <p>`50(0.125) = 6.25`</p>
      <p>The 6.25 is worth staring at — no bar can hold a quarter of a value. An expected count is an average over many reruns of the lab, not a prediction of what any one histogram will show, which is exactly why a bar holding 4 or 9 is not evidence that the generator is broken.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For 50 values generated between 0 and 1, find the width of each bar and the number of values you would expect in each bar, for eight bars.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The width of each bar.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The expected number of values in each bar.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
