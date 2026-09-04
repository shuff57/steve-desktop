// === NAME - DESCRIPTION: s vs sigma - which spread the test statistic uses when sigma is known, and what s is for ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A test where both s and sigma appear. Parts: (a) choices - which spread the test statistic
// uses and why (b) choices - what s would be used for instead.
// Invariant: both answers are constant per scenario.

$anstypes = array("choices", "choices")

$cases = array(
  array("A study of 26 first-time convicted burglars finds a mean time spent in jail of 3 years with a sample standard deviation of 1.8 years. It is somehow known that the population standard deviation is 1.5 years.",
        "`sigma`: it is the actual spread of the whole population, not an estimate built from the sample.",
        "Estimating the spread when `sigma` is unknown: the situation that calls for the t distribution."),
  array("A study of 30 students finds a mean study time of 13.2 hours per week with a sample standard deviation of 4.1 hours. The population standard deviation is known to be 3.8 hours.",
        "`sigma`: it is the actual spread of the whole population, not an estimate built from the sample.",
        "Estimating the spread when `sigma` is unknown: the situation that calls for the t distribution."),
  array("A survey of 40 adults finds a mean daily screen time of 5.6 hours with a sample standard deviation of 1.9 hours. The population standard deviation is known to be 2.0 hours.",
        "`sigma`: it is the actual spread of the whole population, not an estimate built from the sample.",
        "Estimating the spread when `sigma` is unknown: the situation that calls for the t distribution.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$which = $cases[$i][1]
$sFor = $cases[$i][2]

$questions[0] = array(
  $which,
  "`s`: it is the spread of the sample, which is always the right one.",
  "Neither: the test statistic does not use a spread at all."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $sFor,
  "Describing the shape of the histogram.",
  "Nothing: `s` is never used in a hypothesis test."
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
      <p><span class="term-label">Part (a): which spread.</span> ' . $which . '</p>
      <p><span class="term-label">Part (b): what s is for.</span> ' . $sFor . '</p>
      <p>`s_x` is an estimate of the spread built from just the sample, while `sigma` is the actual spread of the whole population: when the problem hands you `sigma`, the better one is the real one.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which spread does the test statistic use, and why?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What would `s` be used for instead?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
