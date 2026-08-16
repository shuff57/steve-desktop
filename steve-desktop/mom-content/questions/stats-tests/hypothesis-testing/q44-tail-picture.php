// === NAME - DESCRIPTION: The Tail Picture - which tail the p-value lives in, and the shape of the shaded region ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A test with a stated alternative. Parts: (a) choices - which tail the p-value lives in
// (b) choices - the shape of the shaded region.
// Invariant: both answers are constant per scenario and consistent with Ha.

$anstypes = array("choices", "choices")

$cases = array(
  array("A test with `H_a: mu < 9`.",
        "The left tail &mdash; the `<` in `H_a` puts the whole p-value in the left tail.",
        "A single shaded region in the left tail beyond the test statistic."),
  array("A test with `H_a: mu > 6`.",
        "The right tail &mdash; the `>` in `H_a` puts the whole p-value in the right tail.",
        "A single shaded region in the right tail beyond the test statistic."),
  array("A test with `H_a: p != 0.25`.",
        "Both tails &mdash; the `!=` in `H_a` splits the p-value evenly between both tails.",
        "Two shaded regions, one in each tail, together representing the two-tailed p-value.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$tail = $cases[$i][1]
$shape = $cases[$i][2]

$questions[0] = array(
  $tail,
  "The left tail &mdash; the `<=` in `H_0` decides the tail.",
  "The right tail &mdash; the `>=` in `H_0` decides the tail."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $shape,
  "The whole area under the curve.",
  "The area between the mean and the test statistic."
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
      <p><span class="term-label">Part (a) &mdash; the tail.</span> ' . $tail . ' The symbol in the alternative names the test &mdash; the `<=`/`>=` in `H_0` is never what decides the tail.</p>
      <p><span class="term-label">Part (b) &mdash; the picture.</span> ' . $shape . ' Draw one bell-shaped curve over a horizontal axis, centered at the value the null claims, then mark the observed sample statistic and shade the tail beyond it &mdash; the picture is the p-value made visible.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which tail does the p-value live in?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the shape of the shaded region?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
