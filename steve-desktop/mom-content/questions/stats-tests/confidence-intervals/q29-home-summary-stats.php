// === NAME - DESCRIPTION: The Home Summary Statistics - x-bar, s, and n of the 35 prices ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Describe-the-Data step (Try It Now 7.3) on the fixed 35-value dataset (Table 7.4.1):
// sum $14,350,000, sum of squares 6,317,692 x 10^6.
// Parts: (a) numfunc - x-bar = 14,350,000/35 = 410000 (b) numfunc - s ~ 113006 (c) numfunc - n = 35.
// Invariant: ~ 410000, ~ 113006, 35 on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$answer[0] = 410000
$answer[1] = 113006
$answer[2] = 35
$abstolerance[0] = 0.5
$abstolerance[1] = 0.5
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
      <p><span class="term-label">Part (a) &mdash; the sample mean.</span> The 35 prices sum to $14,350,000, so</p>
      <p>`x-bar = 14,350,000/35 = 410,000`</p>
      <p><span class="term-label">Part (b) &mdash; the sample standard deviation.</span> The sum of squares is 6,317,692 x 10^6, so the total squared deviation is `(6,317,692 - 5,883,500) x 10^6 = 434,192 x 10^6`, and</p>
      <p>`s^2 = 434,192 x 10^6 / 34 ~= 1.2770 x 10^10`, so `s ~= 113,006`</p>
      <p><span class="term-label">Part (c) &mdash; the count.</span> Five rows of seven prices each gives `n = 35`.</p>
      <p>Look at the size of that standard deviation before moving on &mdash; it is more than a quarter of the mean, which tells you the county\'s listings are spread across a very wide range of prices. That spread is what will make the interval as wide as it turns out to be, and it is a fact about the housing market, not a flaw in the sample.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Compute the summary statistics for the demonstration data in Table 7.4.1. The sum of the 35 prices is $14,350,000 and the sum of their squares is 6,317,692 x 10^6.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `x-bar =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> `s_x =`
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> `n =`
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
