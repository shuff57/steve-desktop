// === NAME - DESCRIPTION: Role of Inference for the Slope - Sample slope vs population slope, sampling variability, CI meaning ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Scenario randomization
$ctx_x = array("hours of study per week",  "monthly advertising budget (thousands)", "minutes of daily exercise",  "outdoor temperature (&deg;F)")
$ctx_y = array("exam score",               "weekly sales (dollars)",                  "resting heart rate (bpm)",   "ice cream sales (dollars)")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xname = $picked_ctx[0]
$yname = $picked_ctx[1]

// CI bounds for part (c): chosen so the interval is always positive (excludes 0)
$lower = round(rand(80, 250) / 100, 2)
$me    = round(rand(40, 80)  / 100, 2)
$upper = round($lower + 2 * $me, 2)

// Part a: population vs sample slope symbol
$choices[0] = array(
  "`beta_1`",
  "`b_1`",
  "`r`",
  "`hat y`"
)
$noshuffle[0] = "all"
$answer[0] = 0

// Part b: why slope estimates differ across samples
$choices[1] = array(
  "Different random samples produce different slope estimates due to sampling variability. Inference quantifies how confident we are about the unknown population slope.",
  "One of the researchers must have made a calculation error.",
  "The true relationship between the variables genuinely changes from sample to sample.",
  "The least-squares formula gives different answers depending on whether the sample size is even or odd."
)
$noshuffle[1] = "all"
$answer[1] = 0

// Part c: interpret the CI
$choices[2] = array(
  'We are 95% confident the true population slope `beta_1` is between ' . $lower . ' and ' . $upper . '. Because this interval does not contain 0, we have evidence the predictor is associated with the response.',
  'Of all possible samples, 95% will produce a slope estimate between ' . $lower . ' and ' . $upper . '.',
  'There is a 95% probability that this specific sample slope `b_1` lies between ' . $lower . ' and ' . $upper . '.',
  'Because the interval is entirely positive, we conclude the predictor causes the response to increase.'
)
$noshuffle[2] = "all"
$answer[2] = 0

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
      <p><b>(a) Sample vs population.</b> The Greek letter `beta_1` denotes the unknown slope of the line in the <b>population</b>: the parameter we want to estimate. The Latin `b_1` is the slope of the line we fit to our <b>sample</b>, a statistic that approximates `beta_1`. `r` is the correlation; `hat y` is a predicted response.</p>
      <p><b>(b) Why the estimates differ.</b> Two random samples from the same population almost always give different slope estimates because they contain different observations. This is sampling variability: the same phenomenon we already see with the sample mean. Inference is the toolkit that lets us reason about the unknown `beta_1` from a single sample.</p>
      <p><b>(c) CI interpretation.</b> A 95% CI is a range of plausible values for the population slope `beta_1`, not for `b_1`, and it is not a probability statement about a particular sample. Because every value in the interval is positive, 0 is not a plausible value for the population slope: that is the regression analog of "reject `H_0: beta_1 = 0`".</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Interpretation rule: if a CI for the slope excludes 0, there is statistical evidence the predictor is associated with the response.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A researcher fits a regression of <b>$yname</b> on <b>$xname</b> to a random sample. She wants to draw conclusions about the relationship in the wider population.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which symbol denotes the <b>true</b> slope of the relationship in the <b>population</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Two other researchers each draw their own random sample from the same population and fit the same regression. Their slope estimates differ from hers and from each other. Which best explains the differences? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Her 95% confidence interval for the slope is `($lower, $upper)`. Which interpretation is correct? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
