// === NAME - DESCRIPTION: Intercept from Means - Compute a = ybar - b*xbar ===
// === SET QUESTION TYPE TO: numerical ===

// === COMMON CONTROL ===

$ctx_options = array(
  array("square footage (in hundreds)", "home sale price (thousands of dollars)"),
  array("age of used car (years)", "selling price (thousands of dollars)"),
  array("temperature (&deg;F)", "daily ice cream sales (dollars)"),
  array("number of hours studied", "exam score (points)")
)
$ctx = $ctx_options[rand(0, count($ctx_options) - 1)]
$xname = $ctx[0]
$yname = $ctx[1]

$xbar = rand(50, 180) / 10
$ybar = rand(200, 800) / 10
$b1 = rand(-45, 45) / 10
if ($b1 == 0) { $b1 = 1.5 }

$a = round($ybar - $b1 * $xbar, 3)

$answer = $a
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
      <p>The least squares line always passes through the point of averages `(bar{x}, bar{y})`. Plugging that point into `hat{y} = b_0 + b_1 x` and solving for the intercept gives:</p>
      <p style="text-align:center;">`b_0 = bar{y} - b_1 cdot bar{x}`</p>
      <p><b>Substitute:</b></p>
      <p>`b_0 = ' . $ybar . ' - (' . $b1 . ')(' . $xbar . ') = ' . $ybar . ' - ' . round($b1 * $xbar, 3) . ' = ' . $a . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> Once you know the slope, the intercept is pinned down by the requirement that the line go through `(bar{x}, bar{y})`.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A least squares regression line predicts <b>$yname</b> from <b>$xname</b>. You are given:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`bar{x} = $xbar, \quad bar{y} = $ybar, \quad "slope " b_1 = $b1`</p>
    <p style="margin:0;">Compute the <b>intercept</b> `b_0` of the regression line. Round to 3 decimal places.</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
