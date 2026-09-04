// === NAME - DESCRIPTION: Read a Line Graph Built from a Frequency Table - Recover the sample size, a relative frequency and the most common value from a line graph of a randomized frequency table, then say what the connecting segments do and do not mean ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
$contexts = array(
  "how many times they visited a store before making a major purchase",
  "how many days a week they cook a meal from scratch"
)
$xLabels = array("Number of times in store", "Days per week cooking from scratch")
$things = array("shoppers", "people")
$context = $contexts[$ci]
$xLabel = $xLabels[$ci]
$thing = $things[$ci]

$catStart = 1
if ($ci == 1) { $catStart = 0 }

// Five categories. One is pushed clear of the rest so the most common value is unique: a tie
// would make part (c) unanswerable, and ties are common if five values are drawn independently.
$f = array(0, 0, 0, 0, 0)
$maxOther = 0
for ($k=0..4) {
  $f[$k] = rand(2, 9)
  if ($f[$k] > $maxOther) { $maxOther = $f[$k] }
}
$pk = rand(1, 3)
$f[$pk] = $maxOther + rand(2, 4)
$peak = $f[$pk]

$n = $f[0] + $f[1] + $f[2] + $f[3] + $f[4]
$modeValue = $catStart + $pk

// Part (b): one category's relative frequency.
$ai = rand(0, 4)
$askValue = $catStart + $ai
$askFreq = $f[$ai]
$relFreq = $askFreq / $n

$answer[0] = $n
$answerformat[0] = "integer"

$answer[1] = $relFreq
$reltolerance[1] = 0.02
$abstolerance[1] = 0.005

$answer[2] = $modeValue
$answerformat[2] = "integer"

$questions[3] = array(
  "The height of each point is the frequency for that value. The segments only lead the eye from one value to the next.",
  "The segments show the values the variable took in between the plotted values.",
  "The height of each point is the running total of every frequency up to that value.",
  "The slope of each segment is the frequency for the value it starts from."
)
$answer[3] = 0

// Axis top rounded up to an even number so every gridline is a whole frequency.
$yMax = $peak + 2
if ($yMax % 2 == 1) { $yMax = $yMax + 1 }
$unitPx = 225 / $yMax
$gN = $yMax / 2

$grid = ""
for ($g=0..$gN) {
  $v = 2 * $g
  $gy = round(250 - $v * $unitPx, 2)
  $grid = $grid . '<line x1="55" y1="' . $gy . '" x2="443" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $grid = $grid . '<text x="48" y="' . ($gy + 4) . '" font-size="11" fill="#6b7280" text-anchor="end">' . $v . '</text>'
}

$pts = ""
$dots = ""
$xlab = ""
for ($k=0..4) {
  $cx = 55 + $k * 97
  $cy = round(250 - $f[$k] * $unitPx, 2)
  if ($k == 0) { $pts = $cx . "," . $cy }
  if ($k > 0) { $pts = $pts . " " . $cx . "," . $cy }
  $dots = $dots . '<circle cx="' . $cx . '" cy="' . $cy . '" r="4.5" fill="#1e40af"/>'
  $xlab = $xlab . '<line x1="' . $cx . '" y1="250" x2="' . $cx . '" y2="255" stroke="#374151" stroke-width="1"/>'
  $xlab = $xlab . '<text x="' . $cx . '" y="271" font-size="12" fill="#374151" text-anchor="middle">' . ($catStart + $k) . '</text>'
}

$svg = '<svg viewBox="0 0 480 310" width="100%" style="max-width:480px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $grid
$svg = $svg . '<polyline points="' . $pts . '" fill="none" stroke="#1e40af" stroke-width="2.5"/>'
$svg = $svg . $dots
$svg = $svg . '<line x1="55" y1="25" x2="55" y2="250" stroke="#374151" stroke-width="2"/><line x1="55" y1="250" x2="443" y2="250" stroke="#374151" stroke-width="2"/>'
$svg = $svg . $xlab
$svg = $svg . '<text x="249" y="295" font-size="13" fill="#374151" text-anchor="middle">' . $xLabel . '</text>'
$svg = $svg . '<text x="16" y="140" font-size="13" fill="#374151" text-anchor="middle" transform="rotate(-90 16 140)">Frequency</text>'
$svg = $svg . '</svg>'

$relRounded = round($relFreq, 3)
$relPercent = round(100 * $relFreq, 1)

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
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Step 1: read every point, then add.</span> Follow each dot across to the frequency axis: ' . $f[0] . ', ' . $f[1] . ', ' . $f[2] . ', ' . $f[3] . ', ' . $f[4] . '. The sample size is the sum of the frequencies, <b>' . $n . '</b> ' . $thing . '. It is not the number of points, which is only how many different answers were possible.</p>
      <p><span class="term-label">Step 2: relative frequency.</span> The point above ' . $askValue . ' sits at ' . $askFreq . '. Divide by the total: ' . $askFreq . ' / ' . $n . ' &approx; <b>' . $relRounded . '</b>, about ' . $relPercent . '% of those surveyed.</p>
      <p><span class="term-label">Step 3: the most common value.</span> The highest point is above <b>' . $modeValue . '</b>, so that is the answer given most often. Read the value on the horizontal axis, not the height: the height is how many people, the position is what they said.</p>
      <p><span class="term-label">Step 4: what the segments mean.</span> Nothing, on their own. This variable only takes whole-number values, so there is no data between the points; the lines are drawn to make the pattern easier to follow. Reading a value off the middle of a segment invents data that was never collected.</p>
      <p><b>Answer:</b> (a) ' . $n . ' &nbsp;&nbsp; (b) ' . $relRounded . ' &nbsp;&nbsp; (c) ' . $modeValue . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">In a survey, a group of $thing were asked $context. The line graph below shows the results.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many $thing were surveyed altogether? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>relative frequency</b> of the answer $askValue? (Give a decimal, accurate to at least 3 decimal places.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which answer was given most often? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What do the line segments joining the points tell you? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
