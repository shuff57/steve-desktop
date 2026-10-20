// === NAME - DESCRIPTION: Graph a Situation from a Starting Amount and a Rate - Plot the starting value, use the rate per unit to get a second point, draw the line, then find the amount at a stated time ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (2.4.1, Example 2.4.1, Try It Now 2.4.1). Six situations, three that grow (savings, a pool
// filling, a bamboo shoot) and three that shrink (a tank draining, a candle burning down, a glider sinking). The start b and the rate are whole numbers
// that are multiples of the gridline step u, so the starting point and every whole-number point on the line
// sit on a gridline. A shrinking line is built to reach 0 at a whole-number time T, which is the second point
// the student plots. The window is time from -1 to the end plus 1, and amount from -u to one step above the top.
// Every lattice point of the line inside the window is an optional dot. The value asked for is at a time k
// that is a whole number inside the part of the line that is above 0.
$anstypes = array("draw", "number")

$ctx = rand(0, 5)
$name = randfrom(array("Dana", "Marcus", "Priya", "Leo"))
$u = 5
$b = 5
$rate = 5
$m = 5
$X = 6
$T = 6
$x2 = 6
$y2 = 0
$growing = 1
$sit = ""
$xAxis = ""
$yAxis = ""
$xUnit = ""
$yUnit = ""
$ask = ""
if ($ctx == 0) {
  $u = 5
  $b = 5 * rand(1, 4)
  $rate = 5 * rand(1, 3)
  $m = $rate
  $X = 6
  $sit = "$name has $b dollars saved and adds $rate dollars every week."
  $xAxis = "time in weeks"
  $yAxis = "savings in dollars"
  $xUnit = "week"
  $yUnit = "dollars"
}
if ($ctx == 1) {
  $u = 2
  $b = 2 * rand(1, 5)
  $rate = 2 * rand(1, 3)
  $m = $rate
  $X = 6
  $sit = "A small pool has $b liters of water in it. A hose adds $rate liters every minute."
  $xAxis = "time in minutes"
  $yAxis = "water in the pool in liters"
  $xUnit = "minute"
  $yUnit = "liters"
}
if ($ctx == 2) {
  $u = 2
  $rate = 2 * rand(1, 3)
  $T = rand(4, 8)
  $b = $rate * $T
  $m = -$rate
  $X = 8
  $growing = 0
  $sit = "A tank holds $b liters of water. A pump removes $rate liters every minute."
  $xAxis = "time in minutes"
  $yAxis = "water in the tank in liters"
  $xUnit = "minute"
  $yUnit = "liters"
}
if ($ctx == 3) {
  $u = 1
  $rate = rand(1, 3)
  $T = rand(4, 8)
  $b = $rate * $T
  $m = -$rate
  $X = 8
  $growing = 0
  $sit = "A candle is $b centimeters tall. It burns down $rate centimeters every hour."
  $xAxis = "time in hours"
  $yAxis = "height of the candle in centimeters"
  $xUnit = "hour"
  $yUnit = "centimeters"
}

if ($ctx == 4) {
  $u = 2
  $b = 2 * rand(2, 7)
  $rate = 2 * rand(1, 3)
  $m = $rate
  $X = 6
  $sit = "A bamboo shoot is $b centimeters tall when it is first measured. It grows $rate centimeters every day."
  $xAxis = "time in days"
  $yAxis = "height of the shoot in centimeters"
  $xUnit = "day"
  $yUnit = "centimeters"
}
if ($ctx == 5) {
  $u = 5
  $rate = 5 * rand(1, 2)
  $T = rand(4, 8)
  $b = $rate * $T
  $m = -$rate
  $X = 8
  $growing = 0
  $sit = "A glider starts $b meters above the ground. It sinks $rate meters every second."
  $xAxis = "time in seconds"
  $yAxis = "height of the glider in meters"
  $xUnit = "second"
  $yUnit = "meters"
}

if ($growing == 1) {
  $k = rand(2, $X - 1)
  $x2 = $X
  $y2 = $b + $m * $X
} else {
  $k = rand(2, $T - 1)
  $x2 = $T
  $y2 = 0
}
$val = $b + $m * $k
if ($ctx == 0) {
  $ask = "How many dollars does $name have after $k weeks?"
}
if ($ctx == 1) {
  $ask = "How many liters are in the pool after $k minutes?"
}
if ($ctx == 2) {
  $ask = "How many liters are left in the tank after $k minutes?"
}
if ($ctx == 3) {
  $ask = "How tall is the candle after $k hours?"
}
if ($ctx == 4) {
  $ask = "How tall is the shoot after $k days?"
}
if ($ctx == 5) {
  $ask = "How many meters above the ground is the glider after $k seconds?"
}

$xmin = -1
$xmax = $X + 1
$top = max($b, $b + $m * $X)
$ymax = (floor($top / $u) + 1) * $u
$ymin = -$u

$ans = array("$m * x + $b")
$kk = 1
$nIter = $X + 2
for ($i = 0..$nIter) {
  $xx = $xmin + $i
  $yy = $b + $m * $xx
  if ($yy >= $ymin && $yy <= $ymax) {
    $ans[$kk] = "optional,$xx,$yy"
    $kk = $kk + 1
  }
}

$answers[0] = $ans
$answerformat[0] = "twopoint,dot,line"
$reltolerance[0] = 0.2
$grid[0] = "$xmin,$xmax,$ymin,$ymax,1,$u,460,440"
$snaptogrid[0] = 1

$answer[1] = $val
$answerboxsize[1] = 6

$changeSentence = "For every increase of 1 " . $xUnit . ", the amount increases by " . $rate . " " . $yUnit . "."
$calc = $b . " + " . $rate . " &times; " . $k . " = " . $b . " + " . ($rate * $k) . " = " . $val
$secondStep = "The rate is " . $rate . " " . $yUnit . " for every 1 " . $xUnit . ", so after " . $X . " " . $xUnit . "s the amount has gone up by " . ($rate * $X) . " to " . $y2 . ". That gives the point `(" . $X . ", " . $y2 . ")`."
if ($growing == 0) {
  $changeSentence = "For every increase of 1 " . $xUnit . ", the amount changes by -" . $rate . " " . $yUnit . "."
  $calc = $b . " - " . $rate . " &times; " . $k . " = " . $b . " - " . ($rate * $k) . " = " . $val
  $secondStep = "The amount drops " . $rate . " " . $yUnit . " every " . $xUnit . ", so it reaches 0 after " . $b . " &divide; " . $rate . " = " . $T . " " . $xUnit . "s. That gives the point `(" . $T . ", 0)`."
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
      <p><span class="term-label">Starting value and rate.</span> The starting value is ' . $b . ' ' . $yUnit . ', so the line crosses the vertical axis at `(0, ' . $b . ')`. The rate of change is the slope. ' . $changeSentence . ' The slope is `' . $m . '`.</p>
      <p><span class="term-label">(a) Graph.</span> Plot `(0, ' . $b . ')`. ' . $secondStep . ' Draw the line through the two points.</p>
      <p><span class="term-label">(b) The amount after ' . $k . ' ' . $xUnit . 's.</span> Start plus rate times time: ' . $calc . ' ' . $yUnit . '. On the graph, the line is at height ' . $val . ' when the time is ' . $k . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$sit</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Graph the line for this situation.
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Horizontal axis: $xAxis. Vertical axis: $yAxis. Each horizontal gridline goes up by $u.</p>
    <p style="margin:10px 0 0 0;">$answerbox[0]</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $ask $answerbox[1] $yUnit
  </div>
</div>

// === ANSWER ===

$sol
