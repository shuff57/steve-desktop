// === NAME - DESCRIPTION: The Pilot Percentiles - P15 and P85 by the index rule ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's percentile blanks on the fixed 12-value pilot dataset:
// 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1
// Parts: (a) numfunc - P15 = (125.9 + 126.4)/2 = 126.15
// (b) numfunc - P85 = (132.6 + 134.1)/2 = 133.35
// Invariant: ~ 126.15 and ~ 133.35 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 126.15
$answer[1] = 133.35
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
      <p><span class="term-label">The index rule.</span> A percentile is found by the index rule `i = (k/100)(n + 1)` from Section 2.3, then averaging the two bracketing values when `i` is not a whole number.</p>
      <p><span class="term-label">Part (a) &mdash; the 15th percentile.</span> `i = (15/100)(12 + 1) = 1.95`, so average the 1st and 2nd values:</p>
      <p>`P15 = (125.9 + 126.4)/2 = 252.3/2 = 126.15`</p>
      <p><span class="term-label">Part (b) &mdash; the 85th percentile.</span> `i = (85/100)(13) = 11.05`, so average the 11th and 12th values:</p>
      <p>`P85 = (132.6 + 134.1)/2 = 266.7/2 = 133.35`</p>
      <p>The 85th percentile says that 85% of these lap times were faster than 133.35 seconds and 15% were slower &mdash; a lap over 133.35 seconds was one of the worst.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A group ran a short pilot before the lap-time lab and sampled twelve lap times, already sorted: 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1. Find the percentiles by the index rule `i = (k/100)(n + 1)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The 15th percentile.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The 85th percentile.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
