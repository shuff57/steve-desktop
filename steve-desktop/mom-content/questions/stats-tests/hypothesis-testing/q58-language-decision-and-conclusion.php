// === NAME - DESCRIPTION: Lab: Language Survey Decision and Conclusion - do not reject at 5%, worded about the school ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The Language Survey's decision (Try It Now 8.6.3 steps 5-6). Parts: (a) choices - the
// decision (b) choices - the conclusion.
// Invariant: both answers are constant on every seed.

$anstypes = array("choices", "choices")

$questions[0] = array(
  "Do not reject `H_0` &mdash; the two-tailed p-value 0.165 is above alpha = 0.05.",
  "Reject `H_0` &mdash; the two-tailed p-value 0.165 is below alpha = 0.05.",
  "Accept `H_0` &mdash; the evidence is strong enough."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "At the 5% significance level there is not sufficient evidence to conclude that the proportion of students at this school who speak a language other than English at home differs from 42.3%.",
  "At the 5% significance level there is sufficient evidence to conclude that the proportion differs from 42.3%.",
  "The claim that 42.3% of Californians speak a language other than English at home is false."
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
      <p><span class="term-label">Part (a) &mdash; the decision.</span> The two-tailed p-value is `2 * P(Z > 1.39) = 2(0.0823) = 0.165`. Compare to alpha = 0.05: 0.165 is above 0.05, so we do not reject `H_0`.</p>
      <p><span class="term-label">Part (b) &mdash; the conclusion.</span> At the 5% significance level there is not sufficient evidence to conclude that the proportion of students at this school who speak a language other than English at home differs from 42.3%.</p>
      <p>Worth noticing: 56% against 42.3% looks like a large gap, and it still is not significant &mdash; 25 students is a small sample, and the two-tailed test spends its evidence on both directions at once. Had the question been one-tailed, the p-value would have been 0.082, which is still above 0.05 but visibly closer.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The <b>Language Survey</b> test produced a two-tailed p-value of 0.165 at `alpha = 0.05`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Do you reject the null hypothesis?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Write the conclusion in a complete sentence.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
