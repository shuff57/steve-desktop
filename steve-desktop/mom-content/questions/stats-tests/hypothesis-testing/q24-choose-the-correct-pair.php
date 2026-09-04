// === NAME - DESCRIPTION: Choose the Correct Pair - the right H0/Ha pair against distractors built from the classic errors ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A claim. Part: (a) choices - the correct H0/Ha pair, with four distractor pairs built from
// the classic errors: a sample statistic in place of the parameter, the wrong claimed value,
// the wrong direction, and a pair that overlaps.
// Invariant: the correct answer is constant per scenario and each distractor is wrong for
// exactly one reason.

$anstypes = array("choices")

$cases = array(
  array("A health organization reports the mean time students spend on homework is 4.5 hours per night. A new survey of 200 students finds a sample mean of 4.75 hours, and the organization thinks the mean is now higher.",
        "`H_0: mu = 4.5`<br>`H_a: mu &gt; 4.5`",
        "`H_0: bar(x) = 4.5`<br>`H_a: bar(x) &gt; 4.5`",
        "`H_0: mu = 4.75`<br>`H_a: mu &gt; 4.75`",
        "`H_0: mu = 4.5`<br>`H_a: mu &lt; 4.5`",
        "`H_0: mu &gt; 4.5`<br>`H_a: mu &le; 4.5`"),
  array("A company claims the mean weight of its cereal boxes is exactly 16 ounces. A consumer group wants to test whether the mean has drifted from 16 oz.",
        "`H_0: mu = 16`<br>`H_a: mu &ne; 16`",
        "`H_0: bar(x) = 16`<br>`H_a: bar(x) &ne; 16`",
        "`H_0: mu = 15.8`<br>`H_a: mu &ne; 15.8`",
        "`H_0: mu = 16`<br>`H_a: mu &gt; 16`",
        "`H_0: mu &ne; 16`<br>`H_a: mu = 16`"),
  array("A researcher claims more than 30% of registered voters in the county voted in the primary election.",
        "`H_0: p &le; 0.30`<br>`H_a: p &gt; 0.30`",
        "`H_0: p' &le; 0.30`<br>`H_a: p' &gt; 0.30`",
        "`H_0: p &le; 0.35`<br>`H_a: p &gt; 0.35`",
        "`H_0: p &le; 0.30`<br>`H_a: p &lt; 0.30`",
        "`H_0: p &gt; 0.30`<br>`H_a: p &le; 0.30`")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$correct = $cases[$i][1]
$d1 = $cases[$i][2]
$d2 = $cases[$i][3]
$d3 = $cases[$i][4]
$d4 = $cases[$i][5]

$questions[0] = array($correct, $d1, $d2, $d3, $d4)
$answer[0] = 0
$noshuffle[0] = "all"

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
      <p><span class="term-label">Step 1: check the symbol in each option.</span> Hypotheses are always statements about a population parameter. Options using `bar(x)` or the sample proportion, the sample statistics, are out immediately.</p>
      <p><span class="term-label">Step 2: check the claimed value.</span> The claimed value comes from the claim, not from the sample. Options built around the sample mean instead of the claim are out.</p>
      <p><span class="term-label">Step 3: check the direction.</span> The direction word picks the alternative. Options pointing the wrong way are out, and so are pairs that overlap (an equal sign in `H_a` means no sample could tell the two claims apart).</p>
      <p><span class="term-label">The correct pair.</span> ' . $correct . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which is the correct pair of hypotheses?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
