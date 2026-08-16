// === NAME - DESCRIPTION: Mean or Proportion Procedure - which procedure the scenario calls for, the deciding facts, and the first number ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// One scenario per context. Parts: (a) choices - which procedure the scenario calls for
// (b) choices - the deciding facts (c) numfunc - the first number of that procedure.
// Invariant: (a) and (b) are constant per scenario, (c) is the precomputed value exactly.

$anstypes = array("choices", "choices", "numfunc")

$cases = array(
  array("A study tests whether the mean hours of sleep for adults is less than 7 hours per night. The population standard deviation is known to be 1.2 hours, and a sample of 50 adults is taken.",
        "One-mean z-test",
        "The parameter is a mean and sigma is known.",
        1.2 / sqrt(50)),
  array("A study tests whether the mean daily fiber intake of college students differs from 25 g. The population standard deviation is not known, and a sample of 30 students gives a sample standard deviation of 8.4 g.",
        "One-mean t-test",
        "The parameter is a mean and sigma is unknown.",
        8.4 / sqrt(30)),
  array("A survey tests whether the proportion of voters who support a measure is greater than 30%. A sample of 200 voters is taken.",
        "One-proportion z-test",
        "The parameter is a proportion.",
        0.30)
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$proc = $cases[$i][1]
$facts = $cases[$i][2]
$first = $cases[$i][3]

$questions[0] = array(
  $proc,
  "Two-sample t-test",
  "Chi-square test"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $facts,
  "The sample size was large enough.",
  "The claim was about a percentage."
)
$answer[1] = 0
$noshuffle[1] = "all"

$answer[2] = $first
$abstolerance[2] = 0.005

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
      <p><span class="term-label">Parts (a) and (b) &mdash; the procedure.</span> ' . $proc . ' &mdash; ' . $facts . ' The parameter picks the row of the table, and the sigma question picks the column.</p>
      <p><span class="term-label">Part (c) &mdash; the first number.</span> ' . ($i == 2 ? "The sample proportion is the point estimate the test is built around: p' = x/n." : "The standard error is the first number of the procedure: " . round($first, 4) . ".") . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which procedure does the scenario call for?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What are the deciding facts?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the first number of that procedure?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
