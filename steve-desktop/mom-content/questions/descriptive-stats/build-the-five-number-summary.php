// === NAME - DESCRIPTION: Build the Five-Number Summary from Raw Data - From an unsorted list of eleven measurements, enter the minimum, first quartile, median, third quartile and maximum, then give the interquartile range and say what the box of a box plot spans ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// 2.4 had nine box-plot questions and not one asked the student to PRODUCE a box plot -- every one
// handed them a finished plot to read. This is the build-it version: the five numbers ARE the box
// plot, so entering them is constructing it. MyOpenMath's draw type has no box-plot format, so
// fill-in is the only route.
//
// n = 11 on purpose. With eleven values the two quartile conventions AGREE: splitting at the median
// puts Q1 at the 3rd value and Q3 at the 9th, and the (n+1) position rule gives 0.25 x 12 = 3 and
// 0.75 x 12 = 9. A student using either method, by hand or on a calculator, gets the same answer.
// An n where the methods disagree marks a correct student wrong.
$anstypes = array("number", "number", "number", "number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A trainer recorded how many push-ups each of eleven athletes completed in one minute."
  $unit = "push-ups"
}
else {
  $intro = "A shop recorded how many minutes each of eleven customers waited to be served."
  $unit = "minutes"
}

// Eleven ascending values built from a start plus gaps, so they are distinct and already sorted for
// the key. Gaps of at least 1 keep every value different, which matters: a repeated value at a
// quartile position makes "which one is Q1" a needless argument.
$v = array(0,0,0,0,0,0,0,0,0,0,0)
$d = 10 + rand(0, 8)
for ($k=0..10) {
  $v[$k] = $d
  $d = $d + rand(1, 4)
}

$minV = $v[0]
$q1 = $v[2]
$med = $v[5]
$q3 = $v[8]
$maxV = $v[10]
$iqr = $q3 - $q1

// Presented out of order: sorting the list is the first real step of the work, and handing it over
// sorted would remove it.
$off = rand(3, 7)
$shown = ""
$first = 1
for ($k=0..10) {
  $idx = ($k * $off) % 11
  $val = $v[$idx]
  if ($first == 1) { $shown = "" . $val }
  if ($first == 0) { $shown = $shown . ", " . $val }
  $first = 0
}

$answer[0] = $minV
$answer[1] = $q1
$answer[2] = $med
$answer[3] = $q3
$answer[4] = $maxV
$answer[5] = $iqr
for ($k=0..5) {
  $answerformat[$k] = "integer"
}
$answerboxsize = 5

$questions[6] = array(
  "From Q1 to Q3, so it holds the middle half of the measurements.",
  "From the smallest value to the largest, so it holds every measurement.",
  "From the median to the largest value, so it holds the top half.",
  "From Q1 to Q3, so it holds the middle half of the RANGE of values."
)
$answer[6] = 0

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
      <p><span class="term-label">Step 1 &mdash; sort the list.</span> Nothing can be found until the values are in order. Sorted, they are:</p>
      <span class="srt">' . $v[0] . ', ' . $v[1] . ', ' . $v[2] . ', ' . $v[3] . ', ' . $v[4] . ', <b>' . $v[5] . '</b>, ' . $v[6] . ', ' . $v[7] . ', ' . $v[8] . ', ' . $v[9] . ', ' . $v[10] . '</span>
      <p><span class="term-label">Step 2 &mdash; the median.</span> With 11 values the median is the 6th one, because five sit below it and five above: <b>' . $med . '</b> ' . $unit . '.</p>
      <p><span class="term-label">Step 3 &mdash; the quartiles.</span> Q1 is the median of the five values BELOW the median, which is the 3rd value, <b>' . $q1 . '</b>. Q3 is the median of the five values above it, the 9th value, <b>' . $q3 . '</b>. The median itself is not counted into either half.</p>
      <p><span class="term-label">Step 4 &mdash; the ends and the spread.</span> The minimum is <b>' . $minV . '</b> and the maximum is <b>' . $maxV . '</b>. The interquartile range is `Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ` <b>' . $iqr . '</b>.</p>
      <p><span class="term-label">What you have just built.</span> Those five numbers ARE the box plot: the box runs from ' . $q1 . ' to ' . $q3 . ' with a line at ' . $med . ', and the whiskers reach out to ' . $minV . ' and ' . $maxV . '. Nothing else is needed to draw it.</p>
      <p><span class="term-label">The usual slip.</span> Reading the quartiles off the unsorted list, or including the median in both halves when finding Q1 and Q3. Both give a box that is the wrong width.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$intro The eleven measurements are listed below, in the order they were recorded.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$shown</p>
    <p style="margin:12px 0 0 0;"><b>Build the five-number summary.</b> These five values are everything a box plot is drawn from.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <table style="border-collapse:collapse; margin:0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 14px; text-align:left;">Value</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Your answer</th>
        <th style="border:1px solid #d1d5db; padding:6px 10px; font-size:13px; color:#6b7280;">Part</th>
      </tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 14px;">Minimum</td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[0]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">a.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 14px;">First quartile, `Q_1`</td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[1]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">b.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 14px;">Median</td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[2]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">c.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 14px;">Third quartile, `Q_3`</td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[3]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">d.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 14px;">Maximum</td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[4]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">e.</td></tr>
      <tr style="background:#f8fafc;"><td style="border:1px solid #d1d5db; padding:8px 14px;"><b>Interquartile range</b></td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[5]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">f.</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">g.</span> On the box plot you could now draw, what does the <b>box</b> span? $answerbox[6]
  </div>
</div>

// === ANSWER ===

$solutionguide
