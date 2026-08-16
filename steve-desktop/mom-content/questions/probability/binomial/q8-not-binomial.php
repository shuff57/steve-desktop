// === NAME - DESCRIPTION: When an Experiment Is Not Binomial - Without replacement breaks independence ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four scenarios, EVERY one NOT binomial (all fail independence via without-replacement).
// Parts: (a) choices - is this binomial? (b) choices - which condition fails?
// Invariant: answers constant across seeds (every scenario fails independence).

$anstypes = array("choices", "choices")

$ctxs = array(
  "Two names are drawn one at a time WITHOUT replacement from a box of 16 committee members (10 staff, 6 students). Let `X` be the number of students drawn.",
  "Three names are drawn one at a time WITHOUT replacement from a hat of 20 seniors. Let `X` be the number of captains who play the same position of interest.",
  "Five cards are dealt one at a time WITHOUT replacement from a standard 52-card deck. Let `X` be the number of diamonds dealt.",
  "Four tiles are drawn one at a time WITHOUT replacement from a bag of 30 tiles (18 red, 12 blue). Let `X` be the number of red tiles drawn."
)

$questions[0] = array(
  "No",
  "Yes"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The trials are not independent: drawing without replacement changes the success probability from trial to trial",
  "There is no fixed number of trials",
  "Each trial has more than two possible outcomes",
  "None of the conditions fail"
)
$answer[1] = 0
$noshuffle[1] = "all"

$i = rand(0, 3)
$ctx = $ctxs[$i]

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
      <p><span class="term-label">The three conditions are a test.</span> Two of them hold here: the number of draws is fixed, and each draw comes out one of two ways.</p>
      <p><span class="term-label">The one that fails.</span> The draws are WITHOUT replacement. After each draw the pool shrinks and its composition changes, so the probability of a success on the next draw depends on what came before. The trials are not independent, and condition 3 fails.</p>
      <p><span class="term-label">Replacement is the tell.</span> If the thing you draw goes back before the next draw, `p` holds steady and the experiment stays binomial. If it does not go back, `p` moves, and the binomial formula no longer applies.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is this a binomial experiment?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which condition fails?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
