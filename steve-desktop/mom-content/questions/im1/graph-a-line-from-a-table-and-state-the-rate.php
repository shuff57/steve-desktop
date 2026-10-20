// === NAME - DESCRIPTION: Graph a Line from a Table and State the Rate - Find the rate of change from a table of four readings, choose its units, then plot the points and draw the line ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.4 (2.4.2, Definition 2.4.1, Investigate 2.4.5 and 2.4.2). Four situations given as a table of four
// pairs, two that grow (water in a tub, savings) and two that shrink (gas in a tank, a cooling bowl of soup).
// The time column starts at 0 or 1 and steps by 1 or 2. The rate is a whole number and a multiple of the
// gridline step u, as is every amount in the table, so every table point sits on a gridline. A shrinking
// line stays above 0 at the last table time. The student enters the rate, picks the units of the rate (output
// units per input unit, against the flipped version and the bare units) and draws. The window runs from
// time -1 to two past the last table time. Every lattice point of the line inside the window is an optional dot.
$anstypes = array("number", "choices", "draw")

$ctx = rand(0, 3)
$u = 2
$b = 2
$rate = 2
$m = 2
$growing = 1
$intro = ""
$xHead = ""
$yHead = ""
$xAxis = ""
$yAxis = ""
$xUnit = ""
$xPlural = ""
$ySing = ""
$yUnit = ""

$x0 = rand(0, 1)
$s = rand(1, 2)
$x3 = $x0 + 3 * $s

if ($ctx == 0) {
  $u = 2
  $b = 2 * rand(1, 4)
  $rate = 2 * rand(1, 2)
  $m = $rate
  $intro = "The table shows the water in a tub as it fills."
  $xHead = "Time in minutes"
  $yHead = "Water in liters"
  $xAxis = "time in minutes"
  $yAxis = "water in the tub in liters"
  $xUnit = "minute"
  $xPlural = "minutes"
  $ySing = "liter"
  $yUnit = "liters"
}
if ($ctx == 1) {
  $u = 1
  $rate = rand(1, 2)
  $m = -$rate
  $growing = 0
  $b = rand(2, 5) + $rate * $x3
  $intro = "The table shows the gas in the tank of a car during a drive."
  $xHead = "Time in hours"
  $yHead = "Gas in gallons"
  $xAxis = "time in hours"
  $yAxis = "gas in the tank in gallons"
  $xUnit = "hour"
  $xPlural = "hours"
  $ySing = "gallon"
  $yUnit = "gallons"
}
if ($ctx == 2) {
  $u = 4
  $rate = 4 * rand(1, 2)
  $m = -$rate
  $growing = 0
  $b = 4 * rand(4, 8) + $rate * $x3
  $intro = "The table shows the temperature of a bowl of soup as it cools."
  $xHead = "Time in minutes"
  $yHead = "Temperature in degrees Celsius"
  $xAxis = "time in minutes"
  $yAxis = "temperature in degrees Celsius"
  $xUnit = "minute"
  $xPlural = "minutes"
  $ySing = "degree Celsius"
  $yUnit = "degrees Celsius"
}
if ($ctx == 3) {
  $u = 5
  $b = 5 * rand(1, 4)
  $rate = 5 * rand(1, 2)
  $m = $rate
  $intro = "The table shows the money saved for a trip."
  $xHead = "Time in weeks"
  $yHead = "Savings in dollars"
  $xAxis = "time in weeks"
  $yAxis = "savings in dollars"
  $xUnit = "week"
  $xPlural = "weeks"
  $ySing = "dollar"
  $yUnit = "dollars"
}

for ($i = 0..3) {
  $tx[$i] = $x0 + $s * $i
  $ty[$i] = $b + $m * $tx[$i]
}

$xmin = -1
$xmax = $x3 + 2
$top = max($b, $b + $m * $xmax)
$ymax = (floor($top / $u) + 1) * $u
$ymin = -$u
$nIter = $xmax - $xmin

$ans = array("$m * x + $b")
$kk = 1
for ($i = 0..$nIter) {
  $xx = $xmin + $i
  $yy = $b + $m * $xx
  if ($yy >= $ymin && $yy <= $ymax) {
    $ans[$kk] = "optional,$xx,$yy"
    $kk = $kk + 1
  }
}

$questions[1] = array("$yUnit per $xUnit", "$xPlural per $ySing", "$yUnit", "$xPlural")
$answer[0] = $m
$answerboxsize[0] = 6
$answer[1] = 0
$displayformat[1] = "select"
$answers[2] = $ans
$answerformat[2] = "twopoint,dot,line"
$reltolerance[2] = 0.2
$grid[2] = "$xmin,$xmax,$ymin,$ymax,1,$u,460,440"
$snaptogrid[2] = 1

$dyT = $ty[1] - $ty[0]
$sy0 = $ty[0]
$changeSentence = "For every increase of 1 " . $xUnit . ", the amount changes by " . $m . " " . $yUnit . "."
$unitWhy = "The rate is the change in " . $yUnit . " divided by the change in " . $xPlural . ", so its units are " . $yUnit . " per " . $xUnit . "."

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
      <p><span class="term-label">(a) Rate of change.</span> Take two pairs from the table, `(' . $tx[0] . ', ' . $ty[0] . ')` and `(' . $tx[1] . ', ' . $ty[1] . ')`. The change in the output is `' . $ty[1] . ' - ' . $sy0 . ' = ' . $dyT . '` and the change in the input is `' . $tx[1] . ' - ' . $tx[0] . ' = ' . $s . '`. The rate is `' . $dyT . '/' . $s . ' = ' . $m . '`. The other pairs give the same rate, so the table is a line.</p>
      <p><span class="term-label">(b) Units.</span> ' . $unitWhy . ' ' . $changeSentence . '</p>
      <p><span class="term-label">(c) Graph.</span> Plot the four pairs from the table and draw the line through them. The line crosses the vertical axis at `(0, ' . $b . ')`, the starting value.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    <table style="border-collapse:collapse; margin:12px 0 0 0;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 12px; text-align:left;">$xHead</th><td style="border:1px solid #d1d5db; padding:6px 12px; text-align:center;">$tx[0]</td><td style="border:1px solid #d1d5db; padding:6px 12px; text-align:center;">$tx[1]</td><td style="border:1px solid #d1d5db; padding:6px 12px; text-align:center;">$tx[2]</td><td style="border:1px solid #d1d5db; padding:6px 12px; text-align:center;">$tx[3]</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 12px; text-align:left;">$yHead</th><td style="border:1px solid #d1d5db; padding:6px 12px; text-align:center;">$ty[0]</td><td style="border:1px solid #d1d5db; padding:6px 12px; text-align:center;">$ty[1]</td><td style="border:1px solid #d1d5db; padding:6px 12px; text-align:center;">$ty[2]</td><td style="border:1px solid #d1d5db; padding:6px 12px; text-align:center;">$ty[3]</td></tr>
    </table>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter the rate as a whole number, with a minus sign if it is negative.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the rate of change? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What are the units of the rate of change? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Graph the line for this situation.
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Horizontal axis: $xAxis. Vertical axis: $yAxis. Each horizontal gridline goes up by $u.</p>
    <p style="margin:10px 0 0 0;">$answerbox[2]</p>
  </div>
</div>

// === ANSWER ===

$sol
