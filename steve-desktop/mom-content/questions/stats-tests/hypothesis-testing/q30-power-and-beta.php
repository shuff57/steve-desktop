// === NAME - DESCRIPTION: Power and Beta - beta = 1 - power, and what the power value means in context ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A test with stated power. Parts: (a) numfunc - beta = 1 - power
// (b) choices - what the power value means in context.
// Invariant: (a) = 1 - power exactly, (b) is constant per scenario.

$anstypes = array("numfunc", "choices")

$cases = array(
  array("A medical trial for a new drug", 0.981, "the chance the trial catches the drug's real effect when one exists"),
  array("A factory's quality-control test", 0.88, "the chance the test catches a real defect rate when one exists"),
  array("A school district's tutoring-program evaluation", 0.70, "the chance the evaluation catches a real improvement when one exists")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$power = $cases[$i][1]
$meaning = $cases[$i][2]

$beta = 1 - $power

$answer[0] = $beta
$abstolerance[0] = 0.005

$questions[1] = array(
  "The power of " . $power . " is " . $meaning . ".",
  "The power of " . $power . " is the probability that `H_0` is true.",
  "The power of " . $power . " is the probability of a Type I error."
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
      <p><span class="term-label">Part (a) &mdash; beta from power.</span> The power of the test is `1 - beta`, so `beta = 1 - power = 1 - ' . $power . ' = ' . $beta . '`. Power and beta are complements that have to sum to 1.</p>
      <p><span class="term-label">Part (b) &mdash; what the power means.</span> ' . $meaning . ' At power 0.40, twelve of twenty real effects are missed.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx has power $power.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is `beta`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the power value mean in context?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
