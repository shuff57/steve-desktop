// === NAME - DESCRIPTION: Two P-Values, One Test - which p-value rejects and why, and what the distance from alpha means for confidence ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Two p-values for the SAME test. Parts: (a) choices - which p-value leads to rejecting H0
// and why (b) choices - what a larger p-value at the same alpha means for confidence.
// Invariant: both answers are constant per scenario.

$anstypes = array("choices", "choices")

$cases = array(
  array("A test of whether the mean hours of sleep for adults is less than 7 at alpha = 0.05. Run 1 gives a p-value of 0.03; run 2 gives a p-value of 0.40.",
        "Run 1 &mdash; 0.03 is below alpha, so the sample result is rarer than the standard you agreed to be surprised by.",
        "The decision is the same either way &mdash; the distance from alpha is what changes the confidence, not the decision."),
  array("A test of whether the proportion of voters who support a measure differs from 0.30 at alpha = 0.05. Run 1 gives a p-value of 0.001; run 2 gives a p-value of 0.04.",
        "Both &mdash; both are below alpha, so both reject.",
        "A p-value of 0.001 earns more confidence in the decision than 0.04, even though both reject."),
  array("A test of whether the mean daily fiber intake of college students differs from 25 g at alpha = 0.01. Run 1 gives a p-value of 0.02; run 2 gives a p-value of 0.4.",
        "Neither &mdash; both are above alpha = 0.01, so both fail to reject.",
        "The decision is the same either way &mdash; the distance from alpha is what changes the confidence, not the decision.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$which = $cases[$i][1]
$confidence = $cases[$i][2]

$questions[0] = array(
  $which,
  "Run 2 &mdash; the larger p-value is the one that rejects.",
  "The run with the smaller sample size."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $confidence,
  "A larger p-value always means the claim is true.",
  "A larger p-value means the test was not run correctly."
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
      <p><span class="term-label">Part (a) &mdash; which rejects.</span> ' . $which . '</p>
      <p><span class="term-label">Part (b) &mdash; confidence.</span> ' . $confidence . ' The two numbers carry more information than the yes-or-no answer they produce: the comparison gives the decision, and the distance between them gives the confidence.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which run leads to rejecting `H_0`, and why?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the distance from alpha mean for confidence in the decision?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
