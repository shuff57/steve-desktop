// === NAME - DESCRIPTION: How Far a Model Goes - Predict beyond the readings a rule was built from, pick which input is extrapolation, decide whether the value is possible or the model breaks down, and find where the rule hits its limit ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (How Far a Model Goes). A rule was built from readings between two inputs. The
// student predicts the output at an input beyond those readings (extrapolation), spots which input
// is extrapolation, decides whether the prediction is a value the situation can have, and finds
// where the rule reaches its limit. The limit is a hard one: zero for a candle or a tub, 100
// degrees for boiling water, the capacity for a pool. Whether the prediction breaks the model is
// random. When it breaks, the output is at least a set margin past the limit; when it does not,
// the output is at least that margin inside it, so the verdict is never a judgement call.
$anstypes = array("number", "choices", "choices", "number")

$ci = rand(0, 3)
$brk = rand(0, 1)
$j = rand(0, 4)
$ix = $ci * 5 + $j
$y0s = array(18, 20, 24, 25, 30,  90, 100, 120, 150, 100,  18, 20, 22, 24, 20,  40, 50, 60, 50, 40)
$ss = array(0.1, 0.12, 0.15, 0.2, 0.25,  2, 2.5, 3, 4, 5,  1.5, 1.8, 2, 2.5, 2,  2.5, 3, 4, 5, 3)
$y0 = $y0s[$ix]
$s = $ss[$ix]
$sgns = array(-1, -1, 1, 1)
$sgn = $sgns[$ci]
$caps = array(200, 250, 300)
$Ls = array(0, 0, 100, 0)
$L = $Ls[$ci]
if ($ci == 3) {
  $cj = rand(0, 2)
  $L = $caps[$cj]
}
$margins = array(2, 10, 8, 10)
$margin = $margins[$ci]
$xas = array(10, 0, 3, 5)
$xa = $xas[$ci]

$xl = ($L - $y0) / ($sgn * $s)
$gap = $margin / $s
$xb = 5 * floor($xl / 12)
if ($xb < 5) {
  $xb = 5
}
$span = $xb - $xa
$i1 = $xa + floor($span * 0.25)
$i2 = $xa + floor($span * 0.5)
$i3 = $xa + floor($span * 0.75)

$xo = ceil($xl + $gap) + rand(0, 8)
if ($brk == 0) {
  $lo = $xb + 1
  $hi = floor($xl - $gap) - 1
  $xo = rand($lo, $hi)
}
$yo = round($y0 + $sgn * $s * $xo, 3)
$xlr = round($xl, 1)

$syms = array("H", "V", "T", "W")
$inSyms = array("t", "t", "m", "t")
$sym = $syms[$ci]
$inSym = $inSyms[$ci]
$op = " + "
if ($sgn < 0) {
  $op = " - "
}
$rule = "`" . $sym . " = " . $y0 . $op . $s . $inSym . "`"

$stories = array("Dana lit a candle and watched it from " . $xa . " to " . $xb . " minutes after lighting. From what she saw she wrote the rule " . $rule . ", where `H` is the height in centimeters and `t` is the minutes since lighting.", "A bathtub is draining. The water was measured from the moment the plug was pulled until " . $xb . " minutes later. The rule is " . $rule . ", where `V` is the liters of water left and `t` is the minutes since the plug was pulled.", "The temperature of a pot of water on a stove was measured from " . $xa . " to " . $xb . " minutes after the heat was turned on. The rule is " . $rule . ", where `T` is the temperature in degrees Celsius and `m` is the minutes.", "A pool holds " . $L . " liters when it is full. A hose is filling it. The water was measured from " . $xa . " to " . $xb . " seconds after the hose was switched on. The rule is " . $rule . ", where `W` is the liters of water in the pool and `t` is the seconds.")
$story = $stories[$ci]

$qtys = array("height of the candle", "water left in the tub", "temperature of the water", "water in the pool")
$units = array("centimeters", "liters", "degrees Celsius", "liters")
$inUnits = array("minutes", "minutes", "minutes", "seconds")
$qty = $qtys[$ci]
$unit = $units[$ci]
$inUnit = $inUnits[$ci]

$whens = array("When does the rule say the height of the candle is 0?", "When does the rule say the tub is empty?", "When does the rule say the water reaches 100 degrees?", "When does the rule say the pool is full?")
$when = $whens[$ci]

$limTexts = array("A candle cannot be shorter than 0 centimeters.", "A tub cannot hold less than 0 liters.", "Water cannot be hotter than 100 degrees Celsius as a liquid.", "The pool cannot hold more than " . $L . " liters.")
$limText = $limTexts[$ci]

$answer[0] = $yo
$abstolerance[0] = 0.001

$questions[1] = array(
  "`" . $inSym . " = " . $xo . "`",
  "`" . $inSym . " = " . $i1 . "`",
  "`" . $inSym . " = " . $i2 . "`",
  "`" . $inSym . " = " . $i3 . "`"
)
$answer[1] = 0

$questions[2] = array(
  "Yes. " . $yo . " " . $unit . " is a value the situation can have.",
  "No. " . $yo . " " . $unit . " is not a value the situation can have, so the model breaks down."
)
$answer[2] = $brk
$noshuffle[2] = "all"

$answer[3] = $xl
$abstolerance[3] = 0.06

$verdict = "This value is possible, so the prediction makes sense."
if ($brk == 1) {
  $verdict = "This value is impossible. " . $limText . " The model breaks down here."
}

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

      <p><span class="term-label">Part a: use the rule.</span> `' . $sym . ' = ' . $y0 . $op . $s . ' xx ' . $xo . ' = ' . $yo . '` ' . $unit . '.</p>
      <p><span class="term-label">Part b: inside or outside the readings.</span> The rule was built from inputs ' . $xa . ' to ' . $xb . '. Using it for an input inside that stretch is interpolation. Using it for an input outside is extrapolation. Only `' . $inSym . ' = ' . $xo . '` is past ' . $xb . ', so it is the extrapolation.</p>
      <p><span class="term-label">Part c: does it make sense?</span> ' . $verdict . '</p>
      <p><span class="term-label">Part d: where the rule reaches its limit.</span> Set the rule equal to ' . $L . ' and solve: `' . $y0 . $op . $s . $inSym . ' = ' . $L . '`, so `' . $inSym . ' = ' . $xlr . '` ' . $inUnit . ', to the nearest tenth. Past that input the rule no longer describes the situation.</p>

    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$story</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Use the rule to predict the $qty after $xo $inUnit. Give it in $unit. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which one of these inputs is extrapolation?<br><br>$answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Does the prediction in part a make sense in this situation?<br><br>$answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> $when Give it in $inUnit, rounded to the nearest tenth. $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
