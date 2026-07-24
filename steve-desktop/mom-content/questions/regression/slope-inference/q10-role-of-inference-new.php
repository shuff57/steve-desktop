// === NAME - DESCRIPTION: Role of Inference for the Slope — what H0:beta1=0 tests ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$ci = rand(0, 3)
$xnames = array("hours of tutoring", "hours of exercise per week", "hours of sleep per night", "years of work experience")
$ynames = array("exam score", "resting heart rate (bpm)", "memory test score", "annual salary (thousands of dollars)")
$b0s    = array(52,   90,  40,   35)
$b1s    = array( 4.3, -1.8,  6.2,   3.1)
$b1disps= array("+4.3", "-1.8", "+6.2", "+3.1")

$xname  = $xnames[$ci]
$yname  = $ynames[$ci]
$b0     = $b0s[$ci]
$b1     = $b1s[$ci]
$b1disp = $b1disps[$ci]

$anstypes = array("choices", "choices")
$answer[0] = 1
$choices[0] = array(
  "Whether the sample slope `b_1 = $b1` equals zero exactly.",
  "Whether there is a real linear relationship between $xname and $yname in the population.",
  "Whether the regression equation predicts $yname without error.",
  "Whether $xname causes changes in $yname."
)
$noshuffle[0] = "all"

$answer[1] = 0
$choices[1] = array(
  "There is not enough evidence of a linear relationship between $xname and $yname.",
  "There is definitely no relationship between $xname and $yname.",
  "The slope `beta_1` is exactly zero in the population.",
  "The regression equation should be discarded."
)
$noshuffle[1] = "all"

$solutionguide = '
<style>
  .sol-wrap details{width:100%;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;}
  .sol-wrap summary{cursor:pointer;display:block;width:100%;background:#f0f4ff;color:#21242c;padding:0.5em 0.75em;font-weight:700;font-size:15px;border-bottom:1px solid #e5e7eb;list-style:none;}
  .sol-wrap summary::-webkit-details-marker{display:none;}
  .sol-arrow-open{display:none;}
  .sol-wrap details[open] .sol-arrow-closed{display:none;}
  .sol-wrap details[open] .sol-arrow-open{display:inline;}
  .sol-body{padding:0.75em;background:#fafafa;}
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;margin:1em 0;">
  <details>
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><b>Part a:</b> The correct answer is: <em>Whether there is a real linear relationship between ' . $xname . ' and ' . $yname . ' in the population.</em></p>
      <p>The sample will almost always give a non-zero slope just from random variation. Testing `H_0: beta_1 = 0` asks whether the slope we see in the sample is large enough to conclude that a real linear trend exists in the <b>population</b>, not just in our data.</p>
      <p><b>Part b:</b> The correct answer is: <em>There is not enough evidence of a linear relationship between ' . $xname . ' and ' . $yname . '.</em></p>
      <p>Failing to reject `H_0` is <b>not</b> the same as proving `beta_1 = 0`. It simply means the data do not provide strong enough evidence to conclude a linear relationship exists. We cannot claim there is "definitely no relationship."</p>
      <div style="margin:10px 0;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 8px 8px 0;">
        <b>Key idea:</b> The slope test is about inference on `beta_1` (population), not on `b_1` (sample). A non-zero sample slope could be noise — the test decides whether it is.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A researcher fits a regression predicting <b>$yname</b> from <b>$xname</b>:</p>
    <p style="margin:0 0 10px 0;text-align:center;">`hat{y} = $b0 $b1disp x`</p>
    <p style="margin:0;">They test `H_0: beta_1 = 0` vs. `H_a: beta_1 ne 0` at `alpha = 0.05`.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:3px 10px;font-size:13px;font-weight:700;margin-right:10px;vertical-align:middle;">a.</span> What does the hypothesis test `H_0: beta_1 = 0` actually test? $answerbox[0]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:3px 10px;font-size:13px;font-weight:700;margin-right:10px;vertical-align:middle;">b.</span> The p-value is 0.31. The researcher <b>fails to reject</b> `H_0`. What is the appropriate conclusion? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
