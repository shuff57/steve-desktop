// === NAME - DESCRIPTION: Name the Parameter - mu for a mean claim, p for a proportion claim, and the claimed value as a parameter statement ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A claim about a mean or a proportion. Parts: (a) choices - the parameter (mu or p)
// (b) choices - the claimed value written as a parameter statement.
// Invariant: (a) and (b) are constant per scenario and match the claim wording.

$anstypes = array("choices", "choices")

$cases = array(
  array("A cable company claims the mean speed of its Internet connection is more than 3 Megabits per second.",
        "`mu` (population mean)", "`mu = 3`", "mean"),
  array("A sociologist claims the probability that a person picked at random in Times Square is visiting the area is 0.83.",
        "`p` (population proportion)", "`p = 0.83`", "proportion"),
  array("A company reports the mean entry-level salary of its employees is $58,000.",
        "`mu` (population mean)", "`mu = 58000`", "mean"),
  array("A health organization claims 9.5% of adults suffer from depression.",
        "`p` (population proportion)", "`p = 0.095`", "proportion"),
  array("A study claims the mean time to graduate from college is 4.5 years.",
        "`mu` (population mean)", "`mu = 4.5`", "mean"),
  array("A researcher claims 42% of the fish in a population are female.",
        "`p` (population proportion)", "`p = 0.42`", "proportion")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$paramLabel = $cases[$i][1]
$claimStmt = $cases[$i][2]
$kind = $cases[$i][3]

$questions[0] = array(
  $paramLabel,
  "`bar(x)` (sample mean)",
  "`sigma` (population standard deviation)"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $claimStmt,
  "`n = 100` (sample size)",
  "`s = 2` (sample standard deviation)"
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
      <p><span class="term-label">Part (a) &mdash; the parameter.</span> The claim is about ' . ($kind == "mean" ? "an average of a measured quantity, so the parameter is a population mean `mu`" : "a share of a group that has some yes-or-no trait, so the parameter is a population proportion `p`") . '. A statement about an average uses `mu`; a statement about a percentage or a chance uses `p`.</p>
      <p><span class="term-label">Part (b) &mdash; the claimed value.</span> The claim being tested is ' . $claimStmt . ' &mdash; the claimed value goes with the parameter, never with the sample size or the sample spread.</p>
      <p>Decide the parameter before touching the symbols: it picks the whole row of the hypothesis-testing table, and getting it wrong makes every calculation after it answer the wrong question.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which parameter is the claim about?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How is the claimed value written as a parameter statement?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
