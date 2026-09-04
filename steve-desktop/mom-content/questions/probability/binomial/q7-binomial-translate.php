// === NAME - DESCRIPTION: Translate Binomial Phrases - Which inequality does the English ask for ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Three fixed phrase sets (constant answers); context varies in threes. Parts (a)-(c): choices.
// Invariant: answers are constant across seeds; each matches the section's Context Pause.

$anstypes = array("choices", "choices", "choices")

$ctxs = array(
  "A school newspaper surveys 12 students about attending Tet festivities; past years say 18% attend. Let `X` be the number of the 12 who attend.",
  "In a random sample of 50 adults, 60% prefer saving over spending. Let `X` be the number who prefer saving.",
  "A student guesses at 32 multiple-choice questions, each with three choices. Let `X` be the number correct."
)

$phraseSets = array(
  array("the probability that at least 4 of the 12 students attend", "the probability that more than 2 of the 12 attend", "the probability that at most 5 of the 12 attend"),
  array("the probability that at most 20 of the 50 prefer saving", "the probability that more than 30 of the 50 prefer saving", "the probability that at least 25 of the 50 prefer saving"),
  array("the probability that more than 24 of the 32 are correct", "the probability that at most 10 of the 32 are correct", "the probability that at least 8 of the 32 are correct")
)

$i = rand(0, 2)
$ctx = $ctxs[$i]
$phrases = $phraseSets[$i]

$questions[0] = array(
  "`X >= k` (at least / greater than or equal to)",
  "`X > k` (strictly greater than)",
  "`X <= k` (at most / less than or equal to)",
  "`X < k` (strictly less than)"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "`X >= k` (at least / greater than or equal to)",
  "`X > k` (strictly greater than)",
  "`X <= k` (at most / less than or equal to)",
  "`X < k` (strictly less than)"
)
$answer[1] = 1
$noshuffle[1] = "all"

$questions[2] = array(
  "`X >= k` (at least / greater than or equal to)",
  "`X > k` (strictly greater than)",
  "`X <= k` (at most / less than or equal to)",
  "`X < k` (strictly less than)"
)
$answer[2] = 2
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
      <p><span class="term-label">The phrase tells you the inequality.</span> "At least k" means `X >= k`; "at most k" means `X <= k`; "more than k" means `X > k`; "less than k" means `X < k`.</p>
      <p><span class="term-label">Part (a).</span> ' . $phrases[0] . ': "at least" is `>=`, so `X >= k`.</p>
      <p><span class="term-label">Part (b).</span> ' . $phrases[1] . ': "more than" is strictly greater, so `X > k`.</p>
      <p><span class="term-label">Part (c).</span> ' . $phrases[2] . ': "at most" is `<=`, so `X <= k`.</p>
      <p>Mistranslating one of these is the single most common way a correct formula produces a wrong answer: the arithmetic is the same either way, only the rows you add change.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx For each phrase, choose the inequality it translates to.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $phrases[0]
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $phrases[1]
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $phrases[2]
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
