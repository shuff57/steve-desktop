// === NAME - DESCRIPTION: Count Inside the Interval - 9 of 35 prices, about 25.7% ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's data-counting step (Try It Now 7.7): 9 of the 35 prices lie inside (377702, 442298).
// Parts: (a) numfunc - the count (b) numfunc - the percent.
// Invariant: 9 and ~ 25.7 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 9
$answer[1] = 25.7
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
      <p><span class="term-label">Part (a) &mdash; the count.</span> Reading Table 7.4.1 from $377,702 upward and stopping at $442,298:</p>
      <p>`379,000, 385,000, 392,000, 399,000, 405,000, 412,000, 420,000, 429,000, 438,000`</p>
      <p>That is 9 values out of 35.</p>
      <p><span class="term-label">Part (b) &mdash; the percent.</span> `9/35 ~= 0.257 = 25.7%`</p>
      <p><span class="term-label">Why it should not be close to 90%.</span> The interval was built around `bar(X)`, whose spread is the standard error `s_x/sqrt(n) ~= $19,101`. Individual prices spread out by `s_x ~= $113,006`, nearly six times as much. The interval is a statement about where the population mean plausibly sits, not about where individual homes sell &mdash; and making it wide enough to hold 90% of the homes would tell you almost nothing about `mu`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Some students think that a 90% confidence interval contains 90% of the data. Count how many of the 35 prices in Table 7.4.1 lie within the interval ($377,702, $442,298), and convert that to a percent.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many of the 35 prices lie inside the interval?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> That count as a percent. (Round to 1 decimal place.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
