// === NAME - DESCRIPTION: Graph a Line from a Rate and One Reading - Draw a situation's line from its rate and one measurement, then write the equation in slope-intercept form ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.6 (2.6.1). The draw part is the main part. A rate (a whole number or a half, up and down by
// context) and one reading are given. The reading is never at time 0, so b has to be found. The window is
// x from -1 to 11 and y from -2 to 16. Rising contexts use b from 1 to 6 and falling contexts use b from
// 9 to 15, and the reading is kept inside 1 to 15, so every draw leaves at least three lattice points of
// the line on the grid. The reading time is even whenever the rate is a half, so b is a whole number.
// The line is checked as y = mx + b and every lattice point on the grid is an optional dot.
$anstypes = array("draw", "number", "number")

$ci = rand(0, 5)
$yv = array("T", "H", "S", "D", "L", "G")
$xv = array("h", "d", "w", "h", "t", "m")
$yName = array("thickness of the ice", "height of the seedling", "amount in the jar", "depth of the water", "height of the candle", "amount of fuel in the tank")
$yUnit = array("millimeters", "centimeters", "dollars", "inches", "centimeters", "gallons")
$xName = array("hours since a cold snap began", "days since planting", "weeks since the jar was started", "hours since a drain was opened", "hours since the candle was lit", "minutes since the pump was started")
$xOne = array("hour", "day", "week", "hour", "hour", "minute")
$story = array(
  "The ice on a pond is T millimeters thick h hours after a cold snap begins.",
  "A seedling is H centimeters tall d days after planting.",
  "A jar holds S dollars w weeks after it was started.",
  "The water in a pond is D inches deep h hours after a drain is opened.",
  "A candle is L centimeters tall t hours after it is lit.",
  "A fuel tank holds G gallons m minutes after a pump is started."
)
$change = array("It is thickening by", "It grows", "It gains", "It is dropping by", "It burns down", "It is dropping by")
$rising = 1
if ($ci >= 3) {
  $rising = 0
}

$mh = rand(2, 6)
$mabs = $mh / 2
$m = $mabs
if ($rising == 0) {
  $m = -$mabs
}
if ($rising == 1) {
  $b = rand(1, 6)
  if (mod($mh, 2) == 1) {
    $x0 = 2 * rand(1, 3) where ($mabs * $x0 <= 15 - $b)
  } else {
    $x0 = rand(2, 6) where ($mabs * $x0 <= 15 - $b)
  }
}
if ($rising == 0) {
  $b = rand(9, 15)
  if (mod($mh, 2) == 1) {
    $x0 = 2 * rand(1, 3) where ($mabs * $x0 <= $b - 1)
  } else {
    $x0 = rand(2, 6) where ($mabs * $x0 <= $b - 1)
  }
}
$y0 = $m * $x0 + $b

$sY = $yv[$ci]
$sX = $xv[$ci]
$sStory = $story[$ci]
$sChange = $change[$ci]
$sUnit = $yUnit[$ci]
$sOne = $xOne[$ci]
$sXName = $xName[$ci]
$sYName = $yName[$ci]

$answerformat[0] = "twopoint,dot,line"
$reltolerance[0] = 0.2
$grid[0] = "-1,11,-2,16,1,1,500,420"
$snaptogrid = 1

$lst = array("($m)*x+($b)")
for ($j = 0..12) {
  $xx = $j - 1
  $yy = $m * $xx + $b
  if ($yy == floor($yy) && $yy >= -2 && $yy <= 16) {
    $lst[] = "optional,$xx,$yy"
  }
}
$answers[0] = $lst

$answer[1] = $m
$answer[2] = $b
$answerboxsize[1] = 5
$answerboxsize[2] = 5
$abstolerance[1] = 0.0001
$abstolerance[2] = 0.0001
$answeights = array(3, 1, 1)

$pic = showplot(array("($m)*x+($b),blue", "dot,0,$b,closed,red", "dot,$x0,$y0,closed,red"), -1, 11, -2, 16, 1, 1, 400, 340)
$going = "up"
if ($m < 0) {
  $going = "down"
}

$sol = '
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
      <p><span class="term-label">The slope.</span> The ' . $sYName . ' goes ' . $going . ' ' . $mabs . ' ' . $sUnit . ' every ' . $sOne . ', so the slope is `m = ' . $m . '`. The sign follows the direction.</p>
      <p><span class="term-label">Find b.</span> The reading is the point `(' . $x0 . ', ' . $y0 . ')`. Put it in `' . $sY . ' = ' . $m . $sX . ' + b`: `' . $y0 . ' = ' . $m . '(' . $x0 . ') + b`, so `' . $y0 . ' = ' . ($m * $x0) . ' + b` and `b = ' . $b . '`.</p>
      <p><span class="term-label">The equation.</span> `' . $sY . ' = ' . $m . $sX . ' + ' . $b . '`. The ' . $b . ' is the ' . $sYName . ' at ' . $sX . ' = 0, where the line meets the vertical axis.</p>
      <p><span class="term-label">(a) Graph.</span> Plot `(0, ' . $b . ')` and `(' . $x0 . ', ' . $y0 . ')` and draw the line through them.</p>
      ' . $pic . '
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$sStory At $sX = $x0 it measured $y0 $sUnit. $sChange $mabs $sUnit every $sOne.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Horizontal axis: $sX, the $sXName. Vertical axis: $sY, in $sUnit. Each gridline is 1 unit.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Graph the line. Use the line tool and click two points on the line.</p>
    $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Write the equation in slope-intercept form.</p>
    <p style="margin:0;">`$sY =` $answerbox[1] `$sX +` $answerbox[2]</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter the slope with its sign, as a decimal if it is not a whole number.</p>
  </div>
</div>

// === ANSWER ===

$sol
