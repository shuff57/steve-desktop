// === NAME - DESCRIPTION: Mean, Median and Mode from a Frequency Table - Compute the sample mean, median and mode from a frequency table, then identify which measure resists an added extreme value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "Car salespeople at a dealership group were asked how many cars they sold last week."
  $valueLabel = "Cars sold in a week"
  $unitWord = "salespeople"
  $v0 = rand(0, 2)
}
else {
  $intro = "Teenagers in a phone-use survey were asked how many text messages they sent during a one-hour study hall."
  $valueLabel = "Texts sent in an hour"
  $unitWord = "teenagers"
  $v0 = rand(2, 5)
}

$vals = array($v0, $v0 + 1, $v0 + 2, $v0 + 3, $v0 + 4)
$f = array(0, 0, 0, 0, 0)
for ($c=0..4) {
  $f[$c] = rand(4, 9)
}

// Force a unique mode by construction: pick the row, then push its frequency
// clear of every other row's frequency instead of leaving uniqueness to chance.
$modeIdx = rand(0, 4)
$maxOther = 0
for ($c=0..4) {
  if ($c != $modeIdx && $f[$c] > $maxOther) { $maxOther = $f[$c] }
}
$f[$modeIdx] = $maxOther + rand(4, 7)
$mode = $vals[$modeIdx]

$n = 0
$sumVF = 0
for ($c=0..4) {
  $n = $n + $f[$c]
  $sumVF = $sumVF + $vals[$c] * $f[$c]
}
$mean = round($sumVF / $n, 2)

// A frequency table is an ordered list written short, so i = (50/100)(n+1) still
// locates the median: walk the cumulative frequencies to see which value it lands on.
$nPlus = $n + 1
$prod = 50 * $nPlus
$rem = $prod % 100
$posLo = ($prod - $rem) / 100
$posHi = $posLo
if ($rem > 0) { $posHi = $posLo + 1 }
$iMed = $prod / 100

$cumRows = ""
$cum = 0
$vLo = 0
$vHi = 0
for ($c=0..4) {
  $cum = $cum + $f[$c]
  if ($vLo == 0 && $cum >= $posLo) { $vLo = $vals[$c] }
  if ($vHi == 0 && $cum >= $posHi) { $vHi = $vals[$c] }
  $cumRows = $cumRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $vals[$c] . '</td><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $f[$c] . '</td></tr>'
}
$median = ($vLo + $vHi) / 2

$answer[0] = $mean
$reltolerance[0] = 0.01
$abstolerance[0] = 0.01

$answer[1] = $median
$reltolerance[1] = 0.01
$abstolerance[1] = 0.05

$answer[2] = $mode
$reltolerance[2] = 0.01
$abstolerance[2] = 0.05

$questions[3][0] = "The mean, because dividing the sum by a larger sample size cancels out the effect of one very large value."
$questions[3][1] = "The median, because it depends on the position of the middle value in the ordered data, not on how far away the extremes sit."
$questions[3][2] = "The mode, because it is defined as the middle value of the frequency table."
$questions[3][3] = "All three, because adding one more value to the data set changes the sample size used by every measure."
$answer[3] = 1

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
      <p><span class="term-label">A frequency table is an ordered list, written short.</span> The first ' . $f[0] . ' positions all hold the value ' . $vals[0] . ', the next ' . $f[1] . ' hold ' . $vals[1] . ', and so on, for `n = ' . $n . '` ' . $unitWord . ' in all. Do not average the five table rows: each value must be weighted by how many ' . $unitWord . ' actually gave that answer.</p>
      <p><span class="term-label">Part (a): the mean.</span> Multiply each value by its frequency, add the products, and divide by `n`: `bar x = (sum x*f)/n = ' . $sumVF . '/' . $n . ' = ` <b>' . $mean . '</b> (rounded to two decimal places). This is not the average of the five values ' . $vals[0] . ' through ' . $vals[4] . ', and it is not the average of the frequencies: both of those skip the weighting entirely.</p>
      <p><span class="term-label">Part (b): the median.</span> `i = (50/100)(n+1) = (50/100)(' . $nPlus . ') = ' . $iMed . '`. Counting down the frequency column to position ' . $posLo . ' (and position ' . $posHi . ' when `i` is not a whole number) gives <b>' . $median . '</b>.</p>
      <p><span class="term-label">Part (c): the mode.</span> The mode is the value with the highest frequency, not the value in the middle row. That is ' . $mode . ', reported ' . $f[$modeIdx] . ' times: more than any other value in the table.</p>
      <p><span class="term-label">Part (d): resistance to an added extreme value.</span> Suppose one more reply came in, far larger than anything already in the table. The mean would be pulled toward it, because every value in the sum counts toward the total. The mode would not change unless the new value happened to repeat enough to out-count ' . $mode . '. But the median is decided purely by <i>where</i> the middle position falls once the data is sorted: one extra value shifts that position by at most one spot, and the size of the new value never enters the calculation at all. That is why the median is the measure that resists an extreme value.</p>
      <p><b>Answer:</b> `bar x` = ' . $mean . ' &nbsp;&nbsp; median = ' . $median . ' &nbsp;&nbsp; mode = ' . $mode . ' &nbsp;&nbsp; resistant measure = the median</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro All <b>$n</b> $unitWord are in the table below.</p>
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
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the sample mean, `bar x`, to two decimal places. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the median. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the mode. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Suppose one more reply came in, far larger than any value already in the table. Which of the three measures above would be unaffected, and why? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
