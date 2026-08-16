// === NAME - DESCRIPTION: Lab: Honesty Check - the convenience-sample limit and the self-reported-data caveat ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's honesty discussion (8.6.1's two honesty problems). Parts: (a) choices - why the
// conclusion is limited (b) choices - why self-reported data is soft (c) choices - whether
// either problem invalidates the arithmetic.
// Invariant: all three answers are constant on every seed.

$anstypes = array("choices", "choices", "choices")

$questions[0] = array(
  "The class is a convenience sample, so the conclusion describes the population actually sampled from &mdash; not the whole school.",
  "The sample is too small to be useful.",
  "The arithmetic is wrong for a class sample."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "People round, estimate, and shade their answers toward what sounds normal, so a small effect may measure the way people report rather than the way people behave.",
  "The data are collected by the teacher, so they are always accurate.",
  "The data are collected anonymously, so they are always accurate."
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "No &mdash; the arithmetic is identical and the practice is real; it limits what the conclusion is a conclusion about.",
  "Yes &mdash; a convenience sample makes the test meaningless.",
  "Yes &mdash; self-reported data can never be tested."
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
      <p><span class="term-label">Part (a) &mdash; the convenience-sample limit.</span> Your class is not a random sample of your school, and the students in a statistics class are not interchangeable with the students who are not in one. When you write the final sentence of each test, write it about the population you actually sampled from, and say plainly that the sample was one of convenience.</p>
      <p><span class="term-label">Part (b) &mdash; the self-reported caveat.</span> Every value is self-reported: nobody is watching you watch television, counting the languages spoken in your home, or opening your closet to audit the jeans. People round, people estimate, and people shade their answers toward what sounds normal.</p>
      <p><span class="term-label">Part (c) &mdash; does it invalidate the arithmetic?</span> No &mdash; the arithmetic is identical, and the practice is real. A test run on a convenience sample can be executed perfectly and still not support the sweeping claim it looks like it supports. Record the number you were given and note where you suspect the reporting is soft.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The lab collects data from your own class and every value is self-reported.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Why is the conclusion limited?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Why is self-reported data soft?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Does either problem invalidate the arithmetic?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
