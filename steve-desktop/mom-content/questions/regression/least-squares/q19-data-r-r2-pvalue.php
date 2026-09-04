// === NAME - DESCRIPTION: Correlation and Determination from Data - From a small (x, y) data set compute r, r-squared, the two-tailed p-value for a linear relationship, and interpret r-squared ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "numfunc", "choices")

// Each case: x-label, y-label, then 6 x-values, then 6 y-values. n is fixed at 6.
$cases = array(
  array("hours studied per week", "exam score (points)", 2, 3, 5, 6, 8, 9, 65, 70, 75, 78, 88, 92),
  array("daily high temperature (&deg;F)", "ice cream sales ($)", 60, 68, 72, 77, 83, 90, 120, 180, 210, 260, 330, 400),
  array("advertising spend ($1000s)", "weekly sales ($1000s)", 1, 2, 3, 4, 5, 7, 14, 19, 23, 28, 34, 41)
)

$i = rand(0, count($cases)-1)
$xlab = $cases[$i][0]
$ylab = $cases[$i][1]
$x0 = $cases[$i][2]
$x1 = $cases[$i][3]
$x2 = $cases[$i][4]
$x3 = $cases[$i][5]
$x4 = $cases[$i][6]
$x5 = $cases[$i][7]
$y0 = $cases[$i][8]
$y1 = $cases[$i][9]
$y2 = $cases[$i][10]
$y3 = $cases[$i][11]
$y4 = $cases[$i][12]
$y5 = $cases[$i][13]
$n = 6

$sumx = $x0 + $x1 + $x2 + $x3 + $x4 + $x5
$sumy = $y0 + $y1 + $y2 + $y3 + $y4 + $y5
$sumxy = $x0*$y0 + $x1*$y1 + $x2*$y2 + $x3*$y3 + $x4*$y4 + $x5*$y5
$sumxx = $x0*$x0 + $x1*$x1 + $x2*$x2 + $x3*$x3 + $x4*$x4 + $x5*$x5
$sumyy = $y0*$y0 + $y1*$y1 + $y2*$y2 + $y3*$y3 + $y4*$y4 + $y5*$y5

$Sxx = $sumxx - $sumx*$sumx/$n
$Syy = $sumyy - $sumy*$sumy/$n
$Sxy = $sumxy - $sumx*$sumy/$n

$r = $Sxy / sqrt($Sxx * $Syy)
$r2 = $r * $r
$df = $n - 2
$t = $r * sqrt($df) / sqrt(1 - $r2)
$pval = 2 * (1 - tcdf(abs($t), $df))

$answer[0] = $r
$answer[1] = $r2
$answer[2] = $pval
$reltolerance[0] = 0.02
$reltolerance[1] = 0.02
$reltolerance[2] = 0.05
$abstolerance[0] = 0.01
$abstolerance[1] = 0.01
$abstolerance[2] = 0.005

$r2pct = round($r2 * 100, 1)

// Part d: interpret r^2: 2 correct-style + 2 misdirects
$choices[3] = array(
  "About " . $r2pct . "% of the variation in " . $ylab . " is explained by its linear relationship with " . $xlab . ".",
  "About " . $r2pct . "% of the data points lie exactly on the regression line.",
  "About " . $r2pct . "% of the " . $ylab . " values are caused by " . $xlab . ".",
  "There is about a " . $r2pct . "% chance that the linear relationship is statistically significant."
)
$answer[3] = 0
$noshuffle[3] = "all"

$dataTable = '<table style="border-collapse:collapse;margin:0.5em 0;font-family:Arial;font-size:14px;">
<tr style="background:#f2f2f2"><th style="border:1px solid #ccc;padding:6px 12px;text-align:left;">' . $xlab . ' (x)</th><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $x0 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $x1 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $x2 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $x3 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $x4 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $x5 . '</td></tr>
<tr><th style="border:1px solid #ccc;padding:6px 12px;text-align:left;background:#f2f2f2;">' . $ylab . ' (y)</th><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $y0 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $y1 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $y2 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $y3 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $y4 . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $y5 . '</td></tr>
</table>'

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
      <p><b>Part a (r):</b> Using `S_(x x) = sum x^2 - (sum x)^2/n`, `S_(y y) = sum y^2 - (sum y)^2/n`, `S_(x y) = sum x y - (sum x)(sum y)/n`, then `r = S_(x y)/sqrt(S_(x x) S_(y y)) ~~ ' . round($r, 4) . '`.</p>
      <p><b>Part b (r^2):</b> `r^2 = (' . round($r, 4) . ')^2 ~~ ' . round($r2, 4) . '`.</p>
      <p><b>Part c (p-value):</b> `t = r sqrt(n-2)/sqrt(1-r^2) ~~ ' . round($t, 3) . '` with `"df" = ' . $df . '`; two-tailed `p ~~ ' . round($pval, 4) . '`.</p>
      <p><b>Part d:</b> `r^2 ~~ ' . $r2pct . '%` is the proportion of the variation in ' . $ylab . ' explained by the linear relationship with ' . $xlab . '. It is NOT the percent of points on the line, a causation claim, or a probability of significance.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">The table shows paired data for <b>$xlab</b> (x) and <b>$ylab</b> (y) from a random sample of `n = $n` observations.</p>
    $dataTable
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the <b>correlation coefficient `r`</b>. (Round to 4 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the <b>coefficient of determination `r^2`</b>. (Round to 4 decimal places.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the <b>two-tailed p-value</b>. (Round to 4 decimal places.) $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What does `r^2` mean in this context? $answerbox[3]
  </div>
</div>


// === ANSWER ===

$solutionguide
