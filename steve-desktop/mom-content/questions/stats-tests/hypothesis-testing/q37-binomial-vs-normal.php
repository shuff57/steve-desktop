// === NAME - DESCRIPTION: Binomial vs Normal - what the raw count follows, and when it may be treated as normal ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A binomial-count scenario. Parts: (a) choices - what the raw count follows
// (b) choices - when it may be treated as normal.
// Invariant: both answers are constant across seeds.

$anstypes = array("choices", "choices")

$contexts = array(
  "A survey asks 200 people whether they own a cat, and counts the number who say yes.",
  "A factory inspects 150 parts and counts the number that are defective.",
  "A school surveys 80 students and counts how many prefer online classes."
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i]

$questions[0] = array(
  "A binomial distribution &mdash; each observation is a single success-or-failure trial, and the count across n independent trials is binomial.",
  "A normal distribution &mdash; the count is a measurement like any other.",
  "A t distribution &mdash; the count is an average."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "When the condition passes &mdash; both np and nq are above 5, so the binomial has enough mass on both sides of its center for the normal curve to be a fair stand-in.",
  "Whenever the sample size is at least 30.",
  "Never &mdash; a count can never be treated as normal."
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
      <p><span class="term-label">Part (a) &mdash; what the count follows.</span> Each observation is a single success-or-failure trial, and the count of successes across n independent trials is binomial.</p>
      <p><span class="term-label">Part (b) &mdash; when the normal curve may stand in.</span> The normal curve is only a stand-in when the binomial has enough mass on both sides of its center &mdash; both np and nq above 5. That is the condition that makes the approximation legal.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What does the raw count follow?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> When may it be treated as normal?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
