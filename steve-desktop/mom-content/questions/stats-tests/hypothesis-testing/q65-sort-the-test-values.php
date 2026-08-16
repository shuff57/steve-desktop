// === NAME - DESCRIPTION: Sort the Test Values - which listed numbers the test statistic uses, and which is the distractor ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A full-test scenario with the numbers given. Parts: (a) choices - which of the listed
// numbers the test statistic actually uses (b) choices - which listed number is the distractor.
// Invariant: both answers are constant per scenario.

$anstypes = array("choices", "choices")

$cases = array(
  array("A study of 26 first-time convicted burglars finds a mean time spent in jail of 3 years with a sample standard deviation of 1.8 years. It is somehow known that the population standard deviation is 1.5 years. The claim on trial is that the mean jail time is 2.5 years.",
        "The claimed value 2.5, the sample mean 3, the sample size 26, and the population standard deviation 1.5",
        "The sample standard deviation 1.8 &mdash; it describes the sample\'s spread, not the population\'s, and does not go in when sigma is known"),
  array("A study of 30 students finds a mean study time of 13.2 hours per week with a sample standard deviation of 4.1 hours. The population standard deviation is known to be 3.8 hours. The claim on trial is that the mean study time is 15 hours.",
        "The claimed value 15, the sample mean 13.2, the sample size 30, and the population standard deviation 3.8",
        "The sample standard deviation 4.1 &mdash; it describes the sample\'s spread, not the population\'s, and does not go in when sigma is known"),
  array("A survey of 40 adults finds a mean daily screen time of 5.6 hours with a sample standard deviation of 1.9 hours. The population standard deviation is known to be 2.0 hours. The claim on trial is that the mean screen time is 5 hours.",
        "The claimed value 5, the sample mean 5.6, the sample size 40, and the population standard deviation 2.0",
        "The sample standard deviation 1.9 &mdash; it describes the sample\'s spread, not the population\'s, and does not go in when sigma is known")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$used = $cases[$i][1]
$distractor = $cases[$i][2]

$questions[0] = array(
  $used,
  "The claimed value, the sample mean, and the sample size only",
  "The sample mean and the sample standard deviation only"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $distractor,
  "The sample size &mdash; it is too small to matter",
  "The claimed value &mdash; it is never used in the arithmetic"
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
      <p><span class="term-label">Part (a) &mdash; sort the numbers you need from the ones you do not.</span> The claimed value, the sample mean, the sample size, and the population standard deviation all belong to the calculation. The test statistic is built from the standard error `sigma/sqrt(n)`, which needs the population spread, not the sample\'s.</p>
      <p><span class="term-label">Part (b) &mdash; the distractor.</span> ' . $distractor . '</p>
      <p>Pull each value out of the problem statement and sort them before you compute: the survey mean is the sample mean, the "somehow known" value is the population standard deviation, and the survey\'s own spread is a different quantity that does not belong in the test statistic.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which numbers does the test statistic actually use?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which listed number is the distractor, and why?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
