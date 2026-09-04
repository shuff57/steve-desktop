// === NAME - DESCRIPTION: Test Conditions - the requirement at risk in the scenario, whether it is met, and why the condition exists ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A test scenario. Parts: (a) choices - the requirement at risk in THIS scenario
// (b) choices - whether the requirement is met (c) choices - why the condition exists.
// Invariant: all three answers are constant per scenario and (b) matches the actual check.

$anstypes = array("choices", "choices", "choices")

$cases = array(
  array("A t-test for a mean is run on a sample of 8 observations from a population that is not known to be normal.",
        "The population must be approximately normally distributed: with only 8 observations there is no central limit theorem to lean on.",
        "No: the population is not known to be normal, so the requirement is not met.",
        "The condition is what makes the distribution legal in the first place: without it the t curve is the wrong ruler."),
  array("A t-test for a mean is run on a simple random sample of 30 observations from a population that is approximately normal.",
        "The data must be a simple random sample, and the population approximately normal.",
        "Yes: the sample is a simple random sample and the population is approximately normal.",
        "The condition is what makes the distribution legal in the first place: without it the t curve is the wrong ruler."),
  array("A proportion test is run on a sample where n = 200 and the claimed proportion is p0 = 0.02.",
        "Both np0 and n(1 - p0) must exceed 5.",
        "No: np0 = 4, which is below 5, so the normal approximation is not legal.",
        "The condition is what makes the distribution legal in the first place: the normal curve is only a fair stand-in for the binomial when it has enough mass on both sides."),
  array("A proportion test is run on a sample where n = 500 and the claimed proportion is p0 = 0.30.",
        "Both np0 and n(1 - p0) must exceed 5.",
        "Yes: np0 = 150 and n(1 - p0) = 350, both well above 5.",
        "The condition is what makes the distribution legal in the first place: the normal curve is only a fair stand-in for the binomial when it has enough mass on both sides.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$req = $cases[$i][1]
$met = $cases[$i][2]
$why = $cases[$i][3]

$questions[0] = array(
  $req,
  "The sample size must be at least 100.",
  "The claimed value must be a whole number."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $met,
  "Yes: every requirement is met.",
  "No: the requirement is not met."
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  $why,
  "The condition is a formality that never actually matters.",
  "The condition makes the arithmetic easier."
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
      <p><span class="term-label">Part (a): the requirement at risk.</span> ' . $req . '</p>
      <p><span class="term-label">Part (b): is it met?</span> ' . $met . '</p>
      <p><span class="term-label">Part (c): why the condition exists.</span> ' . $why . '</p>
      <p>The condition is not a formality: it is what makes the distribution legal in the first place. If you have 200 people and only two said yes, the test will not have the coverage it claims.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which requirement is at risk in this scenario?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Is the requirement met?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why does the condition exist?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
