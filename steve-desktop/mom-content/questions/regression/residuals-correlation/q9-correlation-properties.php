// === NAME - DESCRIPTION: Correlation Properties - Linear-transformation invariance, symmetry, linear-only ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Scenario randomization — variable pair plus a unit conversion for x
$ctx_x      = array("height",      "study time", "tree diameter", "engine displacement", "rainfall")
$ctx_y      = array("arm span",    "exam score", "tree height",   "fuel economy",        "crop yield")
$ctx_xold   = array("inches",      "hours",      "centimeters",   "liters",              "inches")
$ctx_xnew   = array("centimeters", "minutes",    "meters",        "cubic inches",        "millimeters")
$picked = jointrandfrom($ctx_x, $ctx_y, $ctx_xold, $ctx_xnew)
$xvar = $picked[0]
$yvar = $picked[1]
$xunit_old = $picked[2]
$xunit_new = $picked[3]

// Part a — does r change after a linear unit conversion of x? No.
$choices[0] = array("Yes, `r` would change", "No, `r` would stay the same")
$displayformat[0] = "select"
$noshuffle[0] = "all"
$answer[0] = 1

// Part b — does r change if we swap x and y? No.
$choices[1] = array("Yes, `r` would change", "No, `r` would stay the same")
$displayformat[1] = "select"
$noshuffle[1] = "all"
$answer[1] = 1

// Part c — what kind of association does r measure? Linear only.
$choices[2] = array("Linear association only", "Any association, including curved patterns")
$displayformat[2] = "select"
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
      <p><b>(a) Unit conversion.</b> Converting ' . $xvar . ' from ' . $xunit_old . ' to ' . $xunit_new . ' is a <b>linear rescaling</b> of `x`. Correlation is computed from standardized values `(x - bar{x})/s_x`, and any positive linear rescaling cancels out in numerator and denominator. So `r` does <b>not</b> change.</p>
      <p><b>(b) Swapping x and y.</b> The formula for `r` is symmetric in `x` and `y`: <br>`r = 1/(n-1) sum (x_i - bar{x})/s_x cdot (y_i - bar{y})/s_y`. <br>If you swap the roles, the product is the same, so `r` is unchanged.</p>
      <p><b>(c) Type of association.</b> `r` measures the strength of a <b>linear</b> relationship only. A perfectly curved pattern (like `y = x^2` on `[-1, 1]`) can have `r = 0` even though `x` and `y` are tightly related, just not linearly.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Quick recap: `r` is invariant to linear unit changes, symmetric in `x` and `y`, and only sees linear structure.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A study reports the correlation `r` between <b>{ $xvar }</b> and <b>{ $yvar }</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Suppose you re-recorded { $xvar } in <b>{ $xunit_new }</b> instead of <b>{ $xunit_old }</b>. Would the value of `r` change? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Suppose you swap the roles, treating { $yvar } as `x` and { $xvar } as `y`. Would the value of `r` change? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What kind of association does `r` actually measure? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
