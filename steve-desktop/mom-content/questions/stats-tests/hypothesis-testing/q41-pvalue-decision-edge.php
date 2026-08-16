// === NAME - DESCRIPTION: P-Value Decision Edge - the rigid rule when the p-value is close to or equal to alpha ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A test where the p-value is close to alpha, including at least one scenario with
// p-value = alpha exactly. Parts: (a) choices - the correct decision (apply the rule rigidly)
// (b) choices - the conclusion worded about the claim.
// Invariant: (a) and (b) are constant per scenario and consistent with the actual comparison.

$anstypes = array("choices", "choices")

$cases = array(
  array("A test of whether the mean hours of sleep for adults is less than 7 produces a p-value of 0.048 at alpha = 0.05.",
        "Reject `H_0` &mdash; 0.048 is below 0.05, so the sample result is rarer than the standard you agreed to be surprised by.",
        "At the 5% significance level there is sufficient evidence to conclude the mean is less than 7 hours."),
  array("A test of whether the mean daily fiber intake of college students differs from 25 g produces a p-value of 0.052 at alpha = 0.05.",
        "Fail to reject `H_0` &mdash; 0.052 is above 0.05, so the sample result is not rare enough.",
        "At the 5% significance level there is not sufficient evidence to conclude the mean differs from 25 g."),
  array("A test of whether the proportion of voters who support a measure is greater than 0.30 produces a p-value of exactly 0.05 at alpha = 0.05.",
        "Fail to reject `H_0` &mdash; the rule rejects only when alpha is GREATER than the p-value, and equality is not greater than.",
        "At the 5% significance level there is not sufficient evidence to conclude the proportion is greater than 0.30."),
  array("A test of whether the mean commute time of city residents differs from 28 minutes produces a p-value of 0.049 at alpha = 0.05.",
        "Reject `H_0` &mdash; 0.049 is below 0.05, so the sample result is rarer than the standard you agreed to be surprised by.",
        "At the 5% significance level there is sufficient evidence to conclude the mean differs from 28 minutes.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$decision = $cases[$i][1]
$conclusion = $cases[$i][2]

$questions[0] = array(
  $decision,
  "Reject `H_0` &mdash; the p-value is close to alpha, so the evidence is strong enough.",
  "Fail to reject `H_0` &mdash; the p-value is close to alpha, so the evidence is too weak."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $conclusion,
  "The claim is false.",
  "We accept `H_0`."
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
      <p><span class="term-label">Part (a) &mdash; the rigid rule.</span> The decision rule rejects only when `alpha > p`-value. Equality is not "greater than", so `alpha = p`-value falls under the do-not-reject branch. The p-value being close to alpha does not change the rule &mdash; it only changes how much confidence you have in the decision.</p>
      <p><span class="term-label">Part (b) &mdash; the conclusion.</span> ' . $conclusion . '</p>
      <p>Choosing alpha after seeing a p-value of 0.048 is not a decision, it is a rationalization &mdash; and it is the single easiest way to turn a real procedure into a rubber stamp.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the correct decision?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the conclusion?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
