// === NAME - DESCRIPTION: The Theoretical Probability - P(diamond) = 13/52 and X ~ B(10, 0.25) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's first blank. Parts: (a) numfunc - P(diamond) = 13/52 = 0.25
// (b) choices - the distribution of X, the number of diamonds in 10 replaced draws.
// Invariant: (a) = 0.25 on every seed; (b) constant.

$anstypes = array("numfunc", "choices")

$answer[0] = 0.25
$abstolerance[0] = 0.005

$questions[1] = array(
  "`X ~ B(10, 0.25)`: binomial with 10 trials and success probability 0.25",
  "`X ~ B(10, 0.5)`: binomial with 10 trials and success probability 0.5",
  "`X ~ U(0, 10)`: uniform over 0 to 10",
  "`X` is not binomial, because the draws are not independent"
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">Part (a): the theoretical probability.</span> A standard deck has 52 cards in four suits of 13 each, so 13 of the 52 cards are diamonds:</p>
      <p>`P(diamond) = 13/52 = 1/4 = 0.25`</p>
      <p><span class="term-label">Part (b): the distribution.</span> There are ten draws, fixed in advance. Each draw is a diamond (success) or not (failure). Because the card is replaced and the deck reshuffled, the draws are independent and the success probability stays at 0.25 for every one of them. So `X ~ B(10, 0.25)`.</p>
      <p>Step 5 of the procedure is the one students skip, and it is the one that makes the arithmetic work: the card goes back before the next draw, so every draw faces the same full 52-card deck. That is sampling with replacement, and it is what lets you treat the ten draws as ten independent trials.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In the playing-card experiment, you draw one card from a shuffled deck, record whether it is a diamond, put it back, and repeat ten times. Let `X` be the number of diamonds drawn.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the theoretical probability of drawing a diamond?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Theoretically, what is the distribution of `X`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
