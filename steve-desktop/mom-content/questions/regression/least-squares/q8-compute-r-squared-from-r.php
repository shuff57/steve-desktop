// === NAME - DESCRIPTION: Compute R-squared from r - R^2 = r^2 for simple linear regression ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices")

$r = rand(-95, 95) / 100
if (abs($r) < 0.3) { $r = 0.7 }

$r2 = round($r * $r, 4)
$pct = round($r2 * 100, 2)

$answer[0] = $r2
$reltolerance[0] = 0.01

$questions[1] = array(
  "Yes: for simple linear regression, `R^2 = r^2`, so the sign of `r` is lost when squaring.",
  "No: `R^2` must always equal `r`, so two different models would give different `R^2`.",
  "No: a negative correlation always gives a negative `R^2`.",
  "Yes: but only if both datasets have exactly the same sample size."
)
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
      <p><b>Part a:</b> For simple linear regression, the coefficient of determination is just the correlation squared:</p>
      <p style="text-align:center;">`R^2 = r^2 = (' . $r . ')^2 = ' . $r2 . '`</p>
      <p>As a percentage, that is about <b>' . $pct . '%</b> of the variability in `y` explained by `x`.</p>
      <p><b>Part b:</b> Two datasets can easily have correlations like `r = 0.7` and `r = -0.7` that give the <b>same</b> `R^2 = 0.49`. Squaring removes the sign, so direction information is lost in `R^2`: only the <b>strength</b> of the fit is preserved.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> `R^2` is always between 0 and 1 and tells you how tight the fit is: never the direction.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A simple linear regression has correlation coefficient `r = $r`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute `R^2`. Round to 4 decimal places. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> A second dataset has correlation `r = ' . (-$r) . '`. Could it produce the same value of `R^2` as the first dataset? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
