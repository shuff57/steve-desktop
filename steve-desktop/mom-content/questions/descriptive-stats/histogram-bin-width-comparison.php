// === NAME - DESCRIPTION: Bin Width Changes the Picture - One data set is drawn as a histogram twice, with bars one unit wide and then two units wide; count the wider bars, work out the height of one of them, and say what the change of bin width does and does not alter ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A publisher surveyed adult readers on how many paperbacks they bought last month."
  $xLabel = "Books bought"
  $unitWord = "readers"
}
else {
  $intro = "A pharmacy surveyed customers on how many prescriptions they collected last month."
  $xLabel = "Prescriptions collected"
  $unitWord = "customers"
}

// Eight values, 0 through 7, so the wide version pairs them cleanly into four bars.
$f = array(0, 0, 0, 0, 0, 0, 0, 0)
$n = 0
for ($k=0..7) {
  // EVEN counts only: part (b) asks the student to read a bar HEIGHT off the graph, and with a
  // gridline every 2 an odd height has nothing to read against.
  $f[$k] = 2 * rand(2, 8)
  $n = $n + $f[$k]
}

// The wide bars are the adjacent pairs. This is the whole point: no observation is created or
// lost by rebinning, the same values are simply gathered into fewer boxes.
$w = array(0, 0, 0, 0)
for ($j=0..3) {
  $w[$j] = $f[2 * $j] + $f[2 * $j + 1]
}

$aj = rand(0, 3)
$askLo = 2 * $aj
$askHi = 2 * $aj + 1
$askHeight = $w[$aj]

$maxF = 0
$maxW = 0
for ($k=0..7) {
  if ($f[$k] > $maxF) { $maxF = $f[$k] }
}
for ($j=0..3) {
  if ($w[$j] > $maxW) { $maxW = $w[$j] }
}

$tableRows = ""
for ($k=0..7) {
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:5px 16px; text-align:center;">' . $k . '</td><td style="border:1px solid #d1d5db; padding:5px 16px; text-align:center;">' . $f[$k] . '</td></tr>'
}

$answer[0] = 4
$answerformat[0] = "integer"

$answer[1] = $askHeight
$answerformat[1] = "integer"

$answer[2] = $n
$answerformat[2] = "integer"

$questions[3] = array(
  "The number of observations is unchanged; only how finely they are grouped changes. Wider bars smooth the picture and can hide a gap or a spike that the narrow version showed.",
  "The number of observations doubles, because each wide bar covers two values.",
  "The number of observations halves, because there are half as many bars.",
  "Nothing changes at all; the two histograms carry exactly the same information."
)
$answer[3] = 0

// Both histograms are built in one loop: 0 is eight bars of width one, 1 is four of width two.
// Bars touch in both, because the axis is a number line either way.
$sv = array("", "")
for ($vv=0..1) {
  $nbars = 8
  $top = $maxF + 2
  if ($vv == 1) {
    $nbars = 4
    $top = $maxW + 4
  }
  $step = 2
  $rem = $top % $step
  if ($rem > 0) { $top = $top + $step - $rem }
  $gN = $top / $step
  $unitPx = 185 / $top
  $barW = 400 / $nbars

  $grid = ""
  for ($g=0..$gN) {
    $val = $step * $g
    $gy = round(220 - $val * $unitPx, 2)
    $grid = $grid . '<line x1="55" y1="' . $gy . '" x2="455" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
    $grid = $grid . '<text x="49" y="' . ($gy + 4) . '" font-size="11" fill="#6b7280" text-anchor="end">' . $val . '</text>'
  }

  $bars = ""
  $nb = $nbars - 1
  for ($j=0..$nb) {
    $val = $f[$j]
    if ($vv == 1) { $val = $w[$j] }
    $bx = round(55 + $j * $barW, 2)
    $bh = round($val * $unitPx, 2)
    $by = round(220 - $bh, 2)
    $bars = $bars . '<rect x="' . $bx . '" y="' . $by . '" width="' . $barW . '" height="' . $bh . '" fill="#93c5fd" stroke="#1e40af" stroke-width="1.5"/>'
  }

  // Ticks sit on the bar EDGES, not their middles, because the axis is a number line.
  $ticks = ""
  for ($j=0..$nbars) {
    $tx = round(55 + $j * $barW, 2)
    $lab = $j
    if ($vv == 1) { $lab = 2 * $j }
    $ticks = $ticks . '<line x1="' . $tx . '" y1="220" x2="' . $tx . '" y2="225" stroke="#374151" stroke-width="1"/>'
    $ticks = $ticks . '<text x="' . $tx . '" y="240" font-size="11" fill="#374151" text-anchor="middle">' . $lab . '</text>'
  }

  $one = '<svg viewBox="0 0 470 272" width="100%" style="max-width:470px; display:block; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
  $one = $one . $grid . $bars
  $one = $one . '<line x1="55" y1="25" x2="55" y2="220" stroke="#374151" stroke-width="2"/><line x1="55" y1="220" x2="455" y2="220" stroke="#374151" stroke-width="2"/>'
  $one = $one . $ticks
  $one = $one . '<text x="255" y="262" font-size="12" fill="#374151" text-anchor="middle">' . $xLabel . '</text>'
  $one = $one . '<text x="14" y="122" font-size="12" fill="#374151" text-anchor="middle" transform="rotate(-90 14 122)">Frequency</text>'
  $one = $one . '</svg>'
  $sv[$vv] = $one
}

$narrowHist = $sv[0]
$wideHist = $sv[1]

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
      <p><span class="term-label">Part (a) &mdash; how many wide bars.</span> The values run 0 to 7, which is eight values. Two values to a bar gives 8 / 2 = <b>4</b> bars.</p>
      <p><span class="term-label">Part (b) &mdash; the height of one wide bar.</span> A wide bar holds everyone from both of its values, so add the two narrow bars it replaced: ' . $f[$askLo] . ' + ' . $f[$askHi] . ' = <b>' . $askHeight . '</b>. Heights add; they are never averaged. Averaging is the usual mistake and it makes the totals stop working.</p>
      <p><span class="term-label">Part (c) &mdash; the sample size.</span> <b>' . $n . '</b>, and it is the same on both graphs. Adding the four wide bars gives the same total as adding the eight narrow ones, because rebinning only regroups the observations already there.</p>
      <p><span class="term-label">Part (d) &mdash; what changed.</span> Not the data &mdash; the resolution. Wider bars average out the bumpiness, so the shape reads more smoothly, and any gap or spike narrower than a bar disappears inside one. Neither picture is the true one: bin width is a choice, and the honest habit is to look at more than one before saying what shape the data has.</p>
      <p><b>Answer:</b> (a) 4 &nbsp;&nbsp; (b) ' . $askHeight . ' &nbsp;&nbsp; (c) ' . $n . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro The $n replies are in the table below.</p>
    <table style="border-collapse:collapse; margin:0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:6px 16px;">$xLabel</th>
          <th style="border:1px solid #d1d5db; padding:6px 16px;">Frequency</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px; margin:10px 0;">
    <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">Histogram A &mdash; bars one unit wide</p>
    $narrowHist
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px; margin:10px 0;">
    <p style="margin:0 0 4px 0; font-weight:700; color:#1865f2;">Histogram B &mdash; the same data, bars two units wide</p>
    $wideHist
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many bars does Histogram B have? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> In Histogram B, how tall is the bar covering the values $askLo and $askHi? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many $unitWord does Histogram B account for altogether? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What does doubling the bar width change, and what does it leave alone? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
