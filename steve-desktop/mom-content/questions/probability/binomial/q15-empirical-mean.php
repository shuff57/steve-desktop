// === NAME - DESCRIPTION: The Empirical Mean - x-bar of the 30-group class data ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Organize-the-Data step. x-bar of the fixed dataset = 76/30 ~ 2.5333.
// Invariant: ~ 2.5333 on every seed.

$anstypes = array("numfunc")

$answer[0] = 2.5333
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
      <p><span class="term-label">Weight each value by how often it happened.</span></p>
      <p>`x-bar = (0(2) + 1(5) + 2(9) + 3(7) + 4(4) + 5(2) + 6(1)) / 30`</p>
      <p>`= (0 + 5 + 18 + 21 + 16 + 10 + 6) / 30 = 76/30 ~= 2.5333`</p>
      <p>That mean sits within a rounding error of the theoretical 2.5, which is what pooling 30 repetitions buys you. Any one of those 30 groups, looking only at its own ten draws, saw a whole number, 0, or 4, or 6, and none of them saw 2.5 at all. The average is not a value the experiment can produce; it is a value the experiment\'s results cluster around.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Using the same 30-group data (2 groups at 0, 5 at 1, 9 at 2, 7 at 3, 4 at 4, 2 at 5, 1 at 6), find the sample mean `x-bar`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `x-bar =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
