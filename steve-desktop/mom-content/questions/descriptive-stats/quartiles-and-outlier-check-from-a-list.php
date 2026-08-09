// === NAME - DESCRIPTION: Quartiles and Outlier Check from a Sorted List - Use the (n+1) position rule to find Q1 and Q3 in a list already given in sorted order, compute the IQR, then apply the 1.5xIQR fences to judge whether a new value is an outlier ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

// n = 23 on purpose. n + 1 = 24, so Q1 sits at position (25/100)(24) = 6 and Q3 at position
// (75/100)(24) = 18 -- both whole numbers, so neither quartile ever needs averaging two values.
// Software does not all use the same quartile convention, and a position that falls between two
// values can give a calculator one answer and the (n+1) formula another. At n = 23 they agree
// exactly, so the method taught here cannot be second-guessed by whatever tool a student checks it
// against.
$n = 23

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A shelter recorded the age, in months, of 23 kittens currently available for adoption."
  $unitWord = "months"
  $thing = "kittens"
  $v0 = rand(1, 4)
  $stepLo = 1
  $stepHi = 4
}
else {
  $intro = "A bakery recorded how many minutes it took to decorate each of 23 custom cake orders."
  $unitWord = "minutes"
  $thing = "orders"
  $v0 = rand(15, 40)
  $stepLo = 2
  $stepHi = 7
}

// Already sorted, ascending, by construction -- each new value is the last plus a bounded random
// step, so the list can never need re-ordering and can never overflow past a reasonable range.
$sorted = array()
$v = $v0
for ($j=0..22) {
  $sorted[$j] = $v
  $v = $v + rand($stepLo, $stepHi)
}

$valueList = ""
for ($j=0..22) {
  if ($j == 0) { $valueList = "" . $sorted[$j] }
  if ($j > 0) { $valueList = $valueList . ", " . $sorted[$j] }
}

$nPlus = $n + 1
$i1 = 25 * $nPlus / 100
$i2 = 75 * $nPlus / 100

$q1 = $sorted[5]
$q3 = $sorted[17]
$iqr = $q3 - $q1

$lf = $q1 - 1.5 * $iqr
$uf = $q3 + 1.5 * $iqr

// The extra observation sits at least half an IQR past whichever boundary applies -- always
// comfortably more than the 3-unit minimum clearance, so no seed can land it ambiguously close to
// a fence. Twelve steps separate Q1 and Q3, so the IQR itself is never small enough to make that a
// close call.
$oc = rand(0, 1)
$gapExtra = rand(2, 10)
$extra = $sorted[11] + rand(-3, 3)
if ($oc == 0) { $extra = $q3 + 2 * $iqr + $gapExtra }

$dVerdict = "is NOT an outlier"
$dExplain = "lies between the fences, so it is <b>not</b> an outlier."
if ($oc == 0) {
  $dVerdict = "IS an outlier"
  $dExplain = "is above the upper fence, so it <b>is</b> an outlier."
}

$answer[0] = $q1
$reltolerance[0] = 0.01
$abstolerance[0] = 0.05

$answer[1] = $q3
$reltolerance[1] = 0.01
$abstolerance[1] = 0.05

$answer[2] = $iqr
$reltolerance[2] = 0.01
$abstolerance[2] = 0.05

$questions[3] = array(
  "Yes &mdash; it is above the upper fence of " . $uf,
  "No &mdash; it lies between the fences (lower fence " . $lf . ", upper fence " . $uf . ")",
  "Yes &mdash; because it is more than 1.5 times the median away from the mean",
  "No &mdash; because it is within one standard deviation of the mean"
)
$answer[3] = 1
if ($oc == 0) { $answer[3] = 0 }

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
      <p><span class="term-label">The list is already sorted.</span> With `n = ' . $n . '` values in order, `i = (k/100)(n+1)` gives the position of each quartile directly &mdash; no sorting step to do first, only counting to a position.</p>
      <p><span class="term-label">Part (a) &mdash; `Q_1` is the 25th percentile.</span> `i = (25/100)(' . $nPlus . ') = ' . $i1 . '`, a whole number, so `Q_1` is simply the ' . $i1 . 'th value in the list: <b>' . $q1 . '</b>.</p>
      <p><span class="term-label">Part (b) &mdash; `Q_3` is the 75th percentile.</span> `i = (75/100)(' . $nPlus . ') = ' . $i2 . '`, also whole, so `Q_3` is the ' . $i2 . 'th value: <b>' . $q3 . '</b>. (Had a position landed between two values instead, the rule is to round down and up and average those two &mdash; not needed here.)</p>
      <p><span class="term-label">Part (c) &mdash; the interquartile range.</span> `"IQR" = Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ` <b>' . $iqr . '</b> ' . $unitWord . '.</p>
      <p><span class="term-label">Part (d) &mdash; the `1.5 xx "IQR"` fences.</span> Lower fence `= Q_1 - 1.5 xx "IQR" = ' . $q1 . ' - ' . (1.5 * $iqr) . ' = ' . $lf . '`. Upper fence `= Q_3 + 1.5 xx "IQR" = ' . $q3 . ' + ' . (1.5 * $iqr) . ' = ' . $uf . '`. The new value of ' . $extra . ' ' . $unitWord . ' ' . $dExplain . '</p>
      <p><b>Answer:</b> `Q_1` = ' . $q1 . ' &nbsp;&nbsp; `Q_3` = ' . $q3 . ' &nbsp;&nbsp; `"IQR"` = ' . $iqr . ' &nbsp;&nbsp; the new value ' . $dVerdict . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$intro The $n readings, in $unitWord, are listed below <b>already sorted from smallest to largest</b>.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
    <p style="margin:12px 0 0 0; font-size:15px; color:#444;">Use `i = (k/100)(n+1)` to locate each quartile. If `i` is a whole number, take the value at that position; otherwise round down and up and average the two values there.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> First quartile, `Q_1` = $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Third quartile, `Q_3` = $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Interquartile range, `"IQR"` = `Q_3 - Q_1` = $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> A new reading of <b>$extra</b> $unitWord comes in. Is it an outlier by the `1.5 xx "IQR"` rule? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
