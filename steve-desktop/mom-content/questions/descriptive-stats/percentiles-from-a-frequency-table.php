// === NAME - DESCRIPTION: Percentiles Other Than the Quartiles - Locate the 10th and 70th percentiles in data given as a frequency table, then read what the 70th percentile says about the group ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "Car salespeople at a dealership group were asked how many cars they generally sell in one week."
  $valueLabel = "Cars sold in a week"
  $unitWord = "salespeople"
  $v0 = 3
}
else {
  $intro = "Households in one street were asked how many bicycles they own."
  $valueLabel = "Bicycles owned"
  $unitWord = "households"
  $v0 = 1
}

$vals = array($v0, $v0 + 1, $v0 + 2, $v0 + 3, $v0 + 4)
$f = array(0, 0, 0, 0, 0)
$n = 0
for ($c=0..4) {
  $f[$c] = rand(9, 19)
  $n = $n + $f[$c]
}

$tableRows = ""
for ($c=0..4) {
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $vals[$c] . '</td><td style="border:1px solid #d1d5db; padding:6px 20px; text-align:center;">' . $f[$c] . '</td></tr>'
}

// Same machinery as the quartiles, at percentiles that are not quartiles -- the point being that
// 25, 50 and 75 are not special cases with their own rule, they are just three values of k.
$ks = array(10, 70)
$res = array(0, 0)
$posLo = array(0, 0)
for ($t=0..1) {
  $k = $ks[$t]
  $prod = $k * ($n + 1)
  $rem = $prod % 100
  $lo = ($prod - $rem) / 100
  $hi = $lo
  if ($rem > 0) { $hi = $lo + 1 }
  $posLo[$t] = $lo
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

$p10 = $res[0]
$p70 = $res[1]
$nPlus = $n + 1
$i10 = 10 * $nPlus / 100
$i70 = 70 * $nPlus / 100

$answer[0] = $p10
$reltolerance[0] = 0.01
$abstolerance[0] = 0.05

$answer[1] = $p70
$reltolerance[1] = 0.01
$abstolerance[1] = 0.05

$questions[2] = array(
  "About 70% of the " . $unitWord . " reported " . $p70 . " or fewer, and about 30% reported more.",
  "About 70% of the " . $unitWord . " reported " . $p70 . " or more, and about 30% reported fewer.",
  "Exactly 70 of the " . $unitWord . " reported " . $p70 . ".",
  "The typical figure is 70% of " . $p70 . "."
)
$answer[2] = 0

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
      <p><span class="term-label">Quartiles are not a separate rule.</span> `Q_1`, the median and `Q_3` are just `k = 25`, `50` and `75`. Any other `k` works exactly the same way: `i = (k/100)(n+1)` with `n = ' . $n . '`, so `n + 1 = ' . $nPlus . '`.</p>
      <p><span class="term-label">Part (a) &mdash; the 10th percentile.</span> `i = (10/100)(' . $nPlus . ') = ' . $i10 . '`. Counting down the frequency column to position ' . $posLo[0] . ' gives <b>' . $p10 . '</b>. Low percentiles land inside the first row or two, because those rows hold most of the early positions.</p>
      <p><span class="term-label">Part (b) &mdash; the 70th percentile.</span> `i = (70/100)(' . $nPlus . ') = ' . $i70 . '`, landing at position ' . $posLo[1] . ', which gives <b>' . $p70 . '</b>.</p>
      <p><span class="term-label">Part (c) &mdash; saying it plainly.</span> The 70th percentile has about 70% of the data at or below it. So about 70% of the ' . $unitWord . ' reported ' . $p70 . ' or fewer and about 30% reported more. It is not a count of 70, and it is not 70% of the value &mdash; both of those readings survive a glance and fall apart on a second one.</p>
      <p><b>Answer:</b> (a) ' . $p10 . ' &nbsp;&nbsp; (b) ' . $p70 . '</p>
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
        $tableRows
      </tbody>
    </table>
    <p style="margin:12px 0 0 0; font-size:15px; color:#444;">Use `i = (k/100)(n+1)`. If `i` is a whole number, take the value at that position; otherwise round down and up and average the two values there.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>10th percentile</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>70th percentile</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What does the 70th percentile tell you about this group? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
