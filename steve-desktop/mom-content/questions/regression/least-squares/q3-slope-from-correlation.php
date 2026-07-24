// === NAME - DESCRIPTION: Slope from Correlation - Compute b = r(s_y/s_x) ===
// === SET QUESTION TYPE TO: numerical ===

// === COMMON CONTROL ===

$ctx_options = array(
  array("height (inches)", "weight (pounds)", "x", "y"),
  array("study hours per week", "exam score (points)", "x", "y"),
  array("daily screen time (hours)", "hours of sleep", "x", "y"),
  array("years of work experience", "annual salary (thousands of dollars)", "x", "y")
)
$ctx = $ctx_options[rand(0, count($ctx_options) - 1)]
$xname = $ctx[0]
$yname = $ctx[1]

$r = rand(-85, 85) / 100
$sx = rand(20, 80) / 10
$sy = rand(30, 120) / 10

$slope = round($r * $sy / $sx, 3)

$answer = $slope
$reltolerance = 0.02

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
      <p>The slope of the least squares regression line uses both the correlation and the two standard deviations:</p>
      <p style="text-align:center;">`b_1 = r cdot s_y/s_x`</p>
      <p><b>Substitute:</b></p>
      <p>`b_1 = (' . $r . ') cdot (' . $sy . ' -: ' . $sx . ') = ' . $slope . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Sanity check:</b> The slope has the same sign as the correlation. A positive `r` gives a positive slope; a negative `r` gives a negative slope.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A researcher is fitting a least squares regression line that predicts <b>$yname</b> from <b>$xname</b>. The following summary statistics are given:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`r = $r, \quad s_x = $sx, \quad s_y = $sy`</p>
    <p style="margin:0;">Compute the <b>slope</b> `b_1` of the least squares regression line. Round to 3 decimal places.</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
