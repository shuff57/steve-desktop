// === NAME - DESCRIPTION: Choose the Correct Line Graph - Four line graphs of one frequency table; three commit a classic error (running totals plotted as frequencies, an axis not starting at zero, frequencies on the wrong values) and one is right ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

$ci = rand(0, 1)
$contexts = array(
  "how many TV shows they watch on a typical day",
  "how many times they ate out in the past week"
)
$xLabels = array("Number of TV shows", "Times eating out")
$context = $contexts[$ci]
$xLabel = $xLabels[$ci]

// Five whole-number categories starting at 0. Frequencies are kept above 3 so a truncated axis
// has somewhere to start, and one is pushed clear of the rest so the shape has a definite peak.
$f = array(0, 0, 0, 0, 0)
$maxOther = 0
for ($k=0..4) {
  $f[$k] = rand(4, 11)
  if ($f[$k] > $maxOther) { $maxOther = $f[$k] }
}
$pk = rand(1, 3)
$f[$pk] = $maxOther + rand(2, 4)

$n = 0
$minF = 99
$maxF = 0
$cum = array(0, 0, 0, 0, 0)
$tableRows = ""
for ($k=0..4) {
  $n = $n + $f[$k]
  $cum[$k] = $n
  if ($f[$k] < $minF) { $minF = $f[$k] }
  if ($f[$k] > $maxF) { $maxF = $f[$k] }
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:5px 16px; text-align:center;">' . $k . '</td><td style="border:1px solid #d1d5db; padding:5px 16px; text-align:center;">' . $f[$k] . '</td></tr>'
}

// Four graphs from one loop.
//   0 correct   1 running totals   2 vertical axis not starting at zero   3 frequencies shifted onto the wrong values
$sv = array("", "", "", "")
for ($vv=0..3) {
  $base = 0
  $top = $maxF + 2
  if ($vv == 1) { $top = $n + 2 }
  if ($vv == 2) {
    $base = $minF - 1
    $top = $maxF + 1
  }

  $span = $top - $base
  $step = 1
  if ($span > 10) { $step = 2 }
  if ($span > 24) { $step = 5 }
  $rem = $span % $step
  if ($rem > 0) {
    $top = $top + $step - $rem
    $span = $top - $base
  }
  $gN = $span / $step
  $unitPx = 200 / $span

  $grid = ""
  for ($g=0..$gN) {
    $v = $base + $step * $g
    $gy = round(235 - ($v - $base) * $unitPx, 2)
    $grid = $grid . '<line x1="50" y1="' . $gy . '" x2="438" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
    $grid = $grid . '<text x="44" y="' . ($gy + 4) . '" font-size="12" fill="#6b7280" text-anchor="end">' . $v . '</text>'
  }

  $pts = ""
  $dots = ""
  $xlab = ""
  for ($k=0..4) {
    $val = $f[$k]
    if ($vv == 1) { $val = $cum[$k] }
    if ($vv == 3) {
      $sh = ($k + 2) % 5
      $val = $f[$sh]
    }
    $cx = 50 + $k * 97
    $cy = round(235 - ($val - $base) * $unitPx, 2)
    if ($k == 0) { $pts = $cx . "," . $cy }
    if ($k > 0) { $pts = $pts . " " . $cx . "," . $cy }
    $dots = $dots . '<circle cx="' . $cx . '" cy="' . $cy . '" r="4" fill="#1e40af"/>'
    $xlab = $xlab . '<line x1="' . $cx . '" y1="235" x2="' . $cx . '" y2="240" stroke="#374151" stroke-width="1"/>'
    $xlab = $xlab . '<text x="' . $cx . '" y="256" font-size="12" fill="#374151" text-anchor="middle">' . $k . '</text>'
  }

  $one = '<svg viewBox="0 0 470 295" width="100%" style="max-width:470px; display:block; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
  $one = $one . $grid
  $one = $one . '<polyline points="' . $pts . '" fill="none" stroke="#1e40af" stroke-width="2.5"/>'
  $one = $one . $dots
  $one = $one . '<line x1="50" y1="35" x2="50" y2="235" stroke="#374151" stroke-width="2"/><line x1="50" y1="235" x2="438" y2="235" stroke="#374151" stroke-width="2"/>'
  $one = $one . $xlab
  $one = $one . '<text x="244" y="280" font-size="12" fill="#374151" text-anchor="middle">' . $xLabel . '</text>'
  $one = $one . '<text x="14" y="135" font-size="12" fill="#374151" text-anchor="middle" transform="rotate(-90 14 135)">Frequency</text>'
  $one = $one . '</svg>'
  $sv[$vv] = $one
}

// Rotation decides which graph lands in which slot; shuffle() is rejected by the parser.
$off = rand(0, 3)
$i1 = $off
$i2 = ($off + 1) % 4
$i3 = ($off + 2) % 4
$i4 = ($off + 3) % 4
$g1 = $sv[$i1]
$g2 = $sv[$i2]
$g3 = $sv[$i3]
$g4 = $sv[$i4]

// Variant v is displayed at position (v - off + 4) % 4, numbered from 1.
$correctSlot = (4 - $off) % 4
$cumSlot = (5 - $off) % 4
$cumGraphNum = $cumSlot + 1

$questions[0] = array("Graph 1", "Graph 2", "Graph 3", "Graph 4")
$answer[0] = $correctSlot

$questions[1] = array(
  "It plots the running total up to each value instead of the frequency of that value, so the line can only climb.",
  "Its vertical axis does not start at zero, which exaggerates the differences between the values.",
  "Its frequencies have been attached to the wrong values along the horizontal axis.",
  "It joins the points with straight segments, which should never be done for count data."
)
$answer[1] = 0

$lastCum = $cum[4]

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
      <p><span class="term-label">Check 1 &mdash; does the line ever come back down?</span> The frequencies are ' . $f[0] . ', ' . $f[1] . ', ' . $f[2] . ', ' . $f[3] . ', ' . $f[4] . ', so no point should sit above ' . $maxF . '. One graph finishes at ' . $lastCum . ' &mdash; not a frequency at all, but the total number of people surveyed. It is plotting the running total up to each value, which is why it only ever climbs.</p>
      <p><span class="term-label">Check 2 &mdash; where does the vertical axis start?</span> Read the bottom label on each axis, not the shape of the line. One graph starts its axis at ' . ($minF - 1) . ' rather than 0. The data is unchanged, but the smallest value is squashed to nothing and the gaps look far larger than they are.</p>
      <p><span class="term-label">Check 3 &mdash; do the heights match the table?</span> Take one value and check it. The table gives ' . $f[0] . ' for the value 0; one graph shows ' . $f[2] . ' there instead, because its frequencies have been shifted along and attached to the wrong values. Nothing about that graph looks wrong on its own &mdash; the only way to catch it is to read it against the table.</p>
      <p><span class="term-label">And the one that survives.</span> <b>Graph ' . ($correctSlot + 1) . '</b> starts its vertical axis at zero, matches the table value for value, and rises and falls as the frequencies do.</p>
      <p><b>Answer:</b> (a) Graph ' . ($correctSlot + 1) . ' &nbsp;&nbsp; (b) Graph ' . $cumGraphNum . ' plots running totals</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A group of people were asked $context. Their answers are in the table below.</p>
    <table style="border-collapse:collapse; margin:0 0 4px 0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:7px 16px;">$xLabel</th>
          <th style="border:1px solid #d1d5db; padding:7px 16px;">Frequency</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
    <p style="margin:12px 0 0 0;">Four students each drew a line graph of this table. Only one of them did it correctly.</p>
  </div>
  <div style="display:flex; flex-wrap:wrap; gap:14px; margin:10px 0;">
    <div style="flex:1 1 300px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:12px;">
      <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">Graph 1</p>
      $g1
    </div>
    <div style="flex:1 1 300px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:12px;">
      <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">Graph 2</p>
      $g2
    </div>
    <div style="flex:1 1 300px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:12px;">
      <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">Graph 3</p>
      $g3
    </div>
    <div style="flex:1 1 300px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:12px;">
      <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">Graph 4</p>
      $g4
    </div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which graph displays the table correctly? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is wrong with Graph $cumGraphNum? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
