// === NAME - DESCRIPTION: Order the Mean, Median and Mode on a Skewed Picture - From a drawn histogram, put the three measures of center in order along the axis and say which one the tail moves the most ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The section's headline rule is that the mean is dragged toward the tail. This question makes the
// student place all THREE measures relative to one another, which is the form the rule is actually
// tested in: mode at the peak, median next, mean furthest into the tail.
//
// The drawn shape decides the correct ordering: the answer index is computed from $shapeIdx, never
// looked up: so the picture and the key cannot disagree no matter what the seed produced. The
// symmetric case is deliberately included so "the mean is always bigger" is not a winning guess.
$anstypes = array("choices", "choices", "choices")

$shapeIdx = rand(0, 2)

$axisTop = 24
$binWidth = 5
$barCounts = array(0, 0, 0, 0, 0, 0, 0, 0)

if ($shapeIdx == 0) {
  // Skewed LEFT: thin tail of short bars on the low side, bulk piled on the right.
  $barCounts[0] = 2 * rand(1, 3)
  $barCounts[1] = 2 * rand(1, 3)
  $barCounts[2] = 2 * rand(1, 3)
  $barCounts[3] = 2 * rand(5, 8)
  $barCounts[4] = 2 * rand(5, 8)
  $barCounts[5] = 2 * rand(5, 8)
  $barCounts[6] = 2 * rand(8, 11)
  $barCounts[7] = 2 * rand(5, 8)
}
if ($shapeIdx == 1) {
  // Skewed RIGHT: the mirror image.
  $barCounts[0] = 2 * rand(5, 8)
  $barCounts[1] = 2 * rand(8, 11)
  $barCounts[2] = 2 * rand(5, 8)
  $barCounts[3] = 2 * rand(5, 8)
  $barCounts[4] = 2 * rand(5, 8)
  $barCounts[5] = 2 * rand(1, 3)
  $barCounts[6] = 2 * rand(1, 3)
  $barCounts[7] = 2 * rand(1, 3)
}
if ($shapeIdx == 2) {
  // Roughly symmetric, built from one half and mirrored so neither side has a longer tail.
  $u0 = 2 * rand(1, 3)
  $u1 = $u0 + 2 * rand(1, 3)
  $u2 = $u1 + 2 * rand(1, 3)
  $u3 = $u2 + 2 * rand(1, 3)
  $barCounts[0] = $u0
  $barCounts[1] = $u1
  $barCounts[2] = $u2
  $barCounts[3] = $u3
  $barCounts[4] = $u3
  $barCounts[5] = $u2
  $barCounts[6] = $u1
  $barCounts[7] = $u0
}

$n = 0
for ($i=0..7) {
  $n = $n + $barCounts[$i]
}

// Part (a): the ordering. Options are written so index 0/1/2 lines up with $shapeIdx.
$questions[0] = array(
  "mean &lt; median &lt; mode",
  "mode &lt; median &lt; mean",
  "mean, median and mode are all about the same",
  "median &lt; mode &lt; mean"
)
$answer[0] = $shapeIdx

// Part (b): which measure the tail moves most. Same for both skewed shapes; the symmetric case
// has no tail to do any moving, so it gets its own correct option.
$questions[1] = array(
  "The mean. It is computed from every value, so the far-out values in the tail pull it toward them.",
  "The mode. It always sits at the end of the tail.",
  "The median. It is the value most affected by extreme observations.",
  "All three move by the same amount, because they all describe the center."
)
$answer[1] = 0
if ($shapeIdx == 2) {
  $questions[1] = array(
    "None of them is pulled anywhere, because this distribution has no tail on either side.",
    "The mean, because it is always pulled toward the larger values.",
    "The mode, because it always sits at the end of the longer tail.",
    "The median, because it is the value most affected by extreme observations."
  )
  $answer[1] = 0
}

// Part (c): where the mode is read off, independent of the drawn shape.
$questions[2] = array(
  "At the tallest bar, because the mode is the most frequently occurring value.",
  "At the middle of the horizontal axis, because the mode is a kind of center.",
  "At the point where the bars are shortest, because that is where the tail begins.",
  "A histogram cannot show the mode at all."
)
$answer[2] = 0

// --- Bar geometry. Eight touching bars, counts always even so every top lands on a gridline.
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
$axes = '<line x1="55" y1="25" x2="55" y2="260" stroke="#374151" stroke-width="2"/><line x1="55" y1="260" x2="455" y2="260" stroke="#374151" stroke-width="2"/><text x="255" y="303" font-size="13" fill="#374151" text-anchor="middle">Delivery time in minutes</text><text x="16" y="145" font-size="13" fill="#374151" text-anchor="middle" transform="rotate(-90 16 145)">Frequency</text>'
$hist = $svgOpen . $grid . $bars . $axes . $xlabels . '</svg>'

$shapeName = "roughly symmetric"
if ($shapeIdx == 0) { $shapeName = "skewed left" }
if ($shapeIdx == 1) { $shapeName = "skewed right" }

$orderText = "mean, median and mode all sit close together"
if ($shapeIdx == 0) { $orderText = "mean &lt; median &lt; mode" }
if ($shapeIdx == 1) { $orderText = "mode &lt; median &lt; mean" }

$tailSide = "neither side"
if ($shapeIdx == 0) { $tailSide = "the LEFT, toward the smaller values" }
if ($shapeIdx == 1) { $tailSide = "the RIGHT, toward the larger values" }

$whyOrder = "With no tail on either side the distribution balances evenly, so all three land in about the same place."
if ($shapeIdx == 0) { $whyOrder = "The tail runs to the left, so the mean is dragged furthest to the left. The mode stays at the tallest bar on the right, and the median lands between them." }
if ($shapeIdx == 1) { $whyOrder = "The tail runs to the right, so the mean is dragged furthest to the right. The mode stays at the tallest bar on the left, and the median lands between them." }

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
      <p><span class="term-label">Step 1: find the tail, not the bulk.</span> This distribution is <b>' . $shapeName . '</b>: the thin end trails off toward ' . $tailSide . '. Skew is named for where the TAIL points, never for where the tall bars are. That single sentence decides both of the first two answers.</p>
      <p><span class="term-label">Step 2: place the three measures.</span> ' . $whyOrder . ' So the order along the axis is <b>' . $orderText . '</b>.</p>
      <p><span class="term-label">Step 3: why the mean is the one that moves.</span> The mean adds up every observation, so a value far out in the tail contributes its full distance. The median only cares which value sits in the middle position, and the mode only cares which bar is tallest: neither notices HOW far away the extreme values are. That is exactly why the mean is the measure a long tail distorts.</p>
      <p><span class="term-label">Reading the mode off the picture.</span> The mode is the most common value, which on a histogram is the tallest bar. It needs no arithmetic at all.</p>
      <p><span class="term-label">Sanity check you can always run.</span> Imagine the picture balancing on a pencil. The balance point IS the mean, and a long thin tail on one side tips it that way even though few observations live there.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A courier recorded the delivery time of each of $n parcels. The histogram below shows the results.</p>
    $hist
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Reading left to right along the horizontal axis, in what order do the mean, the median and the mode appear? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which measure of center is pulled the furthest by the shape of this distribution? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Where on a histogram do you read the mode? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
