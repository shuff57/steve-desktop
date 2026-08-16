// === NAME - DESCRIPTION: The Two-Tailed Picture - why the shading is split, and what the two regions together represent ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A two-tailed test. Parts: (a) choices - why the shading is split
// (b) choices - what the two shaded regions together represent.
// Invariant: both answers are constant across seeds.

$anstypes = array("choices", "choices")

$contexts = array(
  "A two-tailed test of whether the mean fill weight of cereal boxes differs from 16 ounces.",
  "A two-tailed test of whether the proportion of students who speak a language other than English at home differs from 42.3%.",
  "A two-tailed test of whether the mean daily fiber intake of college students differs from 25 g."
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i]

$questions[0] = array(
  "Because `H_a` carries `!=`, the test is in both tails &mdash; the p-value collects area on both sides of the curve.",
  "Because the sample size is small, the shading has to be split.",
  "Because the null hypothesis is two-sided."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The two-tailed p-value &mdash; twice the one-tailed area, which is why the same data is harder to call significant when the question is posed that way.",
  "The probability that the null hypothesis is true.",
  "The area between the two cutoffs."
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
      <p><span class="term-label">Part (a) &mdash; why the shading is split.</span> Because `H_a` carries `!=`, the test is in both tails &mdash; `!=` picks no direction, so the p-value collects area on both sides of the curve.</p>
      <p><span class="term-label">Part (b) &mdash; what the two regions represent.</span> The two shaded regions together are the two-tailed p-value &mdash; twice the one-tailed area. Mark both cutoffs: the test statistic and its mirror on the other side.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Why is the shading split between both tails?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What do the two shaded regions together represent?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
