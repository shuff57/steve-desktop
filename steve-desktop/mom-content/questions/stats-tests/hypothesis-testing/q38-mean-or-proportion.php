// === NAME - DESCRIPTION: Mean or Proportion - the parameter from the claim, and which row of Table 8.3.1 the test lives in ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A claim. Parts: (a) choices - is the parameter a mean or a proportion
// (b) choices - which row of Table 8.3.1 the test lives in.
// Invariant: (a) and (b) are constant per scenario and consistent.

$anstypes = array("choices", "choices")

$cases = array(
  array("A study tests whether the mean hours of sleep for adults is less than 7 hours per night. The population standard deviation is known.",
        "A population mean `mu`",
        "The normal distribution row &mdash; sigma is known."),
  array("A study tests whether the mean hours of sleep for adults is less than 7 hours per night. The population standard deviation is not known.",
        "A population mean `mu`",
        "The Student's t distribution row &mdash; sigma is unknown."),
  array("A survey tests whether the proportion of adults who exercise regularly is greater than 0.40.",
        "A population proportion `p`",
        "The normal-for-a-proportion row &mdash; the parameter is a proportion."),
  array("A study tests whether the mean commute time of city residents differs from 28 minutes. The population standard deviation is known.",
        "A population mean `mu`",
        "The normal distribution row &mdash; sigma is known."),
  array("A survey tests whether the proportion of students who prefer online classes is less than 0.50.",
        "A population proportion `p`",
        "The normal-for-a-proportion row &mdash; the parameter is a proportion.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$param = $cases[$i][1]
$row = $cases[$i][2]

$questions[0] = array(
  $param,
  "A sample statistic like `bar(x)`",
  "The sample size `n`"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $row,
  "The binomial distribution row",
  "The chi-square distribution row"
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
      <p><span class="term-label">Part (a) &mdash; the parameter.</span> ' . $param . ' An average of a measured quantity is `mu`; a share of a group that has some yes-or-no trait is `p`.</p>
      <p><span class="term-label">Part (b) &mdash; the row of the table.</span> ' . $row . ' The parameter picks the row of Table 8.3.1, and the sigma question picks the column &mdash; decide both before you touch the calculator.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is the parameter a mean or a proportion?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which row of Table 8.3.1 does the test live in?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
