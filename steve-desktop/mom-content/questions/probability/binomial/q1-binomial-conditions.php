// === NAME - DESCRIPTION: Binomial Conditions - Test a described experiment against the three conditions ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four contexts; exactly one is NOT binomial (drawing without replacement breaks independence).
// Scenarios (verified):
//   S0: 50 students, 70% do homework on time, independent -> binomial (all three hold)
//   S1: 20 fair coin flips -> binomial
//   S2: 10 cards drawn WITHOUT replacement from a deck, count diamonds -> NOT binomial (independence fails)
//   S3: 30 adults, 32% volunteer, random sample -> binomial
// Parts: (a) fixed number of trials? (b) two outcomes with constant p? (c) trials independent?
// Invariant: answers match the scenario; exactly one scenario fails independence (S2).

$anstypes = array("choices", "choices", "choices")

$ctxs = array(
  "In a class of 50 statistics students, 70% do their homework on time. Students are selected randomly and each student does homework independently. Let `X` be the number who do homework on time.",
  "A fair coin is flipped 20 times. Let `X` be the number of heads.",
  "Ten cards are drawn one at a time from a standard 52-card deck WITHOUT replacement, and each draw is checked for whether it is a diamond. Let `X` be the number of diamonds drawn.",
  "In a random sample of 30 students, 32% participate in a community volunteer program. Students are selected randomly and independently. Let `X` be the number who participate."
)

$scenarioAns = array(
  array(0, 0, 0),
  array(0, 0, 0),
  array(0, 0, 1),
  array(0, 0, 0)
)

$i = rand(0, 3)
$ctx = $ctxs[$i]
$ansA = $scenarioAns[$i][0]
$ansB = $scenarioAns[$i][1]
$ansC = $scenarioAns[$i][2]

$answer[0] = $ansA
$answer[1] = $ansB
$answer[2] = $ansC

$questions[0] = array("Yes", "No")
$noshuffle[0] = "all"
$questions[1] = array("Yes", "No")
$noshuffle[1] = "all"
$questions[2] = array("Yes", "No")
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
    <div class="sol-body">';

$sg = '<p><span class="term-label">The three conditions.</span> (1) a fixed number of trials `n`; (2) exactly two outcomes per trial with the same success probability `p`; (3) the trials are independent and repeated under identical conditions. All three must hold.</p>'
if ($i == 2) {
  $sg = $sg . '<p><span class="term-label">This one is NOT binomial.</span> The first two conditions hold, there are ten draws and each is a diamond or not, but the card is NOT replaced, so the pool shrinks and the probability of a diamond changes from draw to draw. The draws are dependent, so condition 3 fails.</p>'
} else {
  $sg = $sg . '<p><span class="term-label">All three conditions hold.</span> There is a fixed number of trials, each trial has two outcomes with the same success probability, and the trials are independent: so this is a binomial experiment.</p>'
}
$sg = $sg . '<p>The word "success" does not mean the good outcome; it means the outcome you decided to count. Pick the label first, keep it fixed, and `p` is the probability of the thing you labelled.</p>'

$solutionguide = $solutionguide . $sg . '
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx Is this a binomial experiment? Answer each condition.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is there a fixed number of trials?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Does each trial have exactly two outcomes, with the same success probability `p` on every trial?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Are the trials independent?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
