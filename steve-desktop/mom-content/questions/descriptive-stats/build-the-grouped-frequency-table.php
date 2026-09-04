// === NAME - DESCRIPTION: Build the Grouped Frequency Table for a Histogram - Sort twenty raw measurements into five given classes, enter each class frequency and the total, then say why the bars of a histogram touch while a bar graph leaves gaps ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// 2.2 had no question where the student produces a histogram: all five read a finished one.
// MyOpenMath has no bar or histogram drawing primitive, so the construction work IS the grouped
// frequency table: tally the raw values into classes and the histogram is fully determined.
//
// Every generated value sits STRICTLY inside a class, never on a boundary. The boundary convention
// is still stated in the prompt, because students must know it, but no answer depends on which way
// a borderline value is pushed: that is the difference between testing the skill and testing
// whether the student guessed the same convention the key used.
$anstypes = array("number", "number", "number", "number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A biologist recorded the height, in centimeters, of each of twenty seedlings."
  $unit = "centimeters"
}
else {
  $intro = "A garage recorded how many minutes each of twenty repair jobs took."
  $unit = "minutes"
}

$lo = 10 * rand(2, 4)
$w = 10

// Deal exactly twenty measurements out across the five classes, rather than drawing five
// independent counts: the prompt says twenty, and independent counts summed to anywhere from ten to
// thirty, so the prose and the data disagreed on every seed.
$cnt = array(0, 0, 0, 0, 0)
$total = 20
for ($k=0..19) {
  $cls = rand(0, 4)
  $cnt[$cls] = $cnt[$cls] + 1
}
$c0 = $cnt[0]
$c1 = $cnt[1]
$c2 = $cnt[2]
$c3 = $cnt[3]
$c4 = $cnt[4]

// Class boundaries, as scalars for the question text.
$b0 = $lo
$b1 = $lo + $w
$b2 = $lo + 2 * $w
$b3 = $lo + 3 * $w
$b4 = $lo + 4 * $w
$b5 = $lo + 5 * $w
$e0 = $b1 - 1
$e1 = $b2 - 1
$e2 = $b3 - 1
$e3 = $b4 - 1
$e4 = $b5 - 1

// Build the values class by class, then read them out in a scattered order so the student has to
// tally rather than copy runs straight off the list.
$vals = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)
$n = 0
for ($i=0..4) {
  $base = $lo + $i * $w
  $howMany = $cnt[$i]
  $last = $howMany - 1
  for ($j=0..$last) {
    $vals[$n] = $base + 1 + rand(0, 8)
    $n = $n + 1
  }
}

// The stride MUST be coprime with n or ($k * $off) % $n revisits the same few indices instead of
// permuting: a stride of 8 on twenty values printed "21, 44, 21, 44, ..." forever. With n fixed at
// 20 the safe strides are the odd ones that are not multiples of five.
$strides = array(7, 9, 11, 13)
$off = $strides[rand(0, 3)]
$shown = ""
$first = 1
for ($k=0..$n-1) {
  $idx = ($k * $off) % $n
  $val = $vals[$idx]
  if ($first == 1) { $shown = "" . $val }
  if ($first == 0) { $shown = $shown . ", " . $val }
  $first = 0
}

$answer[0] = $c0
$answer[1] = $c1
$answer[2] = $c2
$answer[3] = $c3
$answer[4] = $c4
$answer[5] = $total
for ($k=0..5) {
  $answerformat[$k] = "integer"
}
$answerboxsize = 5

$questions[6] = array(
  "The horizontal axis is a number line and each class runs straight into the next, so there is no gap between them to draw.",
  "The bars touch only because a histogram usually has more bars than a bar graph.",
  "The bars touch to show that every class holds the same number of measurements.",
  "The bars touch because the classes are categories, and categories are always drawn touching."
)
$answer[6] = 0

$rows = ""
for ($i=0..4) {
  $rlo = $lo + $i * $w
  $rhi = $rlo + $w - 1
  $rows = $rows . '<tr><td style="border:1px solid #d1d5db; padding:5px 16px; text-align:center;">' . $rlo . ' &ndash; ' . $rhi . '</td><td style="border:1px solid #d1d5db; padding:5px 16px; text-align:center;">' . $cnt[$i] . '</td></tr>'
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
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Step 1: work down the list once, tallying.</span> Take each value in turn and put a mark against the class it falls in. Going class by class instead means reading the whole list five times and losing your place; one pass with five tally marks is the method.</p>
      <p><span class="term-label">Step 2: the finished table.</span></p>
      <table style="border-collapse:collapse; margin:8px 0;">
        <tr style="background:#f0f4ff;"><th style="border:1px solid #d1d5db; padding:5px 16px;">Class</th><th style="border:1px solid #d1d5db; padding:5px 16px;">Frequency</th></tr>
        ' . $rows . '
      </table>
      <p><span class="term-label">Step 3: check the total.</span> The frequencies add to <b>' . $total . '</b>, and there were ' . $total . ' measurements. If your column does not add to that, a value has been counted twice or missed: and the check is the whole reason to add the column up at all.</p>
      <p><span class="term-label">You have now built the histogram.</span> Each class is a bar and its frequency is the height. Nothing else is needed: the table fixes the picture completely.</p>
      <p><span class="term-label">Why the bars touch.</span> On a histogram the horizontal axis is a NUMBER LINE, and ' . $b1 . ' is where one class ends and the next begins: there is no space between them to leave. On a bar graph the axis holds separate categories with nothing in between, so the bars are drawn apart. That gap, or the lack of it, is how a reader tells the two displays apart.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$intro The measurements are listed below, in the order they were collected.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$shown</p>
    <p style="margin:12px 0 0 0;"><b>Build the grouped frequency table.</b> The five classes below each span $w $unit. A value falling exactly on a boundary belongs to the class it starts, so $b1 would go in the $b1&ndash;$e1 row.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <table style="border-collapse:collapse; margin:0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 16px;">Class</th>
        <th style="border:1px solid #d1d5db; padding:6px 16px;">Frequency</th>
        <th style="border:1px solid #d1d5db; padding:6px 10px; font-size:13px; color:#6b7280;">Part</th>
      </tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">$b0 &ndash; $e0</td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[0]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">a.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">$b1 &ndash; $e1</td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[1]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">b.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">$b2 &ndash; $e2</td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[2]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">c.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">$b3 &ndash; $e3</td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[3]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">d.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">$b4 &ndash; $e4</td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[4]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">e.</td></tr>
      <tr style="background:#f8fafc;"><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;"><b>Total</b></td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[5]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">f.</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">g.</span> On the histogram you could now draw, the bars <b>touch</b>. On a bar graph they do not. Why? $answerbox[6]
  </div>
</div>

// === ANSWER ===

$solutionguide
