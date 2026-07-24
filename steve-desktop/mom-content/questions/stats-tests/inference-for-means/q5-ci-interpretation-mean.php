// === NAME - DESCRIPTION: CI for one mean interpretation - Pick correct wording in context ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices")

// Three scenarios. Each gives a sample, a CI, and asks for correct interpretation.
// Part a: correct interpretation of CI (4 options)
// Part b: correct interpretation of the confidence level (4 options)

$ctxs = array(
  array("topic" => "average daily sleep among college students", "unit" => "hours", "conf" => 95, "lo" => 6.7, "hi" => 7.4, "context" => "A researcher sampled 80 college students and computed a 95% confidence interval for the mean nightly sleep."),
  array("topic" => "average commute time of urban workers",       "unit" => "minutes", "conf" => 90, "lo" => 27, "hi" => 33, "context" => "A transportation agency sampled 120 urban workers and computed a 90% confidence interval for the mean one-way commute."),
  array("topic" => "average weekly grocery spending per household", "unit" => "dollars", "conf" => 99, "lo" => 142, "hi" => 168, "context" => "A market researcher sampled 60 households and computed a 99% confidence interval for the mean weekly grocery spending.")
)

$topics   = array("average daily sleep among college students", "average commute time of urban workers", "average weekly grocery spending per household")
$units    = array("hours", "minutes", "dollars")
$confs    = array(95, 90, 99)
$los      = array(6.7, 27, 142)
$his      = array(7.4, 33, 168)
$ctxstrs  = array("A researcher sampled 80 college students and computed a 95% confidence interval for the mean nightly sleep.", "A transportation agency sampled 120 urban workers and computed a 90% confidence interval for the mean one-way commute.", "A market researcher sampled 60 households and computed a 99% confidence interval for the mean weekly grocery spending.")
$picked = jointrandfrom($topics, $units, $confs, $los, $his, $ctxstrs)
$topic = $picked[0]
$unit  = $picked[1]
$conf  = $picked[2]
$lo    = $picked[3]
$hi    = $picked[4]
$ctx   = $picked[5]

// Part a: correct interpretation of the interval (about a population parameter, not a sample)
$choices[0] = array(
  "We are " . $conf . "% confident that the population mean " . $topic . " is between " . $lo . " and " . $hi . " " . $unit . ".",
  "There is a " . $conf . "% probability that the sample mean is between " . $lo . " and " . $hi . " " . $unit . ".",
  $conf . "% of all individuals in the population have " . $topic . " between " . $lo . " and " . $hi . " " . $unit . ".",
  "There is a " . $conf . "% probability that the true population mean equals the sample mean."
)
$answer[0] = 0
$noshuffle[0] = "all"

// Part b: meaning of the confidence LEVEL (long-run interpretation, not probability about this interval)
$choices[1] = array(
  "If we repeated the study many times, about " . $conf . "% of the resulting confidence intervals would contain the true population mean.",
  "There is a " . $conf . "% probability that this specific interval contains the population mean.",
  "The interval has a " . $conf . "% chance of being correct for this sample.",
  $conf . "% of individuals in the sample fall inside the interval."
)
$answer[1] = 0
$noshuffle[1] = "all"

$displayformat[0] = "select"
$displayformat[1] = "select"

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
      <p><b>Part a.</b> A confidence interval is a statement about the population <i>parameter</i>, not about individual sample values or about probability for this specific interval. The right wording is "we are ' . $conf . '% confident the population mean is between ' . $lo . ' and ' . $hi . ' ' . $unit . '."</p>
      <p><b>Part b.</b> The confidence <i>level</i> is a long-run statement about the procedure: if we repeated the study many times, about ' . $conf . '% of the resulting intervals would capture the true mean. Once an interval is calculated, it either contains the true mean or it does not. There is no probability statement about a specific interval after the fact.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff8e1; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Common traps:</b> "There is a $conf% probability that this interval contains the mean" is wrong (frequentist intervals don\'t work that way). "$conf% of individuals fall in the interval" is wrong (the interval is about the mean, not individual values).
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===
<style>select{max-width:100%;width:100%;box-sizing:border-box;display:block;margin:4px 0;}</style>

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx The interval reported is `($lo,\ $hi)` $unit.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which is the best interpretation of this confidence interval?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the `$conf`% confidence <i>level</i> mean?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
