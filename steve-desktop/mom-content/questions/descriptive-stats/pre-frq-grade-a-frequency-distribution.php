// === NAME - DESCRIPTION: Pre-FRQ Grade a Frequency Distribution - The scenario and grading checklist of the frequency-distribution FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Chapter 1's pre-FRQ for 1.3, matching the 1.2 and 1.4 ones: the SAME scenario and the SAME grading
// checklist as frq/descriptive-statistics/q6-frequency-distribution-analysis, with the writing
// replaced by grading. The dropped category here is interpretation -- students build the table, name
// the shape, and stop, because the table LOOKS like the answer.
//
// The table itself is generated, and the shape is computed from the same counts that fill it, so the
// numbers a student reads and the shape the key expects cannot disagree.
$anstypes = array("choices", "multans", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $setting = "a 50-point quiz taken by a statistics class"
  $unitWord = "points"
  $doingWell = "most of the class scored in the upper classes, with only a few low scores trailing behind"
  $meaning = "most students had a good grasp of the material and a small group is struggling and needs help"
}
else {
  $setting = "the number of minutes students spent on a homework set"
  $unitWord = "minutes"
  $doingWell = "most students finished in the lower classes, with a few taking far longer"
  $meaning = "the set was manageable for most of the class, while a small group found it much harder going"
}

$lo = 0
$w = 10
$b0 = $lo
$b1 = $lo + $w
$b2 = $lo + 2 * $w
$b3 = $lo + 3 * $w
$b4 = $lo + 4 * $w
$b5 = $lo + 5 * $w

// Counts are built skewed so the shape is unmistakable, and the direction matches the context.
$c0 = rand(1, 2)
$c1 = rand(2, 3)
$c2 = rand(4, 6)
$c3 = rand(8, 11)
$c4 = rand(6, 8)
if ($ci == 1) {
  $c0 = rand(6, 8)
  $c1 = rand(8, 11)
  $c2 = rand(4, 6)
  $c3 = rand(2, 3)
  $c4 = rand(1, 2)
}
$nTot = $c0 + $c1 + $c2 + $c3 + $c4

$shapeName = "skewed left"
if ($ci == 1) { $shapeName = "skewed right" }

$tableRows = '<tr><td>' . $b0 . '&ndash;' . ($b1 - 1) . '</td><td>' . $c0 . '</td></tr>'
$tableRows = $tableRows . '<tr><td>' . $b1 . '&ndash;' . ($b2 - 1) . '</td><td>' . $c1 . '</td></tr>'
$tableRows = $tableRows . '<tr><td>' . $b2 . '&ndash;' . ($b3 - 1) . '</td><td>' . $c2 . '</td></tr>'
$tableRows = $tableRows . '<tr><td>' . $b3 . '&ndash;' . ($b4 - 1) . '</td><td>' . $c3 . '</td></tr>'
$tableRows = $tableRows . '<tr><td>' . $b4 . '&ndash;' . ($b5 - 1) . '</td><td>' . $c4 . '</td></tr>'

// --- Sentence-parts for the four responses.
$sTable = "Using a class width of " . $w . " " . $unitWord . " starting at " . $b0 . ", the five classes and their frequencies are " . $b0 . "-" . ($b1 - 1) . ": " . $c0 . ", " . $b1 . "-" . ($b2 - 1) . ": " . $c1 . ", " . $b2 . "-" . ($b3 - 1) . ": " . $c2 . ", " . $b3 . "-" . ($b4 - 1) . ": " . $c3 . ", and " . $b4 . "-" . ($b5 - 1) . ": " . $c4 . ", making " . $nTot . " values in all."
$sShape = "The distribution is " . $shapeName . ", because " . $doingWell . "."
$sInterp = "That tells us " . $meaning . ", so the summary to report is the median rather than the mean, since the tail would drag the mean away from where most of the class actually sits."

$rFull = $sTable . " " . $sShape . " " . $sInterp
$rNoInterp = $sTable . " " . $sShape . " The table and the shape together describe the data completely."
$rNoShape = $sTable . " " . $sInterp
$rTableOnly = $sTable . " The classes were chosen so that every value falls into exactly one of them."

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoInterp
$rC = $rNoShape
$rD = $rTableOnly
if ($pos == 1) {
  $rA = $rNoInterp
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoShape
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rTableOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noInterpLabel = "B"
if ($pos == 1) { $noInterpLabel = "A" }

$questions[1] = array(
  "Frequency Distribution (4 pts)",
  "Shape Identification (3 pts)",
  "Performance Interpretation (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Naming the shape describes the picture; interpreting it says what the picture means for the people the data is about. The rubric awards those separately because they are separate steps.",
  "Yes. Once the shape has been named the interpretation is implied and does not need writing.",
  "No, but only because the response was too short. Naming the shape a second time would have earned it.",
  "Yes, as long as the frequency table above it was correct."
)
$answer[2] = 0

$css = '
<style>
  .qscope6 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope6 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope6 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope6 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope6 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope6 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope6 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope6 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope6 .row-colored { background:#fff9ea; }
  .qscope6 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope6 .resp b { color:#1865f2; }
  .qscope6 .datatab { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .qscope6 .datatab td, .qscope6 .datatab th { border:1px solid #d1d5db; padding:6px 18px; text-align:center; }
  .qscope6 .datatab th { background:#f0f4ff; }
</style>'

$rubric = $css . '
<div class="qscope6">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Frequency Distribution<br>(4 pts)</b></td>
            <td>Show how the class width and class boundaries were determined, and list each class with its frequency.</td></tr>
          <tr><td style="text-align:center;"><b>Shape Identification<br>(3 pts)</b></td>
            <td>Identify and describe the overall shape of the distribution.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Performance Interpretation<br>(3 pts)</b></td>
            <td>Explain what that shape reveals about the group the data describes.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$dataTable = '
<div class="qscope6">
  <table class="datatab">
    <tr><th>Class (' . $unitWord . ')</th><th>Frequency</th></tr>
    ' . $tableRows . '
  </table>
</div>'

$responses = '
<div class="qscope6">
  <div class="resp"><b>Response A.</b> ' . $rA . '</div>
  <div class="resp"><b>Response B.</b> ' . $rB . '</div>
  <div class="resp"><b>Response C.</b> ' . $rC . '</div>
  <div class="resp"><b>Response D.</b> ' . $rD . '</div>
</div>'

$fullLabel = "A"
if ($pos == 1) { $fullLabel = "B" }
if ($pos == 2) { $fullLabel = "C" }
if ($pos == 3) { $fullLabel = "D" }

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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> shows the class width and boundaries with every frequency, names the shape and says why, and then says what that means for the group. Each of the other three drops a whole category, and a dropped category scores zero however good the rest is.</p>
      <p><span class="term-label">The shape, and how you can tell.</span> The counts run ' . $c0 . ', ' . $c1 . ', ' . $c2 . ', ' . $c3 . ', ' . $c4 . ' across the five classes, so the distribution is <b>' . $shapeName . '</b> &mdash; ' . $doingWell . '. Skew is named for the thin tail, never for the side the tall bars are on.</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noInterpLabel . ' line by line.</span></p>
      <ul>
        <li><b>Frequency Distribution &mdash; earned.</b> The width, the boundaries and all five frequencies are there, and they total ' . $nTot . '.</li>
        <li><b>Shape Identification &mdash; earned.</b> It names the shape and ties it to where the data piles up.</li>
        <li><b>Performance Interpretation &mdash; NOT earned.</b> It ends by saying the table and shape describe the data completely. That is a claim about the ANSWER, not a statement about the group. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why interpretation is its own category.</span> Naming a shape describes the picture. Interpreting it says what the picture means for the people behind the numbers: here, that ' . $meaning . '. Anyone who has to act on the report needs the second sentence, not the first &mdash; and it is the one most often missing, because once the table is built the work FEELS finished.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. Finding the missing category in someone else&rsquo;s answer is the quickest way to stop leaving it out of your own.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> Students were given the raw results of $setting and asked to organize them. The distribution they were meant to produce is below.</p>
    $dataTable
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Build a frequency distribution showing your class width and boundaries, identify the shape, and interpret what it says about how the group performed.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 4px 0;"><b>Four students answered.</b></p>
    $responses
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response earns <b>full credit</b> on all three categories? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noInterpLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is naming the shape enough on its own to cover the interpretation? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
