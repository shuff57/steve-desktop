// === NAME - DESCRIPTION: Choose the Honest Bar Graph - Pick the one bar graph that displays a randomized category table fairly from four versions, three of which commit a classic display error, then name the error in a specified version ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

$si = rand(0, 1)
if ($si == 0) {
  $intro = "The students in one math class have birthdays in each of the four seasons. The teacher recorded how many students fall in each season."
  $colLabel = "Season"
  $c0 = "Spring"
  $c1 = "Summer"
  $c2 = "Autumn"
  $c3 = "Winter"
  $countLabel = "Number of students"
}
else {
  $intro = "A county science competition drew entrants from four high schools. The organiser recorded how many students each school sent."
  $colLabel = "High school"
  $c0 = "Alabaster"
  $c1 = "Concordia"
  $c2 = "Genoa"
  $c3 = "Mocksville"
  $countLabel = "Number of entrants"
}

// Values close enough together that a truncated axis changes the visual story completely.
$v0 = rand(18, 24)
$v1 = $v0 + rand(1, 4)
$v2 = $v1 + rand(1, 4)
$v3 = $v0 - rand(1, 5)

$vals = array($v0, $v1, $v2, $v3)
$labels = array($c0, $c1, $c2, $c3)

$maxV = $v0
for ($i=1..3) {
  if ($vals[$i] > $maxV) {
    $maxV = $vals[$i]
  }
}
$minV = $v0
for ($i=1..3) {
  if ($vals[$i] < $minV) {
    $minV = $vals[$i]
  }
}
$topScale = 5 * ceil($maxV / 5)

$plotLeft = 42
$plotBottom = 128
$plotTop = 14
$plotH = $plotBottom - $plotTop
$barW = 30
$gap = 14

// --- Graph A: correct. Zero baseline, equal widths, numbered axis. ---
$svgA = ""
for ($i=0..3) {
  $x = $plotLeft + 8 + $i * ($barW + $gap)
  $h = round($vals[$i] / $topScale * $plotH, 1)
  $y = round($plotBottom - $h, 1)
  $svgA = $svgA . '<rect x="' . $x . '" y="' . $y . '" width="' . $barW . '" height="' . $h . '" fill="#1865f2"/><text x="' . ($x + $barW / 2) . '" y="' . ($plotBottom + 13) . '" font-size="9" text-anchor="middle" fill="#444">' . $labels[$i] . '</text>'
}

// --- Graph B: vertical axis truncated, so small gaps look enormous. ---
$base = $minV - 2
$svgB = ""
for ($i=0..3) {
  $x = $plotLeft + 8 + $i * ($barW + $gap)
  $h = round(($vals[$i] - $base) / ($topScale - $base) * $plotH, 1)
  $y = round($plotBottom - $h, 1)
  $svgB = $svgB . '<rect x="' . $x . '" y="' . $y . '" width="' . $barW . '" height="' . $h . '" fill="#e8590c"/><text x="' . ($x + $barW / 2) . '" y="' . ($plotBottom + 13) . '" font-size="9" text-anchor="middle" fill="#444">' . $labels[$i] . '</text>'
}

// --- Graph C: bar WIDTH grows with the count as well as the height, so area double-counts. ---
$svgC = ""
$cx = $plotLeft + 4
for ($i=0..3) {
  $h = round($vals[$i] / $topScale * $plotH, 1)
  $w = round(8 + $vals[$i] / $topScale * 44, 1)
  $y = round($plotBottom - $h, 1)
  $svgC = $svgC . '<rect x="' . $cx . '" y="' . $y . '" width="' . $w . '" height="' . $h . '" fill="#7048e8"/><text x="' . ($cx + $w / 2) . '" y="' . ($plotBottom + 13) . '" font-size="9" text-anchor="middle" fill="#444">' . $labels[$i] . '</text>'
  $cx = $cx + $w + 4
}

// --- Graph D: the smallest category is simply left off. ---
$svgD = ""
for ($i=0..2) {
  $x = $plotLeft + 8 + $i * ($barW + $gap)
  $h = round($vals[$i] / $topScale * $plotH, 1)
  $y = round($plotBottom - $h, 1)
  $svgD = $svgD . '<rect x="' . $x . '" y="' . $y . '" width="' . $barW . '" height="' . $h . '" fill="#0ca678"/><text x="' . ($x + $barW / 2) . '" y="' . ($plotBottom + 13) . '" font-size="9" text-anchor="middle" fill="#444">' . $labels[$i] . '</text>'
}

// Axis furniture. Graph B is drawn with its own baseline label so the truncation is honest
// to spot rather than hidden: the student is meant to catch it by reading the axis.
// A bar graph with no name on its vertical axis is unreadable: the student can see a height but
// not what is being counted. $countLabel already exists for the prose; draw it on the chart too.
$midY = ($plotTop + $plotBottom) / 2
$yTitle = '<text x="11" y="' . $midY . '" font-size="8" fill="#444" text-anchor="middle" transform="rotate(-90 11 ' . $midY . ')">' . $countLabel . '</text>'

$axisA = '<line x1="' . $plotLeft . '" y1="' . $plotTop . '" x2="' . $plotLeft . '" y2="' . $plotBottom . '" stroke="#555"/><line x1="' . $plotLeft . '" y1="' . $plotBottom . '" x2="228" y2="' . $plotBottom . '" stroke="#555"/><text x="' . ($plotLeft - 6) . '" y="' . ($plotBottom + 3) . '" font-size="9" text-anchor="end" fill="#444">0</text><text x="' . ($plotLeft - 6) . '" y="' . ($plotTop + 8) . '" font-size="9" text-anchor="end" fill="#444">' . $topScale . '</text>' . $yTitle
$axisB = '<line x1="' . $plotLeft . '" y1="' . $plotTop . '" x2="' . $plotLeft . '" y2="' . $plotBottom . '" stroke="#555"/><line x1="' . $plotLeft . '" y1="' . $plotBottom . '" x2="228" y2="' . $plotBottom . '" stroke="#555"/><text x="' . ($plotLeft - 6) . '" y="' . ($plotBottom + 3) . '" font-size="9" text-anchor="end" fill="#444">' . $base . '</text><text x="' . ($plotLeft - 6) . '" y="' . ($plotTop + 8) . '" font-size="9" text-anchor="end" fill="#444">' . $topScale . '</text>' . $yTitle

$openSvg = '<svg viewBox="0 0 240 150" width="240" height="150" style="background:#fbfbfd; border:1px solid #e5e7eb; border-radius:8px;">'
$chartA = $openSvg . $axisA . $svgA . '</svg>'
$chartB = $openSvg . $axisB . $svgB . '</svg>'
$chartC = $openSvg . $axisA . $svgC . '</svg>'
$chartD = $openSvg . $axisA . $svgD . '</svg>'

// Rotate which chart is shown in which slot so the correct one is not always first.
// shuffle() is rejected by the parser, so a rotation offset does the job.
$charts = array($chartA, $chartB, $chartC, $chartD)
$off = rand(0, 3)
$g1 = $charts[$off]
$g2 = $charts[($off + 1) % 4]
$g3 = $charts[($off + 2) % 4]
$g4 = $charts[($off + 3) % 4]

// Chart A sits at slot (4 - off) % 4, zero-based.
$correctSlot = (4 - $off) % 4
$slotNames = array("Graph 1", "Graph 2", "Graph 3", "Graph 4")
$correctName = $slotNames[$correctSlot]

$questions[0] = array("Graph 1", "Graph 2", "Graph 3", "Graph 4")
$answer[0] = $correctSlot

// Part b asks about the truncated-axis chart, which is chart index 1 -> slot (5 - off) % 4.
$truncSlot = (5 - $off) % 4
$truncName = $slotNames[$truncSlot]

$questions[1] = array(
  "Its vertical axis does not start at zero, so a small difference between the categories is stretched into a large-looking one.",
  "It uses the wrong color for the bars, which makes the categories hard to tell apart.",
  "Its bars are too narrow to read the values accurately.",
  "It shows the categories in the wrong order, so the comparison cannot be made."
)
$answer[1] = 0

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
      <p><span class="term-label">What an honest bar graph has to do.</span> Bars of equal width, one per category, all measured from a zero baseline against one numbered scale. Then bar height is proportional to the count, and comparing bars by eye is the same as comparing the numbers.</p>
      <p><span class="term-label">Check each graph against that.</span></p>
      <ul style="margin:0 0 10px 1.2em;">
        <li>One graph starts its vertical axis at ' . $base . ' instead of 0. The counts run from ' . $minV . ' to ' . $maxV . ', a spread of only ' . ($maxV - $minV) . ', but cutting the axis makes the shortest bar almost vanish next to the tallest. This is the most common way a real bar graph misleads.</li>
        <li>One graph lets each bar get <i>wider</i> as it gets taller. The eye compares areas, not heights, so a category with ' . $maxV . ' looks several times bigger than one with ' . $minV . ' instead of slightly bigger.</li>
        <li>One graph leaves a category out entirely. Whatever is left cannot be read as a breakdown of the whole group any more.</li>
        <li>One graph does none of these. That is <b>' . $correctName . '</b>.</li>
      </ul>
      <p><span class="term-label">Part (b).</span> ' . $truncName . ' is the one with the cut axis: its baseline is labeled ' . $base . ', not 0. The fix is to redraw it from zero, which shrinks the apparent gap back to the real one.</p>
      <p><b>Answer:</b> (a) ' . $correctName . ' &nbsp;&nbsp; (b) the vertical axis does not start at zero.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro</p>
    <table style="border-collapse:collapse; margin:6px 0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:7px 14px;">$colLabel</th>
          <th style="border:1px solid #d1d5db; padding:7px 14px;">$countLabel</th>
        </tr>
      </thead>
      <tbody>
        <tr><td style="border:1px solid #d1d5db; padding:6px 14px;">$c0</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$v0</td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:6px 14px;">$c1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$v1</td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:6px 14px;">$c2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$v2</td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:6px 14px;">$c3</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$v3</td></tr>
      </tbody>
    </table>
    <p style="margin:10px 0 0 0;">Four people each drew a bar graph of this table. Three of them made a mistake.</p>
  </div>
  <div style="display:flex; flex-wrap:wrap; gap:14px; margin:10px 0;">
    <div style="text-align:center;"><div style="font-weight:700; margin-bottom:4px;">Graph 1</div>$g1</div>
    <div style="text-align:center;"><div style="font-weight:700; margin-bottom:4px;">Graph 2</div>$g2</div>
    <div style="text-align:center;"><div style="font-weight:700; margin-bottom:4px;">Graph 3</div>$g3</div>
    <div style="text-align:center;"><div style="font-weight:700; margin-bottom:4px;">Graph 4</div>$g4</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which graph displays the table fairly? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is wrong with $truncName? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
