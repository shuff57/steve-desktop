// === NAME - DESCRIPTION: Compare Two Linear Patterns - Count the tiles in each at a chosen figure, say which is larger, and find the figure where they first match ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.1 (2.1.3, 2.1.4). One pattern starts higher and grows slower, the other starts lower and
// grows faster. They match at Figure xe, where xe is a whole number from 2 to 8, because the start gap
// is d times xe. Figure N is never xe, so part c always has one larger pattern. N is before xe about a
// third of the time. Each pattern is shown either in words or as an equation.
$anstypes = array("number", "number", "choices", "number")

$gSlow = rand(2, 5)
$d = rand(1, 4)
$gFast = $gSlow + $d
$xe = rand(2, 8)
$sFast = rand(2, 10)
$sSlow = $sFast + $d * $xe

$swap = rand(0, 1)
$gA = $gSlow
$sA = $sSlow
$gB = $gFast
$sB = $sFast
if ($swap == 1) {
  $gA = $gFast
  $sA = $sFast
  $gB = $gSlow
  $sB = $sSlow
}

$early = rand(0, 2)
$N = $xe + rand(1, 25)
if ($early == 0 && $xe >= 3) {
  $N = rand(1, $xe - 1)
}
$cA = $gA * $N + $sA
$cB = $gB * $N + $sB
$which = 1
$whichName = "Pattern B"
if ($cA > $cB) {
  $which = 0
  $whichName = "Pattern A"
}
$matchTiles = $gA * $xe + $sA

$modeA = rand(0, 1)
$modeB = rand(0, 1)
$descA = "Pattern A has " . $sA . " tiles in Figure 0 and adds " . $gA . " tiles in each new figure."
if ($modeA == 1) {
  $descA = "Pattern A is described by the equation `y = " . $gA . "x + " . $sA . "`."
}
$descB = "Pattern B has " . $sB . " tiles in Figure 0 and adds " . $gB . " tiles in each new figure."
if ($modeB == 1) {
  $descB = "Pattern B is described by the equation `y = " . $gB . "x + " . $sB . "`."
}

$questions[2] = array("Pattern A", "Pattern B")
$noshuffle[2] = "all"
$displayformat[2] = "select"
$answer[0] = $cA
$answer[1] = $cB
$answer[2] = $which
$answer[3] = $xe
$answerboxsize[0] = 8
$answerboxsize[1] = 8
$answerboxsize[3] = 6
$abstolerance[0] = 0.001
$abstolerance[1] = 0.001
$abstolerance[3] = 0.001

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
      <p><span class="term-label">Two numbers for each pattern.</span> Pattern A: growth ' . $gA . ', starting value ' . $sA . ', so `y = ' . $gA . 'x + ' . $sA . '`. Pattern B: growth ' . $gB . ', starting value ' . $sB . ', so `y = ' . $gB . 'x + ' . $sB . '`.</p>
      <p><span class="term-label">(a) and (b) Figure ' . $N . '.</span> Pattern A: `' . $gA . '(' . $N . ') + ' . $sA . ' = ' . $cA . '`. Pattern B: `' . $gB . '(' . $N . ') + ' . $sB . ' = ' . $cB . '`.</p>
      <p><span class="term-label">(c) Which is larger.</span> Pattern A has ' . $cA . ' tiles and Pattern B has ' . $cB . ' tiles, so ' . $whichName . ' has more.</p>
      <p><span class="term-label">(d) First match.</span> Build a table and watch the gap close. The pattern with the lower start grows faster, so it catches up. At Figure ' . $xe . ' both patterns have ' . $matchTiles . ' tiles: `' . $gA . '(' . $xe . ') + ' . $sA . ' = ' . $matchTiles . '` and `' . $gB . '(' . $xe . ') + ' . $sB . ' = ' . $matchTiles . '`. In every earlier figure the counts are different, so Figure ' . $xe . ' is the first match.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Two tile patterns are linear. Their growth stays the same in every figure.</p>
    <p style="margin:10px 0 0 0;">$descA</p>
    <p style="margin:10px 0 0 0;">$descB</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter whole numbers.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many tiles are in Figure $N of Pattern A? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many tiles are in Figure $N of Pattern B? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which pattern has more tiles in Figure $N? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> In which figure do the two patterns first have the same number of tiles? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
