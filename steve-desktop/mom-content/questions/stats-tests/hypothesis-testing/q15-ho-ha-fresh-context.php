// === NAME - DESCRIPTION: State H0 and Ha and Pick Test Direction - From a fresh story context, identify the parameter, write H0 and Ha, and pick left/right/two-tailed ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// 5 scenarios with claim phrasing → forces direction
$cases = array(
  array("The manufacturer claims the mean fill weight of cereal boxes is exactly 16 oz. A consumer group wants to test whether the mean has drifted from 16 oz.",
        "`mu` (mean fill weight, oz)", "= 16", "!= 16", 2, // two-tailed
        "The phrase 'drifted from' allows either direction, so the test is two-tailed."),
  array("The Department of Transportation claims the average drying time of a new road paint is 45 minutes. A contractor suspects the actual drying time is longer than 45 min.",
        "`mu` (mean drying time, min)", "<= 45", "> 45", 1, // right-tailed
        "The contractor's claim is 'longer than 45', which is a one-sided alternative (right-tailed)."),
  array("Last year, the school's mean ACT composite was 22.4. The principal believes this year's mean is lower.",
        "`mu` (mean ACT)", ">= 22.4", "< 22.4", 0, // left-tailed
        "The belief is 'lower than' last year's mean, which is a left-tailed alternative."),
  array("A clinical trial wants to test whether a new medication changes mean systolic blood pressure from the historical population mean of 132 mmHg.",
        "`mu` (mean systolic BP, mmHg)", "= 132", "!= 132", 2,
        "The word 'changes' is non-directional, so the test is two-tailed."),
  array("A logistics company advertises mean delivery time is 28 hours. A customer group wants to test whether mean delivery time is greater than 28 hours.",
        "`mu` (mean delivery time, hours)", "<= 28", "> 28", 1,
        "The customer group's claim is 'greater than 28', which is a right-tailed alternative.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$param_label = $cases[$i][1]
$h0 = $cases[$i][2]
$ha = $cases[$i][3]
$dirCode = $cases[$i][4]  // 0 left, 1 right, 2 two
$why = $cases[$i][5]

// Part a: pick correct parameter
$choices[0] = array(
  $param_label,
  "`p` (population proportion)",
  "`sigma` (population standard deviation)"
)
$answer[0] = 0

// Part b: pick correct Ha (the alternative)
$choices[1] = array("`H_a:` $param_label `$ha`", "`H_a:` $param_label `$h0`")
$answer[1] = 0

// Part c: pick correct tail
$choices[2] = array("Left-tailed", "Right-tailed", "Two-tailed")
$answer[2] = $dirCode

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
      <p><b>Parameter:</b> ' . $param_label . '.</p>
      <p><b>`H_0`:</b> ' . $param_label . ' `' . $h0 . '`. <b>`H_a`:</b> ' . $param_label . ' `' . $ha . '`.</p>
      <p><b>Tail:</b> ' . $why . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the parameter being tested? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which is the correct alternative hypothesis? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What kind of test is this? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
