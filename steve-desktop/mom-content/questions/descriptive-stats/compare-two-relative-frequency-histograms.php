// === NAME - DESCRIPTION: Compare Two Groups of Different Sizes - Two relative frequency histograms drawn from surveys with different sample sizes; read one class's share, decide which group spends more, and say why raw counts cannot be compared here ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A cruise line surveyed single travellers and couples about their on-board bill for a seven-day cruise."
  $gA = "Singles"
  $gB = "Couples"
  $xLabel = "On-board bill in dollars"
  $unitOne = "traveller"
}
else {
  $intro = "A festival surveyed day-ticket holders and weekend-pass holders about what they spent on food over the event."
  $gA = "Day tickets"
  $gB = "Weekend passes"
  $xLabel = "Food spending in dollars"
  $unitOne = "ticket holder"
}

// Group A sits low and group B sits high, so which group spends more is never in doubt. The two
// sample sizes are deliberately different -- that is the whole reason relative frequency exists.
$aBase = array(9, 12, 8, 5, 2)
$bBase = array(2, 5, 9, 12, 8)

$ca = array(0, 0, 0, 0, 0)
$cb = array(0, 0, 0, 0, 0)
$nA = 0
$nB = 0
for ($k=0..4) {
  $ca[$k] = $aBase[$k] + rand(0, 3)
  $cb[$k] = $bBase[$k] + rand(0, 3)
  $nA = $nA + $ca[$k]
  $nB = $nB + $cb[$k]
}

$ra = array(0, 0, 0, 0, 0)
$rb = array(0, 0, 0, 0, 0)
$maxR = 0
for ($k=0..4) {
  $ra[$k] = $ca[$k] / $nA
  $rb[$k] = $cb[$k] / $nB
  if ($ra[$k] > $maxR) { $maxR = $ra[$k] }
  if ($rb[$k] > $maxR) { $maxR = $rb[$k] }
}

$lo0 = 50
$width = 50
$ak = rand(0, 4)
$askLo = $lo0 + $width * $ak
$askHi = $askLo + $width
$askCount = $ca[$ak]
$askRel = round($ra[$ak], 3)
$askRelPct = round(100 * $ra[$ak], 1)

$answer[0] = $askRel
$reltolerance[0] = 0.02
$abstolerance[0] = 0.005

$answer[1] = $nA
$answerformat[1] = "integer"

$questions[2] = array(
  "The two groups are different sizes, so a taller bar can just mean more people were surveyed. Dividing by each group's own total puts both on the same footing.",
  "Relative frequency is easier to read than a count, but either one would compare the groups correctly.",
  "Counts cannot be used because the classes are ranges of dollars rather than single values.",
  "Counts cannot be used because relative frequencies always add to 1 and counts do not."
)
$answer[2] = 0

// Both histograms share one vertical scale, so the two shapes really are comparable by eye.
$topR = round($maxR * 100 / 5 + 0.5, 0) * 5
$gN = $topR / 5
$sv = array("", "")
for ($vv=0..1) {
  $grid = ""
  for ($g=0..$gN) {
    $val = 5 * $g
    $gy = round(215 - $val * 180 / $topR, 2)
    $grid = $grid . '<line x1="58" y1="' . $gy . '" x2="438" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
    $grid = $grid . '<text x="52" y="' . ($gy + 4) . '" font-size="11" fill="#6b7280" text-anchor="end">' . $val . '</text>'
  }
  $bars = ""
  $ticks = ""
  for ($k=0..4) {
    $pct = 100 * $ra[$k]
    if ($vv == 1) { $pct = 100 * $rb[$k] }
    $bx = 58 + $k * 76
    $bh = round($pct * 180 / $topR, 2)
    $by = round(215 - $bh, 2)
    $bars = $bars . '<rect x="' . $bx . '" y="' . $by . '" width="76" height="' . $bh . '" fill="#93c5fd" stroke="#1e40af" stroke-width="1.5"/>'
  }
  for ($k=0..5) {
    $tx = 58 + $k * 76
    $ticks = $ticks . '<line x1="' . $tx . '" y1="215" x2="' . $tx . '" y2="220" stroke="#374151" stroke-width="1"/>'
    $ticks = $ticks . '<text x="' . $tx . '" y="235" font-size="11" fill="#374151" text-anchor="middle">' . ($lo0 + $width * $k) . '</text>'
  }
  $one = '<svg viewBox="0 0 455 268" width="100%" style="max-width:455px; display:block; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
  $one = $one . $grid . $bars
  $one = $one . '<line x1="58" y1="25" x2="58" y2="215" stroke="#374151" stroke-width="2"/><line x1="58" y1="215" x2="438" y2="215" stroke="#374151" stroke-width="2"/>'
  $one = $one . $ticks
  $one = $one . '<text x="248" y="258" font-size="12" fill="#374151" text-anchor="middle">' . $xLabel . '</text>'
  $one = $one . '<text x="16" y="120" font-size="12" fill="#374151" text-anchor="middle" transform="rotate(-90 16 120)">Percent of group</text>'
  $one = $one . '</svg>'
  $sv[$vv] = $one
}
$histA = $sv[0]
$histB = $sv[1]

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
      <p><span class="term-label">Part (a) &mdash; one class as a share.</span> ' . $askCount . ' of the ' . $gA . ' fell in ' . $askLo . '&ndash;' . $askHi . ', out of ' . $nA . ' surveyed: ' . $askCount . ' / ' . $nA . ' &approx; <b>' . $askRel . '</b>, or ' . $askRelPct . '%. That is what the bar height reads as, because the vertical axis here is percent of the group rather than a headcount.</p>
      <p><span class="term-label">Part (b) &mdash; the group size.</span> <b>' . $nA . '</b> &mdash; and it cannot be read off this graph at all. Relative frequency throws the sample size away: every bar is a share, and shares from a group of 20 look exactly like shares from a group of 2,000. The count had to come from the table.</p>
      <p><span class="term-label">Part (c) &mdash; why not use counts.</span> ' . $nA . ' and ' . $nB . ' are different, so a taller bar in one graph could simply mean more people were asked. Dividing each class by its own group total removes that difference and leaves only the pattern, which is the only thing worth comparing here.</p>
      <p><span class="term-label">Reading the comparison.</span> With both graphs on the same vertical scale, the ' . $gA . ' pile up toward the left of the axis and the ' . $gB . ' toward the right &mdash; so the ' . $gB . ' spend more, and the difference is in the center of each distribution rather than in how spread out they are.</p>
      <p><b>Answer:</b> (a) ' . $askRel . ' &nbsp;&nbsp; (b) ' . $nA . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">$intro <b>$nA</b> in the $gA group and <b>$nB</b> in the $gB group replied. Both histograms below use <b>percent of the group</b> up the side, and both are drawn to the same vertical scale.</p>
    <p style="margin:8px 0 0 0; font-size:14px; color:#666;">In the $gA group, $askCount replies fell in the $askLo to $askHi class.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px; margin:10px 0;">
    <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">$gA</p>
    $histA
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px; margin:10px 0;">
    <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">$gB</p>
    $histB
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>relative frequency</b> of the $askLo to $askHi class in the $gA group? (Give a decimal, accurate to at least 3 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many $unitOne replies make up the whole $gA group? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why must these two groups be compared with relative frequencies rather than raw counts? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
