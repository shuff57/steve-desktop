// === NAME - DESCRIPTION: Classes and Shape from a Raw Data Set - Group a randomized data set into classes of a stated width, then report a class count, where the tallest class starts, a relative frequency and the shape of the distribution ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A trainer recorded how many minutes each client spent on the treadmill in one session."
  $unitWord = "minutes"
  $thing = "clients"
}
else {
  $intro = "A librarian recorded how many pages each borrower read on one day."
  $unitWord = "pages"
  $thing = "borrowers"
}

// The class counts are chosen FIRST and the data values are then generated inside each class, so
// every answer below is fixed by the data itself, whatever tool a student groups it with.
// The three shapes are built from fixed patterns with jitter that cannot disturb the tallest class.
$shape = rand(0, 2)
$base = array(0, 0, 0, 0, 0)
if ($shape == 0) {
  // tail to the left: the pile-up is at the top end
  $base[0] = 1
  $base[1] = 2
  $base[2] = 4
  $base[3] = 7
  $base[4] = 10
}
if ($shape == 1) {
  // tail to the right: the pile-up is at the bottom end
  $base[0] = 10
  $base[1] = 7
  $base[2] = 4
  $base[3] = 2
  $base[4] = 1
}
if ($shape == 2) {
  $base[0] = 3
  $base[1] = 6
  $base[2] = 10
  $base[3] = 6
  $base[4] = 3
}

$cnt = array(0, 0, 0, 0, 0)
$n = 0
$maxCount = 0
$tallIdx = 0
for ($b=0..4) {
  $cnt[$b] = $base[$b] + rand(0, 2)
  $n = $n + $cnt[$b]
  if ($cnt[$b] > $maxCount) {
    $maxCount = $cnt[$b]
    $tallIdx = $b
  }
}

// Values are emitted in ascending order inside each class. Walking the digits 0..9 and forcing a
// take once the remaining need matches the digits left guarantees the class ends up with exactly
// the count chosen above; a digit is written twice only when the count exceeds the ten digits
// available, and the two copies sit next to each other so the list stays sorted.
$valueList = ""
$written = 0
for ($b=0..4) {
  $placed = 0
  for ($d=0..9) {
    $left = 10 - $d
    $need = $cnt[$b] - $placed
    if ($need > 0) {
      $take = 0
      if ($need >= $left) { $take = 1 }
      if ($take == 0 && rand(0, 9) < 5) { $take = 1 }
      if ($take == 1) {
        $v = 10 * $b + $d
        if ($written == 0) { $valueList = "" . $v }
        if ($written > 0) { $valueList = $valueList . ", " . $v }
        $written = $written + 1
        $placed = $placed + 1
        if ($need > $left) {
          $valueList = $valueList . ", " . $v
          $written = $written + 1
          $placed = $placed + 1
        }
      }
    }
  }
}

$ab = rand(0, 4)
$askLo = 10 * $ab
$askHi = $askLo + 10
$askCount = $cnt[$ab]
$askRel = round($askCount / $n, 3)

$tallLo = 10 * $tallIdx
$tallHi = $tallLo + 10

$answer[0] = $askCount
$answerformat[0] = "integer"

$answer[1] = $tallLo
$answerformat[1] = "integer"

$answer[2] = $askRel
$reltolerance[2] = 0.02
$abstolerance[2] = 0.005

$questions[3] = array(
  "Left-skewed (the long tail points toward the smaller values)",
  "Right-skewed (the long tail points toward the larger values)",
  "Roughly symmetric"
)
$answer[3] = $shape
$noshuffle[3] = "all"

$shapeName = "roughly symmetric"
if ($shape == 0) { $shapeName = "left-skewed" }
if ($shape == 1) { $shapeName = "right-skewed" }

$shapeWhy = "the bars pile up at the top end and trail away toward the smaller values, so the tail points left"
if ($shape == 1) { $shapeWhy = "the bars pile up at the bottom end and trail away toward the larger values, so the tail points right" }
if ($shape == 2) { $shapeWhy = "the bars are roughly mirrored about the middle class, with no long tail either way" }

$askRelPct = round(100 * $askCount / $n, 1)

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
      <p><span class="term-label">Setting it up.</span> Group the ' . $n . ' values into classes ten wide, starting at 0. The five classes are 0&ndash;10, 10&ndash;20, 20&ndash;30, 30&ndash;40 and 40&ndash;50, each holding its left endpoint and not its right. Getting that boundary rule right is what makes everyone\'s histogram the same; a value of exactly 20 belongs to 20&ndash;30, not to 10&ndash;20.</p>
      <p><span class="term-label">Part (a): one class count.</span> The bar over ' . $askLo . '&ndash;' . $askHi . ' stands at <b>' . $askCount . '</b>. You can check it against the list without the tool: count the values from ' . $askLo . ' to ' . ($askHi - 1) . '.</p>
      <p><span class="term-label">Part (b): the tallest class.</span> The tallest bar starts at <b>' . $tallLo . '</b>, covering ' . $tallLo . '&ndash;' . $tallHi . ' ' . $unitWord . ' with ' . $maxCount . ' of the ' . $n . ' ' . $thing . '. Report where the bar sits on the horizontal axis, not how tall it is: the height is the count, the position is the measurement.</p>
      <p><span class="term-label">Part (c): relative frequency.</span> Divide that class count by the sample size: ' . $askCount . ' / ' . $n . ' &approx; <b>' . $askRel . '</b>, about ' . $askRelPct . '% of the ' . $thing . '. Drawn as a histogram, switching the vertical axis from frequency to relative frequency changes the numbers up the side but not the shape of the bars.</p>
      <p><span class="term-label">Part (d): the shape.</span> The distribution is <b>' . $shapeName . '</b>: ' . $shapeWhy . '. Skew is named for the tail, not for where the tall bars are, which is the reversal that catches most people.</p>
      <p><b>Answer:</b> (a) ' . $askCount . ' &nbsp;&nbsp; (b) ' . $tallLo . ' &nbsp;&nbsp; (c) ' . $askRel . ' &nbsp;&nbsp; (d) ' . $shapeName . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$intro Here are the $n measurements, in $unitWord.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:18px; margin:10px 0;">
    <p style="margin:0;">Group the data into classes <b>ten $unitWord wide, starting at 0</b>, so the classes are 0&ndash;10, 10&ndash;20, 20&ndash;30, 30&ndash;40 and 40&ndash;50. Each class holds its left endpoint and not its right, so a value of exactly 20 counts in 20&ndash;30.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> In a histogram of this data, how tall is the bar covering $askLo to $askHi $unitWord? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The tallest bar covers a class ten $unitWord wide. What number does that class <b>start</b> at? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the <b>relative frequency</b> of the class from $askLo to $askHi? (Give a decimal, accurate to at least 3 decimal places.) $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What is the <b>shape</b> of the distribution? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
