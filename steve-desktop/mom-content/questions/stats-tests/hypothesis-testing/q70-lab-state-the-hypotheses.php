// === NAME - DESCRIPTION: Lab: State the Hypotheses - the H0/Ha pair for each of the three surveys ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's step-1 for all three surveys (the H0: ____ / Ha: ____ blanks in 8.6.2-8.6.4).
// Parts: (a) choices - the Television Survey's pair (b) choices - the Language Survey's pair
// (c) choices - the Jeans Survey's pair.
// Invariant: all three answers are constant on every seed.

$anstypes = array("choices", "choices", "choices")

$questions[0] = array(
  "`H_0: mu = 4`<br>`H_a: mu < 4`",
  "`H_0: mu < 4`<br>`H_a: mu = 4`",
  "`H_0: mu = 4`<br>`H_a: mu > 4`"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "`H_0: p = 0.423`<br>`H_a: p != 0.423`",
  "`H_0: p = 0.423`<br>`H_a: p < 0.423`",
  "`H_0: p < 0.423`<br>`H_a: p = 0.423`"
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "`H_0: mu = 3`<br>`H_a: mu > 3`",
  "`H_0: mu = 3`<br>`H_a: mu < 3`",
  "`H_0: mu > 3`<br>`H_a: mu = 3`"
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
      <p><span class="term-label">Part (a): Television Survey.</span> The claim on trial is the national average of 4 hours; the question asks whether students are lower. `H_0: mu = 4` and `H_a: mu < 4`.</p>
      <p><span class="term-label">Part (b): Language Survey.</span> "Different from" is two-sided: `H_0: p = 0.423` and `H_a: p != 0.423`.</p>
      <p><span class="term-label">Part (c): Jeans Survey.</span> "Higher than three" is one-sided to the right: `H_0: mu = 3` and `H_a: mu > 3`.</p>
      <p>The null hypothesis is the claim on trial, not the one you want: your guess goes in `H_a`, the null gets the benefit of the doubt, and your data has to be surprising enough under that assumption to overturn it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">State the hypotheses for each of the three surveys: the <b>Television Survey</b> (Americans watch 4 hours per day; are students lower?), the <b>Language Survey</b> (42.3% of Californians speak a language other than English at home; does the school differ?), and the <b>Jeans Survey</b> (young adults own 3 pairs of jeans; is the average higher?).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The Television Survey's hypotheses are:
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The Language Survey's hypotheses are:
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The Jeans Survey's hypotheses are:
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>

// === ANSWER ===

$solutionguide
