// === NAME - DESCRIPTION: Binomial or Not - Classify each described experiment ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Three experiments per seed, each binomial or not. Parts (a)-(c): choices.
// Scenario sets (verified):
//   Set 0: (a) 20 fair coin flips -> binomial; (b) 10 cards WITHOUT replacement -> NOT;
//          (c) 50 students, 70% on time, independent -> binomial
//   Set 1: (a) 12 free throws, 60% -> binomial; (b) 3 captains drawn WITHOUT replacement -> NOT;
//          (c) 30 adults, 32% volunteer -> binomial
//   Set 2: (a) 5 true-false guesses, 80% -> binomial; (b) 4 tiles WITHOUT replacement -> NOT;
//          (c) 100 pages, 8/560 signature -> binomial
// Invariant: exactly one of the three is NOT binomial on every seed.

$anstypes = array("choices", "choices", "choices")

$setA = array(
  "A fair coin is flipped 20 times. Let `X` be the number of heads.",
  "A basketball player makes 60% of her free throws, independently. She shoots 12 free throws. Let `X` be the number she makes.",
  "A student answers 5 true-false questions by guessing, with probability 0.8 of each being right, independently. Let `X` be the number correct."
)

$setB = array(
  "Ten cards are drawn one at a time from a standard 52-card deck WITHOUT replacement. Let `X` be the number of diamonds drawn.",
  "Three names are drawn one at a time WITHOUT replacement from a hat of 20 seniors. Let `X` be the number who play the position of interest.",
  "Four tiles are drawn one at a time WITHOUT replacement from a bag of 30 tiles (18 red, 12 blue). Let `X` be the number of red tiles drawn."
)

$setC = array(
  "In a class of 50 statistics students, 70% do their homework on time, independently. Let `X` be the number who do homework on time.",
  "In a random sample of 30 students, 32% participate in a community volunteer program. Let `X` be the number who participate.",
  "In a 560-page catalog, 8 pages feature signature artists. A random sample of 100 pages is chosen. Let `X` be the number that feature signature artists."
)

$i = rand(0, 2)
$ctxA = $setA[$i]
$ctxB = $setB[$i]
$ctxC = $setC[$i]

$questions[0] = array("Yes", "No")
$answer[0] = 0
$noshuffle[0] = "all"
$questions[1] = array("Yes", "No")
$answer[1] = 1
$noshuffle[1] = "all"
$questions[2] = array("Yes", "No")
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
      <p><span class="term-label">The three conditions.</span> Fixed number of trials; two outcomes per trial with constant success probability; independent trials. All three must hold.</p>
      <p><span class="term-label">Part (a).</span> ' . $ctxA . ': fixed trials, two outcomes, independent: <b>binomial</b>.</p>
      <p><span class="term-label">Part (b).</span> ' . $ctxB . ': the draws are WITHOUT replacement, so the pool shrinks and the success probability changes from trial to trial. The trials are dependent: <b>NOT binomial</b>.</p>
      <p><span class="term-label">Part (c).</span> ' . $ctxC . ': fixed trials, two outcomes, independent: <b>binomial</b>.</p>
      <p>Replacement is the tell: if the thing you draw goes back before the next draw, `p` holds steady and the experiment stays binomial. If it does not go back, `p` moves, and the binomial formula no longer applies.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $ctxA Is this a binomial experiment?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $ctxB Is this a binomial experiment?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $ctxC Is this a binomial experiment?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
