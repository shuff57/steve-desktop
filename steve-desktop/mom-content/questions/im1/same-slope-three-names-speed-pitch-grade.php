// === NAME - DESCRIPTION: Same Slope, Three Names - Find a speed from two readings, a roof pitch from its rise and run, and a road grade from its rise and run, each as a slope with the right units ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (Slopes with Other Names). Speed, pitch and grade are all slope under another
// name. Part a is a speed from two readings, part b is a roof pitch from its rise and run, part c
// is a road grade from its rise and run (negative when the road drops). Every answer is exact:
// speed is a multiple of 0.5, the roof run is a multiple of 12 so the pitch is a whole number,
// and the road grade is a multiple of 0.5 percent over a run that is a multiple of 200 feet.
$anstypes = array("number", "number", "number")

$ci = rand(0, 2)
$r2 = rand(20, 40)
if ($ci == 1) {
  $r2 = rand(14, 24)
}
if ($ci == 2) {
  $r2 = rand(2, 8)
}
$speed = $r2 / 2
$dt = rand(2, 5)
$tA = rand(1, 4)
$tB = $tA + $dt
$dA = rand(5, 30)
$dB = $dA + $speed * $dt

$whos = array("A cyclist", "A runner", "A robot")
$dunits = array("kilometers", "kilometers", "meters")
$tunits = array("hours", "hours", "seconds")
$tsings = array("hour", "hour", "second")
$who = $whos[$ci]
$dunit = $dunits[$ci]
$tunit = $tunits[$ci]
$tsing = $tsings[$ci]

$n = rand(2, 9)
$kk = rand(2, 5)
$run = 12 * $kk
$rise = $n * $kk

$g2 = rand(4, 18)
$grade = $g2 / 2
$j = rand(1, 4)
$roadRun = 200 * $j
$roadRise = $grade * $roadRun / 100
$down = rand(0, 1)
$dirWord = "rises"
$sgn = 1
if ($down == 1) {
  $dirWord = "drops"
  $sgn = -1
}
$gradeAns = $sgn * $grade

$answer[0] = $speed
$answer[1] = $n
$answer[2] = $gradeAns
$abstolerance[0] = 0.001
$abstolerance[1] = 0.001
$abstolerance[2] = 0.001

$slopeRoad = $sgn * $roadRise
$slopeDec = round($slopeRoad / $roadRun, 5)
$dBA = $dB - $dA
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

      <p><span class="term-label">Part a: speed is a slope.</span> Speed is the change in distance divided by the change in time: `(' . $dB . ' - ' . $dA . ') / (' . $tB . ' - ' . $tA . ') = ' . $dBA . ' / ' . $dt . ' = ' . $speed . '`. The speed is ' . $speed . ' ' . $dunit . ' per ' . $tsing . '.</p>
      <p><span class="term-label">Part b: pitch is a slope over 12.</span> The slope is `' . $rise . ' / ' . $run . ' = ' . $n . ' / 12`. An `n`-in-12 pitch has slope `n / 12`, so `n = ' . $n . '`.</p>
      <p><span class="term-label">Part c: grade is the slope times 100.</span> The slope is `' . $slopeRoad . ' / ' . $roadRun . ' = ' . $slopeDec . '`. Times 100, the grade is ' . $gradeAns . ' percent. A road that drops has a negative slope and a negative grade.</p>

    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Speed, pitch and grade are all slope under another name. Find each one.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $who is $dA $dunit from the start after $tA $tunit and $dB $dunit from the start after $tB $tunit. What is its speed? Give it in $dunit per $tsing. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> A roof rises $rise feet over a horizontal run of $run feet. Its pitch is written as an `n`-in-12 pitch. What is `n`? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> A road $dirWord $roadRise feet over a horizontal distance of $roadRun feet. What is its grade, in percent? If the road drops, enter a negative number. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
