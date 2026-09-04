// === NAME - DESCRIPTION: Rare Event Reasoning - the probability of the observed event, and what observing it gives you ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A rare-event story. Parts: (a) numfunc - the probability of the observed event under the
// stated assumption (b) choices - what observing the rare event gives you
// (c) choices - why.
// Invariant: (a) is the precomputed probability exactly, (b) and (c) are constant across seeds.

$anstypes = array("numfunc", "choices", "choices")

$cases = array(
  array("There are 200 plastic bubbles in a basket, and only one holds a $100 bill. Mai reaches in first and pulls out the bubble with the $100 bill.",
        0.005),
  array("A jar is said to hold 500 marbles, exactly 2 of which are red. You draw one marble at random without looking and it is red.",
        0.004),
  array("A lottery sells 1,000 tickets and exactly one wins. You buy one ticket and it wins.",
        0.001)
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$prob = $cases[$i][1]

$answer[0] = $prob
$abstolerance[0] = 0.0005

$questions[1] = array(
  "A reason to doubt the assumption: NOT proof that it is wrong.",
  "Proof that the assumption is false.",
  "Proof that the assumption is true."
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "A rare event almost never happens by chance alone, so the assumption that made it rare is the thing that has to give way.",
  "Rare events are impossible, so the assumption must be wrong.",
  "Rare events prove the sample was not random."
)
$answer[2] = 0
$noshuffle[2] = "all"

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
      <p><span class="term-label">Part (a): the probability.</span> The probability of the observed event under the stated assumption is ' . $prob . ': five chances in a thousand (or fewer).</p>
      <p><span class="term-label">Part (b): what it gives you.</span> A reason to doubt the assumption: not proof that it is wrong. Rare things do happen; that is what makes them rare rather than impossible.</p>
      <p><span class="term-label">Part (c): why.</span> Because a rare event almost never happens by chance alone, observing one gives us reason to doubt the assumption that made it rare. The surprise is the evidence: nothing in a hypothesis test measures whether a claim is true: it measures how surprised you should be by your data if the claim were true.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the probability of the observed event under the stated assumption?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does observing the rare event give you?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
