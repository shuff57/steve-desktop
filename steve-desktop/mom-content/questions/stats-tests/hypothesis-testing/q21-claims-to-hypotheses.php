// === NAME - DESCRIPTION: Claims to Hypotheses - the full H0 and Ha pair in symbols for a mean claim with a direction ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A mean claim with a direction. Parts: (a) choices - the full H0 statement in symbols
// (b) choices - the full Ha statement.
// Invariant: both answers are constant per scenario and the pair is contradictory.

$anstypes = array("choices", "choices")

$cases = array(
  array("You are testing that the mean speed of your cable Internet connection is more than three Megabits per second.",
        "`H_0: mu &le; 3`", "`H_a: mu &gt; 3`"),
  array("The mean entry-level salary of an employee at a company is $58,000. You believe it is higher for IT professionals.",
        "`H_0: mu &le; 58000`", "`H_a: mu &gt; 58000`"),
  array("A study claims the mean time to graduate from college is 4.5 years. You suspect it is actually longer.",
        "`H_0: mu &le; 4.5`", "`H_a: mu &gt; 4.5`"),
  array("The mean height of eighth graders is claimed to be 66 inches. You want to test whether it is different from 66.",
        "`H_0: mu = 66`", "`H_a: mu &ne; 66`"),
  array("A school claims its students study an average of 15 hours per week. You believe the mean is lower.",
        "`H_0: mu &ge; 15`", "`H_a: mu &lt; 15`"),
  array("A factory claims the mean lifespan of its tires is at least 50,000 miles. You believe it is less.",
        "`H_0: mu &ge; 50000`", "`H_a: mu &lt; 50000`")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$h0 = $cases[$i][1]
$ha = $cases[$i][2]

$questions[0] = array(
  $h0,
  "`H_0: bar(x) = 3`",
  "`H_0: mu &lt; 3`"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $ha,
  "`H_a: mu = 3`",
  "`H_a: bar(x) &gt; 3`"
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
      <p><span class="term-label">Part (a) &mdash; the null.</span> The status quo goes in `H_0` with its equality: ' . $h0 . '. Writing `H_0: mu = 3` is also accepted, since the test statistic is computed at the boundary value either way &mdash; what matters is consistency inside the problem.</p>
      <p><span class="term-label">Part (b) &mdash; the alternative.</span> The researcher\'s claim goes in `H_a`: ' . $ha . '. The two statements have to partition the number line, so that whatever the truth is, exactly one of your two sentences contains it.</p>
      <p>Get the pair wrong and every calculation after it answers the wrong question, no matter how clean the arithmetic is.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> State the null hypothesis.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> State the alternative hypothesis.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
