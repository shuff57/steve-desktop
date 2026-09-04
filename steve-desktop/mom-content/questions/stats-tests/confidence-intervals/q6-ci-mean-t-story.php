// === NAME - DESCRIPTION: CI for One Mean (t) with Story Context - Compute a t-interval from a story scenario, then interpret the interval and the meaning of confidence ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices")

// Each scenario: story, n, xbar, s, confidence
$cases = array(
  array("the average number of minutes per day local students spend on social media",
        "minutes", 36, 142.5, 28.4, 0.95),
  array("the average daily caffeine consumption (in mg) among professional truck drivers",
        "mg", 28, 415, 92, 0.90),
  array("the average weekly grocery bill (in dollars) for a family of four in this city",
        "dollars", 50, 187.40, 32.10, 0.99),
  array("the average commute time (in minutes) for telework-eligible employees who chose to work in-office",
        "minutes", 42, 31.8, 9.6, 0.95),
  array("the average ankle range-of-motion (in degrees) for high-school cross-country runners",
        "degrees", 24, 47.5, 6.3, 0.95)
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$units = $cases[$i][1]
$n = $cases[$i][2]
$xbar = $cases[$i][3]
$s = $cases[$i][4]
$conf = $cases[$i][5]

$df = $n - 1
$alpha = 1 - $conf
$tcrit = invtcdf(1 - $alpha/2, $df)
$se = $s / sqrt($n)
$me = $tcrit * $se
$lo = $xbar - $me
$hi = $xbar + $me

$answer[0] = $lo
$answer[1] = $hi
$reltolerance[0] = 0.02
$reltolerance[1] = 0.02
$abstolerance[0] = 0.5
$abstolerance[1] = 0.5

// Part c: pick correct interpretation
$confPct = $conf * 100

$choices[2] = array(
  "We are " . $confPct . "% confident that " . $ctx . " falls in this interval.",
  "In " . $confPct . "% of all possible samples of this size, the resulting interval would contain the population mean.",
  "There is a " . $confPct . "% chance the population mean is between the two endpoints.",
  "About " . $confPct . "% of individual values in the population fall inside this interval."
)
$answer[2] = 0  // The standard "we are X% confident that mu lies in this interval" wording IS correct; the others are common misinterpretations
$noshuffle[2] = "all"
// However: rubric note: "We are X% confident that <population mean of the variable> falls in this interval" is the canonical correct phrasing.

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
      <p><b>SE:</b> `"SE" = s/sqrt(n) = ' . $s . '/sqrt(' . $n . ') ~~ ' . round($se, 4) . '`.</p>
      <p><b>Critical value:</b> `"df" = ' . $df . '`, `t^** ~~ ' . round($tcrit, 3) . '` for ' . $confPct . '% confidence.</p>
      <p><b>Margin of error:</b> `"ME" = t^** * "SE" ~~ ' . round($me, 3) . '`.</p>
      <p><b>CI:</b> `(' . round($lo, 3) . ', ' . round($hi, 3) . ')` ' . $units . '.</p>
      <p><b>Interpretation:</b> We are ' . $confPct . '% confident that the population mean `mu` falls in this interval.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher is studying <b>$ctx</b>. From a random sample of $n subjects: `bar(x) = $xbar` $units and `s = $s` $units. Construct a <b>$confPct% confidence interval</b> for the population mean.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>lower endpoint</b>? (Round to 2 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>upper endpoint</b>? (Round to 2 decimal places.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which is the correct interpretation? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
