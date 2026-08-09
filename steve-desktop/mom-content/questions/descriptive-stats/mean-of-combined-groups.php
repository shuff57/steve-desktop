// === NAME - DESCRIPTION: Mean of Two Combined Groups - Recover each group's total from its mean and size, combine them to get the overall mean, and see why averaging the two means is wrong when the groups differ in size ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The target here is one specific, very common error: averaging two averages. It only shows up when
// the two groups differ in SIZE, so the sizes are drawn far apart on purpose and the question makes
// the student produce BOTH numbers -- the correct combined mean and the naive average of the means --
// so the gap is something they compute rather than something they are told about.
//
// Group totals are forced to come out whole: each mean is an integer and each size is an integer, so
// n * mean is exact, and the combined mean is reported to two decimals with a tolerance.
$anstypes = array("number", "number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A college reports the average score on the same placement test for its two campuses."
  $g0 = "the main campus"
  $g1 = "the satellite campus"
  $unitWord = "points"
  $thing = "students"
}
else {
  $intro = "A delivery firm reports the average parcel weight handled by its two depots."
  $g0 = "the city depot"
  $g1 = "the rural depot"
  $unitWord = "kilograms"
  $thing = "parcels"
}

// Sizes deliberately far apart -- with n0 at least three times n1 the combined mean is pulled
// clearly toward the larger group and can never round to the naive average.
$n0 = 10 * rand(12, 20)
$n1 = 10 * rand(2, 4)

$m0 = rand(60, 75)
$m1 = $m0 + rand(8, 20)

$t0 = $n0 * $m0
$t1 = $n1 * $m1
$nAll = $n0 + $n1
$tAll = $t0 + $t1

$combined = round($tAll / $nAll, 2)
$naive = round(($m0 + $m1) / 2, 2)
$gap = round($naive - $combined, 2)

$answer[0] = $t0
$answer[1] = $t1
$answer[2] = $combined
$abstolerance[2] = 0.005
$answer[3] = $naive
$abstolerance[3] = 0.005
$answerboxsize = 7

$questions[4] = array(
  "The two groups are different sizes, so the larger group must count for more. Averaging the two means would give a small group the same say as a large one.",
  "Averaging the two means is fine here; the two answers differ only because of rounding.",
  "The combined mean is always the smaller of the two group means, whatever the sizes are.",
  "Averaging the two means would be correct if the two means were closer together."
)
$answer[4] = 0

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
      <p><span class="term-label">Step 1 &mdash; a mean hides a total.</span> A mean is the total divided by the count, so the total is the mean times the count. That is the move the whole question rests on: you cannot combine averages directly, but you can always combine totals.</p>
      <p>' . $g0 . ': `' . $n0 . ' xx ' . $m0 . ' = ' . $t0 . '` ' . $unitWord . ' in all.<br>
         ' . $g1 . ': `' . $n1 . ' xx ' . $m1 . ' = ' . $t1 . '` ' . $unitWord . ' in all.</p>
      <p><span class="term-label">Step 2 &mdash; combine, then divide once.</span> Together there are `' . $t0 . ' + ' . $t1 . ' = ' . $tAll . '` ' . $unitWord . ' spread over `' . $n0 . ' + ' . $n1 . ' = ' . $nAll . '` ' . $thing . ':</p>
      <p style="text-align:center;">`bar x = ' . $tAll . ' -: ' . $nAll . ' = ` <b>' . $combined . '</b> ' . $unitWord . '</p>
      <p><span class="term-label">Step 3 &mdash; the shortcut that fails.</span> Averaging the two means gives `(' . $m0 . ' + ' . $m1 . ')/2 = ' . $naive . '`, which is off by ' . $gap . '. It treats ' . $g1 . ' &#40;' . $n1 . ' ' . $thing . '&#41; as though it carried the same weight as ' . $g0 . ' &#40;' . $n0 . ' ' . $thing . '&#41;.</p>
      <p><span class="term-label">Where the answer has to land.</span> The combined mean always sits between the two group means, and closer to the one with more ' . $thing . '. Here ' . $g0 . ' is much bigger, so the answer sits near ' . $m0 . ' rather than halfway. If your answer is outside the two means, a total was mis-formed.</p>
      <p><span class="term-label">When the shortcut happens to work.</span> Only when the two groups are the same size. That is a special case, not the rule &mdash; and it is why the sizes are the first thing to look at.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    <table style="border-collapse:collapse; margin:12px 0 0 0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 16px; text-align:left;">Group</th>
        <th style="border:1px solid #d1d5db; padding:6px 16px;">How many $thing</th>
        <th style="border:1px solid #d1d5db; padding:6px 16px;">Mean</th>
      </tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 16px;">$g0</td><td style="border:1px solid #d1d5db; padding:7px 16px; text-align:center;">$n0</td><td style="border:1px solid #d1d5db; padding:7px 16px; text-align:center;">$m0 $unitWord</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 16px;">$g1</td><td style="border:1px solid #d1d5db; padding:7px 16px; text-align:center;">$n1</td><td style="border:1px solid #d1d5db; padding:7px 16px; text-align:center;">$m1 $unitWord</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the TOTAL for $g0, in $unitWord? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the TOTAL for $g1, in $unitWord? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Using those totals, what is the mean over all $nAll $thing? Round to two decimal places. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Now do it the tempting way: average the two group means, $m0 and $m1. What do you get? Round to two decimal places. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Parts (c) and (d) disagree. Which is right, and why? $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
