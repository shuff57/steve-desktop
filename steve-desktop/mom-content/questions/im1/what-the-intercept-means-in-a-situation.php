// === NAME - DESCRIPTION: What the Intercept Means - Run a table back to input 0 to find the y-intercept, say what it means in the situation, and decide whether that value could really happen ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (What the Intercept Means). A table of four readings is given that does not
// include input 0. The student runs the pattern back to input 0 to get the y-intercept, says what
// it means, and decides whether it makes sense. Contexts 0 and 1 have a starting value that can
// really happen (b is positive). Contexts 2 and 3 have an intercept that is impossible (b is
// negative, so the model gives a negative count at input 0). Every table value stays positive.
$anstypes = array("number", "choices", "choices")

$ci = rand(0, 3)
$ok = 1
if ($ci > 1) {
  $ok = 0
}
$j = rand(0, 3)
$ix = $ci * 4 + $j

$ms = array(1.5, 2, 2.5, 3,  -0.1, -0.15, -0.2, -0.25,  10, 12, 15, 18,  4, 5, 6, 7)
$bs = array(2, 3, 4, 5,  20, 24, 25, 30,  -100, -150, -200, -240,  -8, -12, -16, -10)
$m = $ms[$ix]
$j2 = rand(0, 3)
$ix2 = $ci * 4 + $j2
$b = $bs[$ix2]

$xStarts = array(2, 10, 60, 6)
$xSteps = array(2, 10, 10, 2)
$x1 = $xStarts[$ci]
$dx = $xSteps[$ci]
$x2 = $x1 + $dx
$x3 = $x1 + 2 * $dx
$x4 = $x1 + 3 * $dx
$y1 = round($b + $m * $x1, 3)
$y2 = round($b + $m * $x2, 3)
$y3 = round($b + $m * $x3, 3)
$y4 = round($b + $m * $x4, 3)

$intros = array("A taxi company charges by the mile. The table shows the fare for four rides.", "A candle was lit and its height was measured at four times.", "A shop recorded how many ice cream cones it sold on four days with different temperatures.", "A gardener counted the leaves on a vine at four times after it sprouted.")
$xLabels = array("Miles ridden", "Minutes since lighting", "Temperature in degrees Fahrenheit", "Weeks since sprouting")
$yLabels = array("Fare in dollars", "Height in centimeters", "Cones sold", "Leaves")
$qtys = array("fare", "height of the candle", "number of cones sold", "number of leaves")
$inPhrases = array("the miles ridden", "the minutes since lighting", "the temperature", "the weeks since sprouting")
$inNames = array("miles", "minutes", "degrees", "weeks")
$inSings = array("mile", "minute", "degree", "week")
$units = array("dollars", "centimeters", "cones", "leaves")
$intro = $intros[$ci]
$xLabel = $xLabels[$ci]
$yLabel = $yLabels[$ci]
$qty = $qtys[$ci]
$inPhrase = $inPhrases[$ci]
$inName = $inNames[$ci]
$inSing = $inSings[$ci]
$unit = $units[$ci]

$tbl = '<table style="border-collapse:collapse; margin:10px 0; font-size:15px;">'
$tbl = $tbl . '<tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">' . $xLabel . '</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $x1 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $x2 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $x3 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $x4 . '</td></tr>'
$tbl = $tbl . '<tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">' . $yLabel . '</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $y1 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $y2 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $y3 . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $y4 . '</td></tr>'
$tbl = $tbl . '</table>'

$answer[0] = $b
$abstolerance[0] = 0.001

$questions[1] = array(
  "It is the " . $qty . " when " . $inPhrase . " is 0.",
  "It is how much the " . $qty . " changes for every increase of 1 " . $inSing . ".",
  "It is the " . $qty . " at the last reading in the table.",
  "It is the number of " . $inName . " when the " . $qty . " is 0."
)
$answer[1] = 0

$questions[2] = array(
  "Yes. " . $b . " " . $unit . " is a value the " . $qty . " can have.",
  "No. " . $b . " " . $unit . " is not a value the " . $qty . " can have."
)
$answer[2] = 1 - $ok
$noshuffle[2] = "all"

$why = "Yes. A starting value like this can be checked in the real situation."
if ($ok == 0) {
  $why = "No. The model reaches this number only by running the pattern back to input 0, which is outside the readings. The real situation cannot have it."
}

$slope = round(($y2 - $y1) / $dx, 3)

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

      <p><span class="term-label">Part a: run the pattern back to 0.</span> The slope is `(' . $y2 . ' - ' . $y1 . ') / (' . $x2 . ' - ' . $x1 . ') = ' . $slope . '` ' . $unit . ' per ' . $inSing . '. Going back from `x = ' . $x1 . '` to `x = 0` is a change of ' . $x1 . ' ' . $inName . ', so `b = ' . $y1 . ' - ' . $slope . ' xx ' . $x1 . ' = ' . $b . '`. The y-intercept is ' . $b . ' ' . $unit . '.</p>
      <p><span class="term-label">Part b: what it means.</span> The y-intercept is the output when the input is 0. Here it is the ' . $qty . ' when ' . $inPhrase . ' is 0. It is not the rate and it is not the last reading.</p>
      <p><span class="term-label">Part c: does it make sense?</span> ' . $why . '</p>

    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    $tbl
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the $qty when $inPhrase is 0? Give it in $unit. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does that value mean?<br><br>$answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Does that value make sense in this situation?<br><br>$answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
