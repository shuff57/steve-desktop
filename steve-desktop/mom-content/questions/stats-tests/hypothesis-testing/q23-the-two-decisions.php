// === NAME - DESCRIPTION: The Two Decisions - what do not reject means, why a test never accepts H0, and what reject means ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// One scenario per context. Parts: (a) choices - what "do not reject H0" means
// (b) choices - why a test can never "accept" H0 (c) choices - what "reject H0" means.
// Invariant: all three answers are constant across seeds.

$anstypes = array("choices", "choices", "choices")

$contexts = array(
  "A medical trial tests whether a new medicine reduces cholesterol by 25%.",
  "A school claims its students study an average of 15 hours per week.",
  "A factory claims the mean lifespan of its tires is at least 50,000 miles."
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i]

$questions[0] = array(
  "The sample did not settle the question: the evidence was not strong enough to overturn the claim.",
  "The claim has been proven true.",
  "The sample was too small to be useful."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The procedure is built to protect the null, so the only thing a test can do is find the evidence against it strong or find it weak: weak evidence tells you the sample did not settle the question, not that the claim is true.",
  "Because the null hypothesis is always true.",
  "Because the alternative hypothesis is always false."
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "The sample information favors the alternative hypothesis.",
  "The claim has been proven false.",
  "The sample was large enough to be useful."
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
      <p><span class="term-label">Part (a): do not reject.</span> Failing to find enough evidence against a claim is not the same as showing the claim is true. A jury that acquits has not declared the defendant innocent, only that the prosecution did not meet its burden.</p>
      <p><span class="term-label">Part (b): never accept.</span> The whole procedure is built to protect the null, so the only thing a test can ever do is find the evidence against it strong or find it weak. Weak evidence tells you the sample did not settle the question, which is a very different sentence from "the claim is true."</p>
      <p><span class="term-label">Part (c): reject.</span> You reject `H_0` if the sample information favors the alternative hypothesis. That is the verdict that the evidence was strong enough.</p>
      <p>Keeping this wording straight is worth more than it looks: the phrase you write in your conclusion is the part a reader checks first, and it is the one place where a correct calculation can still earn a wrong answer.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What does "do not reject `H_0`" mean?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Why can a test never "accept" `H_0`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What does "reject `H_0`" mean?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
