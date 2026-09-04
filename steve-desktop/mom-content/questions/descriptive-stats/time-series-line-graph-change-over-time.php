// === NAME - DESCRIPTION: Read a Time Series Line Graph - From a line graph of eight months of data, read one month's value, find the largest month-to-month rise, work out the net change across the whole period, and say why a line graph suits data collected over time ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A museum recorded how many hundreds of visitors came through its doors in each of eight months."
  $axisName = "Visitors in hundreds"
  $unitWord = "hundreds of visitors"
}
else {
  $intro = "A repair shop recorded how many bicycles it serviced in each of eight months."
  $axisName = "Bicycles serviced"
  $unitWord = "bicycles"
}

$mNames = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug")

// A wandering series with a bounded step, so the graph always fits its axis whatever the seed.
// One month is given a clearly largest rise, otherwise two months can tie and part (b) has no
// single answer even though the question asks for a size rather than a month.
$v = array(0, 0, 0, 0, 0, 0, 0, 0)
$v[0] = 2 * rand(7, 12)
for ($k=1..7) {
  $v[$k] = $v[$k-1] + 2 * rand(-2, 3)
  if ($v[$k] < 6) { $v[$k] = 6 }
}
$jump = rand(1, 7)
$v[$jump] = $v[$jump] + 8

$minV = 99
$maxV = 0
for ($k=0..7) {
  if ($v[$k] < $minV) { $minV = $v[$k] }
  if ($v[$k] > $maxV) { $maxV = $v[$k] }
}

// The largest single-month rise, taken from the finished series rather than assumed.
$bestRise = 0
for ($k=1..7) {
  $d = $v[$k] - $v[$k-1]
  if ($d > $bestRise) { $bestRise = $d }
}

$netChange = $v[7] - $v[0]
$ai = rand(0, 7)
$askMonth = $mNames[$ai]
$askValue = $v[$ai]

$answer[0] = $askValue
$answerformat[0] = "integer"

$answer[1] = $bestRise
$answerformat[1] = "integer"

$answer[2] = $netChange
$answerformat[2] = "integer"

$questions[3] = array(
  "The months come in a fixed order with real time passing between them, so the segments stand for something: the rise or fall from one month to the next.",
  "Because a line graph may be used for any data at all, whatever the variable.",
  "Because the months are categories, and categories are always shown with a line graph.",
  "Because a line graph is the only display that can show more than five values."
)
$answer[3] = 0

// Axis starts at zero. A time series is exactly where a truncated axis does the most damage,
// so this question models the honest version.
$top = $maxV + 4
$step = 2
$rem = $top % $step
if ($rem > 0) { $top = $top + $step - $rem }
$gN = $top / $step
$unitPx = 195 / $top

$grid = ""
for ($g=0..$gN) {
  $val = $step * $g
  $gy = round(230 - $val * $unitPx, 2)
  $grid = $grid . '<line x1="60" y1="' . $gy . '" x2="520" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $grid = $grid . '<text x="54" y="' . ($gy + 4) . '" font-size="12" fill="#6b7280" text-anchor="end">' . $val . '</text>'
}

$pts = ""
$dots = ""
$xlab = ""
for ($k=0..7) {
  $cx = 76 + $k * 62
  $cy = round(230 - $v[$k] * $unitPx, 2)
  if ($k == 0) { $pts = $cx . "," . $cy }
  if ($k > 0) { $pts = $pts . " " . $cx . "," . $cy }
  $dots = $dots . '<circle cx="' . $cx . '" cy="' . $cy . '" r="4" fill="#1e40af"/>'
  $xlab = $xlab . '<line x1="' . $cx . '" y1="230" x2="' . $cx . '" y2="235" stroke="#374151" stroke-width="1"/>'
  $xlab = $xlab . '<text x="' . $cx . '" y="250" font-size="12" fill="#374151" text-anchor="middle">' . $mNames[$k] . '</text>'
}

$svg = '<svg viewBox="0 0 545 280" width="100%" style="max-width:545px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $grid
$svg = $svg . '<polyline points="' . $pts . '" fill="none" stroke="#1e40af" stroke-width="2.5"/>'
$svg = $svg . $dots
$svg = $svg . '<line x1="60" y1="28" x2="60" y2="230" stroke="#374151" stroke-width="2"/><line x1="60" y1="230" x2="520" y2="230" stroke="#374151" stroke-width="2"/>'
$svg = $svg . $xlab
$svg = $svg . '<text x="20" y="130" font-size="12" fill="#374151" text-anchor="middle" transform="rotate(-90 20 130)">' . $axisName . '</text>'
$svg = $svg . '</svg>'

$firstV = $v[0]
$lastV = $v[7]
$dirWord = "higher than"
if ($netChange < 0) { $dirWord = "lower than" }

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
      <p><span class="term-label">Step 1: read one point.</span> Find ' . $askMonth . ' along the bottom, go up to the dot, then across to the axis: <b>' . $askValue . '</b> ' . $unitWord . '.</p>
      <p><span class="term-label">Step 2: the largest rise.</span> A month-to-month change is the gap between two neighbouring dots, so look for the steepest upward segment rather than the highest dot. The biggest single-month rise is <b>' . $bestRise . '</b>. The highest point on the graph is usually not where the biggest rise happens: that is the trap in this part.</p>
      <p><span class="term-label">Step 3: the net change.</span> Compare the two ends and ignore everything between: ' . $lastV . ' &minus; ' . $firstV . ' = <b>' . $netChange . '</b>. August finished ' . $dirWord . ' January by that much, however much the line wandered on the way.</p>
      <p><span class="term-label">Step 4: why a line graph.</span> The months have a fixed order and real time passes between them, so a segment means the change from one month to the next and its steepness is a rate. This is the one place the connecting lines carry meaning: joining up categories such as bus routes would be nonsense, because nothing lies between two bus routes.</p>
      <p><b>Answer:</b> (a) ' . $askValue . ' &nbsp;&nbsp; (b) ' . $bestRise . ' &nbsp;&nbsp; (c) ' . $netChange . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$intro The results are shown on the line graph below.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What was the figure for <b>$askMonth</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>largest rise</b> from one month to the very next month? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How much higher was August than January? (A drop is a negative number.) $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Why is a line graph a sensible choice for this data? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
