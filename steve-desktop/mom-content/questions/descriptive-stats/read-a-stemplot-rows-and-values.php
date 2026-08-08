// === NAME - DESCRIPTION: Read a Stem-and-Leaf Plot - Recover the count in one stem row, the largest value in another, and the total sample size from a stemplot built from randomized data ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

// Two contexts, both two-digit whole numbers so every value lands on a single stem.
$ci = rand(0, 1)
$contexts = array(
  "the miles per gallon rating of each of the cars on a dealer's lot",
  "the height, in feet, of each of the trees measured along one street"
)
$units = array("miles per gallon", "feet")
$context = $contexts[$ci]
$unit = $units[$ci]

// Build the plot stem by stem and digit by digit, in ascending order. An earlier version walked
// a running sum with a small random step; that sorts itself for free but lets repeats pile up --
// one seed produced six identical values in a row. Iterating digits 0..9 caps any value at two
// occurrences by construction, so no student can draw an absurd-looking plot.
$loStem = rand(2, 5)
$hiStem = $loStem + 3

$n = 0
$stemRows = ""
$countByStem = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
$maxByStem = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0)

for ($s=$loStem..$hiStem) {
  $leaves = ""
  for ($d=0..9) {
    $r = rand(0, 9)
    if ($r < 6) {
      $leaves = $leaves . $d . "&nbsp;&nbsp;"
      $n = $n + 1
      $countByStem[$s] = $countByStem[$s] + 1
      $maxByStem[$s] = 10 * $s + $d
    }
    if ($r == 9) {
      $leaves = $leaves . $d . "&nbsp;&nbsp;"
      $n = $n + 1
      $countByStem[$s] = $countByStem[$s] + 1
      $maxByStem[$s] = 10 * $s + $d
    }
  }
  $stemRows = $stemRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; font-weight:700; background:#f8fafc;">' . $s . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; font-family:ui-monospace,Menlo,Consolas,monospace; letter-spacing:1px;">' . $leaves . '</td></tr>'
}

// Ask about two different stems, both guaranteed non-empty by the >=6-of-10 inclusion rule.
$askStem = rand($loStem, $hiStem)
$askStem2 = $loStem
if ($askStem == $loStem) {
  $askStem2 = $hiStem
}

$countInStem = $countByStem[$askStem]
$maxInStem2 = $maxByStem[$askStem2]

$keyExample = $loStem * 10 + 4
$askStemLow = $askStem * 10
$askStemHigh = $askStem * 10 + 9
$askStem2Low = $askStem2 * 10
$askStem2High = $askStem2 * 10 + 9

$answer[0] = $countInStem
$answerformat[0] = "integer"

$answer[1] = $maxInStem2
$answerformat[1] = "integer"

$answer[2] = $n
$answerformat[2] = "integer"

$questions[3] = array(
  "Each leaf is one data value, so counting every leaf in the plot gives the sample size.",
  "Each row is one data value, so counting the rows gives the sample size.",
  "Each stem is one data value, so counting the stems gives the sample size.",
  "The sample size cannot be read off a stemplot; the original list is needed."
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
      <p><span class="term-label">How a stemplot stores a value.</span> The stem is everything except the last digit and the leaf is the last digit, so stem ' . $askStem . ' with leaf 4 means the value ' . ($askStem * 10 + 4) . '. Nothing is rounded away &mdash; every original value is still readable.</p>
      <p><span class="term-label">Part (a) &mdash; count the leaves in one row.</span> Values between ' . $askStemLow . ' and ' . $askStemHigh . ' ' . $unit . ' all sit on stem ' . $askStem . '. Counting the leaves in that one row gives <b>' . $countInStem . '</b>. Count leaves, not stems: one row can hold many values.</p>
      <p><span class="term-label">Part (b) &mdash; largest value in a row.</span> Leaves are written in ascending order, so the largest value on stem ' . $askStem2 . ' is the <i>last</i> leaf in that row. That leaf is ' . ($maxInStem2 - 10 * $askStem2) . ', giving <b>' . $maxInStem2 . '</b> ' . $unit . '.</p>
      <p><span class="term-label">Part (c) &mdash; the sample size.</span> Every leaf is one observation, so the sample size is the total number of leaves in the whole plot: <b>' . $n . '</b>. This is the check students most often get wrong by counting rows instead.</p>
      <p><b>Answer:</b> (a) ' . $countInStem . ' &nbsp;&nbsp; (b) ' . $maxInStem2 . ' &nbsp;&nbsp; (c) ' . $n . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">A stem-and-leaf plot was built from $context, measured in $unit.</p>
    <table style="border-collapse:collapse; margin:10px 0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">Stem</th>
          <th style="border:1px solid #d1d5db; padding:8px 14px; text-align:left;">Leaf</th>
        </tr>
      </thead>
      <tbody>
        $stemRows
      </tbody>
    </table>
    <p style="margin:8px 0 0 0; font-size:14px; color:#666;">Key: a stem of $loStem with a leaf of 4 means $keyExample $unit.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many of the measurements are between $askStemLow and $askStemHigh $unit, inclusive? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the largest measurement between $askStem2Low and $askStem2High $unit? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many measurements are in the whole data set? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Which statement explains how the sample size is found from a stemplot? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
