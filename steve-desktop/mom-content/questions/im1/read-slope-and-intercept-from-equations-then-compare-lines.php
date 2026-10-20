// === NAME - DESCRIPTION: Read Slope and Intercept from y = mx + b - Name the slope and y-intercept of one line, pick the steepest of three lines, and test whether a point is on the line ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.3 (2.3.1, 2.3.4). Three lines get different slope sizes drawn from ten values, so the
// steepest line is always unique. Each slope has a random sign, so the greatest slope and the steepest
// line are often different lines. Each equation is written either as y = mx + b or as y = b + mx.
// The point is on line A about half the time. Its x-value is a multiple of the denominator of the slope,
// so the y-value is a whole number. Intercepts are never 0.
$anstypes = array("calculated", "number", "choices", "choices")

$mn = array(1, 2, 3, 1, 4, 3, 2, 5, 3, 4)
$md = array(2, 3, 4, 1, 3, 2, 1, 2, 1, 1)
$idx = diffrands(0, 9, 3)

for ($i = 0; $i < 3; $i++) {
  $an = $mn[$idx[$i]]
  $ad = $md[$idx[$i]]
  $sg = 1
  if (rand(0, 1) == 0) {
    $sg = -1
  }
  $sn[$i] = $sg * $an
  $sd[$i] = $ad
  $mag[$i] = $an / $ad
  $bb[$i] = nonzerorand(-9, 9)
  $coefT = $an . "x"
  if ($ad != 1) {
    $coefT = $an . "/" . $ad . " x"
  }
  if ($ad == 1 && $an == 1) {
    $coefT = "x"
  }
  $szT[$i] = $an
  $slT[$i] = $sg * $an
  if ($ad != 1) {
    $szT[$i] = $an . "/" . $ad
    $slT[$i] = ($sg * $an) . "/" . $ad
  }
  $lead = ""
  $join = " + "
  if ($sg == -1) {
    $lead = "-"
    $join = " - "
  }
  $bAbs = abs($bb[$i])
  $bSign = " + "
  if ($bb[$i] < 0) {
    $bSign = " - "
  }
  $txt[$i] = "`y = " . $lead . $coefT . $bSign . $bAbs . "`"
  if (rand(0, 1) == 1) {
    $txt[$i] = "`y = " . $bb[$i] . $join . $coefT . "`"
  }
}
$L0 = $txt[0]
$L1 = $txt[1]
$L2 = $txt[2]
$mA = $sn[0] / $sd[0]
$bA = $bb[0]
$mTxtA = $slT[0]

$best = 0
if ($mag[1] > $mag[$best]) {
  $best = 1
}
if ($mag[2] > $mag[$best]) {
  $best = 2
}
$names = array("Line A", "Line B", "Line C")
$bestName = $names[$best]
$bestSz = $szT[$best]

$kk = rand(1, 5)
if (rand(0, 1) == 0) {
  $kk = -$kk
}
$p = $sd[0] * $kk
$qOn = $sn[0] * $kk + $bA
$q = $qOn
$onLine = rand(0, 1)
$yn = 0
$ynWord = "is on the line"
if ($onLine == 0) {
  $off = rand(1, 3)
  if (rand(0, 1) == 0) {
    $off = -$off
  }
  $q = $qOn + $off
  $yn = 1
  $ynWord = "is not on the line"
}
$slParen = "(" . $mTxtA . ")"

$questions[2] = array("Line A", "Line B", "Line C")
$questions[3] = array("Yes, the point is on Line A", "No, the point is not on Line A")
$noshuffle[2] = "all"
$noshuffle[3] = "all"
$displayformat[2] = "select"
$displayformat[3] = "select"
$answer[0] = $mA
$answer[1] = $bA
$answer[2] = $best
$answer[3] = $yn
$answerformat[0] = "reducedfraction"
$showanswer[0] = $mTxtA
$answerboxsize[0] = 8
$answerboxsize[1] = 6
$abstolerance[0] = 0.0001
$abstolerance[1] = 0.001

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
      <p><span class="term-label">(a) and (b).</span> In `y = mx + b`, the slope `m` is the number multiplying x and the y-intercept `b` is the number added on its own, whichever side it is written on. Line A is ' . $L0 . ', so the slope is ' . $mTxtA . ' and the y-intercept is ' . $bA . '.</p>
      <p><span class="term-label">(c) Steepest.</span> Steepness is the size of the slope, ignoring the sign. The sizes are ' . $szT[0] . ' for Line A, ' . $szT[1] . ' for Line B and ' . $szT[2] . ' for Line C. The largest size is ' . $bestSz . ', so ' . $bestName . ' is the steepest. A negative slope does not make a line less steep: the sign only says that the line falls.</p>
      <p><span class="term-label">(d) The point.</span> Put x = ' . $p . ' into Line A: `y = ' . $slParen . '(' . $p . ') + (' . $bA . ') = ' . $qOn . '`. The line has y = ' . $qOn . ' at x = ' . $p . ', and the point has y = ' . $q . ', so the point ' . $ynWord . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Three lines are given by their equations.</p>
    <p style="margin:10px 0 0 0;"><b>Line A:</b> $L0</p>
    <p style="margin:6px 0 0 0;"><b>Line B:</b> $L1</p>
    <p style="margin:6px 0 0 0;"><b>Line C:</b> $L2</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter the slope as a whole number or a fraction in lowest terms, such as -3/2. The y-intercept is the y-value where the line crosses the y-axis.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The slope of Line A is $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The y-intercept of Line A is $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which line is the steepest? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Is the point `($p, $q)` on Line A? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
