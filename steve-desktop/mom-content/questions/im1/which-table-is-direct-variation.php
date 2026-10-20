// === NAME - DESCRIPTION: Which Table Is Direct Variation? - Compare two tables, pick the one with a constant ratio, write its equation, and find the starting value of the other ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.5. Section 2.5.4, Investigate 2.5.4: one table has a constant ratio y/x and passes through
// (0, 0); the other has a starting value (a fee), so y/x keeps changing. In every table the first input
// equals the step, so one step back is x = 0. The direct table can be Table A or Table B.
// k is a multiple of 0.25 and the inputs are whole numbers, so every answer is exact.
$anstypes = array("choices", "number", "number", "number")

$c = rand(0, 3)
$dXA = array("servings", "square meters", "hours", "hours")
$dYA = array("cups of rice", "liters of paint", "dollars earned", "miles driven")
$fXA = array("miles", "days", "months", "minutes")
$fYA = array("taxi fare in dollars", "truck rental cost in dollars", "gym cost in dollars", "liters in a pool")

$dX = $dXA[$c]
$dY = $dYA[$c]
$fX = $fXA[$c]
$fY = $fYA[$c]

$st1 = randfrom(array(1, 2, 3, 5))
$st2 = randfrom(array(1, 2, 3, 5))
$kk = randfrom(array(1.5, 2, 2.5, 3, 4, 5, 0.75, 1.25))
$mm = randfrom(array(1, 1.5, 2, 2.5, 3, 4))
$bb = randfrom(array(2, 3, 4, 5, 6, 8, 10))
$xq = randfrom(array(7, 11, 13, 14))

$dx1 = $st1
$dx2 = 2 * $st1
$dx3 = 3 * $st1
$dx4 = 4 * $st1
$dy1 = round($kk * $dx1, 2)
$dy2 = round($kk * $dx2, 2)
$dy3 = round($kk * $dx3, 2)
$dy4 = round($kk * $dx4, 2)
$fx1 = $st2
$fx2 = 2 * $st2
$fx3 = 3 * $st2
$fx4 = 4 * $st2
$fy1 = round($bb + $mm * $fx1, 2)
$fy2 = round($bb + $mm * $fx2, 2)
$fy3 = round($bb + $mm * $fx3, 2)
$fy4 = round($bb + $mm * $fx4, 2)

$dirB = rand(0, 1)
$aX = $dX
$aY = $dY
$aX1 = $dx1
$aX2 = $dx2
$aX3 = $dx3
$aX4 = $dx4
$aY1 = $dy1
$aY2 = $dy2
$aY3 = $dy3
$aY4 = $dy4
$bX = $fX
$bY = $fY
$bX1 = $fx1
$bX2 = $fx2
$bX3 = $fx3
$bX4 = $fx4
$bY1 = $fy1
$bY2 = $fy2
$bY3 = $fy3
$bY4 = $fy4
$dirName = "Table A"
$otherName = "Table B"
if ($dirB == 1) {
  $aX = $fX
  $aY = $fY
  $aX1 = $fx1
  $aX2 = $fx2
  $aX3 = $fx3
  $aX4 = $fx4
  $aY1 = $fy1
  $aY2 = $fy2
  $aY3 = $fy3
  $aY4 = $fy4
  $bX = $dX
  $bY = $dY
  $bX1 = $dx1
  $bX2 = $dx2
  $bX3 = $dx3
  $bX4 = $dx4
  $bY1 = $dy1
  $bY2 = $dy2
  $bY3 = $dy3
  $bY4 = $dy4
  $dirName = "Table B"
  $otherName = "Table A"
}
$atX = round($kk * $xq, 2)

$questions[0] = array("Table A", "Table B")
$answer[0] = $dirB
$answer[1] = $kk
$answer[2] = $atX
$answer[3] = $bb
$abstolerance[1] = 0.001
$abstolerance[2] = 0.001
$abstolerance[3] = 0.001

$sol = '<style>
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
      <p><span class="term-label">(a) Divide y by x.</span> In ' . $dirName . ' every column gives the same ratio: ' . $dy1 . ' &divide; ' . $dx1 . ' = ' . $dy2 . ' &divide; ' . $dx2 . ' = ' . $dy3 . ' &divide; ' . $dx3 . ' = ' . $dy4 . ' &divide; ' . $dx4 . ' = ' . $kk . '. In ' . $otherName . ' the ratio keeps changing: ' . $fy1 . ' &divide; ' . $fx1 . ' is not ' . $fy2 . ' &divide; ' . $fx2 . '. So ' . $dirName . ' is direct variation.</p>
      <p><span class="term-label">(b) The equation.</span> The constant ratio is k = ' . $kk . ', so `y = ' . $kk . ' x`.</p>
      <p><span class="term-label">(c) Use it.</span> `y = ' . $kk . ' * ' . $xq . ' = ' . $atX . '`.</p>
      <p><span class="term-label">(d) The other table.</span> The output goes up by ' . round($mm * $st2, 2) . ' for each step of ' . $st2 . ' in the input. One step back from the first column gives the output at an input of 0: ' . $fy1 . ' - ' . round($mm * $st2, 2) . ' = ' . $bb . '. That starting value is not 0, so the line does not pass through the origin.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Exactly one of these two tables is direct variation.</p>
    <p style="margin:10px 0 4px 0;"><b>Table A:</b> $aX and $aY</p>
    <table style="border-collapse:collapse; margin:0 0 10px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">$aX</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$aX1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$aX2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$aX3</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$aX4</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">$aY</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$aY1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$aY2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$aY3</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$aY4</td></tr>
    </table>
    <p style="margin:10px 0 4px 0;"><b>Table B:</b> $bX and $bY</p>
    <table style="border-collapse:collapse; margin:0 0 10px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">$bX</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$bX1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$bX2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$bX3</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$bX4</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">$bY</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$bY1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$bY2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$bY3</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$bY4</td></tr>
    </table>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter numbers only. Every answer is exact, so there is nothing to round.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which table is direct variation?<br><br>$answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Write the equation of that table as `y = k x`, where `x` is the top row and `y` is the bottom row. Find `k`. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Use that equation. What is `y` when `x = $xq`? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> The other table is not direct variation. What would its output be if its input were 0? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
