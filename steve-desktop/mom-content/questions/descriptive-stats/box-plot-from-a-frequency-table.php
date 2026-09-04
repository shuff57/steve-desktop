// === NAME - DESCRIPTION: Box Plot from a Frequency Table - Read the minimum, first quartile, median and third quartile off a frequency table so the finished box plot can be checked against them ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number")

$ci = rand(0, 2)
if ($ci == 0) {
  $intro = "Students in a statistics class were surveyed on how many movies they watched last week."
  $valueLabel = "Movies watched last week"
  $unitWord = "movies"
}
else if ($ci == 1) {
  $intro = "Customers at a coffee shop were surveyed on how many cups of coffee they drank yesterday."
  $valueLabel = "Cups of coffee drank yesterday"
  $unitWord = "cups of coffee"
}
else {
  $intro = "Members of a book club were surveyed on how many novels they read last month."
  $valueLabel = "Novels read last month"
  $unitWord = "novels"
}

// Small whole-number values 0..4, so every position the box plot needs lands on a value the
// student can point to in the table. Each frequency is drawn from 5..8, so the total always
// falls in 25..40 and every one of the five values is guaranteed to appear at least 5 times:
// the smallest and largest listed values are always the true min and max, no zero-frequency rows.
$vals = array(0, 1, 2, 3, 4)
$f = array(0, 0, 0, 0, 0)
$n = 0
for ($c=0..4) {
  $f[$c] = rand(5, 8)
  $n = $n + $f[$c]
}

$minV = $vals[0]
$maxV = $vals[4]

$cumRows = ""
for ($c=0..4) {
  $cumRows = $cumRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $vals[$c] . '</td><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $f[$c] . '</td></tr>'
}

// A frequency table IS an ordered list, just written compactly: the same i = (k/100)(n+1)
// method applies, walking the cumulative counts to see which value a position lands in.
$ks = array(25, 50, 75)
$res = array(0, 0, 0)
$posLo = array(0, 0, 0)
$posHi = array(0, 0, 0)
for ($t=0..2) {
  $k = $ks[$t]
  $prod = $k * ($n + 1)
  $rem = $prod % 100
  $lo = ($prod - $rem) / 100
  $hi = $lo
  if ($rem > 0) { $hi = $lo + 1 }
  $posLo[$t] = $lo
  $posHi[$t] = $hi
  $cum = 0
  $vLo = 0
  $vHi = 0
  for ($c=0..4) {
    $cum = $cum + $f[$c]
    if ($vLo == 0 && $cum >= $lo) { $vLo = $vals[$c] }
    if ($vHi == 0 && $cum >= $hi) { $vHi = $vals[$c] }
  }
  $res[$t] = ($vLo + $vHi) / 2
}

$q1 = $res[0]
$med = $res[1]
$q3 = $res[2]

$nPlus = $n + 1
$i1 = 25 * $nPlus / 100
$i2 = 50 * $nPlus / 100
$i3 = 75 * $nPlus / 100

$answer[0] = $minV
$reltolerance[0] = 0.01
$abstolerance[0] = 0.05

$answer[1] = $q1
$reltolerance[1] = 0.01
$abstolerance[1] = 0.05

$answer[2] = $med
$reltolerance[2] = 0.01
$abstolerance[2] = 0.05

$answer[3] = $q3
$reltolerance[3] = 0.01
$abstolerance[3] = 0.05

// Box-plot geometry, adapted from read-a-box-plot.php: the number line runs from x = 55 to
// x = 495, but here the data only span 0..4, so ticks land every one unit instead of every ten.
$span = 495 - 55
$xMin = round(55 + $minV * $span / $maxV, 2)
$xQ1 = round(55 + $q1 * $span / $maxV, 2)
$xMed = round(55 + $med * $span / $maxV, 2)
$xQ3 = round(55 + $q3 * $span / $maxV, 2)
$xMax = round(55 + $maxV * $span / $maxV, 2)
$boxW = round($xQ3 - $xQ1, 2)

$ticks = ""
for ($g=0..4) {
  $tx = round(55 + $g * $span / $maxV, 2)
  $ticks = $ticks . '<line x1="' . $tx . '" y1="110" x2="' . $tx . '" y2="116" stroke="#374151" stroke-width="1"/>'
  $ticks = $ticks . '<line x1="' . $tx . '" y1="26" x2="' . $tx . '" y2="110" stroke="#eef2f7" stroke-width="1"/>'
  $ticks = $ticks . '<text x="' . $tx . '" y="132" font-size="12" fill="#374151" text-anchor="middle">' . $g . '</text>'
}

$svg = '<svg viewBox="0 0 520 160" width="100%" style="max-width:520px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $ticks
$svg = $svg . '<line x1="' . $xMin . '" y1="68" x2="' . $xQ1 . '" y2="68" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xQ3 . '" y1="68" x2="' . $xMax . '" y2="68" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMin . '" y1="52" x2="' . $xMin . '" y2="84" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMax . '" y1="52" x2="' . $xMax . '" y2="84" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<rect x="' . $xQ1 . '" y="42" width="' . $boxW . '" height="52" fill="#bfdbfe" stroke="#1e40af" stroke-width="2"/>'
$svg = $svg . '<line x1="' . $xMed . '" y1="42" x2="' . $xMed . '" y2="94" stroke="#1e3a8a" stroke-width="3"/>'
$svg = $svg . '<line x1="55" y1="110" x2="495" y2="110" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<text x="275" y="152" font-size="13" fill="#374151" text-anchor="middle">' . $valueLabel . '</text>'
$svg = $svg . '</svg>'

$solutionguide = '
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
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">A frequency table is an ordered list, written short.</span> The first ' . $f[0] . ' positions all hold the value ' . $vals[0] . ', the next ' . $f[1] . ' hold ' . $vals[1] . ', and so on up to ' . $vals[4] . '. Nothing about the method changes: `i = (k/100)(n+1)` still gives a position, and the only extra work is counting down the frequency column to see which value that position falls in. Do not treat the five rows as five data values; there are `n = ' . $n . '`.</p>
      <p><span class="term-label">Part (a): the minimum.</span> The smallest value listed with a nonzero frequency is <b>' . $minV . '</b> ' . $unitWord . '.</p>
      <p><span class="term-label">Part (b): the first quartile, `Q_1`, is the 25th percentile.</span> `i = (25/100)(' . $nPlus . ') = ' . $i1 . '`. Counting down the frequencies to position ' . $posLo[0] . ' gives <b>' . $q1 . '</b>.</p>
      <p><span class="term-label">Part (c): the median is the 50th percentile.</span> `i = (50/100)(' . $nPlus . ') = ' . $i2 . '`, landing at position ' . $posLo[1] . ', which gives <b>' . $med . '</b>.</p>
      <p><span class="term-label">Part (d): the third quartile, `Q_3`, is the 75th percentile.</span> `i = (75/100)(' . $nPlus . ') = ' . $i3 . '`, giving <b>' . $q3 . '</b>. Where a position falls between two, round down and up and average the two values: with repeated values those two are often the same number, and the average is simply that number.</p>
      <p><span class="term-label">The finished box plot.</span> The five numbers, the minimum, `Q_1`, the median, `Q_3`, and the stated maximum, are exactly what a box plot draws: a box from `Q_1` to `Q_3` split by a line at the median, with whiskers reaching out to the minimum and the maximum.</p>
      ' . $svg . '
      <p><b>Answer:</b> minimum = ' . $minV . ' &nbsp;&nbsp; `Q_1` = ' . $q1 . ' &nbsp;&nbsp; median = ' . $med . ' &nbsp;&nbsp; `Q_3` = ' . $q3 . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro All <b>$n</b> replies are in the table below. The largest value reported was <b>$maxV</b> $unitWord.</p>
    <table style="border-collapse:collapse; margin:0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:7px 20px;">$valueLabel</th>
          <th style="border:1px solid #d1d5db; padding:7px 20px;">Frequency</th>
        </tr>
      </thead>
      <tbody>
        $cumRows
      </tbody>
    </table>
    <p style="margin:12px 0 0 0; font-size:15px; color:#444;">Suppose you were asked to construct a box plot of this data. Find the five numbers it needs. Use `i = (k/100)(n+1)` to locate each percentile: if `i` is a whole number, take the value at that position; otherwise round down and up and average the two values there.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Minimum = $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> First quartile, `Q_1` = $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Median = $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Third quartile, `Q_3` = $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
