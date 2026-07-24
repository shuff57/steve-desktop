// === NAME - DESCRIPTION: State H0 and Ha for mean tests in context (one-mean, two-mean, paired) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")

// Six scenarios. Each carries its own pre-built choice array (correct option always index 0).
// Drawn 3 per attempt via diffrands.

$stories = array(
  "A nutrition guide claims the mean sodium content in a brand of frozen meals is 600 mg. A researcher wants to test whether the true mean differs from 600 mg.",
  "A teacher believes that the mean score on a standardized test exceeds 75. To test this, she samples scores from her class.",
  "A coach compares mean vertical jump for two independent training programs and wants to test whether their population means differ.",
  "A clinic compares mean recovery times for a new therapy versus a standard therapy. The clinic claims the new therapy reduces mean recovery time (so the new-therapy population mean is less than the standard).",
  "A trainer measures resting heart rate of each athlete before and after a 6-week program. He wants to test whether the mean within-subject change differs from zero.",
  "A factory's quality team claims the mean weight of cereal boxes is exactly 500 g. An inspector wants to test whether this is correct."
)

// Choice 0 is always the correct H0/Ha pair. Other three are common distractors.
$choiceSets = array(
  array(
    "`H_0: mu = 600`; `H_a: mu != 600`",
    "`H_0: mu != 600`; `H_a: mu = 600`",
    "`H_0: bar(x) = 600`; `H_a: bar(x) != 600`",
    "`H_0: mu_d = 0`; `H_a: mu_d != 0`"
  ),
  array(
    "`H_0: mu = 75`; `H_a: mu > 75`",
    "`H_0: mu > 75`; `H_a: mu = 75`",
    "`H_0: bar(x) = 75`; `H_a: bar(x) > 75`",
    "`H_0: mu = 75`; `H_a: mu != 75`"
  ),
  array(
    "`H_0: mu_1 = mu_2`; `H_a: mu_1 != mu_2`",
    "`H_0: mu_1 != mu_2`; `H_a: mu_1 = mu_2`",
    "`H_0: bar(x)_1 = bar(x)_2`; `H_a: bar(x)_1 != bar(x)_2`",
    "`H_0: mu_d = 0`; `H_a: mu_d != 0`"
  ),
  array(
    "`H_0: mu_1 = mu_2`; `H_a: mu_1 < mu_2` (where 1 = new therapy)",
    "`H_0: mu_1 < mu_2`; `H_a: mu_1 = mu_2`",
    "`H_0: bar(x)_1 = bar(x)_2`; `H_a: bar(x)_1 < bar(x)_2`",
    "`H_0: mu_d = 0`; `H_a: mu_d < 0`"
  ),
  array(
    "`H_0: mu_d = 0`; `H_a: mu_d != 0`",
    "`H_0: mu_1 = mu_2`; `H_a: mu_1 != mu_2`",
    "`H_0: bar(d) = 0`; `H_a: bar(d) != 0`",
    "`H_0: mu = 0`; `H_a: mu != 0`"
  ),
  array(
    "`H_0: mu = 500`; `H_a: mu != 500`",
    "`H_0: mu != 500`; `H_a: mu = 500`",
    "`H_0: bar(x) = 500`; `H_a: bar(x) != 500`",
    "`H_0: mu_1 = mu_2`; `H_a: mu_1 != mu_2`"
  )
)

$idx = diffrands(0, count($stories)-1, 3)
$story0 = $stories[$idx[0]]
$story1 = $stories[$idx[1]]
$story2 = $stories[$idx[2]]

$choices[0] = $choiceSets[$idx[0]]
$choices[1] = $choiceSets[$idx[1]]
$choices[2] = $choiceSets[$idx[2]]
$answer[0] = 0
$answer[1] = 0
$answer[2] = 0
$noshuffle[0] = "all"
$noshuffle[1] = "all"
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
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Two rules.</b></p>
      <ul>
        <li>Hypotheses are statements about the <b>population parameter</b> (`mu`, `mu_1 - mu_2`, `mu_d`) and never about the sample statistic.</li>
        <li>`H_a` matches the question wording: "differs" &rarr; `!=`, "is greater than" / "exceeds" &rarr; `&gt;`, "is less than" / "reduces" &rarr; `&lt;`.</li>
      </ul>
      <p>For a one-mean test, write `mu = c` vs the appropriate inequality. For two independent groups, write `mu_1 = mu_2` vs the appropriate inequality. For a paired (same-subject) design, write `mu_d = 0` and use the differences.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For each scenario, choose the correct null and alternative hypotheses.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $story0
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $story1
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $story2
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
