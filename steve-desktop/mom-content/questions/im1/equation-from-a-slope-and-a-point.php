// === NAME - DESCRIPTION: Equation from a Slope and a Point - Write y = mx + b from a rate and one reading far from the y-axis, predict a later value, and say what b means ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.6 (2.6.1 A Slope and a Point). The rate is given and the one reading is NOT at x = 0,
// so b has to be solved for. Falling contexts get a negative slope, and b is chosen large enough
// that every value the question asks about stays positive. The slope is a multiple of 0.25, so
// m * x1 is exact and every number printed has at most two decimals.
$anstypes = array("calculated", "calculated", "calculated", "choices")
$abstolerance = 0.0001
$answerboxsize = [10, 10, 10, 0]

$i = rand(0, 5)
$yv = array("T", "A", "P", "V", "H", "D")
$xv = array("h", "t", "t", "t", "w", "d")
$xUnit = array("hour", "second", "hour", "minute", "week", "day")
$xUnits = array("hours", "seconds", "hours", "minutes", "weeks", "days")
$dirs = array(1, -1, -1, 1, 1, -1)
$yDesc = array("the thickness of the ice in millimeters", "the altitude in meters", "the pressure in psi", "the liters in the tank", "the height in centimeters", "the snow depth in centimeters")
$xDesc = array("the hours since the cold snap began", "the seconds since the descent began", "the hours since the tire was pumped", "the minutes since the pump started", "the weeks since it was planted", "the days since the storm")
$pre = array("The ice on a pond is thickening by ", "A survey balloon is coming down by ", "A bike tire is losing ", "A tank is filling at ", "A sapling is growing ", "Snow on the ground is melting by ")
$mid = array(" millimeters every hour. At hour ", " meters every second. At second ", " psi every hour. At hour ", " liters every minute. At minute ", " centimeters every week. In week ", " centimeters every day. On day ")
$post = array(" of a cold snap it measures ", " of the descent its altitude is ", " after it was pumped its pressure is ", " after the pump starts it holds ", " after it was planted it is ", " after the storm the depth is ")
$end = array(" millimeters.", " meters.", " psi.", " liters.", " centimeters tall.", " centimeters.")

$yvar = $yv[$i]
$xvar = $xv[$i]
$unit1 = $xUnit[$i]
$unitN = $xUnits[$i]
$whatY = $yDesc[$i]
$whatX = $xDesc[$i]

$absm = randfrom(array(0.75, 1.25, 1.5, 1.75, 2.25, 2.5, 3.5, 4.5))
$m = $dirs[$i] * $absm
$x1 = rand(6, 24)
$x2 = $x1 + rand(8, 20)
$b = rand(4, 40)
if ($m < 0) {
  $b = ceil($absm * $x2) + rand(5, 30)
}
$y1 = round($m * $x1 + $b, 2)
$y2 = round($m * $x2 + $b, 2)
$mx1 = round($m * $x1, 2)
$story = $pre[$i] . $absm . $mid[$i] . $x1 . $post[$i] . $y1 . $end[$i]
$eq = $yvar . ' = ' . $m . $xvar . ' + ' . $b

$slopeWord = "goes up, so the slope is positive"
if ($m < 0) {
  $slopeWord = "goes down, so the slope is negative"
}

$questions[3] = array("The starting value, when no time has passed", "The amount it changes each " . $unit1, "The reading that was given, taken at " . $x1 . " " . $unitN, "The value after one " . $unit1)
$answer[0] = $m
$answer[1] = $b
$answer[2] = $y2
$answer[3] = 0

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
      <p><span class="term-label">(a) The slope.</span> The rate is ' . $absm . ' per ' . $unit1 . '. The value ' . $slopeWord . ': <b>m = ' . $m . '</b>.</p>
      <p><span class="term-label">(b) Solve for b.</span> Put the slope and the reading into `' . $yvar . ' = m' . $xvar . ' + b`: `' . $y1 . ' = ' . $m . '(' . $x1 . ') + b`. That gives `' . $y1 . ' = ' . $mx1 . ' + b`, so `b = ' . $y1 . ' - (' . $mx1 . ') = ' . $b . '`. The equation is `' . $eq . '`. Check the reading: `' . $m . '(' . $x1 . ') + ' . $b . ' = ' . $y1 . '`.</p>
      <p>A common mistake is to use ' . $y1 . ' as b. That is the reading at ' . $x1 . ' ' . $unitN . ', not the starting value.</p>
      <p><span class="term-label">(c) Predict.</span> At `' . $xvar . ' = ' . $x2 . '`: `' . $m . '(' . $x2 . ') + ' . $b . ' = ' . $y2 . '`.</p>
      <p><span class="term-label">(d) Meaning of b.</span> `b = ' . $b . '` is the value when `' . $xvar . ' = 0`, the starting value.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$story</p>
    <p style="margin:10px 0 0 0;">Let `$yvar` be $whatY and `$xvar` be $whatX. The equation has the form `$yvar = m$xvar + b`.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter each answer as a number. A fraction or a decimal is fine.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The slope <b>m</b> = $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The y-intercept <b>b</b> = $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Use your equation to find `$yvar` when `$xvar = $x2`. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What does <b>b</b> mean here?<br><br>$answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
