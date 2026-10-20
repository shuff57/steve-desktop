// === NAME - DESCRIPTION: What the Slope Means - Pick the sentence that reads the slope of a rule as for every increase of 1, the output changes by, with units, then use that rate over a longer stretch ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (What the Slope Means). A rule is given and the student picks the sentence that
// reads its slope as "for every increase of 1 [input unit], the [output] changes by [slope]
// [output units]". The three wrong sentences are the habitual slips: the sign flipped, input and
// output swapped, and the starting value used as the rate. Part b applies the rate to a longer
// stretch of input. Magnitudes are never 1 and never equal to the starting value, so the four
// sentences are always different.
$anstypes = array("choices", "number")

$ci = rand(0, 3)
$j = rand(0, 5)
$ix = $ci * 6 + $j
$mags = array(3, 4, 5, 6, 7, 8, 2, 3, 4, 5, 6, 8, 1.5, 2, 2.5, 3, 3.5, 4, 0.4, 0.5, 0.6, 0.8, 1.2, 1.5)
$mag = $mags[$ix]
$sgns = array(-1, 1, -1, -1)
$sgn = $sgns[$ci]
$m = $sgn * $mag
$mFlip = -1 * $m

$bLos = array(60, 10, 20, 80)
$bHis = array(95, 40, 40, 100)
$bLo = $bLos[$ci]
$bHi = $bHis[$ci]
$b = rand($bLo, $bHi)

$outSyms = array("T", "P", "F", "B")
$inSyms = array("m", "t", "h", "m")
$outNames = array("temperature of the soup", "number of pages printed", "fuel in the generator", "charge of the battery")
$outShorts = array("temperature", "number of pages printed", "amount of fuel", "battery charge")
$outUnits = array("degrees", "pages", "liters", "percent")
$outSings = array("degree", "page", "liter", "percent")
$inUnits = array("minutes", "minutes", "hours", "minutes")
$inSings = array("minute", "minute", "hour", "minute")
$outSym = $outSyms[$ci]
$inSym = $inSyms[$ci]
$outName = $outNames[$ci]
$outShort = $outShorts[$ci]
$outUnit = $outUnits[$ci]
$outSing = $outSings[$ci]
$inUnit = $inUnits[$ci]
$inSing = $inSings[$ci]

$op = " + "
if ($sgn < 0) {
  $op = " - "
}
$rule = "`" . $outSym . " = " . $b . $op . $mag . $inSym . "`"

$introA = array("A bowl of soup is cooling. Its temperature in degrees is given by the rule ", "A printer is running. The pages printed so far are given by the rule ", "A generator is running. The liters of fuel left are given by the rule ", "A phone has been unplugged. The battery charge in percent is given by the rule ")
$introB = array(", where `m` is the minutes since the soup was served.", ", where `t` is the minutes since printing started.", ", where `h` is the hours since the generator was switched on.", ", where `m` is the minutes since the phone was unplugged.")
$intro = $introA[$ci] . $rule . $introB[$ci]

$questions[0] = array(
  "For every increase of 1 " . $inSing . ", the " . $outName . " changes by " . $m . " " . $outUnit . ".",
  "For every increase of 1 " . $inSing . ", the " . $outName . " changes by " . $mFlip . " " . $outUnit . ".",
  "For every increase of 1 " . $outSing . ", the time changes by " . $m . " " . $inUnit . ".",
  "For every increase of 1 " . $inSing . ", the " . $outName . " changes by " . $b . " " . $outUnit . "."
)
$answer[0] = 0

$k = rand(3, 7)
$ans = round($m * $k, 3)
$answer[1] = $ans
$abstolerance[1] = 0.001

$dirWord = "goes up"
if ($sgn < 0) {
  $dirWord = "goes down"
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

      <p><span class="term-label">Part a: find the slope.</span> In ' . $rule . ' the number that multiplies `' . $inSym . '` is the slope, with its sign. Here the slope is ' . $m . '. Its units are ' . $outUnit . ' per ' . $inSing . '.</p>
      <p>The sentence is: "For every increase of 1 ' . $inSing . ', the ' . $outName . ' changes by ' . $m . ' ' . $outUnit . '." The number ' . $b . ' is the value at the start, not the rate. The sign matters: ' . $m . ' means the ' . $outShort . ' ' . $dirWord . '.</p>
      <p><span class="term-label">Part b: use the rate.</span> In ' . $k . ' ' . $inUnit . ' the change is `' . $m . ' xx ' . $k . ' = ' . $ans . '` ' . $outUnit . '.</p>

    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What does the slope of this rule mean? Pick the sentence that says it correctly, with units.<br><br>$answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Use the rate. By how much does the $outShort change when `$inSym` increases by $k $inUnit? Give it in $outUnit. If it goes down, enter a negative number. $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
