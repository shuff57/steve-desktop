// === NAME - DESCRIPTION: Graph a Line from Two Readings - Find the rate of change and the starting value from two time and amount readings, then draw the line for the situation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (2.4.2, Example 2.4.2, Try It Now 2.4.2, Problem 13 in 2.3). Five situations given as two readings,
// a hiker walking away from camp, a phone battery draining, a bike rental with a flat fee, a candle burning down
// and a tub filling. The rate is a
// whole number and a multiple of the gridline step u, and so is the starting value, so the start (0, b)
// and both readings sit on gridlines. The first reading is at hour 1 to 3 and the second is 2 or 3 hours
// later, so the rate needs a real division. The window runs from time -1 to 8. Every lattice point of the
// line inside the window is an optional dot. The student enters the rate and the starting value, then draws.
$anstypes = array("number", "number", "draw")

$ctx = rand(0, 4)
$u = 2
$b = 2
$rate = 2
$m = 2
$X = 7
$sit0 = ""
$sit1 = ""
$sit2 = ""
$xAxis = ""
$yAxis = ""
$xUnit = "hour"
$yUnit = ""
$yPer = ""
$startWord = ""
$growing = 1
if ($ctx == 0) {
  $u = 2
  $b = 2 * rand(1, 5)
  $rate = 2 * rand(1, 3)
  $m = $rate
  $yUnit = "kilometers"
  $yAxis = "distance from camp in kilometers"
  $yPer = "kilometers per hour"
  $startWord = "the distance from camp when the hiker started"
}
if ($ctx == 1) {
  $u = 5
  $rate = 5 * rand(1, 2)
  $b = 5 * rand(14, 20)
  $m = -$rate
  $growing = 0
  $yUnit = "percent"
  $yAxis = "battery charge in percent"
  $yPer = "percent per hour"
  $startWord = "the battery charge when it was unplugged"
}
if ($ctx == 2) {
  $u = 5
  $b = 5 * rand(1, 4)
  $rate = 5 * rand(1, 2)
  $m = $rate
  $yUnit = "dollars"
  $yAxis = "cost in dollars"
  $yPer = "dollars per hour"
  $startWord = "the flat fee"
}
if ($ctx == 3) {
  $u = 2
  $rate = 2 * rand(1, 2)
  $b = 6 * $rate + 2 * rand(2, 5)
  $m = -$rate
  $growing = 0
  $yUnit = "centimeters"
  $yAxis = "height of the candle in centimeters"
  $yPer = "centimeters per hour"
  $startWord = "the height of the candle before it was lit"
}
if ($ctx == 4) {
  $u = 2
  $b = 2 * rand(1, 5)
  $rate = 2 * rand(1, 3)
  $m = $rate
  $yUnit = "liters"
  $yAxis = "water in the tub in liters"
  $yPer = "liters per hour"
  $startWord = "the water in the tub at time 0"
}
$xAxis = "time in hours"

$x1 = rand(1, 3)
$x2 = $x1 + rand(2, 3)
$y1 = $b + $m * $x1
$y2 = $b + $m * $x2
$h1 = "hours"
if ($x1 == 1) {
  $h1 = "hour"
}
if ($ctx == 0) {
  $sit0 = "A hiker walks away from camp at a steady pace."
  $sit1 = "After $x1 $h1, the hiker is $y1 kilometers from camp."
  $sit2 = "After $x2 hours, the hiker is $y2 kilometers from camp."
}
if ($ctx == 1) {
  $sit0 = "A phone battery drains at a steady pace after it is unplugged."
  $sit1 = "After $x1 $h1, the battery reads $y1 percent."
  $sit2 = "After $x2 hours, the battery reads $y2 percent."
}
if ($ctx == 3) {
  $sit0 = "A candle burns down at a steady pace."
  $sit1 = "After $x1 $h1, the candle is $y1 centimeters tall."
  $sit2 = "After $x2 hours, the candle is $y2 centimeters tall."
}
if ($ctx == 4) {
  $sit0 = "A tub fills with water at a steady pace."
  $sit1 = "After $x1 $h1, the tub holds $y1 liters."
  $sit2 = "After $x2 hours, the tub holds $y2 liters."
}
if ($ctx == 2) {
  $sit0 = "A bike rental costs a flat fee plus the same price for each hour."
  $sit1 = "Renting for $x1 $h1 costs $y1 dollars."
  $sit2 = "Renting for $x2 hours costs $y2 dollars."
}

$dy = $y2 - $y1
$dx = $x2 - $x1
$sy1 = $y1
if ($y1 < 0) {
  $sy1 = "(" . $y1 . ")"
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

$answer[0] = $m
$answerboxsize[0] = 6
$answer[1] = $b
$answerboxsize[1] = 6
$answers[2] = $ans
$answerformat[2] = "twopoint,dot,line"
$reltolerance[2] = 0.2
$grid[2] = "$xmin,$xmax,$ymin,$ymax,1,$u,460,440"
$snaptogrid[2] = 1

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
      <p><span class="term-label">(a) Rate of change.</span> The rate is the slope of the line through the two readings `(' . $x1 . ', ' . $y1 . ')` and `(' . $x2 . ', ' . $y2 . ')`. `m = (y_2 - y_1)/(x_2 - x_1) = (' . $y2 . ' - ' . $sy1 . ')/(' . $x2 . ' - ' . $x1 . ') = ' . $dy . '/' . $dx . ' = ' . $m . '`. That is ' . $m . ' ' . $yPer . '.</p>
      <p><span class="term-label">(b) Starting value.</span> The starting value is the amount at time 0. Go back from the first reading by ' . $x1 . ' hours at ' . $m . ' per hour: `' . $y1 . ' - (' . $m . ')(' . $x1 . ') = ' . $b . '`. So the line crosses the vertical axis at `(0, ' . $b . ')`. This is ' . $startWord . '.</p>
      <p><span class="term-label">(c) Graph.</span> Plot the two readings `(' . $x1 . ', ' . $y1 . ')` and `(' . $x2 . ', ' . $y2 . ')`. Plot the starting value `(0, ' . $b . ')` as a check: it should be on the same line. Draw the line through the points.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$sit0 $sit1 $sit2</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter whole numbers, with a minus sign if the number is negative.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the rate of change? $answerbox[0] $yPer
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the starting value, the amount at time 0? $answerbox[1] $yUnit
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Graph the line for this situation.
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Horizontal axis: $xAxis. Vertical axis: $yAxis. Each horizontal gridline goes up by $u.</p>
    <p style="margin:10px 0 0 0;">$answerbox[2]</p>
  </div>
</div>

// === ANSWER ===

$sol
