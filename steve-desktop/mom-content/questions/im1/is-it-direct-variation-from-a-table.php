// === NAME - DESCRIPTION: Is It Direct Variation? Test a Table - Divide y by x in two columns, decide yes or no, then give k or the starting value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.5. Section 2.5.4: direct variation means y divided by x is the same in every column, and the
// line passes through the origin. Half the draws are y = kx (k is never 1). The other half are y = mx + b
// with b not 0, so the ratio changes. Ratios are rounded to two places with a tolerance of 0.006, so a
// ratio that lands on a half in the third decimal accepts either rounding. In the "no" case the last
// part asks for y at x = 0, which is b.
$anstypes = array("number", "number", "choices", "number")

$mode = rand(0, 1)
$cfg = rand(0, 5)
$sA = array(1, 2, 3, 1, 2, 5)
$dA = array(1, 2, 3, 2, 1, 5)
$xs = $sA[$cfg]
$xd = $dA[$cfg]
$x1 = $xs
$x2 = $xs + $xd
$x3 = $xs + 2 * $xd
$x4 = $xs + 3 * $xd

$kk = randfrom(array(1.5, 2, 2.5, 3, 4, 5, 6, 7, 0.5))
$mm = randfrom(array(1.5, 2, 2.5, 3, 4, 5, 6))
$bb = randfrom(array(1, 2, 3, 4, 5, 6, 7, 8, 9, -1, -2, -3, -4, -5))
if ($mm * $x1 + $bb <= 0) {
  $bb = 3
}

$y1 = round($kk * $x1, 2)
$y2 = round($kk * $x2, 2)
$y3 = round($kk * $x3, 2)
$y4 = round($kk * $x4, 2)
$firstRatio = $kk
$lastRatio = $kk
$finalAns = $kk
$verdict = "y divided by x is " . $kk . " in every column, so it never changes. It is direct variation, with k = " . $kk . "."
if ($mode == 1) {
  $y1 = round($mm * $x1 + $bb, 2)
  $y2 = round($mm * $x2 + $bb, 2)
  $y3 = round($mm * $x3 + $bb, 2)
  $y4 = round($mm * $x4 + $bb, 2)
  $firstRatio = round($y1 / $x1, 2)
  $lastRatio = round($y4 / $x4, 2)
  $finalAns = $bb
  $verdict = "y divided by x changes from column to column, so it is not direct variation. Each time x goes up by " . $xd . ", y goes up by " . round($mm * $xd, 2) . ". Going back to x = 0 gives y = " . $bb . ", not 0."
}

$questions[2] = array("Yes, it is direct variation", "No, it is not direct variation")
$answer[0] = $firstRatio
$answer[1] = $lastRatio
$answer[2] = $mode
$answer[3] = $finalAns
$abstolerance[0] = 0.006
$abstolerance[1] = 0.006
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
      <p><span class="term-label">(a) and (b) Divide y by x.</span> First column: ' . $y1 . ' &divide; ' . $x1 . ' = ' . $firstRatio . '. Last column: ' . $y4 . ' &divide; ' . $x4 . ' = ' . $lastRatio . '.</p>
      <p><span class="term-label">(c) Decide.</span> ' . $verdict . '</p>
      <p><span class="term-label">(d) Last box.</span> If it is direct variation, the answer is k. If it is not, the answer is the value of y at x = 0. Here that is ' . $finalAns . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Here is a table of x and y.</p>
    <table style="border-collapse:collapse; margin:10px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">x</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x3</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x4</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">y</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y3</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$y4</td></tr>
    </table>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Round to 2 decimal places if you need to.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `y / x` in the first column, where `x = $x1`. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `y / x` in the last column, where `x = $x4`. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is the table direct variation?<br><br>$answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> If you answered yes in part c, enter `k`. If you answered no, enter the value of `y` when `x = 0`. $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
