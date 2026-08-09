// === NAME - DESCRIPTION: Build the Whole Stem-and-Leaf Plot - Given an unsorted list of measurements and an empty plot, the student enters every leaf in every row in order, then says how a repeated measurement appears ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The student builds the ENTIRE plot: sixteen boxes, one per leaf, nothing pre-filled.
//
// The row counts (3, 5, 4, 4) are FIXED rather than randomized, and that is forced by the engine,
// not a preference: $answerbox tokens only expand when they are written literally in the question
// text, so the number of boxes cannot vary with the seed. Verified by probe -- an $answerbox built
// into a string in the control block prints as literal text and no input appears. The stems, the
// digits and the order of the raw list are all still randomized, so no two seeds share a plot.
$anstypes = array("number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "choices")

$ci = rand(0, 1)
$contexts = array(
  "the number of seconds each contestant took to solve a puzzle",
  "the weight, in kilograms, of each crate loaded onto a truck"
)
$units = array("seconds", "kilograms")
$context = $contexts[$ci]
$unit = $units[$ci]

$loStem = rand(2, 5)

$cnt = array(3, 5, 4, 4)
$n = 16
$allLeaf = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)
$stem = array(0, 0, 0, 0)

for ($i=0..3) {
  $stem[$i] = $loStem + $i
  $c = $cnt[$i]
  $cm = $c - 1
  $base = $i * 10
  $d = rand(0, 1)
  for ($j=0..$cm) {
    $slot = $base + $j
    $allLeaf[$slot] = $d
    $g = rand(1, 2)
    // Leave room for every remaining leaf at a gap of one, so a row can never run past 9.
    $room = $d + $g + $cm - $j
    if ($room > 9) { $g = 1 }
    $d = $d + $g
  }
}

// The raw list is interleaved across the stems rather than sorted, so the student has to bucket the
// values by tens digit and then order each row. A sorted list would leave nothing to do but copy.
$off = rand(0, 3)
$valueList = ""
$first = 1
for ($p=0..4) {
  for ($t=0..3) {
    $i = ($t + $off) % 4
    $howMany = $cnt[$i]
    if ($p < $howMany) {
      $slot = $i * 10 + $p
      $v = 10 * $stem[$i] + $allLeaf[$slot]
      if ($first == 1) { $valueList = "" . $v }
      if ($first == 0) { $valueList = $valueList . ", " . $v }
      $first = 0
    }
  }
}

$stem0 = $stem[0]
$stem1 = $stem[1]
$stem2 = $stem[2]
$stem3 = $stem[3]

$answer[0] = $allLeaf[0]
$answer[1] = $allLeaf[1]
$answer[2] = $allLeaf[2]
$answer[3] = $allLeaf[10]
$answer[4] = $allLeaf[11]
$answer[5] = $allLeaf[12]
$answer[6] = $allLeaf[13]
$answer[7] = $allLeaf[14]
$answer[8] = $allLeaf[20]
$answer[9] = $allLeaf[21]
$answer[10] = $allLeaf[22]
$answer[11] = $allLeaf[23]
$answer[12] = $allLeaf[30]
$answer[13] = $allLeaf[31]
$answer[14] = $allLeaf[32]
$answer[15] = $allLeaf[33]

for ($k=0..15) {
  $answerformat[$k] = "integer"
}
$answerboxsize = 3

$questions[16] = array(
  "The leaf is written once for each measurement, so a value recorded twice puts two identical leaves on that row.",
  "The leaf is written once, and a small note beside the row records that it happened twice.",
  "The leaf is written once. A stem-and-leaf plot cannot show that a value occurred more than once.",
  "The second one moves to the next stem row, so that the leaves on a row are never repeated."
)
$answer[16] = 0

$keyStem = $stem[0]
$keyLeaf = $allLeaf[0]
$keyVal = 10 * $keyStem + $keyLeaf

$rowStr0 = ""
$rowStr1 = ""
$rowStr2 = ""
$rowStr3 = ""
for ($j=0..2) { $rowStr0 = $rowStr0 . $allLeaf[$j] . "&nbsp;&nbsp;" }
for ($j=10..14) { $rowStr1 = $rowStr1 . $allLeaf[$j] . "&nbsp;&nbsp;" }
for ($j=20..23) { $rowStr2 = $rowStr2 . $allLeaf[$j] . "&nbsp;&nbsp;" }
for ($j=30..33) { $rowStr3 = $rowStr3 . $allLeaf[$j] . "&nbsp;&nbsp;" }

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
  .plot-tbl td { border:1px solid #d1d5db; padding:5px 14px; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Step 1 &mdash; split every value into a stem and a leaf.</span> With two-digit data the tens digit is the stem and the ones digit is the leaf, so ' . $keyVal . ' goes on the ' . $keyStem . ' row with a leaf of ' . $keyLeaf . '. Work through the list once, dropping each value onto its row as you meet it. Do not try to read the rows straight off the list &mdash; the list is not in order.</p>
      <p><span class="term-label">Step 2 &mdash; put each row in order.</span> Once every value is on a row, write the leaves smallest to largest across that row. Ordering is not decoration: it is what lets a reader see the shape and find the median by counting along.</p>
      <p><span class="term-label">Step 3 &mdash; the finished plot.</span></p>
      <table class="plot-tbl" style="border-collapse:collapse; margin:8px 0; font-family:ui-monospace,Menlo,Consolas,monospace;">
        <tr style="background:#f0f4ff;"><td><b>Stem</b></td><td><b>Leaf</b></td></tr>
        <tr><td style="text-align:center;"><b>' . $stem0 . '</b></td><td>' . $rowStr0 . '</td></tr>
        <tr><td style="text-align:center;"><b>' . $stem1 . '</b></td><td>' . $rowStr1 . '</td></tr>
        <tr><td style="text-align:center;"><b>' . $stem2 . '</b></td><td>' . $rowStr2 . '</td></tr>
        <tr><td style="text-align:center;"><b>' . $stem3 . '</b></td><td>' . $rowStr3 . '</td></tr>
      </table>
      <p><span class="term-label">Step 4 &mdash; check the count.</span> Count the leaves: there must be ' . $n . ', one per measurement. If your plot holds fewer, you have almost certainly written a repeated value only once.</p>
      <p><span class="term-label">Repeated measurements.</span> A stemplot keeps every value, so a measurement recorded twice is written twice and the same leaf appears two times on that row. Writing it once loses a data value and the plot stops adding up to the sample size. It is the most common mistake in building one.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">A researcher recorded $context. The $n measurements are listed below, in the order they were collected.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
    <p style="margin:12px 0 0 0;"><b>Build the stem-and-leaf plot.</b> The stems are already in place. Enter every leaf, working left to right across each row in order from smallest to largest. Use the tens digit as the stem and the ones digit as the leaf.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <table style="border-collapse:collapse; margin:0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Stem</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px; text-align:left;">Leaf</th>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center; font-weight:700; background:#f8fafc;">$stem0</td>
        <td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[0] $answerbox[1] $answerbox[2]</td>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center; font-weight:700; background:#f8fafc;">$stem1</td>
        <td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[3] $answerbox[4] $answerbox[5] $answerbox[6] $answerbox[7]</td>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center; font-weight:700; background:#f8fafc;">$stem2</td>
        <td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[8] $answerbox[9] $answerbox[10] $answerbox[11]</td>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center; font-weight:700; background:#f8fafc;">$stem3</td>
        <td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[12] $answerbox[13] $answerbox[14] $answerbox[15]</td>
      </tr>
    </table>
    <p style="margin:10px 0 0 0; font-size:14px; color:#6b7280;">Key: a stem of $keyStem with a leaf of $keyLeaf means $keyVal $unit.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">q.</span> Suppose two of the measurements had been exactly the same. How does that show up on the plot? $answerbox[16]
  </div>
</div>

// === ANSWER ===

$solutionguide
