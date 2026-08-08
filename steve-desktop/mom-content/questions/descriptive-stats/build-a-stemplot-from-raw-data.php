// === NAME - DESCRIPTION: Build a Stem-and-Leaf Plot from Raw Data - From an ordered list of two-digit measurements, work out how many stem rows the plot needs, how many leaves land on one stem, and how many values clear a cutoff, then say what a single leaf stands for ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

// Two contexts, both two-digit whole numbers so every value sits on a single stem.
$ci = rand(0, 1)
$contexts = array(
  "the miles per gallon rating of each car on a dealer's lot",
  "the height, in feet, of each tree measured along one street"
)
$units = array("miles per gallon", "feet")
$context = $contexts[$ci]
$unit = $units[$ci]

// Stems are walked digit by digit so no row can be empty and no value can appear more than
// twice -- the worst case is bounded by construction rather than merely unlikely.
$nStems = rand(3, 4)
$lo = rand(2, 5)
$hi = $lo + $nStems - 1

$n = 0
$valueList = ""
$countByStem = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0)

for ($s=$lo..$hi) {
  // Two forced digits, one from each half, guarantee at least two leaves on every stem.
  $f1 = rand(0, 4)
  $f2 = rand(5, 9)
  for ($d=0..9) {
    $r = rand(0, 9)
    $take = 0
    if ($r < 4) { $take = 1 }
    if ($d == $f1) { $take = 1 }
    if ($d == $f2) { $take = 1 }
    if ($take == 1) {
      $v = 10 * $s + $d
      if ($n == 0) { $valueList = "" . $v }
      if ($n > 0) { $valueList = $valueList . ", " . $v }
      $n = $n + 1
      $countByStem[$s] = $countByStem[$s] + 1
    }
  }
}

// One stem to count, and a cutoff at a stem boundary so the third part has one exact answer.
$askStem = rand($lo, $hi)
$countInStem = $countByStem[$askStem]
$askLow = 10 * $askStem
$askHigh = 10 * $askStem + 9

$cutStem = $lo + 1
$cutoff = 10 * $cutStem
$atOrAbove = 0
for ($s=$cutStem..$hi) {
  $atOrAbove = $atOrAbove + $countByStem[$s]
}

$exampleValue = 10 * $lo + 4
$exampleLeaf = 4

$questions[3] = array(
  "The last digit of one data value. Its stem supplies the digits in front of it, so stem " . $lo . " with leaf 4 is the value " . $exampleValue . ".",
  "The number of data values on that row, written once per row.",
  "The average of the data values on that row, rounded to one digit.",
  "A place-holder that keeps the rows evenly spaced; it carries no data."
)
$answer[3] = 0

$answer[0] = $nStems
$answerformat[0] = "integer"

$answer[1] = $countInStem
$answerformat[1] = "integer"

$answer[2] = $atOrAbove
$answerformat[2] = "integer"

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
      <p><span class="term-label">Step 1 &mdash; split each value into a stem and a leaf.</span> For two-digit data the stem is the tens digit and the leaf is the ones digit. The value ' . $exampleValue . ' splits into stem ' . $lo . ' and leaf ' . $exampleLeaf . '.</p>
      <p><span class="term-label">Step 2 &mdash; count the rows.</span> The data run from the ' . $lo . '0s up to the ' . $hi . '0s, so the stems are ' . $lo . ' through ' . $hi . ' &mdash; <b>' . $nStems . '</b> rows. Every stem in that span gets a row even if a stem had no values; leaving one out would hide a gap in the data.</p>
      <p><span class="term-label">Step 3 &mdash; count the leaves on one row.</span> Every value from ' . $askLow . ' to ' . $askHigh . ' ' . $unit . ' lands on stem ' . $askStem . '. Reading those out of the list gives <b>' . $countInStem . '</b> values. Count leaves, not rows: one row holds many values.</p>
      <p><span class="term-label">Step 4 &mdash; count above a cutoff.</span> Values of ' . $cutoff . ' ' . $unit . ' or more sit on stems ' . $cutStem . ' through ' . $hi . '. Adding the leaves on those rows gives <b>' . $atOrAbove . '</b>. This is what a stemplot is for &mdash; the original values survive, so a count like this can still be read off exactly.</p>
      <p><b>Answer:</b> (a) ' . $nStems . ' &nbsp;&nbsp; (b) ' . $countInStem . ' &nbsp;&nbsp; (c) ' . $atOrAbove . ' &nbsp;&nbsp; (d) the last digit of one data value</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">A researcher recorded $context. The $n measurements are listed below from lowest to highest.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
    <p style="margin:12px 0 0 0;">You are about to display this data in a stem-and-leaf plot, using the tens digit as the stem and the ones digit as the leaf.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many stem rows will the completed plot have? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many leaves will sit on the row for stem $askStem, that is, how many measurements fall between $askLow and $askHigh $unit inclusive? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many of the measurements are $cutoff $unit or more? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> In the finished plot, what does a single leaf stand for? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
