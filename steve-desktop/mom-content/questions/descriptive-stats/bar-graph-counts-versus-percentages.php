// === NAME - DESCRIPTION: Bar Graph of Counts Versus Percentages - The same category table is drawn twice, once as counts and once as percentages; recover the group size, compute one category's percentage, and say why changing to percentages leaves the shape of the graph alone ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "The students in one math class have birthdays in each of the four seasons. Their teacher recorded how many fall in each season."
  $colLabel = "Season"
  $c0 = "Spring"
  $c1 = "Summer"
  $c2 = "Autumn"
  $c3 = "Winter"
  $unitWord = "students"
}
else {
  $intro = "A campus bike shop recorded which of four repair jobs each customer came in for last month."
  $colLabel = "Repair"
  $c0 = "Flat tyre"
  $c1 = "Brakes"
  $c2 = "Gears"
  $c3 = "Chain"
  $unitWord = "customers"
}

$labels = array($c0, $c1, $c2, $c3)
$cts = array(0, 0, 0, 0)
$n = 0
for ($k=0..3) {
  $cts[$k] = rand(6, 15)
  $n = $n + $cts[$k]
}

$pcts = array(0, 0, 0, 0)
$maxC = 0
$maxP = 0
$tableRows = ""
for ($k=0..3) {
  $pcts[$k] = round(100 * $cts[$k] / $n, 1)
  if ($cts[$k] > $maxC) { $maxC = $cts[$k] }
  if ($pcts[$k] > $maxP) { $maxP = $pcts[$k] }
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 16px;">' . $labels[$k] . '</td><td style="border:1px solid #d1d5db; padding:6px 16px; text-align:center;">' . $cts[$k] . '</td></tr>'
}

$ai = rand(0, 3)
$askLabel = $labels[$ai]
$askCount = $cts[$ai]
$askPct = $pcts[$ai]

$answer[0] = $n
$answerformat[0] = "integer"

$answer[1] = $askPct
$reltolerance[1] = 0.02
$abstolerance[1] = 0.15

$questions[2] = array(
  "Identical, because every count was divided by the same total. Only the numbers on the vertical axis change.",
  "Different, because dividing by the total changes which category is largest.",
  "Different, because percentages always even out the differences between categories.",
  "Identical, but only because these four counts happen to add to a round number."
)
$answer[2] = 0

// Both graphs are built in one loop: 0 counts, 1 percentages. Bars are drawn with a gap between
// them because the horizontal axis holds categories, not numbers -- that gap is the visible
// difference between a bar graph and a histogram.
$topC = $maxC + 2
if ($topC % 2 == 1) { $topC = $topC + 1 }
$topP = round($maxP / 10 + 0.5, 0) * 10

$sv = array("", "")
for ($vv=0..1) {
  $top = $topC
  $step = 2
  $axisName = "Number of " . $unitWord
  if ($vv == 1) {
    $top = $topP
    $step = 10
    $axisName = "Percent of " . $unitWord
  }
  $gN = $top / $step
  $unitPx = 190 / $top

  $grid = ""
  for ($g=0..$gN) {
    $v = $step * $g
    $gy = round(225 - $v * $unitPx, 2)
    $grid = $grid . '<line x1="58" y1="' . $gy . '" x2="430" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
    $grid = $grid . '<text x="52" y="' . ($gy + 4) . '" font-size="12" fill="#6b7280" text-anchor="end">' . $v . '</text>'
  }

  $bars = ""
  for ($k=0..3) {
    $val = $cts[$k]
    if ($vv == 1) { $val = $pcts[$k] }
    $bx = 70 + $k * 92
    $bh = round($val * $unitPx, 2)
    $by = round(225 - $bh, 2)
    $bars = $bars . '<rect x="' . $bx . '" y="' . $by . '" width="60" height="' . $bh . '" fill="#93c5fd" stroke="#1e40af" stroke-width="1.5"/>'
    $bars = $bars . '<text x="' . ($bx + 30) . '" y="245" font-size="12" fill="#374151" text-anchor="middle">' . $labels[$k] . '</text>'
  }

  $one = '<svg viewBox="0 0 450 285" width="100%" style="max-width:450px; display:block; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
  $one = $one . $grid . $bars
  $one = $one . '<line x1="58" y1="25" x2="58" y2="225" stroke="#374151" stroke-width="2"/><line x1="58" y1="225" x2="430" y2="225" stroke="#374151" stroke-width="2"/>'
  $one = $one . '<text x="244" y="272" font-size="13" fill="#374151" text-anchor="middle">' . $axisName . '</text>'
  $one = $one . '</svg>'
  $sv[$vv] = $one
}

$countGraph = $sv[0]
$pctGraph = $sv[1]

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
      <p><span class="term-label">Step 1 &mdash; the group size.</span> Add the four bars on the count graph: ' . $cts[0] . ' + ' . $cts[1] . ' + ' . $cts[2] . ' + ' . $cts[3] . ' = <b>' . $n . '</b> ' . $unitWord . '. Every percentage in this question is a percentage of that number.</p>
      <p><span class="term-label">Step 2 &mdash; one percentage.</span> ' . $askLabel . ' has ' . $askCount . ' of the ' . $n . '. A percentage is the part over the whole, times 100: 100 &times; ' . $askCount . ' / ' . $n . ' &approx; <b>' . $askPct . '%</b>. Dividing by the number of categories instead of the number of ' . $unitWord . ' is the usual slip.</p>
      <p><span class="term-label">Step 3 &mdash; why the two graphs look the same.</span> Every count was divided by the same total, ' . $n . ', and multiplied by the same 100. That is a change of units, like measuring in inches instead of feet: each bar shrinks by the same factor, so the tallest bar stays tallest and every ratio between bars survives. Only the numbers up the side change.</p>
      <p><span class="term-label">So which graph should you use?</span> Counts when the size of the group matters, percentages when you want to compare this group against another of a different size. Neither is more honest &mdash; but a percentage with no group size attached hides how few ' . $unitWord . ' it might stand for.</p>
      <p><b>Answer:</b> (a) ' . $n . ' &nbsp;&nbsp; (b) ' . $askPct . '%</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro</p>
    <table style="border-collapse:collapse; margin:0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:7px 16px; text-align:left;">$colLabel</th>
          <th style="border:1px solid #d1d5db; padding:7px 16px;">Number of $unitWord</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
    <p style="margin:14px 0 0 0;">The same data is drawn twice below &mdash; once as counts, once as percentages.</p>
  </div>
  <div style="display:flex; flex-wrap:wrap; gap:14px; margin:10px 0;">
    <div style="flex:1 1 300px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:12px;">
      <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">Graph A &mdash; counts</p>
      $countGraph
    </div>
    <div style="flex:1 1 300px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:12px;">
      <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">Graph B &mdash; percentages</p>
      $pctGraph
    </div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many $unitWord are in the group altogether? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What percent of the group is <b>$askLabel</b>? (Give your answer to one decimal place, without the percent sign.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How do the shapes of Graph A and Graph B compare, and why? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
