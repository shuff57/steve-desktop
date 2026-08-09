// === NAME - DESCRIPTION: Quartiles from a Frequency Table - Find the first quartile, the median and the third quartile of data given as a frequency table rather than a list, then compute the interquartile range from them ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "Car salespeople at a dealership group were asked how many cars they generally sell in one week."
  $valueLabel = "Cars sold in a week"
  $unitWord = "salespeople"
  $v0 = 3
}
else {
  $intro = "Members of a walking group were asked how many days a week they usually walk."
  $valueLabel = "Days walked per week"
  $unitWord = "members"
  $v0 = 2
}

$vals = array($v0, $v0 + 1, $v0 + 2, $v0 + 3, $v0 + 4)
$f = array(0, 0, 0, 0, 0)
$n = 0
for ($c=0..4) {
  $f[$c] = rand(8, 20)
  $n = $n + $f[$c]
}

$cumRows = ""
$run = 0
for ($c=0..4) {
  $run = $run + $f[$c]
  $cumRows = $cumRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $vals[$c] . '</td><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $f[$c] . '</td></tr>'
}

// A frequency table IS an ordered list, just written compactly: the first f[0] positions all hold
// the smallest value, and so on. So the same i = (k/100)(n+1) applies -- the only new work is
// walking the cumulative counts to see which value a position lands in.
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
$iqr = $q3 - $q1

$nPlus = $n + 1
$i1 = 25 * $nPlus / 100
$i2 = 50 * $nPlus / 100
$i3 = 75 * $nPlus / 100

$answer[0] = $q1
$reltolerance[0] = 0.01
$abstolerance[0] = 0.05

$answer[1] = $med
$reltolerance[1] = 0.01
$abstolerance[1] = 0.05

$answer[2] = $q3
$reltolerance[2] = 0.01
$abstolerance[2] = 0.05

$answer[3] = $iqr
$reltolerance[3] = 0.01
$abstolerance[3] = 0.05

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
      <p><span class="term-label">A frequency table is an ordered list, written short.</span> The first ' . $f[0] . ' positions all hold the value ' . $vals[0] . ', the next ' . $f[1] . ' hold ' . $vals[1] . ', and so on. Nothing about the method changes &mdash; `i = (k/100)(n+1)` still gives a position, and the only extra work is counting down the frequency column to see which value that position falls in. Do not treat the five rows as five data values; there are `n = ' . $n . '`.</p>
      <p><span class="term-label">Part (a) &mdash; the first quartile, `Q_1`, is the 25th percentile.</span> `i = (25/100)(' . $nPlus . ') = ' . $i1 . '`. Counting down the frequencies to position ' . $posLo[0] . ' gives <b>' . $q1 . '</b>.</p>
      <p><span class="term-label">Part (b) &mdash; the median is the 50th percentile.</span> `i = (50/100)(' . $nPlus . ') = ' . $i2 . '`, landing at position ' . $posLo[1] . ', which gives <b>' . $med . '</b>.</p>
      <p><span class="term-label">Part (c) &mdash; the third quartile, `Q_3`, is the 75th percentile.</span> `i = (75/100)(' . $nPlus . ') = ' . $i3 . '`, giving <b>' . $q3 . '</b>. Where a position falls between two, round down and up and average the two values &mdash; with repeated values those two are often the same number, and the average is simply that number.</p>
      <p><span class="term-label">Part (d) &mdash; the interquartile range.</span> `"IQR" = Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ` <b>' . $iqr . '</b>. It is the width of the middle half of the data, and unlike the full range it ignores the extremes entirely, which is what makes it the spread to quote when a data set has outliers.</p>
      <p><b>Answer:</b> `Q_1` = ' . $q1 . ' &nbsp;&nbsp; median = ' . $med . ' &nbsp;&nbsp; `Q_3` = ' . $q3 . ' &nbsp;&nbsp; `"IQR"` = ' . $iqr . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro All <b>$n</b> replies are in the table below.</p>
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
    <p style="margin:12px 0 0 0; font-size:15px; color:#444;">Use `i = (k/100)(n+1)` to locate each percentile. If `i` is a whole number, take the value at that position; otherwise round down and up and average the two values there.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> First quartile, `Q_1` = $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Second quartile, the median = $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Third quartile, `Q_3` = $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Interquartile range, `"IQR"` = $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
