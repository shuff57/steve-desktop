// === NAME - DESCRIPTION: Read a Bar Graph and Compare Categories - Take one category's count off a drawn bar graph, work out how many more one category holds than another, total the whole graph, and say why the bars are drawn with gaps between them ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A sports centre recorded which class each of its members attended on one evening."
  $unitWord = "members"
  $unitOne = "member"
  $axisName = "Number of members"
  $b0 = "Yoga"
  $b1 = "Spin"
  $b2 = "Boxing"
  $b3 = "Pilates"
  $b4 = "Swim"
}
else {
  $intro = "A garden centre recorded which department each of its customers bought from on one Saturday."
  $unitWord = "customers"
  $unitOne = "customer"
  $axisName = "Number of customers"
  $b0 = "Seeds"
  $b1 = "Tools"
  $b2 = "Pots"
  $b3 = "Shrubs"
  $b4 = "Compost"
}
$labels = array($b0, $b1, $b2, $b3, $b4)

// Counts are even so every bar top lands on a gridline and the value read off is exact. They are
// drawn from five non-overlapping bands so no two categories can tie -- five independent draws
// tie often, and a tie makes "how many more than" have the answer 0, which is not a question.
// A rotation then scatters the bands across the categories so the bars are not left in order.
$band = array(0, 0, 0, 0, 0)
for ($k=0..4) {
  $band[$k] = 2 * rand(3 + 3 * $k, 5 + 3 * $k)
}
$rot = rand(0, 4)
$cts = array(0, 0, 0, 0, 0)
$n = 0
$maxC = 0
for ($k=0..4) {
  $bi = ($k + $rot) % 5
  $cts[$k] = $band[$bi]
  $n = $n + $cts[$k]
  if ($cts[$k] > $maxC) { $maxC = $cts[$k] }
}

// Two different categories to compare, with the larger named first so the answer stays positive.
$p = rand(0, 4)
$q = ($p + rand(1, 4)) % 5
$hi = $p
$lo = $q
if ($cts[$q] > $cts[$p]) {
  $hi = $q
  $lo = $p
}
$hiLabel = $labels[$hi]
$loLabel = $labels[$lo]
$diff = $cts[$hi] - $cts[$lo]

$ai = rand(0, 4)
$askLabel = $labels[$ai]
$askCount = $cts[$ai]

$answer[0] = $askCount
$answerformat[0] = "integer"

$answer[1] = $diff
$answerformat[1] = "integer"

$answer[2] = $n
$answerformat[2] = "integer"

$questions[3] = array(
  "The horizontal axis holds separate categories, not a number line, so there is no meaning to the space between two bars and the gap makes that plain.",
  "The gaps are there to make the graph easier to read; a bar graph may equally be drawn with the bars touching.",
  "The gaps stand for categories that were measured but had a count of zero.",
  "The gaps show that the categories were recorded at different times."
)
$answer[3] = 0

$top = $maxC + 2
if ($top % 2 == 1) { $top = $top + 1 }
$step = 2
if ($top > 20) { $step = 4 }
$rem = $top % $step
if ($rem > 0) { $top = $top + $step - $rem }
$gN = $top / $step
$unitPx = 195 / $top

$grid = ""
for ($g=0..$gN) {
  $v = $step * $g
  $gy = round(230 - $v * $unitPx, 2)
  $grid = $grid . '<line x1="60" y1="' . $gy . '" x2="510" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $grid = $grid . '<text x="54" y="' . ($gy + 4) . '" font-size="12" fill="#6b7280" text-anchor="end">' . $v . '</text>'
}

// A clear gap between bars: 56 wide on an 88 pitch. That gap is the point of part (d).
$bars = ""
for ($k=0..4) {
  $bx = 76 + $k * 88
  $bh = round($cts[$k] * $unitPx, 2)
  $by = round(230 - $bh, 2)
  $bars = $bars . '<rect x="' . $bx . '" y="' . $by . '" width="56" height="' . $bh . '" fill="#93c5fd" stroke="#1e40af" stroke-width="1.5"/>'
  $bars = $bars . '<text x="' . ($bx + 28) . '" y="249" font-size="12" fill="#374151" text-anchor="middle">' . $labels[$k] . '</text>'
}

$svg = '<svg viewBox="0 0 530 285" width="100%" style="max-width:530px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $grid . $bars
$svg = $svg . '<line x1="60" y1="28" x2="60" y2="230" stroke="#374151" stroke-width="2"/><line x1="60" y1="230" x2="510" y2="230" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<text x="20" y="130" font-size="12" fill="#374151" text-anchor="middle" transform="rotate(-90 20 130)">' . $axisName . '</text>'
$svg = $svg . '</svg>'

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
      <p><span class="term-label">Step 1 &mdash; read one bar.</span> Follow the top of the <b>' . $askLabel . '</b> bar straight across to the axis. It sits on <b>' . $askCount . '</b>. Every bar top here lands on a gridline, so nothing has to be estimated.</p>
      <p><span class="term-label">Step 2 &mdash; compare two bars by subtracting.</span> ' . $hiLabel . ' has ' . $cts[$hi] . ' and ' . $loLabel . ' has ' . $cts[$lo] . ', so ' . $hiLabel . ' has ' . $cts[$hi] . ' &minus; ' . $cts[$lo] . ' = <b>' . $diff . '</b> more. Subtract the counts; do not compare the heights by eye, and do not divide unless you were asked how many <i>times</i> as many.</p>
      <p><span class="term-label">Step 3 &mdash; total the graph.</span> ' . $cts[0] . ' + ' . $cts[1] . ' + ' . $cts[2] . ' + ' . $cts[3] . ' + ' . $cts[4] . ' = <b>' . $n . '</b> ' . $unitWord . '. This only works because each ' . $unitOne . ' was counted in exactly one category &mdash; if people could appear in two, the bars would overlap and the total would double-count.</p>
      <p><span class="term-label">Step 4 &mdash; why the gaps.</span> The categories are names, not numbers. Nothing lies "between" two of them, so the axis is not a number line and the bars are kept apart to say so. A histogram, whose axis <i>is</i> a number line, has its bars touching for exactly the same reason in reverse.</p>
      <p><b>Answer:</b> (a) ' . $askCount . ' &nbsp;&nbsp; (b) ' . $diff . ' &nbsp;&nbsp; (c) ' . $n . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$intro Each $unitOne is counted in exactly one category.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many $unitWord are in the <b>$askLabel</b> category? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many <b>more</b> $unitWord are in <b>$hiLabel</b> than in <b>$loLabel</b>? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many $unitWord does the graph account for altogether? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Why is a bar graph drawn with gaps between the bars? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
