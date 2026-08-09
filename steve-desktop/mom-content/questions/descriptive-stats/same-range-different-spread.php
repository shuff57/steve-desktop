// === NAME - DESCRIPTION: Same Range, Different Spread - Two data sets share a minimum and a maximum but not their standard deviations; compute the range of each, decide which is more spread out, and say what the range misses ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The range is the first measure of spread students meet and the one they over-trust, because it is
// the easiest to compute. This question makes the two ranges IDENTICAL by construction and the two
// distributions obviously different, so the range's blind spot is something the student demonstrates
// rather than reads about: it uses two values out of the whole data set and ignores every other one.
//
// Set A is tightly clustered with the two extremes far out; set B is evenly spread across the same
// interval. Both sets are given already sorted, share $lo and $hi exactly, and have the same n, so no
// difference other than the interior arrangement can explain the result.
$anstypes = array("number", "number", "choices", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "Two machines fill bottles. A sample of nine bottles from each was weighed, in grams."
  $unitWord = "grams"
  $nameA = "Machine A"
  $nameB = "Machine B"
  $consequence = "Machine A fills far more consistently, which is what a bottling line actually cares about"
}
else {
  $intro = "Two bus routes were timed on nine mornings each, in minutes."
  $unitWord = "minutes"
  $nameA = "Route A"
  $nameB = "Route B"
  $consequence = "Route A is far more predictable, which is what a commuter actually cares about"
}

$lo = 10 * rand(4, 7)
$gap = 8 * rand(3, 5)
$hi = $lo + $gap
$mid = ($lo + $hi) / 2
$step = $gap / 8

// Set A: seven values bunched at the middle, plus the two extremes.
$tight = $step
$a1 = $mid - $tight
$a2 = $mid
$a3 = $mid
$a4 = $mid
$a5 = $mid
$a6 = $mid
$a7 = $mid + $tight

// Set B: evenly spaced right across the same interval.
$b1 = $lo + $step
$b2 = $lo + 2 * $step
$b3 = $lo + 3 * $step
$b4 = $mid
$b5 = $lo + 5 * $step
$b6 = $lo + 6 * $step
$b7 = $lo + 7 * $step

$rangeA = $hi - $lo
$rangeB = $hi - $lo

$answer[0] = $rangeA
$answer[1] = $rangeB
$answerboxsize = 6

$questions[2] = array(
  $nameB . ". Its values are spread right across the interval, while " . $nameA . " has almost everything bunched at the middle with only the two extremes far out.",
  $nameA . ", because its two extreme values are further from its middle values.",
  "They are equally spread out, because their ranges are identical.",
  "There is no way to compare their spread without being told the standard deviations."
)
$answer[2] = 0

$questions[3] = array(
  "The range uses only two numbers, the largest and the smallest, and ignores every value in between. The standard deviation uses all of them, so it notices how the middle is arranged.",
  "The range is the wrong formula; the largest minus the smallest is actually the interquartile range.",
  "The range only works when the data set has an odd number of values.",
  "Nothing is missing. Two data sets with the same range always have the same spread."
)
$answer[3] = 0

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
      <p><span class="term-label">Parts (a) and (b) &mdash; the two ranges.</span> Range is largest minus smallest. Both sets run from ' . $lo . ' to ' . $hi . ', so both have a range of <b>' . $rangeA . '</b> ' . $unitWord . '. Identical.</p>
      <p><span class="term-label">Part (c) &mdash; but the pictures are not the same.</span> Look at what sits BETWEEN the extremes. In ' . $nameA . ' seven of the nine values sit on or beside ' . $mid . ' &mdash; the set is tightly packed, with two lone values out at the ends. In ' . $nameB . ' the nine values step evenly across the whole interval, so a typical value is genuinely far from the middle. <b>' . $nameB . ' is the more spread out</b>, and its standard deviation would be much the larger of the two.</p>
      <p><span class="term-label">Part (d) &mdash; what the range cannot see.</span> The range is computed from exactly two numbers and throws the rest away. Every value between the extremes could be rearranged, piled on the mean or spread out evenly, and the range would not move at all. The standard deviation asks how far EVERY value sits from the mean, so it registers precisely the difference the range is blind to.</p>
      <p><span class="term-label">Why this matters outside the exercise.</span> ' . $consequence . '. Two summaries can agree completely and still describe different situations &mdash; which is the reason a distribution is reported with a center AND a spread, and why the spread is usually the standard deviation or the IQR rather than the range.</p>
      <p><span class="term-label">The other weakness.</span> Because it depends only on the two most extreme values, the range is the least resistant measure of spread there is: one unusual observation changes it completely.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$intro Both samples are shown already sorted.</p>
    <p style="margin:0 0 6px 0;"><b>$nameA</b></p>
    <p style="margin:0 0 12px 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px;">$lo, $a1, $a2, $a3, $a4, $a5, $a6, $a7, $hi</p>
    <p style="margin:0 0 6px 0;"><b>$nameB</b></p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px;">$lo, $b1, $b2, $b3, $b4, $b5, $b6, $b7, $hi</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>range</b> of $nameA? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>range</b> of $nameB? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which sample is genuinely <b>more spread out</b>? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Two samples with the same range can have very different spread. What does the range miss that the standard deviation catches? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
