// === NAME - DESCRIPTION: Both Errors with the Template - Type I and Type II errors stated in context from a stated null ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A scenario with a stated null. Parts: (a) choices - the Type I error stated in context
// (b) choices - the Type II error stated in context.
// Invariant: both answers are constant per scenario and match the stated null.

$anstypes = array("choices", "choices")

$cases = array(
  array("A school claims its students study an average of 15 hours per week. The null hypothesis is `H_0: mu >= 15`; the alternative is `H_a: mu < 15`.",
        "Conclude the mean study time is less than 15 hours when in fact it is at least 15.",
        "Conclude the mean study time is at least 15 hours when in fact it is less than 15."),
  array("A factory claims the mean lifespan of its tires is at least 50,000 miles. The null hypothesis is `H_0: mu >= 50000`; the alternative is `H_a: mu < 50000`.",
        "Conclude the mean lifespan is less than 50,000 miles when in fact it is at least 50,000.",
        "Conclude the mean lifespan is at least 50,000 miles when in fact it is less than 50,000."),
  array("An instructor believes fewer than 20% of students attended the midnight showing. The null hypothesis is `H_0: p >= 0.20`; the alternative is `H_a: p < 0.20`.",
        "Conclude fewer than 20% attended when in fact at least 20% did.",
        "Conclude at least 20% attended when in fact fewer than 20% did."),
  array("A health organization claims 9.5% of adults suffer from depression. The null hypothesis is `H_0: p = 0.095`; the alternative is `H_a: p != 0.095`.",
        "Conclude the proportion differs from 9.5% when in fact it is 9.5%.",
        "Conclude the proportion is 9.5% when in fact it differs from 9.5%.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$type1 = $cases[$i][1]
$type2 = $cases[$i][2]

$questions[0] = array(
  $type1,
  $type2,
  "The sample was not random."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $type2,
  $type1,
  "The sample was not random."
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
      <p><span class="term-label">The template.</span> Use the same template for every part: each statement is the null hypothesis. The Type I error says the claim was true and we rejected it; the Type II error says the claim was false and we did not.</p>
      <p><span class="term-label">Part (a): Type I.</span> ' . $type1 . '</p>
      <p><span class="term-label">Part (b): Type II.</span> ' . $type2 . '</p>
      <p>The trick that never fails: write down what `H_0` says, then say out loud "I rejected it, and it was true" for the Type I error and "I did not reject it, and it was false" for the Type II.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the Type I error in this context?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the Type II error in this context?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
