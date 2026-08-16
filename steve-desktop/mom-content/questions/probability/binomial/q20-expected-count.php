// === NAME - DESCRIPTION: The Expected Count - 30 times P(X = 2) against the observed 9 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Try It Now 4.8. Parts: (a) numfunc - 30 * 0.2816 = 8.448
// (b) numfunc - the observed count from the fixed dataset (9).
// Invariant: (a) ~ 8.448 and (b) = 9 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 8.448
$answer[1] = 9
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
      <p><span class="term-label">Part (a) &mdash; turn a probability into an expected count.</span> Multiply the probability of the outcome by the number of repetitions:</p>
      <p>`30 * 0.2816 = 8.448`</p>
      <p><span class="term-label">Part (b) &mdash; the observed count.</span> The class observed 9 groups at `x = 2`, against an expected 8.448.</p>
      <p>Two things are worth noticing. The expected count is not a whole number even though the observed count has to be, which is the same "you cannot draw 2.5 diamonds" point from a different angle. And this is the arithmetic that turns "the graphs look similar" into something you can defend: instead of comparing two pictures by eye, compare each bar\'s observed count against `30 * P(x)` and say which bars are off and by how much.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The theoretical distribution says `P(X = 2) ~= 0.2816`. If 30 groups each run the experiment once, how many of them should get exactly 2 diamonds? Compare that to the 9 groups who actually did.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The expected number of groups at exactly 2 diamonds.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The number of groups who actually got exactly 2 diamonds.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
