// === NAME - DESCRIPTION: When to use ANOVA - TOP-MISS drill, pick ANOVA vs alternatives across scenarios ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")

// 6 scenarios. Pick 3 per attempt. Answer index:
//   0 = one-sample t-test
//   1 = independent two-sample t-test
//   2 = paired t-test
//   3 = one-way ANOVA

$scenarios = array(
  "A nutritionist compares mean daily calorie intake across three diet plans (low-carb, Mediterranean, vegetarian) using independent samples from each plan.",
  "A psychologist compares mean reaction time of two independent groups (a treatment group and a control group).",
  "A trainer measures resting heart rate for each athlete before and after a 6-week training program and asks whether the mean within-subject change is zero.",
  "A teacher tests whether the mean test score of one class equals the school-wide target of 75.",
  "A market researcher compares mean weekly grocery spending across four U.S. regions (Northeast, South, Midwest, West), each region sampled independently.",
  "A coach measures vertical jump for each athlete using their dominant foot, then again using their non-dominant foot, and asks whether mean dominant-foot jump differs."
)

$correct = array(3, 1, 2, 0, 3, 2)

$idx = diffrands(0, count($scenarios)-1, 3)
$story = array($scenarios[$idx[0]], $scenarios[$idx[1]], $scenarios[$idx[2]])
$answer[0] = $correct[$idx[0]]
$answer[1] = $correct[$idx[1]]
$answer[2] = $correct[$idx[2]]

$opt = array(
  "One-sample t-test",
  "Independent two-sample t-test",
  "Paired t-test",
  "One-way ANOVA"
)
$choices[0] = $opt
$choices[1] = $opt
$choices[2] = $opt
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"

$why = array(
  "One sample compared to a fixed claimed mean: <b>one-sample t-test</b>.",
  "Two independent groups, compare two means: <b>independent two-sample t-test</b>.",
  "Same subjects measured twice (or matched pairs): <b>paired t-test</b> on the differences.",
  "Three or more independent groups, compare more than two means at once: <b>one-way ANOVA</b>. Using multiple two-sample t-tests inflates the Type I error rate."
)

$rationale[0] = "<p>" . $why[$answer[0]] . "</p>"
$rationale[1] = "<p>" . $why[$answer[1]] . "</p>"
$rationale[2] = "<p>" . $why[$answer[2]] . "</p>"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Decision tree:</b></p>
      <ul>
        <li>One sample compared to a claimed mean &rarr; one-sample t.</li>
        <li>Two independent groups &rarr; independent two-sample t.</li>
        <li>Two measurements on the same subjects (or matched pairs) &rarr; paired t.</li>
        <li>Three or more independent groups, compare all their means at once &rarr; one-way ANOVA.</li>
      </ul>
      <p><b>Part a.</b> ' . $story[0] . '</p>
      ' . $rationale[0] . '
      <p><b>Part b.</b> ' . $story[1] . '</p>
      ' . $rationale[1] . '
      <p><b>Part c.</b> ' . $story[2] . '</p>
      ' . $rationale[2] . '
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff8e1; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Why not multiple t-tests for 3+ groups?</b> Running several pairwise t-tests inflates the chance of at least one false rejection. ANOVA controls the overall Type I error rate by testing all group means together first.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For each scenario, pick the right test from: one-sample t-test, independent two-sample t-test, paired t-test, or one-way ANOVA.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $story[0]
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $story[1]
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $story[2]
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
