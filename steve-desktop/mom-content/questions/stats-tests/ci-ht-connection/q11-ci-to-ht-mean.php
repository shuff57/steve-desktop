// === NAME - DESCRIPTION: Use a CI to Test a Hypothesis About a Mean - Given a confidence interval for mu and a null value mu_0, decide whether to reject H_0 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

// Each scenario: a 95% (or 99%) CI for mean mu and a null value mu_0
$cases = array(
  array(0.95, 12.4, 14.8, 13.0, 0, "the mean fuel use (gal/100mi) of a new engine vs the manufacturer's claim of 13.0"),
  array(0.95, 102.1, 108.6, 110.0, 1, "the mean daily ad-clicks for a new layout vs the historic mean of 110"),
  array(0.99, 38.5, 42.1, 40.0, 0, "the mean daily caffeine consumption (mg) for nurses vs an industry benchmark of 40 mg"),
  array(0.95, 6.8, 7.4, 7.0, 0, "the mean weekly hours of sleep on a wearable's recommendation vs the public-health target of 7.0"),
  array(0.95, 28.2, 31.4, 35.0, 1, "the mean delivery time (hr) under a new route plan vs the advertised 35 hr")
)
// Format: [confidence, lo, hi, mu_0, decision (0 fail-to-reject, 1 reject), study description]

$i = rand(0, count($cases)-1)
$conf = $cases[$i][0]
$lo   = $cases[$i][1]
$hi   = $cases[$i][2]
$mu0  = $cases[$i][3]
$dec  = $cases[$i][4]
$ctx  = $cases[$i][5]

$alpha = 1 - $conf
$confPct = $conf * 100
$alphaStr = $alpha

// Part a: is mu_0 in the interval?
$inInterval = ($mu0 >= $lo && $mu0 <= $hi) ? 0 : 1   // 0=yes (in), 1=no (out)
$answer[0] = $inInterval

// Part b: decision at alpha
$answer[1] = $dec

$choices[0] = array(
  "Yes — `mu_0 = " . $mu0 . "` is inside the interval.",
  "No — `mu_0 = " . $mu0 . "` is outside the interval."
)
$choices[1] = array(
  "Fail to reject `H_0` (a two-sided test at `alpha = " . $alphaStr . "` would NOT reject).",
  "Reject `H_0` (a two-sided test at `alpha = " . $alphaStr . "` WOULD reject)."
)
$noshuffle[0] = "all"
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
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>Rule: a two-sided test at `alpha = ' . $alphaStr . '` rejects `H_0: mu = mu_0` exactly when `mu_0` is <b>outside</b> the `(1 - alpha)` confidence interval.</p>
      <p>Here `mu_0 = ' . $mu0 . '`. Interval is `(' . $lo . ', ' . $hi . ')`.</p>
      <p>' . ($inInterval == 0 ? "`mu_0` is INSIDE the interval, so FAIL TO REJECT `H_0`." : "`mu_0` is OUTSIDE the interval, so REJECT `H_0`.") . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher constructed a <b>$confPct% confidence interval</b> for $ctx and obtained `($lo, $hi)`. She wants to test the two-sided hypothesis `H_0: mu = $mu0` vs `H_a: mu != $mu0` at significance level `alpha = $alphaStr`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is the null value `mu_0 = $mu0` inside the interval? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the decision? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
