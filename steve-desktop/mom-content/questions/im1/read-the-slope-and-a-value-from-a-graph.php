// === NAME - DESCRIPTION: Read the Slope and a Value from a Graph - Use two marked points on a context line with labeled axes to find the slope with units, then read the output at a given input ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (Reading a Rate from a Graph). The graph is drawn from the random parameters: 6
// columns by 12 rows, every gridline labeled. The line starts on a gridline at input 0 and rises
// or falls by g rows per column, with g one of 0.5, 1 or 1.5. The two marked points and the input
// asked about are all on even columns, so the line is on a gridline crossing at each of them and
// nothing has to be estimated. The pay context only rises. Each row is ys output units and each
// column is xs input units, so the slope is g * ys / xs with its sign.
$anstypes = array("number", "choices", "number")

$ci = rand(0, 4)
$gs = array(0.5, 1, 1.5)
$gi = rand(0, 2)
$g = $gs[$gi]
$sg = 1
$dn = rand(0, 1)
if ($dn == 1) {
  $sg = -1
}
if ($ci == 3) {
  $sg = 1
}
$pick = rand(0, 2)
$xsList = array(1, 2, 5,  1, 2, 5,  1, 2, 5,  1, 2, 5,  10, 20, 50)
$ysList = array(5, 10, 20,  5, 10, 20,  2, 5, 10,  5, 10, 20,  5, 5, 5)
$pidx = $ci * 3 + $pick
$xs = $xsList[$pidx]
$ys = $ysList[$pidx]

$b = rand(1, 3)
if ($sg > 0) {
  $top = 12 - 6 * $g
  $b = rand(1, $top)
}
if ($sg < 0) {
  $low = 6 * $g + 1
  $b = rand($low, 12)
}
$c = 2 * rand(1, 3)
$c3 = 2 * rand(1, 3)
if ($c3 == $c) {
  $c3 = ($c % 6) + 2
}
$rowC = $b + $sg * $g * $c
$row3 = $b + $sg * $g * $c3
$rowEnd = $b + $sg * $g * 6

$m = round($sg * $g * $ys / $xs, 3)
$p1y = $b * $ys
$p2x = $c * $xs
$p2y = $rowC * $ys
$x3 = $c3 * $xs
$y3 = $row3 * $ys

$things = array("the water in a tank", "the distance of a cyclist from home", "the height of a drone above the ground", "the pay of a worker", "the charge of a phone battery")
$xTitles = array("Time in minutes", "Time in hours", "Time in seconds", "Time worked in hours", "Time in minutes")
$yTitles = array("Water in liters", "Distance from home in kilometers", "Height in meters", "Pay in dollars", "Battery charge in percent")
$inUnits = array("minutes", "hours", "seconds", "hours", "minutes")
$inSings = array("minute", "hour", "second", "hour", "minute")
$outUnits = array("liters", "kilometers", "meters", "dollars", "percent")
$outSings = array("liter", "kilometer", "meter", "dollar", "percent")
$outNames = array("water in the tank", "distance from home", "height of the drone", "pay", "battery charge")
$thing = $things[$ci]
$xTitle = $xTitles[$ci]
$yTitle = $yTitles[$ci]
$inUnit = $inUnits[$ci]
$inSing = $inSings[$ci]
$outUnit = $outUnits[$ci]
$outSing = $outSings[$ci]
$outName = $outNames[$ci]

$questions[1] = array(
  $outUnit . " per " . $inSing,
  $inUnit . " per " . $outSing,
  $outUnit,
  $inUnit
)
$answer[1] = 0

$answer[0] = $m
$answer[2] = $y3
$abstolerance[0] = 0.001
$abstolerance[2] = 0.001

$px0 = 60
$py0 = 308
$gridSvg = ""
for ($k=0..12) {
  $yy = $py0 - $k * 24
  $lab = $k * $ys
  $gridSvg = $gridSvg . '<line x1="60" y1="' . $yy . '" x2="420" y2="' . $yy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $gridSvg = $gridSvg . '<text x="53" y="' . ($yy + 4) . '" font-size="11" fill="#374151" text-anchor="end">' . $lab . '</text>'
}
for ($k=0..6) {
  $xx = 60 + $k * 60
  $lab = $k * $xs
  $gridSvg = $gridSvg . '<line x1="' . $xx . '" y1="20" x2="' . $xx . '" y2="308" stroke="#e5e7eb" stroke-width="1"/>'
  $gridSvg = $gridSvg . '<text x="' . $xx . '" y="324" font-size="11" fill="#374151" text-anchor="middle">' . $lab . '</text>'
}
$ly0 = $py0 - $b * 24
$ly1 = $py0 - $rowEnd * 24
$cx = 60 + $c * 60
$cy = $py0 - $rowC * 24

$svg = '<svg viewBox="0 0 440 364" width="100%" style="max-width:440px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Line graph with labeled axes">'
$svg = $svg . $gridSvg
$svg = $svg . '<line x1="60" y1="20" x2="60" y2="308" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<line x1="60" y1="308" x2="420" y2="308" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<line x1="60" y1="' . $ly0 . '" x2="420" y2="' . $ly1 . '" stroke="#1d4ed8" stroke-width="3"/>'
$svg = $svg . '<circle cx="60" cy="' . $ly0 . '" r="5" fill="#1d4ed8" stroke="#fff" stroke-width="1.5"/>'
$svg = $svg . '<circle cx="' . $cx . '" cy="' . $cy . '" r="5" fill="#1d4ed8" stroke="#fff" stroke-width="1.5"/>'
$svg = $svg . '<text x="240" y="352" font-size="12" fill="#374151" text-anchor="middle">' . $xTitle . '</text>'
$svg = $svg . '<text x="14" y="164" font-size="12" fill="#374151" text-anchor="middle" transform="rotate(-90 14 164)">' . $yTitle . '</text>'
$svg = $svg . '</svg>'

$dy = $p2y - $p1y
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
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">

      <p><span class="term-label">Part a: the slope.</span> The two marked points are at ' . $inSing . ' 0 with ' . $p1y . ' ' . $outUnit . ', and at ' . $p2x . ' ' . $inUnit . ' with ' . $p2y . ' ' . $outUnit . '. The slope is the change in the output over the change in the input: `(' . $p2y . ' - ' . $p1y . ') / (' . $p2x . ' - 0) = ' . $dy . ' / ' . $p2x . ' = ' . $m . '`.</p>
      <p><span class="term-label">Part b: the units.</span> The slope is ' . $outUnit . ' per ' . $inSing . ', which is output units per input unit. For every increase of 1 ' . $inSing . ', the ' . $outName . ' changes by ' . $m . ' ' . $outUnit . '.</p>
      <p><span class="term-label">Part c: read the graph.</span> Go up from ' . $x3 . ' ' . $inUnit . ' on the bottom axis to the line, then across to the side axis. The line is at ' . $y3 . ' ' . $outUnit . '.</p>

    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">The graph shows $thing.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Use the two dots to find the slope of the line. If the line goes down, enter a negative number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which units go with that slope?<br><br>$answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Use the graph to find the $outName when the time is $x3 $inUnit. Give it in $outUnit. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
