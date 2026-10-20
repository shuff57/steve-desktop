// === NAME - DESCRIPTION: Convert a Rate with One Conversion Factor - Find the unit rate, choose the factor that cancels the old unit, then change the unit of length ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.5. Section 2.5.1: a rate given as "so much every so often" becomes a unit rate, then one
// conversion factor changes the unit of length. The time unit stays the same, so only one factor is needed.
// The unit rate u is a multiple of 0.5 and the time is even, so the distance is a whole number and every
// answer is exact. The unit rate is at least 1.5, so no distance reads "1 meters".
$anstypes = array("number", "choices", "number")

$c = rand(0, 7)
$whoA = array("A robot moves", "A toy car rolls", "A tram travels", "A cyclist rides", "A conveyor belt moves", "A cart rolls", "A garden bot rolls", "A line painting robot paints")
$bigA = array("meters", "meters", "kilometers", "kilometers", "feet", "feet", "yards", "yards")
$bigOneA = array("meter", "meter", "kilometer", "kilometer", "foot", "foot", "yard", "yard")
$smallA = array("centimeters", "centimeters", "meters", "meters", "inches", "inches", "inches", "inches")
$smallOneA = array("centimeter", "centimeter", "meter", "meter", "inch", "inch", "inch", "inch")
$facA = array(100, 100, 1000, 1000, 12, 12, 36, 36)
$timeA = array("seconds", "seconds", "minutes", "hours", "seconds", "seconds", "minutes", "minutes")
$timeOneA = array("second", "second", "minute", "hour", "second", "second", "minute", "minute")
$loA = array(3, 3, 3, 10, 3, 3, 3, 3)
$hiA = array(18, 18, 5, 30, 18, 18, 16, 16)

$who = $whoA[$c]
$bigName = $bigA[$c]
$bigOne = $bigOneA[$c]
$smallName = $smallA[$c]
$smallOne = $smallOneA[$c]
$fac = $facA[$c]
$tWord = $timeA[$c]
$tOne = $timeOneA[$c]

$nT = randfrom(array(2, 4, 6, 8, 10))
$u = rand($loA[$c], $hiA[$c]) / 2
$nD = round($u * $nT, 2)
$ans = round($u * $fac, 2)

$questions[1] = array("$fac $smallName on top and 1 $bigOne on the bottom", "1 $bigOne on top and $fac $smallName on the bottom", "$fac $bigName on top and 1 $smallOne on the bottom")
$answer[0] = $u
$answer[1] = 0
$answer[2] = $ans
$abstolerance[0] = 0.001
$abstolerance[2] = 0.001

$sol = '<style>
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
      <p><span class="term-label">(a) Unit rate.</span> Divide the amount by the time: ' . $nD . ' ' . $bigName . ' &divide; ' . $nT . ' ' . $tWord . ' = ' . $u . ' ' . $bigName . ' per ' . $tOne . '.</p>
      <p><span class="term-label">(b) The factor.</span> There are ' . $fac . ' ' . $smallName . ' in 1 ' . $bigOne . '. Put the unit you want on top and the unit you want to remove on the bottom, so the ' . $bigName . ' cancel: ' . $fac . ' ' . $smallName . ' over 1 ' . $bigOne . '. That fraction equals 1, so it changes the unit and not the speed.</p>
      <p><span class="term-label">(c) Change the unit.</span> Multiply the unit rate by the factor: ' . $u . ' &times; ' . $fac . ' = ' . $ans . '. The speed is ' . $ans . ' ' . $smallName . ' per ' . $tOne . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$who $nD $bigName every $nT $tWord.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter numbers only. Every answer is exact, so there is nothing to round.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the unit rate. How many $bigName per $tOne? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which conversion factor changes $bigName to $smallName and cancels the $bigName?<br><br>$answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Write the speed in $smallName per $tOne. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
