// === NAME - DESCRIPTION: Describe a Distribution - Shape, Center and Spread - Work through the three things a distribution is always described by, reading each one off the same drawn histogram, and choose the center that matches the shape you named ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The capstone for 2.6. Shape, center and spread is the checklist every free-response answer about a
// distribution is graded against, and students lose credit for naming one of the three and stopping.
// Here all three are asked about the SAME picture, in that order, and the last part makes the choice
// of center depend on the shape they named in the first: which is the link the checklist exists to
// build.
//
// The class holding the median is found by walking the cumulative count, so it is computed from the
// drawn bars rather than assumed, and the shape drives both the bars and the key.
$anstypes = array("choices", "number", "number", "choices")

$dir = rand(0, 1)

$axisTop = 24
$binWidth = 10
$barCounts = array(0, 0, 0, 0, 0, 0, 0, 0)

if ($dir == 0) {
  // Skewed RIGHT.
  $barCounts[0] = 2 * rand(5, 7)
  $barCounts[1] = 2 * rand(9, 11)
  $barCounts[2] = 2 * rand(6, 8)
  $barCounts[3] = 2 * rand(3, 5)
  $barCounts[4] = 2 * rand(2, 3)
  $barCounts[5] = 2 * rand(1, 2)
  $barCounts[6] = 2 * rand(1, 2)
  $barCounts[7] = 2 * rand(1, 2)
}
else {
  // Skewed LEFT.
  $barCounts[0] = 2 * rand(1, 2)
  $barCounts[1] = 2 * rand(1, 2)
  $barCounts[2] = 2 * rand(1, 2)
  $barCounts[3] = 2 * rand(2, 3)
  $barCounts[4] = 2 * rand(3, 5)
  $barCounts[5] = 2 * rand(6, 8)
  $barCounts[6] = 2 * rand(9, 11)
  $barCounts[7] = 2 * rand(5, 7)
}

$n = 0
for ($i=0..7) {
  $n = $n + $barCounts[$i]
}

// Which class holds the median: walk the cumulative count to the halfway observation.
$half = $n / 2
$run = 0
$medClass = 0
$found = 0
for ($i=0..7) {
  $run = $run + $barCounts[$i]
  if ($found == 0 && $run >= $half) {
    $medClass = $i
    $found = 1
  }
}
$medLo = $medClass * $binWidth
$medHi = $medLo + $binWidth

// Spread is reported as the range the data covers: first non-empty class start to last class end.
$rangeVal = 8 * $binWidth

$trueName = "skewed right"
$tailWord = "the RIGHT, toward the larger values"
if ($dir == 1) {
  $trueName = "skewed left"
  $tailWord = "the LEFT, toward the smaller values"
}

$questions[0] = array(
  "Skewed right: one peak, with a long thin tail running toward the larger values",
  "Skewed left: one peak, with a long thin tail running toward the smaller values",
  "Roughly symmetric: the two sides mirror each other",
  "Bimodal: two separate peaks with a dip between them"
)
$answer[0] = $dir

$answer[1] = $n
$answer[2] = $medLo
$answerboxsize = 5

$questions[3] = array(
  "The MEDIAN, with the interquartile range for spread. The distribution is skewed, so the tail drags the mean away from where most of the data sits.",
  "The MEAN, with the standard deviation for spread. The mean is the more precise measure in every situation.",
  "The MODE, with the range for spread, because the tallest bar is the easiest feature to read.",
  "Any of the three; on a skewed distribution the choice of center makes no difference."
)
$answer[3] = 0

// --- Bar geometry, shared with the other 2.6 histograms.
$plotL = 55
$plotB = 260
$plotT = 25
$barW = 50
$unitPx = ($plotB - $plotT) / $axisTop

$grid = ""
for ($g=0..12) {
  $v = 2 * $g
  $gy = round($plotB - $v * $unitPx, 2)
  $grid = $grid . '<line x1="55" y1="' . $gy . '" x2="455" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $grid = $grid . '<text x="48" y="' . ($gy + 4) . '" font-size="11" fill="#6b7280" text-anchor="end">' . $v . '</text>'
}

$xlabels = ""
for ($k=0..8) {
  $xp = $plotL + $k * $barW
  $xval = $binWidth * $k
  $xlabels = $xlabels . '<line x1="' . $xp . '" y1="260" x2="' . $xp . '" y2="265" stroke="#374151" stroke-width="1"/>'
  $xlabels = $xlabels . '<text x="' . $xp . '" y="280" font-size="11" fill="#374151" text-anchor="middle">' . $xval . '</text>'
}

$bars = ""
for ($k=0..7) {
  $bx = $plotL + $k * $barW
  $bh = round($barCounts[$k] * $unitPx, 2)
  $by = round($plotB - $bh, 2)
  $bars = $bars . '<rect x="' . $bx . '" y="' . $by . '" width="' . $barW . '" height="' . $bh . '" fill="#93c5fd" stroke="#1e40af" stroke-width="1.5"/>'
}

$svgOpen = '<svg viewBox="0 0 500 320" width="100%" style="max-width:500px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$axes = '<line x1="55" y1="25" x2="55" y2="260" stroke="#374151" stroke-width="2"/><line x1="55" y1="260" x2="455" y2="260" stroke="#374151" stroke-width="2"/><text x="255" y="303" font-size="13" fill="#374151" text-anchor="middle">Repair cost in dollars</text><text x="16" y="145" font-size="13" fill="#374151" text-anchor="middle" transform="rotate(-90 16 145)">Frequency</text>'
$hist = $svgOpen . $grid . $bars . $axes . $xlabels . '</svg>'

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
      <p><span class="term-label">The checklist.</span> A distribution is described by three things, always in this order: <b>shape</b>, <b>center</b>, <b>spread</b>. Naming one and stopping is what loses credit on a written answer, so run all three every time.</p>
      <p><span class="term-label">Shape.</span> One peak, with the bars thinning into a long low tail toward ' . $tailWord . '. That makes it <b>' . $trueName . '</b>: named for the tail, never for the side the tall bars are on.</p>
      <p><span class="term-label">How much data there is.</span> Adding the eight bar heights gives <b>' . $n . '</b> observations. You need this before you can locate the center, because the median is defined by counting.</p>
      <p><span class="term-label">Center.</span> The median is the middle observation, so walk the bars from the left adding counts until you pass ' . $half . '. That happens in the class <b>' . $medLo . ' to ' . $medHi . '</b> dollars, so the median lies somewhere in there. A histogram groups the data, so it fixes the median to a class rather than to a single value: naming the class is the honest answer, not a rounding-down.</p>
      <p><span class="term-label">Spread.</span> The data runs across all eight classes, from 0 to ' . $rangeVal . ' dollars.</p>
      <p><span class="term-label">Matching the summary to the shape.</span> Because the distribution is skewed, report the <b>median</b> for center and the <b>interquartile range</b> for spread. The mean and the standard deviation both use every value, so both are dragged by the tail; the median and the IQR are positional and are not. On a roughly symmetric picture the mean and standard deviation would be the natural pair instead: the shape decides, which is why shape comes first on the checklist.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A garage recorded the cost of every repair it carried out last month. Each class includes its left endpoint and excludes its right.</p>
    $hist
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>Shape.</b> How would you describe this distribution? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many repairs are shown altogether? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>Center.</b> The median falls inside one of the classes. What dollar amount does that class <b>start</b> at? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Given the shape you named, which pair of summaries should a report quote? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
