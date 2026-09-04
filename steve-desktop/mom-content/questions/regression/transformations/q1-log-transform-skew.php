// === NAME - DESCRIPTION: Log Transform for Right-Skewed Residuals - Pick the right transform, then judge its effect ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

// Scenario randomization: `y` is a positive quantity that often shows right-skew
$ctx_y = array("annual household income (dollars)", "home sale price (dollars)", "city population", "company revenue (millions of dollars)", "lifetime earnings (thousands)")
$ctx_x = array("years of education",                "square footage",            "median age",      "annual ad spend (thousands)",          "years of work experience")
$picked = jointrandfrom($ctx_y, $ctx_x)
$yname = $picked[0]
$xname = $picked[1]

// Part a: which transform best addresses right-skew on y?
$choices[0] = array(
  "Replace `y` with `ln(y)` (natural log of `y`).",
  "Leave `y` alone; right-skewed residuals are not a problem.",
  "Replace `x` with `1/x` (reciprocal of `x`).",
  "Square the response, replacing `y` with `y^2`."
)
$noshuffle[0] = "all"
$answer[0] = 0

// Part b: after the log transform the new residuals look patternless and roughly Normal. Useful?
$choices[1] = array(
  "Yes. The original residuals had a right-skewed pattern, but the log-transformed residuals look random and roughly Normal, so the LINE conditions are much closer to being met.",
  "No. Transformations always make a model harder to interpret, so we should keep the original.",
  "We cannot tell from a residual plot whether a transformation helped.",
  "Yes, but only because the new slope is larger than the old one."
)
$noshuffle[1] = "all"
$answer[1] = 0

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
      <p><b>(a) Why log.</b> Quantities like ' . $yname . ' are bounded below by 0 and have a long right tail. Taking `ln(y)` pulls in the upper tail and pushes the bulk of the distribution toward symmetry, which usually fixes right-skewed residuals and the heteroscedastic spread that comes with it.</p>
      <p><b>(b) Did the transform help?</b> The diagnostic check is the new residual plot. If the residuals of `ln(y)` regressed on ' . $xname . ' look patternless and roughly Normal, the LINE conditions are close to being met and we can trust the inference on the transformed scale.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Quick rule of thumb: when `y` is positive and right-skewed (income, price, population, revenue), try `ln(y)` first. Always re-check the residuals to see if the fix worked.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">An analyst regresses <b>{ $yname }</b> on <b>{ $xname }</b>. The residual plot shows a strong right-skew: most residuals are clustered slightly below 0 with a long upper tail of large positive residuals.</p>
    <p style="margin:0;">The analyst is considering a transformation of `y` to fix the diagnostic problems.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which transformation is the most natural first attempt for this kind of right-skew? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> After applying that transformation, the new residual plot looks patternless and roughly Normal. Was the transformation useful? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
