// === NAME - DESCRIPTION: Proportion Claim Hypotheses - the full H0 and Ha pair in symbols for a proportion claim with a direction ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A proportion claim with a direction. Parts: (a) choices - H0 in symbols
// (b) choices - Ha in symbols.
// Invariant: both answers are constant per scenario.

$anstypes = array("choices", "choices")

$cases = array(
  array("Dr. Minho Kang claims the probability that a person picked at random in Times Square is visiting the area is 0.83. You want to test whether the proportion is actually less.",
        "`H_0: p &ge; 0.83`", "`H_a: p &lt; 0.83`"),
  array("In a population of fish, approximately 42% are female. A test is conducted to see if, in fact, the proportion is less.",
        "`H_0: p &ge; 0.42`", "`H_a: p &lt; 0.42`"),
  array("A health organization claims 9.5% of adults suffer from depression. You want to test whether the proportion is different from 9.5%.",
        "`H_0: p = 0.095`", "`H_a: p &ne; 0.095`"),
  array("A researcher claims more than 30% of registered voters in the county voted in the primary election.",
        "`H_0: p &le; 0.30`", "`H_a: p &gt; 0.30`"),
  array("An instructor believes fewer than 20% of students attended the midnight showing of a new movie.",
        "`H_0: p &ge; 0.20`", "`H_a: p &lt; 0.20`"),
  array("A medical trial tests whether a new medicine reduces cholesterol by 25%.",
        "`H_0: p = 0.25`", "`H_a: p &ne; 0.25`")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$h0 = $cases[$i][1]
$ha = $cases[$i][2]

$questions[0] = array(
  $h0,
  "`H_0: p' = 0.5`",
  "`H_0: mu = 0.5`"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $ha,
  "`H_a: p = 0.5`",
  "`H_a: p' &lt; 0.5`"
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
      <p><span class="term-label">Part (a) &mdash; the null.</span> The claim on trial goes in `H_0` with its equality: ' . $h0 . '. The parameter is the population proportion `p`, never the sample proportion.</p>
      <p><span class="term-label">Part (b) &mdash; the alternative.</span> The direction word &mdash; "more than", "fewer than", or "different from" &mdash; goes in `H_a`: ' . $ha . '. The two statements must partition the number line with no overlap.</p>
      <p>Same machinery as the mean: the claim on trial goes in the null, the direction word goes in the alternative.</p>
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
