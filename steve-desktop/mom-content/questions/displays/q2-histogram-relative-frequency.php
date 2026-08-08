// === NAME - DESCRIPTION: Histogram - Relative Frequency and Shape - Read a class count off a drawn histogram, turn it into a relative frequency, and identify the shape of the distribution ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "choices")

// Five classes. Counts are all EVEN so every bar top lands exactly on a gridline -- the student
// has to read the graph, but the value read off it is exact, so part (a) can be graded exactly.
// Format: [counts, class asked about, shape code]. Shape codes: 0 left-skewed, 1 right-skewed, 2 symmetric.
$cases = array(
  array(array(2, 6, 14, 10, 4), 3, 2),
  array(array(2, 4, 10, 14, 12), 2, 0),
  array(array(14, 12, 8, 4, 2), 1, 1),
  array(array(6, 14, 18, 16, 6), 0, 2),
  array(array(2, 4, 6, 12, 18), 4, 0)
)

$pick = $cases[rand(0, count($cases)-1)]
$counts = $pick[0]
$binIdx = $pick[1]
$shapeIdx = $pick[2]
$n = $counts[0] + $counts[1] + $counts[2] + $counts[3] + $counts[4]
$binCount = $counts[$binIdx]
$relFreq = $binCount / $n

$answer[0] = $relFreq
$reltolerance[0] = 0.02
$abstolerance[0] = 0.005
$answer[1] = $shapeIdx

$choices[1] = array(
  "Left-skewed (long tail on the left, mean &lt; median)",
  "Right-skewed (long tail on the right, mean > median)",
  "Roughly symmetric"
)
$noshuffle[1] = "all"

$binLo = 10 * $binIdx
$binHi = $binLo + 10
$label = $binLo . " to " . $binHi

// Two histograms are built in one pass: a plain one for the question and a second with the
// class in question picked out in orange for the solution. The solution should show the same
// graph again with the answer marked on it, not describe it in words.
$axisTop = 20
$plotL = 55
$plotB = 260
$plotT = 25
$unitPx = ($plotB - $plotT) / $axisTop
$barW = 80

$grid = ""
for ($g=0..10) {
  $v = 2 * $g
  $gy = round($plotB - $v * $unitPx, 2)
  $grid = $grid . '<line x1="55" y1="' . $gy . '" x2="455" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $grid = $grid . '<text x="48" y="' . ($gy + 4) . '" font-size="11" fill="#6b7280" text-anchor="end">' . $v . '</text>'
}

$xlabels = ""
for ($k=0..5) {
  $xp = $plotL + $k * $barW
  $xlabels = $xlabels . '<line x1="' . $xp . '" y1="260" x2="' . $xp . '" y2="265" stroke="#374151" stroke-width="1"/>'
  $xlabels = $xlabels . '<text x="' . $xp . '" y="280" font-size="12" fill="#374151" text-anchor="middle">' . (10 * $k) . '</text>'
}

$barsQ = ""
$barsSol = ""
for ($k=0..4) {
  $bx = $plotL + $k * $barW
  $bh = round($counts[$k] * $unitPx, 2)
  $by = round($plotB - $bh, 2)
  // Bars sit edge to edge with no gap between them -- that is what makes this a histogram
  // rather than a bar graph, and it is why the horizontal axis is a number line.
  $barsQ = $barsQ . '<rect x="' . $bx . '" y="' . $by . '" width="' . $barW . '" height="' . $bh . '" fill="#93c5fd" stroke="#1e40af" stroke-width="1.5"/>'
  $fill = "#93c5fd"
  if ($k == $binIdx) { $fill = "#fbbf24" }
  $barsSol = $barsSol . '<rect x="' . $bx . '" y="' . $by . '" width="' . $barW . '" height="' . $bh . '" fill="' . $fill . '" stroke="#1e40af" stroke-width="1.5"/>'
}

$svgOpen = '<svg viewBox="0 0 500 320" width="100%" style="max-width:500px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$axes = '<line x1="55" y1="25" x2="55" y2="260" stroke="#374151" stroke-width="2"/><line x1="55" y1="260" x2="455" y2="260" stroke="#374151" stroke-width="2"/><text x="255" y="303" font-size="13" fill="#374151" text-anchor="middle">Value</text><text x="16" y="145" font-size="13" fill="#374151" text-anchor="middle" transform="rotate(-90 16 145)">Frequency</text>'

$histQ = $svgOpen . $grid . $barsQ . $axes . $xlabels . '</svg>'
$histSol = $svgOpen . $grid . $barsSol . $axes . $xlabels . '</svg>'

$shapeWhy = array(
  "the bars pile up on the right and trail off to the left, so the tail points toward the smaller values",
  "the bars pile up on the left and trail off to the right, so the tail points toward the larger values",
  "the bars are roughly mirrored about the middle class, with no long tail on either side"
)
$why = $shapeWhy[$shapeIdx]

$shapeName = "roughly symmetric"
if ($shapeIdx == 0) { $shapeName = "left-skewed" }
if ($shapeIdx == 1) { $shapeName = "right-skewed" }

$relRounded = round($relFreq, 3)
$relPercent = round(100 * $relFreq, 1)

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
      <p><span class="term-label">Step 1 &mdash; read the count off the graph.</span> Find the bar running from ' . $binLo . ' to ' . $binHi . ' on the horizontal axis, then follow its top across to the frequency axis. It lands on <b>' . $binCount . '</b>.</p>
      ' . $histSol . '
      <p><span class="term-label">Step 2 &mdash; find the total.</span> Add the heights of all five bars: ' . $counts[0] . ' + ' . $counts[1] . ' + ' . $counts[2] . ' + ' . $counts[3] . ' + ' . $counts[4] . ' = <b>' . $n . '</b> observations.</p>
      <p><span class="term-label">Step 3 &mdash; divide.</span> Relative frequency is the class count over the total, not over the number of classes: ' . $binCount . ' / ' . $n . ' &approx; <b>' . $relRounded . '</b>, or about ' . $relPercent . '% of the data.</p>
      <p><span class="term-label">Step 4 &mdash; read the shape.</span> Shape is about which way the tail points, so look along the tops of the bars: ' . $why . '. The distribution is <b>' . $shapeName . '</b>. Skew is named for the tail, not for where the peak sits &mdash; that is the reversal students most often get caught by.</p>
      <p><b>Answer:</b> (a) ' . $relRounded . ' &nbsp;&nbsp; (b) ' . $shapeName . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">The histogram below displays a set of measurements. Each class includes its left endpoint and excludes its right, so the bar drawn from $binLo to $binHi counts every value that is at least $binLo but less than $binHi.</p>
    $histQ
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>relative frequency</b> of the class from <b>$label</b>. (Give a decimal, accurate to at least 3 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Describe the <b>shape</b> of the distribution. $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
