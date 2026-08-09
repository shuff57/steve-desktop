// === NAME - DESCRIPTION: Find a Percentile from an Ordered List - Locate two percentiles in a sorted data set using i = (k/100)(n+1), once where the position lands on a whole number and once where it falls between two values, then say what the percentile means ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ci = rand(0, 1)
$contexts = array(
  "the ages, in years, of the winners of a national film award",
  "the finishing times, in minutes, of the runners in a charity 10k"
)
$unitWords = array("years", "minutes")
$context = $contexts[$ci]
$unitWord = $unitWords[$ci]

// 24 values, so n + 1 = 25 and the position i = k(25)/100 = k/4. A k divisible by 4 lands on a
// whole position; any other k falls between two, which is the case students skip. Fixing n this
// way is what lets both cases be guaranteed rather than hoped for.
$n = 24
$nPlus = 25

$start = rand(18, 26)
$vals = array()
$v = $start
$valueList = ""
for ($j=0..23) {
  $vals[$j] = $v
  if ($j == 0) { $valueList = "" . $v }
  if ($j > 0) { $valueList = $valueList . "; " . $v }
  $v = $v + rand(1, 4)
}

// Part (a): k divisible by 4, so i is a whole number and the answer is a single listed value.
$m1 = rand(3, 20)
$k1 = 4 * $m1
$i1 = $k1 / 4
$ans1 = $vals[$i1 - 1]

// Part (b): k not divisible by 4, so i falls between two positions and the two are averaged.
$k2 = 4 * rand(3, 20) + rand(1, 3)
$r2 = $k2 % 4
$lo2 = ($k2 - $r2) / 4
$hi2 = $lo2 + 1
$loVal = $vals[$lo2 - 1]
$hiVal = $vals[$hi2 - 1]
$ans2 = ($loVal + $hiVal) / 2
$i2 = $k2 / 4

// "$k1 th" substitutes with a space in front of the suffix, and a bare $k1th would be read as a
// variable name. Build the ordinal in the control block instead. Multiples of four reach 32nd,
// 52nd and 72nd, so the suffix cannot be hard-coded to "th".
$suf1 = "th"
$last1 = $k1 % 10
$tens1 = ($k1 - $last1) / 10
if ($tens1 != 1) {
  if ($last1 == 1) { $suf1 = "st" }
  if ($last1 == 2) { $suf1 = "nd" }
  if ($last1 == 3) { $suf1 = "rd" }
}
$k1Label = $k1 . $suf1

$suf2 = "th"
$last2 = $k2 % 10
$tens2 = ($k2 - $last2) / 10
if ($tens2 != 1) {
  if ($last2 == 1) { $suf2 = "st" }
  if ($last2 == 2) { $suf2 = "nd" }
  if ($last2 == 3) { $suf2 = "rd" }
}
$k2Label = $k2 . $suf2

$answer[0] = $ans1
$reltolerance[0] = 0.01
$abstolerance[0] = 0.05

$answer[1] = $ans2
$reltolerance[1] = 0.01
$abstolerance[1] = 0.05

$questions[2] = array(
  "About " . $k1 . "% of the values in the data set are at or below " . $ans1 . " " . $unitWord . ".",
  "About " . $k1 . "% of the values in the data set are at or above " . $ans1 . " " . $unitWord . ".",
  "Exactly " . $k1 . " of the values in the data set are at or below " . $ans1 . " " . $unitWord . ".",
  "The value " . $ans1 . " is " . $k1 . "% of the largest value in the data set."
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
      <p><span class="term-label">The rule.</span> To find a percentile in an ordered list, work out the <i>position</i> first: `i = (k/100)(n+1)`. The data is already ordered and `n = ' . $n . '`, so `n + 1 = ' . $nPlus . '`.</p>
      <p><span class="term-label">Part (a) &mdash; the position is a whole number.</span> `i = (' . $k1 . '/100)(' . $nPlus . ') = ' . $i1 . '`. Because ' . $i1 . ' is a whole number, take the ' . $i1 . 'th value in the ordered list: <b>' . $ans1 . ' ' . $unitWord . '</b>. Count positions from the smallest value, not from the largest.</p>
      <p><span class="term-label">Part (b) &mdash; the position falls between two values.</span> `i = (' . $k2 . '/100)(' . $nPlus . ') = ' . $i2 . '`. That is not a whole number, so round down to ' . $lo2 . ' and up to ' . $hi2 . ', then average those two values. The ' . $lo2 . 'th value is ' . $loVal . ' and the ' . $hi2 . 'th is ' . $hiVal . ', so `P_' . $k2 . ' = (' . $loVal . ' + ' . $hiVal . ')/2 = ' . $ans2 . '` ' . $unitWord . '.</p>
      <p><span class="term-label">Part (c) &mdash; what it means.</span> A percentile is a position in the data, not a score and not a share of the largest value. The ' . $k1Label . ' percentile is the value with about ' . $k1 . '% of the data at or below it.</p>
      <p><b>Answer:</b> (a) ' . $ans1 . ' &nbsp;&nbsp; (b) ' . $ans2 . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">Listed below, in order, are $context. There are $n values.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
    <p style="margin:12px 0 0 0; font-size:15px; color:#444;">Use `i = (k/100)(n+1)` to locate a percentile. If `i` is a whole number, the percentile is the `i`th value. If it is not, round down and up and average those two values.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>$k1Label percentile</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>$k2Label percentile</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What does your answer to part (a) tell you? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
