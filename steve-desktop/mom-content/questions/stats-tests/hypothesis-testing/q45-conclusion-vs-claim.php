// === NAME - DESCRIPTION: Conclusion vs Claim - the conclusion worded about the population, and what it is NOT entitled to say ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A completed test with its decision. Parts: (a) choices - the conclusion worded correctly
// about the population and the claim (b) choices - what the conclusion is NOT entitled to say.
// Invariant: both answers are constant per scenario.

$anstypes = array("choices", "choices")

$cases = array(
  array("A test of whether the mean hours of television watched per day by students is less than 4 rejects `H_0` at the 5% significance level.",
        "At the 5% significance level there is sufficient evidence to conclude that the mean hours of television watched per day by students in this class is less than four.",
        "The test did not prove the null, and it did not measure whether the claim is true."),
  array("A test of whether the proportion of students who speak a language other than English at home differs from 42.3% fails to reject `H_0` at the 5% significance level.",
        "At the 5% significance level there is not sufficient evidence to conclude that the proportion of students at this school who speak a language other than English at home differs from 42.3%.",
        "The test did not prove the null, and it did not measure whether the claim is true."),
  array("A test of whether the mean lifespan of a brand of tires is less than 50,000 miles rejects `H_0` at the 5% significance level.",
        "At the 5% significance level there is sufficient evidence to conclude that the mean lifespan of the tires is less than 50,000 miles.",
        "The test did not prove the null, and it did not measure whether the claim is true.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$conclusion = $cases[$i][1]
$notEntitled = $cases[$i][2]

$questions[0] = array(
  $conclusion,
  "The claim is false.",
  "We accept `H_0`."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $notEntitled,
  "The test proved the sample was random.",
  "The test proved the alternative hypothesis is true."
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
      <p><span class="term-label">Part (a): the conclusion.</span> ' . $conclusion . '</p>
      <p><span class="term-label">Part (b): what it is NOT entitled to say.</span> ' . $notEntitled . ' The conclusion is the part a reader checks first, and it is the one place where a correct calculation can still earn a wrong answer: write it about the population the sample represents, in plain sentences about the original claim.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the conclusion, worded correctly?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the conclusion NOT entitled to say?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
