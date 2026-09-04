// === NAME - DESCRIPTION: Which Measures of Spread Survive an Outlier - Find the range and the interquartile range, add one extreme value, recompute both, and identify which measures of spread are resistant ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// 2.5 shows an outlier moving the mean but not the median. Spread has the same split and it is rarely
// taught: the range and the standard deviation both use the extreme values and are wrecked by one, the
// IQR is positional and is not. Here the student computes the range and the IQR before and after, and
// the contrast is in their own four answers.
//
// The IQR is UNCHANGED by construction. Adding a 12th value above the maximum shifts the quartile
// positions of an 11-value set by exactly the right amount that Q1 and Q3 land on the same two values
// as before: checked against the split-at-the-median convention the course uses.
$anstypes = array("number", "number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A cafe recorded how many minutes each of eleven customers waited to be served."
  $unitWord = "minutes"
}
else {
  $intro = "A lab recorded how many seconds each of eleven trials took to complete."
  $unitWord = "seconds"
}

// Eleven ascending values. With n = 11 the median is the 6th, Q1 the 3rd, Q3 the 9th.
$g0 = rand(10, 14)
$g1 = $g0 + rand(1, 3)
$g2 = $g1 + rand(1, 3)
$g3 = $g2 + rand(1, 3)
$g4 = $g3 + rand(1, 3)
$g5 = $g4 + rand(1, 3)
$g6 = $g5 + rand(1, 3)
$g7 = $g6 + rand(1, 3)
$g8 = $g7 + rand(1, 3)
$g9 = $g8 + rand(1, 3)
$g10 = $g9 + rand(1, 3)

$q1a = $g2
$q3a = $g8
$iqrA = $q3a - $q1a
$rangeA = $g10 - $g0

// The new value sits far above everything, so it becomes the new maximum.
$outlier = $g10 + 10 * rand(6, 12)

// With 12 values the lower half is the first 6 and the upper half the last 6, so Q1 is the average
// of the 3rd and 4th and Q3 the average of the 9th and 10th.
$q1b = ($g2 + $g3) / 2
$q3b = ($g8 + $g9) / 2
$iqrB = $q3b - $q1b
$rangeB = $outlier - $g0

$answer[0] = $rangeA
$answer[1] = $iqrA
$answer[2] = $rangeB
$answer[3] = $iqrB
$abstolerance[3] = 0.005
$answerboxsize = 6

$questions[4] = array(
  "The range and the standard deviation are both wrecked by it, because both are built from how far the values reach. The interquartile range barely moves, because it depends on quartile POSITIONS rather than on the extremes.",
  "All three measures of spread are affected equally, since all three describe the same thing.",
  "Only the interquartile range is affected, because it is computed from the middle of the data.",
  "None of them is affected; measures of spread ignore outliers by definition."
)
$answer[4] = 0

$rangeJump = $rangeB - $rangeA
$iqrJump = $iqrB - $iqrA

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
  .srt { font-family:ui-monospace,Menlo,Consolas,monospace; background:#f8fafc; border:1px solid #e5e7eb; border-radius:6px; padding:8px 10px; display:block; margin:6px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Before: eleven values.</span></p>
      <span class="srt">' . $g0 . ', ' . $g1 . ', <b>' . $g2 . '</b>, ' . $g3 . ', ' . $g4 . ', ' . $g5 . ', ' . $g6 . ', ' . $g7 . ', <b>' . $g8 . '</b>, ' . $g9 . ', ' . $g10 . '</span>
      <p>Range = `' . $g10 . ' - ' . $g0 . ' = ` <b>' . $rangeA . '</b>. With 11 values the median is the 6th, so `Q_1` is the 3rd &#40;' . $q1a . '&#41; and `Q_3` the 9th &#40;' . $q3a . '&#41;, giving `"IQR" = ' . $q3a . ' - ' . $q1a . ' = ` <b>' . $iqrA . '</b>.</p>
      <p><span class="term-label">After: one value added, far out.</span> The set now has 12 values, ending ' . $g10 . ', ' . $outlier . '.</p>
      <p>Range = `' . $outlier . ' - ' . $g0 . ' = ` <b>' . $rangeB . '</b>: up by ' . $rangeJump . '. With 12 values the lower half is the first six and the upper half the last six, so `Q_1` is the average of the 3rd and 4th and `Q_3` the average of the 9th and 10th: `"IQR" = ' . $q3b . ' - ' . $q1b . ' = ` <b>' . $iqrB . '</b>, a change of only ' . $iqrJump . '.</p>
      <p><span class="term-label">What separates them.</span> The range is LITERALLY built from the two most extreme values, so one new extreme rewrites it completely. The IQR is built from quartile positions, and adding one value at the top end shifts those positions by half a place: it does not care how far away the new value is. Put the outlier ten times further out and the range would grow tenfold while the IQR would not move at all.</p>
      <p><span class="term-label">Where the standard deviation sits.</span> With the range, not with the IQR. It squares each value&rsquo;s distance from the mean, so a distant value contributes an enormous term and the mean it is measured from has moved as well. That is why a skewed data set is normally reported as median with IQR, and a symmetric one as mean with standard deviation: the two summaries in each pair are resistant, or not, together.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro The eleven values, already in order:</p>
    <p style="margin:12px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$g0, $g1, $g2, $g3, $g4, $g5, $g6, $g7, $g8, $g9, $g10</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>range</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>interquartile range</b>? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; border-left:4px solid #b91c1c;">
    <p style="margin:0 0 12px 0;">A twelfth measurement of <b>$outlier $unitWord</b> is now added. Every original value stays.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$g0, $g1, $g2, $g3, $g4, $g5, $g6, $g7, $g8, $g9, $g10, $outlier</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the <b>new range</b>? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What is the <b>new interquartile range</b>? $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Which measures of spread does one extreme value damage, and which survive it? $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
