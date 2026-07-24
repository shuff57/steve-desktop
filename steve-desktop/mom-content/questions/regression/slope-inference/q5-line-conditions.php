// === NAME - DESCRIPTION: LINE Conditions for Regression - Spot which condition (Linearity/Independence/Normality/Equal variance) is violated ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

// 4 parallel scenarios, each violates exactly one LINE condition.
// $ans_idx encodes the correct option index in $choices[0]:
//   0 = Linearity, 1 = Independence, 2 = Normality, 3 = Equal variance
$desc      = array(
  "The residual plot has a clear U-shape: residuals are positive for small and large values of `x` and negative in the middle.",
  "The data were collected weekly over two years, and consecutive residuals tend to share the same sign (long positive runs followed by long negative runs).",
  "A histogram of the residuals is strongly right-skewed with a long upper tail and several extreme positive residuals.",
  "The residual plot widens like a megaphone: residuals cluster tightly near 0 for small `x` but fan out far above and below 0 for large `x`."
)
$ans_idx   = array(0, 1, 2, 3)
$why_text  = array(
  "the residuals show systematic curvature, so the linearity condition is violated",
  "consecutive residuals are correlated over time, which violates the independence condition",
  "the residuals are not approximately Normal (they are strongly right-skewed)",
  "the spread of the residuals grows with `x`, which violates the equal-variance (constant spread) condition"
)
$picked = jointrandfrom($desc, $ans_idx, $why_text)
$desc_picked = $picked[0]
$correct_idx = $picked[1]
$why_picked  = $picked[2]

// Part a — which LINE condition is violated?
$choices[0] = array(
  "Linearity",
  "Independence",
  "Normality of residuals",
  "Equal variance (constant spread of residuals)"
)
$displayformat[0] = "select"
$noshuffle[0] = "all"
$answer[0] = $correct_idx

// Part b — should we use the standard regression inference here?
$choices[1] = array(
  "No, because when a LINE condition fails the standard slope `t`-test and CI are unreliable. We should fix the issue (transform a variable, add a term, use a different model) before trusting the inference.",
  "Yes, because regression is robust to any violation, so we can proceed as usual.",
  "Yes, because only the linearity condition matters; the other LINE conditions are optional."
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
      <p><b>The LINE conditions for regression inference:</b></p>
      <ul style="margin:0 0 10px 0; padding-left:24px;">
        <li><b>L</b>inearity: the true mean of `y` is a linear function of `x`.</li>
        <li><b>I</b>ndependence: observations (and their residuals) do not influence each other.</li>
        <li><b>N</b>ormality: residuals are approximately Normal.</li>
        <li><b>E</b>qual variance: the residuals have roughly the same spread for every value of `x`.</li>
      </ul>
      <p><b>(a) Which condition fails.</b> In this scenario, ' . $why_picked . '.</p>
      <p><b>(b) Should we use the standard inference?</b> No. When a LINE condition is violated, the slope `t`-test and CI rely on assumptions that no longer hold, so the resulting p-values and intervals can be misleading. Fix the issue first (e.g., transform a variable, add a term, switch to an autocorrelation-aware model) and re-check the residuals.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff4e5; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Memory hook:</b> a U or curved residual plot = Linearity; runs over time = Independence; skew or heavy tails = Normality; fan/wedge shape = Equal variance.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">After fitting a simple linear regression, an analyst checks the diagnostic plots and notices the following:</p>
    <p style="margin:0; padding:0.6em 0.9em; background:#f0f4ff; border-left:4px solid #1865f2; border-radius:0 8px 8px 0;">$desc_picked</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which LINE condition is most clearly violated? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Given this diagnostic, should we trust the standard slope `t`-test and CI as is? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
