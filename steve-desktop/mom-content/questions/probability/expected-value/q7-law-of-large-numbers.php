// === NAME - DESCRIPTION: Law of Large Numbers - The long-run relative frequency approaches the theoretical probability ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Three contexts for the Law of Large Numbers. Parts:
//   (a) choices - best description of the Law of Large Numbers (constant answer)
//   (b) choices - what a short-run outcome promises about the next trial (constant answer)
//   (c) number  - expected count = n * p for the stated experiment (varies)
// Invariant: answers (a) and (b) are constant across seeds; (c) = n * p.

$anstypes = array("choices", "choices", "number")
$answerformat[2] = "integer"

$scenarios = array(
  "A fair coin is flipped 10 times and lands heads 7 times. You are about to flip it an 11th time.",
  "A fair six-sided die is rolled 10 times and shows a six 4 times. You are about to roll it an 11th time.",
  "A basketball player makes 60% of her free throws. She has just made 5 in a row. She steps to the line for another."
)

$ns = array(24000, 60000, 500)
$ps = array(0.5, 1/6, 0.6)
$pDisps = array("0.5", "1/6", "0.6")
$expecteds = array(12000, 10000, 300)

$countNouns = array("heads", "sixes", "makes")
$eventPhrases = array(
  "the next flip lands heads",
  "the next roll shows a six",
  "her next free throw goes in"
)
$runPhrases = array(
  "the 10 flips so far",
  "the 10 rolls so far",
  "the 5 makes in a row so far"
)

$i = rand(0, 2)
$scenario = $scenarios[$i]
$n = $ns[$i]
$p = $ps[$i]
$pDisp = $pDisps[$i]
$expected = $expecteds[$i]
$countNoun = $countNouns[$i]
$eventPhrase = $eventPhrases[$i]
$runPhrase = $runPhrases[$i]

$questions[0] = array(
  "As the number of trials grows, the relative frequency of an event approaches its theoretical probability.",
  "A long run of one outcome makes the opposite outcome more likely, so the results even out.",
  "The theoretical probability of an event changes to match the results you have observed.",
  "After enough trials, every outcome is guaranteed to occur exactly its theoretical number of times."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "Probability makes no promise about a single trial: the next outcome has the same probability it always had.",
  "Because the run has been long, the next trial is more likely to break it.",
  "The next trial is guaranteed to break the run.",
  "The next trial is guaranteed to continue the run."
)
$answer[1] = 0
$noshuffle[1] = "all"

$answer[2] = $expected

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
      <p><span class="term-label">Part (a): the Law of Large Numbers.</span> As the number of trials in a probability experiment increases, the difference between the theoretical probability of an event and its relative frequency approaches zero. The two get closer and closer together: that is what the law says, and nothing more.</p>
      <p><span class="term-label">Part (b): the next trial.</span> Probability says nothing about the short term. ' . $runPhrase . ' happened, but ' . $eventPhrase . ' with exactly the same probability as always: the deck, coin or player does not remember, and there is no tendency to "make up for" the run.</p>
      <p><span class="term-label">Part (c): the expected count.</span> The expected value is the long-run average: `mu = n * p = ' . $n . ' * ' . $pDisp . ' = ` <b>' . $expected . '</b> ' . $countNoun . '.</p>
      <p>Karl Pearson once tossed a fair coin 24,000 times and recorded 12,012 heads: a relative frequency of 0.5005, almost dead on the theoretical 0.5. Ten tosses can land anywhere. Twenty-four thousand cannot.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $scenario</p>
    <p style="margin:0;">Answer each part about long-term probability.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which statement best describes the Law of Large Numbers?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Given the run described, what does probability promise about the next trial?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> If the experiment is repeated a very large number of times (say $n trials), how many $countNoun would you expect in the long run?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
