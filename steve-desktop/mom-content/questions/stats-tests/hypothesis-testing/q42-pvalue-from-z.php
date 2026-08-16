// === NAME - DESCRIPTION: P-Value from a Z - the one-tailed p-value from the test statistic, and whether it is doubled ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A z-test with a test statistic. Parts: (a) numfunc - the one-tailed p-value
// (b) choices - whether that p-value is doubled for a two-tailed test.
// Invariant: (a) is the precomputed probability exactly, (b) matches the tail in the scenario.

loadlibrary("stats");

$anstypes = array("numfunc", "choices")

$cases = array(
  array("A left-tailed test of whether the mean hours of sleep for adults is less than 7 produces z = -2.19.",
        -2.19, "left"),
  array("A right-tailed test of whether the mean number of pairs of jeans owned is greater than 3 produces z = 1.86.",
        1.86, "right"),
  array("A two-tailed test of whether the proportion of students who speak a language other than English at home differs from 0.423 produces z = 1.39.",
        1.39, "two"),
  array("A left-tailed test of whether the proportion of family members who shed tears at a reunion is less than 0.60 produces z = -1.71.",
        -1.71, "left")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$z = $cases[$i][1]
$tail = $cases[$i][2]

$pOne = normalcdf($z)
$pOne = 1 - $pOne if ($tail == "right")
$pOne = 2 * $pOne if ($tail == "two")

$answer[0] = $pOne
$abstolerance[0] = 0.005

$questions[1] = array(
  "Yes &mdash; `H_a` carries `!=`, so the p-value collects area in both tails and is twice the one-tailed area.",
  "No &mdash; `H_a` points one way, so the p-value is the single tail beyond the test statistic.",
  "No &mdash; the p-value is always the area to the left of the test statistic."
)
$answer[1] = 0
$noshuffle[1] = "all"
$answer[1] = 1 if ($tail != "two")

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
      <p><span class="term-label">Part (a) &mdash; the p-value.</span> ' . ($tail == "left" ? "The alternative is `mu < 7`, so this is a left-tailed test and the p-value is the area to the left of z = " . $z . ": P(Z < " . $z . ") = " . round($pOne, 4) . "." : ($tail == "right" ? "The alternative is `mu > 3`, so this is a right-tailed test and the p-value is the area to the right of z = " . $z . ": P(Z > " . $z . ") = " . round($pOne, 4) . "." : "The alternative is `p != 0.423`, so this is a two-tailed test and the p-value is twice the area in the upper tail: 2 * P(Z > " . $z . ") = " . round($pOne, 4) . ".")) . '</p>
      <p><span class="term-label">Part (b) &mdash; doubled or not.</span> ' . ($tail == "two" ? "Yes &mdash; `!=` picks no direction, so the p-value collects area in both tails and is twice the one-tailed area. That is why the same data is harder to call significant when the question is posed that way." : "No &mdash; the alternative points one way, so the p-value is the single tail beyond the test statistic.") . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the p-value?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Is that p-value doubled for a two-tailed test?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
