// === NAME - DESCRIPTION: Equation from a Table of Readings - Find the rate of change from equal steps in a table, find the value at x = 0 that the table leaves out, write y = mx + b and predict a later value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.7 (2.7.1 Equation from a Table). Four rows with equal steps in x. There is never a row
// for x = 0, so b has to be found by going back or by solving. The slope is p/q with q in 1, 2, 4, 5,
// so it is an exact decimal, and every x is a multiple of q, so every y is a whole number. Falling
// contexts get b large enough that every table value and the predicted value stay positive.
$anstypes = array("calculated", "calculated", "calculated")
$abstolerance = 0.0001
$answerboxsize = [10, 10, 10]

$i = rand(0, 4)
$intro = array("A baker wrote down the temperature of a proofing box at four times after switching it on.", "A sorting center scanner counted the parcels that had passed it at four times during a shift.", "A weather station measured the depth of snow on the ground on four days after a storm.", "A printer's ink level was checked at four times while it printed.", "A tank is filling. Its volume was read at four times after the pump started.")
$yNames = array("Temperature T in degrees", "Parcels P", "Snow depth D in centimeters", "Ink V in milliliters", "Volume W in liters")
$xNames = array("Minutes t", "Minutes t", "Days d", "Minutes t", "Minutes t")
$yVars = array("T", "P", "D", "V", "W")
$xVars = array("t", "t", "d", "t", "t")
$dirs = array(1, 1, -1, -1, 1)
$yWhat = array("the temperature in degrees", "the parcels", "the depth in centimeters", "the ink in milliliters", "the liters")
$introText = $intro[$i]
$yName = $yNames[$i]
$xName = $xNames[$i]
$yvar = $yVars[$i]
$xvar = $xVars[$i]
$whatY = $yWhat[$i]

$q = randfrom(array(1, 2, 4, 5))
$p0 = rand(1, 9)
if (gcd($p0, $q) != 1) {
  $p0 = 1
}
$p = $p0 * $dirs[$i]
$m = round($p / $q, 4)
$stepJ = rand(1, 2)
$j0 = rand(1, 4)
$jq = $j0 + 3 * $stepJ + rand(3, 8)
$b = rand(10, 60)
if ($p < 0) {
  $b = abs($p) * $jq + rand(5, 25)
}
$x0 = $q * $j0
$x1 = $q * ($j0 + $stepJ)
$x2 = $q * ($j0 + 2 * $stepJ)
$x3 = $q * ($j0 + 3 * $stepJ)
$y0 = $p * $j0 + $b
$y1 = $p * ($j0 + $stepJ) + $b
$y2 = $p * ($j0 + 2 * $stepJ) + $b
$y3 = $p * ($j0 + 3 * $stepJ) + $b
$xq = $q * $jq
$yq = $p * $jq + $b
$mx0 = round($m * $x0, 4)
$mxq = round($m * $xq, 4)
$dyA = $y1 - $y0
$dxA = $x1 - $x0
$dyB = $y3 - $y2
$dxB = $x3 - $x2
$eq = $yvar . " = " . $m . $xvar . " + " . $b

$answer[0] = $m
$answer[1] = $b
$answer[2] = $yq

$sol = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">(a) The rate of change.</span> Use any two rows and subtract in the same order on the top and the bottom: `(' . $y1 . ' - ' . $y0 . ')/(' . $x1 . ' - ' . $x0 . ') = ' . $dyA . '/' . $dxA . ' = ' . $m . '`. Another pair agrees: `(' . $y3 . ' - ' . $y2 . ')/(' . $x3 . ' - ' . $x2 . ') = ' . $dyB . '/' . $dxB . ' = ' . $m . '`. So `m = ' . $m . '`.</p>
      <p><span class="term-label">(b) The value at ' . $xvar . ' = 0.</span> The table has no row for `' . $xvar . ' = 0`, so solve for b with the first row: `' . $y0 . ' = ' . $m . '(' . $x0 . ') + b`. That gives `' . $y0 . ' = ' . $mx0 . ' + b`, so `b = ' . $y0 . ' - (' . $mx0 . ') = ' . $b . '`. The equation is `' . $eq . '`. A common mistake is to use ' . $y0 . ' as b. That is the value at `' . $xvar . ' = ' . $x0 . '`, not at 0.</p>
      <p><span class="term-label">(c) Predict.</span> At `' . $xvar . ' = ' . $xq . '`: `' . $m . '(' . $xq . ') + ' . $b . ' = ' . $mxq . ' + ' . $b . ' = ' . $yq . '`. Check the equation against a row you did not use: at `' . $xvar . ' = ' . $x3 . '` it gives `' . $m . '(' . $x3 . ') + ' . $b . ' = ' . $y3 . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText The table is linear. The equation has the form `$yvar = m$xvar + b`.</p>
    <table style="border-collapse:collapse; margin:10px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">$xName</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x0</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x3</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">$yName</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y0</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y3</td></tr>
    </table>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter each answer as a number. A fraction or a decimal is fine.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The rate of change <b>m</b> = $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The table has no row for `$xvar = 0`. Find <b>b</b>, the value of `$yvar` when `$xvar = 0`. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Use your equation to find `$yvar` when `$xvar = $xq`. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
