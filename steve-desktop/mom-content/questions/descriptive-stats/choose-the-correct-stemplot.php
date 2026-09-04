// === NAME - DESCRIPTION: Choose the Correct Stem-and-Leaf Plot - Four stemplots of one data set; three commit a classic construction error (repeats written once, leaves out of order, whole values as leaves) and one is right. Pick the correct plot, then name one plot's error ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

$ci = rand(0, 1)
$contexts = array(
  "the number of push-ups each student in a fitness class completed in two minutes",
  "the age, in years, of each adult singing in a community choir"
)
$unitWords = array("push-ups", "years")
$context = $contexts[$ci]
$unitWord = $unitWords[$ci]

// Three stems, walked digit by digit. Three forced digits (one from each third of 0-9)
// guarantee every row holds at least three leaves, which is what makes an out-of-order row
// visibly wrong rather than merely different.
$lo = rand(3, 6)
$hi = $lo + 2
$dupStem = rand($lo, $hi)

$cnt = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
$L = array()
$n = 0
$valueList = ""
$dupValue = 0

for ($s=$lo..$hi) {
  $f1 = rand(0, 2)
  $f2 = rand(3, 6)
  $f3 = rand(7, 9)
  for ($d=0..9) {
    $r = rand(0, 9)
    $take = 0
    if ($r < 2) { $take = 1 }
    if ($d == $f1) { $take = 1 }
    if ($d == $f2) { $take = 1 }
    if ($d == $f3) { $take = 1 }
    if ($take == 1) {
      $v = 10 * $s + $d
      $k = $cnt[$s]
      $ix = $s * 100 + $k
      $L[$ix] = $d
      $cnt[$s] = $cnt[$s] + 1
      $n = $n + 1
      if ($n == 1) { $valueList = "" . $v }
      if ($n > 1) { $valueList = $valueList . ", " . $v }
      // Exactly one value is recorded twice, so the plot that collapses repeats is short by one.
      if ($s == $dupStem && $d == $f2) {
        $k2 = $cnt[$s]
        $ix2 = $s * 100 + $k2
        $L[$ix2] = $d
        $cnt[$s] = $cnt[$s] + 1
        $n = $n + 1
        $valueList = $valueList . ", " . $v
        $dupValue = $v
      }
    }
  }
}

// Four bodies from one pass over the data.
//   A correct   B leaves rotated out of ascending order   C repeats collapsed   D whole values as leaves
$bodyA = ""
$bodyB = ""
$bodyC = ""
$bodyD = ""

$tdStem = '<td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center; font-weight:700; background:#f8fafc;">'
$tdLeaf = '<td style="border:1px solid #d1d5db; padding:5px 12px; font-family:ui-monospace,Menlo,Consolas,monospace; letter-spacing:1px; min-width:150px;">'

for ($s=$lo..$hi) {
  $c = $cnt[$s]
  $cm = $c - 1
  $rot = 1 + ($s % 2)
  $sA = ""
  $sB = ""
  $sC = ""
  $sD = ""
  $prev = 99
  for ($k=0..$cm) {
    $ix = $s * 100 + $k
    $d = $L[$ix]
    $jj = ($k + $rot) % $c
    $jx = $s * 100 + $jj
    $d2 = $L[$jx]
    $sA = $sA . $d . "&nbsp;&nbsp;"
    $sB = $sB . $d2 . "&nbsp;&nbsp;"
    if ($d != $prev) { $sC = $sC . $d . "&nbsp;&nbsp;" }
    $prev = $d
    $sD = $sD . (10 * $s + $d) . "&nbsp;&nbsp;"
  }
  $bodyA = $bodyA . '<tr>' . $tdStem . $s . '</td>' . $tdLeaf . $sA . '</td></tr>'
  $bodyB = $bodyB . '<tr>' . $tdStem . $s . '</td>' . $tdLeaf . $sB . '</td></tr>'
  $bodyC = $bodyC . '<tr>' . $tdStem . $s . '</td>' . $tdLeaf . $sC . '</td></tr>'
  $bodyD = $bodyD . '<tr>' . $tdStem . $s . '</td>' . $tdLeaf . $sD . '</td></tr>'
}

$tblOpen = '<table style="border-collapse:collapse; margin:6px 0;"><thead><tr style="background:#f0f4ff;"><th style="border:1px solid #d1d5db; padding:6px 12px;">Stem</th><th style="border:1px solid #d1d5db; padding:6px 12px; text-align:left;">Leaf</th></tr></thead><tbody>'
$tblClose = '</tbody></table>'

$plotA = $tblOpen . $bodyA . $tblClose
$plotB = $tblOpen . $bodyB . $tblClose
$plotC = $tblOpen . $bodyC . $tblClose
$plotD = $tblOpen . $bodyD . $tblClose

// Rotation, not shuffle(): shuffle() is rejected by the parser and kills the whole block.
$plots = array($plotA, $plotB, $plotC, $plotD)
$off = rand(0, 3)
$i1 = $off
$i2 = ($off + 1) % 4
$i3 = ($off + 2) % 4
$i4 = ($off + 3) % 4
$p1 = $plots[$i1]
$p2 = $plots[$i2]
$p3 = $plots[$i3]
$p4 = $plots[$i4]

// Variant v sits at display position (v - off + 4) % 4, numbered from 1.
$correctSlot = (4 - $off) % 4
$dupSlot = (6 - $off) % 4
$dupPlotNum = $dupSlot + 1

$questions[0] = array("Plot 1", "Plot 2", "Plot 3", "Plot 4")
$answer[0] = $correctSlot

$questions[1] = array(
  "A value that appears twice in the data has been written as a single leaf, so the plot shows fewer measurements than were collected.",
  "The leaves in each row are not in order from smallest to largest.",
  "Each leaf holds a whole data value instead of just its last digit.",
  "A stem row that should appear in the plot has been left out."
)
$answer[1] = 0

$exampleValue = 10 * $lo + 4

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
      <p><span class="term-label">Step 1: check the leaves are single digits.</span> The stem carries everything except the last digit, so a leaf is always one digit. One of the four plots writes whole values such as ' . $exampleValue . ' in the leaf column; that plot is really just the sorted list with extra lines round it, and a reader cannot tell where one value ends and the next begins.</p>
      <p><span class="term-label">Step 2: check the leaves are in order.</span> Leaves run smallest to largest across each row. That ordering is what lets a reader find the median or the largest value in a row by looking rather than searching, so a row out of order is wrong even though it holds the right digits.</p>
      <p><span class="term-label">Step 3: count the leaves against the data.</span> There are <b>' . $n . '</b> measurements, so a correct plot has ' . $n . ' leaves. The value ' . $dupValue . ' occurs twice in the list and must be written twice: one plot writes it once and so shows only ' . ($n - 1) . ' leaves. This is the error that is easiest to miss, because the plot looks perfectly tidy.</p>
      <p><span class="term-label">Step 4: the one that survives.</span> <b>Plot ' . ($correctSlot + 1) . '</b> passes all three checks: single-digit leaves, ascending within each row, and ' . $n . ' leaves in total.</p>
      <p><b>Answer:</b> (a) Plot ' . ($correctSlot + 1) . ' &nbsp;&nbsp; (b) Plot ' . $dupPlotNum . ' writes a repeated value only once</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">A student recorded $context. The $n measurements are listed below from lowest to highest.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
    <p style="margin:12px 0 0 0;">Four classmates each drew a stem-and-leaf plot of this data, using the tens digit as the stem and the ones digit as the leaf. Only one of them did it correctly.</p>
  </div>
  <div style="display:flex; flex-wrap:wrap; gap:14px; margin:10px 0;">
    <div style="flex:1 1 260px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
      <p style="margin:0 0 6px 0; font-weight:700; color:#1865f2;">Plot 1</p>
      $p1
    </div>
    <div style="flex:1 1 260px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
      <p style="margin:0 0 6px 0; font-weight:700; color:#1865f2;">Plot 2</p>
      $p2
    </div>
    <div style="flex:1 1 260px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
      <p style="margin:0 0 6px 0; font-weight:700; color:#1865f2;">Plot 3</p>
      $p3
    </div>
    <div style="flex:1 1 260px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
      <p style="margin:0 0 6px 0; font-weight:700; color:#1865f2;">Plot 4</p>
      $p4
    </div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which plot displays the data correctly? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is wrong with Plot $dupPlotNum? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
