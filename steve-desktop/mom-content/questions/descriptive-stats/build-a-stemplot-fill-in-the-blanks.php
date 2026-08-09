// === NAME - DESCRIPTION: Build a Stem-and-Leaf Plot by Filling the Blanks - A stemplot of a randomized data set has one leaf erased from each of its four rows; work out from the raw list which digit belongs in each blank, then say how a repeated measurement appears ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four blanks, one per stem row, plus one question about repeats. Every other stemplot in the bank
// asks the student to READ a finished plot or pick the right one from four; this is the only one
// where a digit has to be placed. The blanks sit in DIFFERENT rows on purpose: a single row with
// four boxes would tell the student how many values land on that row, which is half the work.
$anstypes = array("number", "number", "number", "number", "choices")

$ci = rand(0, 1)
$contexts = array(
  "the number of seconds each contestant took to solve a puzzle",
  "the weight, in kilograms, of each crate loaded onto a truck"
)
$units = array("seconds", "kilograms")
$context = $contexts[$ci]
$unit = $units[$ci]

$loStem = rand(2, 5)

// Leaves are stored flat as $allLeaf[stemIndex * 10 + position] so the whole plot can be built in
// one loop. Four separate arrays would need the stem loop unrolled four times.
$allLeaf = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)
$cnt = array(0, 0, 0, 0)
$blank = array(0, 0, 0, 0)
$stem = array(0, 0, 0, 0)
$n = 0

for ($i=0..3) {
  $stem[$i] = $loStem + $i
  $c = rand(4, 6)
  $cnt[$i] = $c
  $n = $n + $c
  $cm = $c - 1
  $base = $i * 10
  $d = rand(0, 1)
  for ($j=0..$cm) {
    $slot = $base + $j
    $allLeaf[$slot] = $d
    $g = rand(1, 2)
    // Keep room for every remaining leaf at a gap of one, so the row can never run past 9.
    $room = $d + $g + $cm - $j
    if ($room > 9) { $g = 1 }
    $d = $d + $g
  }
  $blank[$i] = rand(0, $cm)
}

// The raw list is interleaved across the stems rather than sorted, so the student has to bucket the
// values by tens digit instead of reading finished rows straight off the page. A sorted list would
// leave nothing to do but copy.
$off = rand(0, 3)
$valueList = ""
$first = 1
for ($p=0..5) {
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

// Each row is split into the leaves before the blank and the leaves after it, so the question text
// can drop $answerbox in between using scalars only.
$preArr = array("", "", "", "")
$postArr = array("", "", "", "")
for ($i=0..3) {
  $last = $cnt[$i] - 1
  $gap = $blank[$i]
  for ($j=0..$last) {
    $slot = $i * 10 + $j
    $leaf = $allLeaf[$slot]
    if ($j < $gap) { $preArr[$i] = $preArr[$i] . $leaf . "&nbsp;&nbsp;" }
    if ($j > $gap) { $postArr[$i] = $postArr[$i] . "&nbsp;&nbsp;" . $leaf }
  }
}

$stem0 = $stem[0]
$stem1 = $stem[1]
$stem2 = $stem[2]
$stem3 = $stem[3]
$pre0 = $preArr[0]
$pre1 = $preArr[1]
$pre2 = $preArr[2]
$pre3 = $preArr[3]
$post0 = $postArr[0]
$post1 = $postArr[1]
$post2 = $postArr[2]
$post3 = $postArr[3]

$b0 = 0 + $blank[0]
$answer[0] = $allLeaf[$b0]
$b1 = 10 + $blank[1]
$answer[1] = $allLeaf[$b1]
$b2 = 20 + $blank[2]
$answer[2] = $allLeaf[$b2]
$b3 = 30 + $blank[3]
$answer[3] = $allLeaf[$b3]
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answerformat[2] = "integer"
$answerformat[3] = "integer"
$answerboxsize = 3

$val0 = 10 * $stem[0] + $answer[0]
$val1 = 10 * $stem[1] + $answer[1]
$val2 = 10 * $stem[2] + $answer[2]
$val3 = 10 * $stem[3] + $answer[3]

$questions[4] = array(
  "The leaf is written once for each measurement, so a value recorded twice puts two identical leaves on that row.",
  "The leaf is written once, and a small note beside the row records that it happened twice.",
  "The leaf is written once. A stem-and-leaf plot cannot show that a value occurred more than once.",
  "The second one moves to the next stem row, so that the leaves on a row are never repeated."
)
$answer[4] = 0

// The key must illustrate a leaf that is still PRINTED. Taking position 0 blindly hands the answer
// to part (a) away on every seed where row 0's blank happens to sit first.
$keySlot = 0
if ($blank[0] == 0) { $keySlot = 1 }
$keyStem = $stem[0]
$keyLeaf = $allLeaf[$keySlot]
$keyVal = 10 * $keyStem + $keyLeaf

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
      <p><span class="term-label">Step 1 &mdash; split each value into a stem and a leaf.</span> With two-digit data the tens digit is the stem and the ones digit is the leaf, so ' . $keyVal . ' sits on the ' . $keyStem . ' row with a leaf of ' . $keyLeaf . '. The list is deliberately out of order, so work through it once and drop each value onto its row rather than trying to read the rows off the list.</p>
      <p><span class="term-label">Step 2 &mdash; put each row in order.</span> Leaves run smallest to largest across the row. That is what makes the plot readable, and it is also what fixes each blank: once a row is in order there is exactly one digit that can sit in the gap.</p>
      <p><span class="term-label">Step 3 &mdash; read off the missing leaves.</span> Compare the leaves already printed on a row with the values from that row in the list. The one value not yet showing is the blank.</p>
      <ul>
        <li>Row ' . $stem0 . ': the missing value is ' . $val0 . ', so the blank leaf is <b>' . $answer[0] . '</b>.</li>
        <li>Row ' . $stem1 . ': the missing value is ' . $val1 . ', so the blank leaf is <b>' . $answer[1] . '</b>.</li>
        <li>Row ' . $stem2 . ': the missing value is ' . $val2 . ', so the blank leaf is <b>' . $answer[2] . '</b>.</li>
        <li>Row ' . $stem3 . ': the missing value is ' . $val3 . ', so the blank leaf is <b>' . $answer[3] . '</b>.</li>
      </ul>
      <p><span class="term-label">Step 4 &mdash; repeated measurements.</span> A stemplot keeps every value, so a measurement recorded twice is written twice: the same leaf appears two times on that row. Writing it once would lose a data value, and the plot would no longer add up to the sample size. This is the most common mistake in building one.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">A researcher recorded $context. The $n measurements are listed below, in the order they were collected.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
    <p style="margin:12px 0 0 0;">Someone started a stem-and-leaf plot of this data, using the tens digit as the stem and the ones digit as the leaf, but <b>one leaf has been rubbed out of every row</b>. Work out which digit belongs in each blank.</p>
    <table style="border-collapse:collapse; margin:14px 0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Stem</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px; text-align:left;">Leaf</th>
        <th style="border:1px solid #d1d5db; padding:6px 10px; font-size:13px; color:#6b7280;">Part</th>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; font-weight:700; background:#f8fafc;">$stem0</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; font-family:ui-monospace,Menlo,Consolas,monospace; letter-spacing:1px;">$pre0 $answerbox[0] $post0</td>
        <td style="border:1px solid #d1d5db; padding:6px 10px; text-align:center; font-size:13px; color:#6b7280;">a.</td>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; font-weight:700; background:#f8fafc;">$stem1</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; font-family:ui-monospace,Menlo,Consolas,monospace; letter-spacing:1px;">$pre1 $answerbox[1] $post1</td>
        <td style="border:1px solid #d1d5db; padding:6px 10px; text-align:center; font-size:13px; color:#6b7280;">b.</td>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; font-weight:700; background:#f8fafc;">$stem2</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; font-family:ui-monospace,Menlo,Consolas,monospace; letter-spacing:1px;">$pre2 $answerbox[2] $post2</td>
        <td style="border:1px solid #d1d5db; padding:6px 10px; text-align:center; font-size:13px; color:#6b7280;">c.</td>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; font-weight:700; background:#f8fafc;">$stem3</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; font-family:ui-monospace,Menlo,Consolas,monospace; letter-spacing:1px;">$pre3 $answerbox[3] $post3</td>
        <td style="border:1px solid #d1d5db; padding:6px 10px; text-align:center; font-size:13px; color:#6b7280;">d.</td>
      </tr>
    </table>
    <p style="margin:0; font-size:14px; color:#6b7280;">Key: a stem of $keyStem with a leaf of $keyLeaf means $keyVal $unit.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Suppose two of the measurements had been exactly the same. How does that show up on the plot? $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
