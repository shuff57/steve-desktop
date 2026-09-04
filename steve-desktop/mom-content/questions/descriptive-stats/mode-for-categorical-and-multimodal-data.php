// === NAME - DESCRIPTION: When the Mode Is the Only Center - Name the mode of a categorical variable, say why the mean and median cannot be computed for it, and identify a numeric data set that has two modes ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The rest of 2.5 quietly assumes the data is numeric. This is the question that says so out loud:
// for a qualitative variable the mean and the median do not exist, and the mode is the only measure
// of center available. Students who have been taught "mean, median, mode" as three interchangeable
// options need the case where two of the three are simply unavailable.
//
// Part (c) uses a numeric set with EXACTLY two modes, built by giving two different values the same
// top count and every other value a strictly smaller one, so "bimodal" is a fact about the data
// rather than a judgement call.
$anstypes = array("choices", "choices", "choices", "number")

$ci = rand(0, 1)
if ($ci == 0) {
  $varName = "the color of the car"
  $subject = "cars in a parking lot"
  $k0 = "silver"
  $k1 = "white"
  $k2 = "blue"
  $k3 = "red"
}
else {
  $varName = "the brand of phone"
  $subject = "students in a lecture"
  $k0 = "Pixel"
  $k1 = "Samsung"
  $k2 = "Apple"
  $k3 = "OnePlus"
}

// Four categories, one strictly the most common.
$f0 = rand(9, 15)
$f1 = rand(9, 15)
$f2 = rand(9, 15)
$f3 = rand(9, 15)
$topIdx = rand(0, 3)
$maxOther = 15
if ($topIdx == 0) { $f0 = $maxOther + rand(4, 9) }
if ($topIdx == 1) { $f1 = $maxOther + rand(4, 9) }
if ($topIdx == 2) { $f2 = $maxOther + rand(4, 9) }
if ($topIdx == 3) { $f3 = $maxOther + rand(4, 9) }
$nCat = $f0 + $f1 + $f2 + $f3

$topName = $k0
if ($topIdx == 1) { $topName = $k1 }
if ($topIdx == 2) { $topName = $k2 }
if ($topIdx == 3) { $topName = $k3 }

$questions[0] = array(
  "The mode is " . $topName . ", because it is the category that appears most often.",
  "The mode is " . $nCat . ", because that is how many were recorded altogether.",
  "There is no mode, because the categories are words rather than numbers.",
  "The mode is the category listed first in the table."
)
$answer[0] = 0

$questions[1] = array(
  "Neither can be computed. Adding or ordering the categories is meaningless, and both the mean and the median require that.",
  "Both can be computed, by numbering the categories 1, 2, 3, 4 first.",
  "The mean cannot be computed but the median can, because the categories can be listed alphabetically.",
  "Both can be computed as long as every category has at least one observation."
)
$answer[1] = 0

// A numeric set with exactly two modes: two values share the top count of 3, everything else has 1.
$d0 = rand(4, 7)
$d1 = $d0 + rand(2, 4)
$d2 = $d1 + rand(2, 4)
$d3 = $d2 + rand(2, 4)
$d4 = $d3 + rand(2, 4)
$twoModeList = $d0 . ", " . $d0 . ", " . $d0 . ", " . $d1 . ", " . $d2 . ", " . $d3 . ", " . $d3 . ", " . $d3 . ", " . $d4

$questions[2] = array(
  "It has two modes, " . $d0 . " and " . $d3 . ", because both occur three times and nothing occurs more often. A set like this is called bimodal.",
  "It has no mode, because no single value stands alone as the most common.",
  "Its mode is " . $d2 . ", because that value sits in the middle of the list.",
  "Its mode is " . $d0 . " only, because that value appears first in the list."
)
$answer[2] = 0

$answer[3] = $nCat
$answerboxsize = 6

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
      <p><span class="term-label">Part (a): the mode of a category.</span> The mode is the value that occurs most often, and it does not have to be a number. Here ' . $topName . ' was recorded more than any other, so the mode is <b>' . $topName . '</b>. Note what the mode is NOT: it is the category, not the count. Answering with the frequency is the most common mistake on this part.</p>
      <p><span class="term-label">Part (b): why the other two are unavailable.</span> The mean needs you to ADD the values and divide; the median needs you to put them IN ORDER and take the middle. Neither operation means anything for ' . $varName . ': there is no sum of ' . $k0 . ' and ' . $k1 . ', and no reason one comes before the other. So for a qualitative variable the mode is the only measure of center there is.</p>
      <p>Numbering the categories 1, 2, 3, 4 does not rescue this. Those numbers are labels, and averaging labels produces a number that answers no question: a "mean color" of 2.4 is not a color.</p>
      <p><span class="term-label">Part (c): two modes at once.</span> In that list both ' . $d0 . ' and ' . $d3 . ' appear three times, and no value appears more. When two values tie for the most common the data set is <b>bimodal</b> and both are reported. A data set can have one mode, several, or, when every value appears exactly once, none at all. That is unlike the mean and the median, which are always single numbers when they exist.</p>
      <p><span class="term-label">Part (d).</span> The four category counts add to <b>' . $nCat . '</b>, which is how many ' . $subject . ' were recorded.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A survey recorded $varName for a group of $subject.</p>
    <table style="border-collapse:collapse; margin:12px 0 0 0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 18px; text-align:left;">Category</th>
        <th style="border:1px solid #d1d5db; padding:6px 18px;">Frequency</th>
      </tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 18px;">$k0</td><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$f0</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 18px;">$k1</td><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$f1</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 18px;">$k2</td><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$f2</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 18px;">$k3</td><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$f3</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the mode of $varName? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Can you compute the <b>mean</b> and the <b>median</b> of $varName? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;">A different, numeric data set was recorded:</p>
    <p style="margin:0 0 12px 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px;">$twoModeList</p>
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is its mode? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Back to the table: how many $subject were recorded altogether? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
