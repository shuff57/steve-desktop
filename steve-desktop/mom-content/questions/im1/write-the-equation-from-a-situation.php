// === NAME - DESCRIPTION: Write the Equation from a Situation - Turn so much every so often and a starting value into y = mx + b, naming the rate with units and the starting value, then use the rule to find a later value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (From a Situation to a Line). The situation gives a starting value and a rate as
// "so much every so often". The student turns the rate into units per one unit of time (m), names
// the starting value (b), then uses the rule y = mx + b to find a later value. D/T always ends in
// at most three decimal places, and decreasing situations start high enough that the value at
// the asked time is still above zero.
$anstypes = array("number", "number", "number")

$ci = rand(0, 3)
$D = rand(3, 12)
$Ts = array(2, 4, 5, 8, 10)
$ti = rand(0, 4)
$T = $Ts[$ti]
$k = rand(2, 5)
$x3 = $T * $k

$sgns = array(1, -1, 1, -1)
$sgn = $sgns[$ci]
$sD = $sgn * $D
$m = round($sD / $T, 3)

$b = rand(5, 60)
if ($sgn < 0) {
  $b = 6 * $D + rand(2, 20)
}
$val = $b + $sD * $k

$qtys = array("water in the pool", "height of the glider", "distance from camp", "water in the tank")
$qunits = array("liters", "meters", "kilometers", "liters")
$tunits = array("seconds", "seconds", "hours", "seconds")
$tsings = array("second", "second", "hour", "second")
$qty = $qtys[$ci]
$qunit = $qunits[$ci]
$tunit = $tunits[$ci]
$tsing = $tsings[$ci]

if ($ci == 0) {
  $story = "A pool holds " . $b . " liters of water when a hose is switched on. The hose adds " . $D . " liters every " . $T . " seconds."
}
if ($ci == 1) {
  $story = "A glider is " . $b . " meters above the ground. It sinks " . $D . " meters every " . $T . " seconds."
}
if ($ci == 2) {
  $story = "A hiker starts " . $b . " kilometers from camp and walks away from it, going " . $D . " kilometers every " . $T . " hours."
}
if ($ci == 3) {
  $story = "A tank holds " . $b . " liters of water. A drain removes " . $D . " liters every " . $T . " seconds."
}

$rule = $m . "x + " . $b

$answer[0] = $m
$answer[1] = $b
$answer[2] = $val
$abstolerance[0] = 0.001
$abstolerance[1] = 0.001
$abstolerance[2] = 0.001

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

      <p><span class="term-label">Step 1: the rate in one ' . $tsing . '.</span> The ' . $qty . ' changes by ' . $sD . ' ' . $qunit . ' every ' . $T . ' ' . $tunit . '. In one ' . $tsing . ' that is `' . $sD . ' / ' . $T . ' = ' . $m . '`. So `m = ' . $m . '` ' . $qunit . ' per ' . $tsing . '.</p>
      <p><span class="term-label">Step 2: the starting value.</span> It is the ' . $qty . ' at `x = 0`, so `b = ' . $b . '` ' . $qunit . '.</p>
      <p><span class="term-label">Step 3: the rule.</span> `y = mx + b` becomes `y = ' . $rule . '`.</p>
      <p><span class="term-label">Step 4: the value at ' . $x3 . ' ' . $tunit . '.</span> `y = ' . $m . ' xx ' . $x3 . ' + ' . $b . ' = ' . $val . '` ' . $qunit . '.</p>
      <p><b>Answer:</b> (a) ' . $m . ' &nbsp;&nbsp; (b) ' . $b . ' &nbsp;&nbsp; (c) ' . $val . '</p>

    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$story</p>
    <p style="margin:10px 0 0 0;">Write the rule `y = mx + b`, where `x` is the time in $tunit and `y` is the $qty in $qunit.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the rate of change `m`? Give it in $qunit per $tsing. If the $qty goes down, enter a negative number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the starting value `b`, in $qunit? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Use your rule to find the $qty after $x3 $tunit. Give it in $qunit. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
