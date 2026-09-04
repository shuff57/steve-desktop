// === NAME - DESCRIPTION: Misleading Axis Scale in a Line-Graph Comparison - Two line graphs compare a randomized pair of companies with no labeled or scaled vertical axis; name the flaw that lets each line stretch independently, and state how to correct the comparison ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

$companyPairs = array(
  array("Acme Investments", "Brightline Capital"),
  array("Silverline Wealth", "Marlow & Vance Capital"),
  array("Northgate Financial", "Coral Bay Capital"),
  array("Vantage Growth Fund", "Pinecrest Capital"),
  array("Ridgeline Investments", "Harborview Capital")
)
$ci = rand(0, 4)
$companyA = $companyPairs[$ci][0]
$companyB = $companyPairs[$ci][1]

$metricPhrases = array("portfolio value", "monthly active subscribers", "quarterly revenue", "daily active users")
$periodPhrases = array("over the past 10 years", "over the past 2 years", "over the past 3 years", "over the past 18 months")
$mi = rand(0, 3)
$metricPhrase = $metricPhrases[$mi]
$periodPhrase = $periodPhrases[$mi]

$steepIsA = rand(0, 1)
if ($steepIsA == 1) {
  $steepCompany = $companyA
  $flatCompany = $companyB
} else {
  $steepCompany = $companyB
  $flatCompany = $companyA
}

$startV = rand(15, 30)
$endV = rand(70, 92)
$trendRange = $endV - $startV

$v0 = $startV
$v1 = $startV + $trendRange * (1 / 6) + rand(3, 9)
$v2 = $startV + $trendRange * (2 / 6) - rand(3, 9)
$v3 = $startV + $trendRange * (3 / 6) + rand(3, 9)
$v4 = $startV + $trendRange * (4 / 6) - rand(3, 9)
$v5 = $startV + $trendRange * (5 / 6) + rand(3, 9)
$v6 = $endV

$vals = array($v0, $v1, $v2, $v3, $v4, $v5, $v6)

$vMin = $v0
$vMax = $v0
for ($i=1..6) {
  if ($vals[$i] < $vMin) {
    $vMin = $vals[$i]
  }
  if ($vals[$i] > $vMax) {
    $vMax = $vals[$i]
  }
}
$vRange = $vMax - $vMin
if ($vRange == 0) {
  $vRange = 1
}

$plotLeft = 30
$plotRight = 265
$plotTop = 15
$plotBottom = 145
$plotWidth = $plotRight - $plotLeft
$plotHeight = $plotBottom - $plotTop
$squeezedHeight = $plotHeight * 0.22
$squeezedBaseline = $plotBottom - $plotHeight * 0.03

$ptsSteep = ''
$ptsFlat = ''
$sep = ''
for ($i=0..6) {
  $x = round($plotLeft + ($i / 6) * $plotWidth, 1)
  $norm = ($vals[$i] - $vMin) / $vRange
  $ySteep = round($plotBottom - $norm * $plotHeight, 1)
  $yFlat = round($squeezedBaseline - $norm * $squeezedHeight, 1)
  $ptsSteep = $ptsSteep . $sep . $x . ',' . $ySteep
  $ptsFlat = $ptsFlat . $sep . $x . ',' . $yFlat
  $sep = ' '
}

if ($steepIsA == 1) {
  $ptsA = $ptsSteep
  $ptsB = $ptsFlat
} else {
  $ptsA = $ptsFlat
  $ptsB = $ptsSteep
}

$tickYStr = ''
for ($k=1..3) {
  $ty = round($plotTop + ($k / 4) * $plotHeight, 1)
  $tickYStr = $tickYStr . '<line x1="' . ($plotLeft - 4) . '" y1="' . $ty . '" x2="' . $plotLeft . '" y2="' . $ty . '" stroke="#aab0bd" stroke-width="1"/>'
}
$tickXStr = ''
for ($k=1..3) {
  $tx = round($plotLeft + ($k / 4) * $plotWidth, 1)
  $tickXStr = $tickXStr . '<line x1="' . $tx . '" y1="' . $plotBottom . '" x2="' . $tx . '" y2="' . ($plotBottom + 4) . '" stroke="#aab0bd" stroke-width="1"/>'
}
$axesStr = '<line x1="' . $plotLeft . '" y1="' . $plotTop . '" x2="' . $plotLeft . '" y2="' . $plotBottom . '" stroke="#555" stroke-width="1.5"/><line x1="' . $plotLeft . '" y1="' . $plotBottom . '" x2="' . $plotRight . '" y2="' . $plotBottom . '" stroke="#555" stroke-width="1.5"/>' . $tickYStr . $tickXStr

$svgA = '<svg viewBox="0 0 280 170" width="280" height="170" style="background:#fbfbfd; border-radius:8px;">' . $axesStr . '<polyline points="' . $ptsA . '" fill="none" stroke="#1865f2" stroke-width="2.5"/></svg>'
$svgB = '<svg viewBox="0 0 280 170" width="280" height="170" style="background:#fbfbfd; border-radius:8px;">' . $axesStr . '<polyline points="' . $ptsB . '" fill="none" stroke="#e8590c" stroke-width="2.5"/></svg>'

$choices[0] = array(
  "Neither graph's vertical axis has any numbers on it, so each company's line can be stretched or squeezed to whatever height makes its growth look bigger or smaller: the two graphs may not even use the same scale.",
  "The number of data points plotted for each company is too small to draw a reliable conclusion.",
  "A line graph is the wrong type of display for comparing two companies over time; a bar graph should have been used instead.",
  $companyA . "'s graph and " . $companyB . "'s graph cover different lengths of time, so the two lines cannot be compared directly."
)
$answer[0] = 0

// Part b was a free-response essay. Homework is auto-graded only, so it is multiple choice now.
$choices[1] = array(
  "Redraw both companies on a single graph with one labeled vertical axis that starts at zero, so both lines are read against the same numbered scale.",
  "Keep the two separate graphs but print them at the same physical size, so neither picture looks bigger than the other.",
  "Add a title and a legend to each graph so the reader knows which company each line belongs to.",
  "Redraw each graph with its own labeled vertical axis, scaled to fit that company's own range of values."
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
      <p><span class="term-label">Step 1: Look at what is missing from both graphs.</span> Neither ' . $companyA . '\'s graph nor ' . $companyB . '\'s graph has a single number on its vertical axis. There are gridlines and tick marks, but nothing tells you what one gridline is worth, or what span of time the horizontal axis covers.</p>
      <p><span class="term-label">Step 2: Ask what that lets the advertiser do.</span> Without labeled scales, the two pictures can be drawn to any vertical scale the advertiser likes. ' . $steepCompany . '\'s line is drawn to fill its plot from bottom to top, while ' . $flatCompany . '\'s line is squeezed into the bottom quarter of a plot that is otherwise empty. If ' . $flatCompany . '\'s vertical axis actually runs to a much larger maximum, that flat-looking line could represent the same growth as ' . $steepCompany . '\'s: or more.</p>
      <p><span class="term-label">Step 3: Notice the shapes are nearly the same.</span> Both lines start low, wobble through similar dips and bumps, and end higher than they started. The visual difference between "steep climb" and "barely moving" is being produced by the framing of each plot, not by how different the underlying data really are.</p>
      <p><span class="term-label">Step 4: State the correction.</span> Put a labeled, numbered vertical axis on both graphs, using the <b>same</b> scale and the <b>same</b> time range. Better still, plot both companies as two lines on <b>one</b> set of axes, so the reader compares the data instead of comparing two independently stretched pictures.</p>
      <p><b>Answer:</b> (a) the graphs are misleading because neither vertical axis is labeled or scaled, so the reader cannot tell whether the two scales agree: the apparent gap between ' . $companyA . ' and ' . $companyB . ' may just be an artifact of how each plot was framed. (b) Correct it by labeling both axes with real numbers, giving both graphs the same numeric scale and the same time range, and ideally drawing both companies\' data as two lines on one shared set of axes.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">An advertisement for $companyA compares its $metricPhrase with $companyB's, $periodPhrase for both companies. The ad displays the two graphs below, exactly as they appeared.</p>
  </div>
  <div style="display:flex; flex-wrap:wrap; gap:16px; margin:10px 0;">
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); text-align:center;">
      $svgA
      <p style="margin:8px 0 0 0;"><b>Graph 1.</b> $companyA's $metricPhrase, $periodPhrase.</p>
    </div>
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); text-align:center;">
      $svgB
      <p style="margin:8px 0 0 0;"><b>Graph 2.</b> $companyB's $metricPhrase, $periodPhrase.</p>
    </div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the potentially misleading feature of these two graphs? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How could the two graphs be redrawn so the comparison is no longer misleading? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
