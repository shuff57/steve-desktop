// === NAME - DESCRIPTION: Skewness and the Mean-Median Relationship - From a drawn histogram, identify skewed-left, skewed-right or symmetric, connect that shape to whether the mean is less than, greater than, or about equal to the median, and name a two-peaked distribution ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// The shape is drawn fresh every render: 0 skewed left, 1 skewed right, 2 roughly symmetric.
// Whichever shape wins the draw controls both the bar heights below AND the correct answer
// index into parts (a) and (b) -- there is no separate hardcoded answer table.
$shapeIdx = rand(0, 2)

$axisTop = 24
$binWidth = 5

$barCounts = array(0, 0, 0, 0, 0, 0, 0, 0)

if ($shapeIdx == 0) {
  // Skewed LEFT: a long, thin tail of short bars on the LEFT (small values), the bulk piled
  // up on the right. Tail bars stay in 2..6 and bulk bars stay in 10..20, so the contrast
  // between tail and bulk is unmistakable regardless of the exact random draw.
  $barCounts[0] = 2 * rand(1, 3)
  $barCounts[1] = 2 * rand(1, 3)
  $barCounts[2] = 2 * rand(1, 3)
  $barCounts[3] = 2 * rand(5, 10)
  $barCounts[4] = 2 * rand(5, 10)
  $barCounts[5] = 2 * rand(5, 10)
  $barCounts[6] = 2 * rand(5, 10)
  $barCounts[7] = 2 * rand(5, 10)
}
if ($shapeIdx == 1) {
  // Skewed RIGHT: the mirror image -- bulk on the left, a long thin tail on the right.
  $barCounts[0] = 2 * rand(5, 10)
  $barCounts[1] = 2 * rand(5, 10)
  $barCounts[2] = 2 * rand(5, 10)
  $barCounts[3] = 2 * rand(5, 10)
  $barCounts[4] = 2 * rand(5, 10)
  $barCounts[5] = 2 * rand(1, 3)
  $barCounts[6] = 2 * rand(1, 3)
  $barCounts[7] = 2 * rand(1, 3)
}
if ($shapeIdx == 2) {
  // Roughly symmetric: a mirror image about the center, built from one increasing half so
  // both sides taper the same amount -- no long tail favors either side.
  $v0 = 2 * rand(1, 3)
  $v1 = $v0 + 2 * rand(1, 3)
  $v2 = $v1 + 2 * rand(1, 3)
  $v3 = $v2 + 2 * rand(1, 3)
  $barCounts[0] = $v0
  $barCounts[1] = $v1
  $barCounts[2] = $v2
  $barCounts[3] = $v3
  $barCounts[4] = $v3
  $barCounts[5] = $v2
  $barCounts[6] = $v1
  $barCounts[7] = $v0
}

$n = 0
for ($i=0..7) {
  $n = $n + $barCounts[$i]
}

// --- Part (a): name the shape. Order 0/1/2 matches $shapeIdx exactly, so the correct choice
// is computed, not looked up. Index 3 is bimodal, never the drawn shape here.
$questions[0] = array(
  "Skewed left &mdash; a long, thin tail stretches out toward the SMALLER values on the left",
  "Skewed right &mdash; a long, thin tail stretches out toward the LARGER values on the right",
  "Roughly symmetric &mdash; the two sides mirror each other, with no long tail on either side",
  "Bimodal &mdash; the distribution has two separate peaks instead of one"
)
$answer[0] = $shapeIdx

// --- Part (b): the mean-median relationship FOR THE DRAWN SHAPE. Same trick: order 0/1/2
// matches $shapeIdx, index 3 is the "can't tell" option.
$questions[1] = array(
  "The mean is LESS than the median, because the tail toward the small values pulls the mean down",
  "The mean is GREATER than the median, because the tail toward the large values pulls the mean up",
  "The mean is approximately EQUAL to the median, because the distribution balances evenly on both sides",
  "A histogram alone cannot tell you anything about the mean or the median"
)
$answer[1] = $shapeIdx

// --- Part (c): vocabulary, independent of which shape got drawn above.
$questions[2] = array("Bimodal", "Symmetric", "Skewed", "Uniform")
$answer[2] = 0

// Bar geometry. 8 classes, width $binWidth each, bars touch because the horizontal axis is a
// number line. Counts are always even, so every bar top lands exactly on a gridline.
$plotL = 55
$plotR = 455
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
$axes = '<line x1="55" y1="25" x2="55" y2="260" stroke="#374151" stroke-width="2"/><line x1="55" y1="260" x2="455" y2="260" stroke="#374151" stroke-width="2"/><text x="255" y="303" font-size="13" fill="#374151" text-anchor="middle">Commute time in minutes</text><text x="16" y="145" font-size="13" fill="#374151" text-anchor="middle" transform="rotate(-90 16 145)">Frequency</text>'

$hist = $svgOpen . $grid . $bars . $axes . $xlabels . '</svg>'

// Shape-dependent prose for the solution guide -- a function of $shapeIdx, not a lookup keyed
// off the drawn bars, so it stays correct no matter what the random draw produced.
$shapeName = "roughly symmetric"
if ($shapeIdx == 0) { $shapeName = "skewed left" }
if ($shapeIdx == 1) { $shapeName = "skewed right" }

$tailWhy = "the two sides mirror each other, with no long tail favoring either side"
if ($shapeIdx == 0) { $tailWhy = "the bars trail off into a long, thin tail on the LEFT, toward the smaller values, while the bulk of the data piles up on the right" }
if ($shapeIdx == 1) { $tailWhy = "the bars trail off into a long, thin tail on the RIGHT, toward the larger values, while the bulk of the data piles up on the left" }

$meanMedText = "the mean is approximately equal to the median"
if ($shapeIdx == 0) { $meanMedText = "the mean is LESS than the median" }
if ($shapeIdx == 1) { $meanMedText = "the mean is GREATER than the median" }

$meanMedWhy = "the distribution balances evenly on both sides, so the balancing point &#40;the mean&#41; lands close to the middle value &#40;the median&#41;"
if ($shapeIdx == 0) { $meanMedWhy = "the tail of small values pulls the mean down toward it, while the median only counts positions and barely moves &mdash; so the mean ends up below the median" }
if ($shapeIdx == 1) { $meanMedWhy = "the tail of large values pulls the mean up toward it, while the median only counts positions and barely moves &mdash; so the mean ends up above the median" }

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
      <p><span class="term-label">Step 1 &mdash; find the tail.</span> Skew is named for the TAIL, not for where the peak sits. Looking along the tops of the bars, ' . $tailWhy . '. That makes this distribution <b>' . $shapeName . '</b>.</p>
      ' . $hist . '
      <p><span class="term-label">Step 2 &mdash; follow the tail to the mean.</span> The mean is dragged toward the tail because it is an average of every value, including the extreme ones out in the tail; the median only counts how many values are above and below it, so a stretched-out tail barely moves it. Here, ' . $meanMedWhy . ', so ' . $meanMedText . '.</p>
      <p><span class="term-label">Step 3 &mdash; bimodal is a different question entirely.</span> A distribution with two separate, clearly-separated peaks &mdash; not one peak with a tail &mdash; is called <b>bimodal</b>. It usually means the data actually come from two different groups mixed together.</p>
      <p><b>Answer:</b> (a) ' . $shapeName . ' &nbsp;&nbsp; (b) ' . $meanMedText . ' &nbsp;&nbsp; (c) bimodal</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A transportation researcher recorded the one-way commute time, in minutes, for $n employees at a company. The histogram below shows how those commute times are distributed.</p>
    $hist
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>shape</b> of this distribution? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Based on that shape, what is the relationship between the <b>mean</b> and the <b>median</b> of this distribution? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> A different data set produced a histogram with <b>two clear, separate peaks</b> instead of one. What word describes that kind of distribution? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
