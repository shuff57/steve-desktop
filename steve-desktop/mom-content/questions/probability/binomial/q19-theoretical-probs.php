// === NAME - DESCRIPTION: Theoretical Probabilities - P(1 < x < 4) and P(x >= 8) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Using-the-Data step. Parts: (a) numfunc - P(1 < x < 4) = P(2) + P(3) ~ 0.5319
// (b) numfunc - P(x >= 8) = P(8) + P(9) + P(10) ~ 0.0004.
// Invariant: ~ 0.5319 and ~ 0.0004 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 0.5319
$answer[1] = 0.0004
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
      <p><span class="term-label">Read the inequalities carefully.</span> `1 < x < 4` is a strict inequality on both ends, so it covers `x = 2` and `x = 3` only: 1 and 4 are outside it. `x >= 8` does include 8, so it covers 8, 9, and 10.</p>
      <p><span class="term-label">Part (a).</span> `P(1 < x < 4) = P(2) + P(3) = 0.2816 + 0.2503 = 0.5319`</p>
      <p><span class="term-label">Part (b).</span> `P(x >= 8) = P(8) + P(9) + P(10) = 0.0004 + 0.0000 + 0.0000 = 0.0004`</p>
      <p>Those two numbers say something worth pausing on: more than half of all classes will land on 2 or 3 diamonds, while fewer than 1 class in 2,000 will land on 8 or more. If your class did get 8 or more, you have not broken statistics: you have met the tail, and that is a better lab result than the boring one.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Using the theoretical distribution `X ~ B(10, 0.25)`, compute the following to four decimal places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `P(1 < x < 4) =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> `P(x >= 8) =`
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
