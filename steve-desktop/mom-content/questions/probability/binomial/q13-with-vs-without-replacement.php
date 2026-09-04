// === NAME - DESCRIPTION: With Replacement or Without - The two-draw probability both ways ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Try It Now 4.3. Parts: (a) numfunc - P(both diamonds) WITH replacement = 0.0625
// (b) numfunc - WITHOUT replacement = (13/52)(12/51) ~ 0.0588.
// Invariant: (a) = 0.0625 and (b) ~ 0.0588 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 0.0625
$answer[1] = 0.0588
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
      <p><span class="term-label">Part (a): with replacement.</span> The card goes back, so the second draw faces the same 52-card deck as the first and the two draws are independent:</p>
      <p>`P(both diamonds) = (13/52)(13/52) = (0.25)(0.25) = 0.0625`</p>
      <p><span class="term-label">Part (b): without replacement.</span> The first diamond is gone, so the second draw faces 51 cards of which only 12 are diamonds:</p>
      <p>`P(both diamonds) = (13/52)(12/51) = (0.25)(0.2353) ~= 0.0588`</p>
      <p><span class="term-label">Which one the binomial assumes.</span> The binomial formula assumes the first one. The gap looks small, and on two draws it is. It is not small in principle: without replacement, the second draw\'s probability depends on the first draw\'s outcome, and dependence is the one thing the binomial model is not allowed to have. Replacing the card is not a fussy detail of lab procedure; it is the step that makes `B(10, 0.25)` the correct model rather than an approximation to one.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Find the probability that the first two draws are both diamonds, once with replacement and once without.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> With replacement.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Without replacement.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
