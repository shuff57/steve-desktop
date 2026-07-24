// === NAME - DESCRIPTION: Chi-square Test of Independence - Interpret a completed test in context ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")

// Three scenarios. Each provides a chi-square statistic, df, p-value, alpha, and two categorical variables.
// Part a: at the given alpha, reject or fail to reject H0?
// Part b: best conclusion-in-context wording
// Part c: what does the result mean for the two variables in the population

$scenarios = array(
  array("v1" => "gender", "v2" => "major area", "chi" => 9.41, "df" => 2, "p" => 0.009, "alpha" => 0.05, "ctx" => "A university surveys 480 students about gender and major area (STEM, humanities, business)."),
  array("v1" => "age group", "v2" => "phone brand preference", "chi" => 4.05, "df" => 4, "p" => 0.399, "alpha" => 0.05, "ctx" => "A market researcher surveys 600 adults about age group and preferred phone brand."),
  array("v1" => "region", "v2" => "primary diet type", "chi" => 16.72, "df" => 6, "p" => 0.010, "alpha" => 0.05, "ctx" => "A nutrition study surveys 540 adults about U.S. region and primary diet type.")
)

$story  = randfrom($scenarios)
$v1     = $story["v1"]
$v2     = $story["v2"]
$chival = $story["chi"]
$dfval  = $story["df"]
$pval   = $story["p"]
$alpha  = $story["alpha"]
$ctx    = $story["ctx"]

// Reject when p < alpha
$reject = 0
if ($pval < $alpha) { $reject = 1 }

// Part a: reject vs fail-to-reject
$choices[0] = array(
  "Reject `H_0`",
  "Fail to reject `H_0`"
)
$answer[0] = 1 - $reject
$noshuffle[0] = "all"

// Part b: conclusion in context
// Reject -> evidence that variables are NOT independent (associated)
// Fail to reject -> not enough evidence to say the variables are associated
$choices[1] = array(
  "There is sufficient evidence at the chosen alpha to conclude that $v1 and $v2 are associated in the population.",
  "There is not sufficient evidence at the chosen alpha to conclude that $v1 and $v2 are associated in the population.",
  "There is sufficient evidence to prove that $v1 and $v2 are independent in the population.",
  "There is sufficient evidence to prove that $v1 has a causal effect on $v2."
)
$answer[1] = 1
if ($reject == 1) { $answer[1] = 0 }
$noshuffle[1] = "all"

// Part c: what does it mean for population
$choices[2] = array(
  "The two variables are definitely independent, so neither variable's distribution affects the other.",
  "Even when we fail to reject, the variables may still be related; the test only says the data don't give us strong enough evidence to conclude an association.",
  "Even when we reject, we are not proving the variables are associated; we have evidence at the chosen alpha that they are, and we could still be making a Type I error.",
  "Statistical significance proves that one variable causes the other."
)
// If we rejected, the correct nuance is option 2 (we have evidence, not proof)
// If we failed to reject, the correct nuance is option 1 (still possibly related)
$answer[2] = 1
if ($reject == 1) { $answer[2] = 2 }
$noshuffle[2] = "all"

$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"

$decision_text = "fail to reject"
if ($reject == 1) { $decision_text = "reject" }

$conclusion_text = "we do not have sufficient evidence to conclude that " . $v1 . " and " . $v2 . " are associated"
if ($reject == 1) { $conclusion_text = "we have sufficient evidence to conclude that " . $v1 . " and " . $v2 . " are associated" }

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Part a.</b> Compare the p-value to the significance level. Since `p = ' . $pval . '` and `alpha = ' . $alpha . '`, we ' . $decision_text . ' `H_0`.</p>
      <p><b>Part b.</b> In context, ' . $conclusion_text . '. Notice the conclusion talks about association, not causation, and never claims to <i>prove</i> independence.</p>
      <p><b>Part c.</b> Two common nuances to remember:</p>
      <ul>
        <li>Failing to reject does <b>not</b> prove independence. It only means the data did not give strong enough evidence to detect an association.</li>
        <li>Rejecting does <b>not</b> prove association either. It says the observed pattern would be unlikely if the variables were independent, at the chosen `alpha`. A Type I error is still possible.</li>
      </ul>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<style>select{max-width:100%;width:100%;box-sizing:border-box;display:block;margin:4px 0;}</style>

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">A chi-square test of independence is run to test whether $v1 and $v2 are associated. The output gives `chi^2 = $chival` with `df = $dfval`, so `p = $pval`. Use significance level `alpha = $alpha`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the correct decision at `alpha = $alpha`?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which conclusion best describes the result in context?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which statement correctly describes what this result means for the population?
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
