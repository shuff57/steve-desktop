// === NAME - DESCRIPTION: Systematic Versus Simple Random - exactly one random decision in the whole procedure, and why that makes it systematic ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

$questions[0] = array(
  "Exactly one: the starting point. Every later mark is determined by adding 4.",
  "Twelve: each marked name is its own random pick.",
  "None: the rule is fully mechanical, so nothing is random at all.",
  "Two: the start and the step size are both chosen randomly."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "One random start plus a fixed step decides all 12 names, so not every group of 12 is equally likely.",
  "It uses a numbered list, and a simple random sample never uses a list.",
  "The start is chosen randomly, which is all that a simple random sample requires.",
  "It is the same thing as a simple random sample, just with a rule to follow."
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
      <p><span class="term-label">The random part.</span> The randomness lives entirely in the one starting name you pick. After that the rule is mechanical, so nobody&rsquo;s preferences can sneak in &mdash; not yours, and not your classmates&rsquo;.</p>
      <p><span class="term-label">Why it is not a simple random sample.</span> In a simple random sample, every possible group of 12 classmates is equally likely to be chosen. Once you have committed to a starting point and a step of 4, eleven of your twelve names are already decided &mdash; there is exactly one genuinely random decision in the entire procedure.</p>
      <p><span class="term-label">Why the rule is still worth having.</span> It is far easier to carry out in a classroom than drawing twelve names from a hat, and it spreads your picks evenly across the whole list. The one thing to watch for is a list with a repeating pattern built into it: if the step lines up with that pattern, you sample from one slice of the list over and over.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In the lab, a class randomly picks one starting name on the class list, then steps down 4 names at a time, wrapping at the bottom, until 12 names are marked.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>a.</b> How many genuinely random decisions does the whole procedure make? $answerbox[0]</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>b.</b> What makes this a <b>systematic sample</b> rather than a simple random sample? $answerbox[1]</p>
  </div>
</div>


// === ANSWER ===

$solutionguide