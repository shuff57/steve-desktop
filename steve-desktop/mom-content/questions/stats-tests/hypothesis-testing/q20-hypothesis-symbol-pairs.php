// === NAME - DESCRIPTION: Hypothesis Symbol Pairs - the H0 and Ha symbols from the claim's direction, and the tail the test runs on ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A claim with a direction. Parts: (a) choices - the H0 symbol (=, <=, >=)
// (b) choices - the Ha symbol (!=, >, <) (c) choices - the tail (left, right, two).
// Invariant: (a), (b), (c) are constant per scenario and consistent with each other.

$anstypes = array("choices", "choices", "choices")

$cases = array(
  array("A consumer group wants to test whether the mean fill weight of cereal boxes has drifted from the claimed 16 oz.",
        "=", "&ne;", "two-tailed", "drifted from"),
  array("A contractor suspects the actual drying time of a new road paint is longer than the claimed 45 minutes.",
        "&le;", "&gt;", "right-tailed", "longer than"),
  array("A principal believes this year's mean ACT score is lower than last year's 22.4.",
        "&ge;", "&lt;", "left-tailed", "lower than"),
  array("A clinical trial tests whether a new medication changes mean systolic blood pressure from the historical 132 mmHg.",
        "=", "&ne;", "two-tailed", "changes"),
  array("A customer group wants to test whether mean delivery time is greater than the advertised 28 hours.",
        "&le;", "&gt;", "right-tailed", "greater than"),
  array("A nutritionist believes the mean daily sugar intake of clients is less than the recommended 35 grams.",
        "&ge;", "&lt;", "left-tailed", "less than")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$h0sym = $cases[$i][1]
$hasym = $cases[$i][2]
$tail = $cases[$i][3]
$dirWord = $cases[$i][4]

$questions[0] = array(
  "`H_0` carries " . $h0sym,
  "`H_0` carries " . $hasym,
  "`H_0` carries &ne;"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "`H_a` carries " . $hasym,
  "`H_a` carries " . $h0sym,
  "`H_a` carries ="
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "The test is " . $tail . ".",
  "The test is one-tailed to the left.",
  "The test is one-tailed to the right."
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
      <p><span class="term-label">The direction word.</span> The claim says the value is "' . $dirWord . '", which points the alternative ' . ($tail == "two-tailed" ? "in no single direction" : "to the " . ($tail == "left-tailed" ? "left" : "right")) . '.</p>
      <p><span class="term-label">Parts (a) and (b): the symbols.</span> Read the table across, not down: each row is a matched pair, and the two entries always cover every possible value of the parameter between them with no overlap. The equal sign always lives in `H_0`: `H_a` never has a symbol with an equal in it.</p>
      <p><span class="term-label">Part (c): the tail.</span> The symbol in `H_a` is what names the test: ' . ($tail == "two-tailed" ? "`&ne;` picks no direction, so the test is two-tailed" : ($tail == "left-tailed" ? "`&lt;` puts the whole test in the left tail" : "`&gt;` puts the whole test in the right tail")) . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which symbol does `H_0` carry?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which symbol does `H_a` carry?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which tail does the test run on?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
