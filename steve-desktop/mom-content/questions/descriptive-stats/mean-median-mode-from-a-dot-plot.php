// === NAME - DESCRIPTION: Mean, Median and Mode from a Dot Plot - Read the counts off a drawn dot plot, then produce all three measures of center from the same picture and say which one the tallest stack gives you ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Every other center question in 2.5 hands over a list or a table. This one hands over a PICTURE and
// makes the student pull the counts out of it first -- the step that separates reading a display from
// reading a spreadsheet. A dot plot is used rather than a histogram because each dot is one
// observation, so the median can be located by counting dots rather than by interpolating.
//
// Values run 1..7 with a tick on every whole number, so every dot sits on a labeled gridline and the
// step-2 rule for read-off axes is comfortably satisfied. The total is forced ODD so the median is a
// single dot, and the tallest stack is forced to be strictly tallest so the mode is unique.
$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A librarian recorded how many books each visitor borrowed one morning."
  $axisName = "Books borrowed"
  $unitWord = "books"
  $who = "visitors"
}
else {
  $intro = "A coach recorded how many goals each player scored across the season."
  $axisName = "Goals scored"
  $unitWord = "goals"
  $who = "players"
}

// Counts for values 1..7. The peak is placed at a random position and made strictly tallest.
$cnt = array(0, 0, 0, 0, 0, 0, 0)
for ($i=0..6) {
  $cnt[$i] = rand(1, 3)
}
$peak = rand(1, 5)
$cnt[$peak] = 5

$n = 0
for ($i=0..6) {
  $n = $n + $cnt[$i]
}
// Force an odd total so the median is one dot, not the average of two. Adding to the peak keeps it
// strictly tallest, so the mode cannot become ambiguous.
$par = $n % 2
if ($par == 0) {
  $cnt[$peak] = $cnt[$peak] + 1
  $n = $n + 1
}

$mode = $peak + 1

// Total of value x count, for the mean.
$tot = 0
for ($i=0..6) {
  $val = $i + 1
  $tot = $tot + $val * $cnt[$i]
}
$mean = round($tot / $n, 2)

// Median: walk the cumulative count to the middle dot.
$half = ($n + 1) / 2
$run = 0
$median = 1
$found = 0
for ($i=0..6) {
  $run = $run + $cnt[$i]
  if ($found == 0 && $run >= $half) {
    $median = $i + 1
    $found = 1
  }
}

$answer[0] = $mean
$abstolerance[0] = 0.005
$answer[1] = $median
$answer[2] = $mode
$answerboxsize = 6

$questions[3] = array(
  "The mode. The tallest stack marks the value that occurred most often, which is exactly what the mode is.",
  "The median, because the tallest stack is always in the middle of the plot.",
  "The mean, because the tallest stack is where the plot balances.",
  "None of them; the tallest stack only tells you the sample size."
)
$answer[3] = 0

// Plot geometry. One tick per whole number 1..7, one dot per observation stacked upward.
$plotL = 70
$stepX = 55
$baseY = 250
$dotStep = 22

$axisTicks = ""
for ($k=0..6) {
  $tx = $plotL + $k * $stepX
  $tval = $k + 1
  $axisTicks = $axisTicks . '<line x1="' . $tx . '" y1="250" x2="' . $tx . '" y2="257" stroke="#374151" stroke-width="1"/>'
  $axisTicks = $axisTicks . '<text x="' . $tx . '" y="274" font-size="13" fill="#374151" text-anchor="middle">' . $tval . '</text>'
}

$dots = ""
for ($k=0..6) {
  $dx = $plotL + $k * $stepX
  $c = $cnt[$k]
  $cm = $c - 1
  for ($j=0..$cm) {
    $dy = $baseY - 14 - $j * $dotStep
    $dots = $dots . '<circle cx="' . $dx . '" cy="' . $dy . '" r="8" fill="#93c5fd" stroke="#1e40af" stroke-width="1.5"/>'
  }
}

$svg = '<svg viewBox="0 0 460 300" width="100%" style="max-width:460px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $dots
$svg = $svg . '<line x1="45" y1="250" x2="425" y2="250" stroke="#374151" stroke-width="2"/>'
$svg = $svg . $axisTicks
$svg = $svg . '<text x="235" y="296" font-size="13" fill="#374151" text-anchor="middle">' . $axisName . '</text>'
$svg = $svg . '</svg>'

$c1 = $cnt[0]
$c2 = $cnt[1]
$c3 = $cnt[2]
$c4 = $cnt[3]
$c5 = $cnt[4]
$c6 = $cnt[5]
$c7 = $cnt[6]

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
      <p><span class="term-label">Step 1 &mdash; turn the picture into counts.</span> Each dot is one of the ' . $who . '. Counting up each stack:</p>
      <table style="border-collapse:collapse; margin:8px 0;">
        <tr style="background:#f0f4ff;"><th style="border:1px solid #d1d5db; padding:5px 14px;">' . $axisName . '</th><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">1</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">2</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">3</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">4</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">5</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">6</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">7</td></tr>
        <tr><th style="border:1px solid #d1d5db; padding:5px 14px; background:#f0f4ff;">How many ' . $who . '</th><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $c1 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $c2 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $c3 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $c4 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $c5 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $c6 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $c7 . '</td></tr>
      </table>
      <p>That is ' . $n . ' ' . $who . ' altogether.</p>
      <p><span class="term-label">Step 2 &mdash; the mean.</span> A stack of 3 dots over the value 5 means 5 happened three times, so multiply each value by its count rather than adding the seven labels. The values total ' . $tot . ' ' . $unitWord . ', so</p>
      <p style="text-align:center;">`bar x = ' . $tot . ' -: ' . $n . ' = ` <b>' . $mean . '</b> ' . $unitWord . '</p>
      <p><span class="term-label">Step 3 &mdash; the median.</span> With ' . $n . ' values the median is the ' . $half . 'th dot counting from the left. Walk the stacks left to right, adding counts until you reach it: the ' . $half . 'th dot sits over <b>' . $median . '</b>.</p>
      <p><span class="term-label">Step 4 &mdash; the mode.</span> The tallest stack is over <b>' . $mode . '</b>, so that is the value that occurred most often. On a dot plot the mode is the one measure you can read without any arithmetic at all.</p>
      <p><span class="term-label">The usual slip.</span> Averaging the seven axis labels &#40;1 through 7&#41; instead of the data. That answers "what is the middle of the scale", not "what is the middle of the data" &mdash; the counts are what carry the information.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$intro Each dot stands for one of the $who.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>mean</b>. Round to two decimal places. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>median</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the <b>mode</b>. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Which measure of center can you read straight off the tallest stack, with no arithmetic? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
