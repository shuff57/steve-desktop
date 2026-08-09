// === NAME - DESCRIPTION: Identify a Bimodal Distribution - Spot two separate peaks in a drawn histogram, say why a single measure of center describes it badly, and name what usually causes two peaks ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Every other shape question in 2.6 draws one hump. This one draws two, because "bimodal" is a word
// students can define and still not recognize -- and because a bimodal picture is the clearest case
// where reporting a single center is actively misleading: the mean lands in the VALLEY, at a value
// that is close to nothing in the data.
//
// The two peaks are separated by a forced dip: the middle bars are capped well below both peaks, so
// the gap is a fact about the drawing rather than something the reader has to judge.
$anstypes = array("choices", "choices", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A gym recorded how many minutes each member spent on the treadmill in one session."
  $axisName = "Minutes on the treadmill"
  $cause = "two different kinds of member using the same machine -- people warming up briefly before weights, and people there for a long run"
}
else {
  $intro = "A campus cafe recorded how much each customer spent, in dollars, on one visit."
  $axisName = "Amount spent in dollars"
  $cause = "two different kinds of customer in the same queue -- people buying only a coffee, and people buying a full lunch"
}

$axisTop = 24
$binWidth = 5
$barCounts = array(0, 0, 0, 0, 0, 0, 0, 0)

// Peak, dip, peak. Peaks stay in 14..20 and the valley in 2..6, so the two humps are unmistakable.
$barCounts[0] = 2 * rand(3, 5)
$barCounts[1] = 2 * rand(7, 10)
$barCounts[2] = 2 * rand(5, 7)
$barCounts[3] = 2 * rand(1, 3)
$barCounts[4] = 2 * rand(1, 3)
$barCounts[5] = 2 * rand(5, 7)
$barCounts[6] = 2 * rand(7, 10)
$barCounts[7] = 2 * rand(3, 5)

$n = 0
for ($i=0..7) {
  $n = $n + $barCounts[$i]
}

$questions[0] = array(
  "Bimodal &mdash; there are two separate peaks with a dip between them",
  "Skewed right &mdash; a long thin tail runs toward the larger values",
  "Skewed left &mdash; a long thin tail runs toward the smaller values",
  "Roughly symmetric with a single center peak"
)
$answer[0] = 0

$questions[1] = array(
  "Badly. The mean lands in the dip between the two peaks, at a value that hardly anybody actually recorded.",
  "Well. The mean is always the best summary, whatever shape the data takes.",
  "Badly, but only because the two peaks are different heights.",
  "Well, as long as the two peaks are the same width."
)
$answer[1] = 0

$questions[2] = array(
  "Two different groups have been measured together and then reported as one.",
  "The sample was too small for a single peak to form.",
  "The class width was chosen too wide, which always splits one peak into two.",
  "Two peaks mean the measurements contain an error and should be discarded."
)
$answer[2] = 0

// --- Bar geometry, identical to the other 2.6 histograms so the section looks consistent.
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
$axes = '<line x1="55" y1="25" x2="55" y2="260" stroke="#374151" stroke-width="2"/><line x1="55" y1="260" x2="455" y2="260" stroke="#374151" stroke-width="2"/><text x="255" y="303" font-size="13" fill="#374151" text-anchor="middle">' . $axisName . '</text><text x="16" y="145" font-size="13" fill="#374151" text-anchor="middle" transform="rotate(-90 16 145)">Frequency</text>'
$hist = $svgOpen . $grid . $bars . $axes . $xlabels . '</svg>'

$peakA = $binWidth * 1
$peakAend = $binWidth * 2
$peakB = $binWidth * 6
$peakBend = $binWidth * 7

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
      <p><span class="term-label">Part (a) &mdash; count the peaks before naming the shape.</span> There is a tall bar around ' . $peakA . '&ndash;' . $peakAend . ', the bars drop away in the middle, and then they climb again to a second tall bar around ' . $peakB . '&ndash;' . $peakBend . '. Two peaks with a dip between them is <b>bimodal</b>.</p>
      <p>It is not skewed either way. Skew means ONE peak with a thin tail trailing off to one side; here both ends are tall and the middle is short, which is the opposite arrangement.</p>
      <p><span class="term-label">Part (b) &mdash; why one center is the wrong summary.</span> The mean and the median both land in the DIP, somewhere between the two peaks &mdash; a value that very few observations are anywhere near. Quoting it suggests a typical case that does not exist. A summary that describes nothing in the data is worse than no summary, because it sounds authoritative.</p>
      <p><span class="term-label">Part (c) &mdash; what two peaks usually mean.</span> Almost always, two groups have been measured together: here, ' . $cause . '. The honest move is to split them and describe each group on its own, rather than to average across a divide the data is telling you about.</p>
      <p><span class="term-label">Why this matters beyond the picture.</span> Every measure of center you have met assumes there is one center to find. A bimodal histogram is the data warning you that the assumption does not hold &mdash; which is the whole reason to look at the shape before reporting any number.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$intro All $n measurements are shown below.</p>
    $hist
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the shape of this distribution? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How well does a single mean describe this data? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What most often causes a distribution to have two peaks? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
