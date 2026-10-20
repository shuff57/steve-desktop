// === NAME - DESCRIPTION: Choose a Method for Each Line - Pick the first step for four packets (a slope and a point, two points, two grid corners, a table), then write the equation and use it ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.6 (2.6.5 Choosing a Method). One line y = (p/q)x + b, written four ways. All x values
// are multiples of q, so every y is a whole number and every point is a lattice point. The four kinds
// are dealt onto packets A to D by diffrands, so the menu keys change with the seed. Part (a) is
// four menus: the key for a packet is the kind of information it holds.
$anstypes = array("choices", "choices", "choices", "choices", "calculated", "calculated", "calculated")
$abstolerance = 0.0001
$answerboxsize = [0, 0, 0, 0, 8, 8, 8]
$answeights = array(0.1, 0.1, 0.1, 0.1, 0.2, 0.2, 0.2)
$noshuffle = "all"
$displayformat = "select"

$q = rand(1, 5)
$p = rand(1, 7)
if (gcd($p, $q) != 1) {
  $p = 1
}
$p = $p * randfrom(array(-1, 1))
$bb = rand(1, 12) * randfrom(array(-1, 1))
$slopeText = $p
if ($q > 1) {
  $slopeText = $p . "/" . $q
}

$ja = rand(1, 5) * randfrom(array(-1, 1))
$xa = $q * $ja
$ya = $p * $ja + $bb
$mxa = $p * $ja

$jb1 = rand(-5, 3)
$jb2 = $jb1 + rand(2, 4)
$xb1 = $q * $jb1
$yb1 = $p * $jb1 + $bb
$xb2 = $q * $jb2
$yb2 = $p * $jb2 + $bb
$dyb = $yb2 - $yb1
$dxb = $xb2 - $xb1

$jc1 = rand(-5, 3)
$jc2 = $jc1 + rand(2, 4)
$xc1 = $q * $jc1
$yc1 = $p * $jc1 + $bb
$xc2 = $q * $jc2
$yc2 = $p * $jc2 + $bb

$j0 = rand(1, 5)
$xt0 = $q * $j0
$xt1 = $q * ($j0 + 1)
$xt2 = $q * ($j0 + 2)
$xt3 = $q * ($j0 + 3)
$yt0 = $p * $j0 + $bb
$yt1 = $p * ($j0 + 1) + $bb
$yt2 = $p * ($j0 + 2) + $bb
$yt3 = $p * ($j0 + 3) + $bb
$dyt = $yt1 - $yt0
$dxt = $xt1 - $xt0

$descs = array("slope `" . $slopeText . "`, passes through `(" . $xa . ", " . $ya . ")`", "passes through `(" . $xb1 . ", " . $yb1 . ")` and `(" . $xb2 . ", " . $yb2 . ")`", "a graph that passes through the grid corners `(" . $xc1 . ", " . $yc1 . ")` and `(" . $xc2 . ", " . $yc2 . ")`", "a table with `x = " . $xt0 . ", " . $xt1 . ", " . $xt2 . ", " . $xt3 . "` and `y = " . $yt0 . ", " . $yt1 . ", " . $yt2 . ", " . $yt3 . "`")
$st = array("Put the point in y = mx + b and solve for b", "Use the slope formula on the two points", "Read two grid corners and find rise over run", "Find the rate of change between two rows", "Write the equation in standard form first")
$o = diffrands(0, 3, 4)
$k0 = $o[0]
$k1 = $o[1]
$k2 = $o[2]
$k3 = $o[3]
$packetA = "Packet A: " . $descs[$k0]
$packetB = "Packet B: " . $descs[$k1]
$packetC = "Packet C: " . $descs[$k2]
$packetD = "Packet D: " . $descs[$k3]
for ($k=0..3) {
  $questions[$k] = $st
  $answer[$k] = $o[$k]
}

$jq = rand(6, 9)
$xq = $q * $jq
$yq = $p * $jq + $bb
$mq = $p * $jq

$slopeTerm = $p . "x"
if ($q > 1) {
  $slopeTerm = $p . "/" . $q . " x"
}
if ($q == 1 && $p == 1) {
  $slopeTerm = "x"
}
if ($q == 1 && $p == -1) {
  $slopeTerm = "-x"
}
$bTerm = " + " . $bb
if ($bb < 0) {
  $bTerm = " - " . abs($bb)
}
$eqText = "y = " . $slopeTerm . $bTerm

$answer[4] = $p / $q
$answer[5] = $bb
$answer[6] = $yq

$kindOf = array("a slope and a point", "two points", "two grid corners", "a table")
$name0 = "Packet A (" . $kindOf[$k0] . ")"
$name1 = "Packet B (" . $kindOf[$k1] . ")"
$name2 = "Packet C (" . $kindOf[$k2] . ")"
$name3 = "Packet D (" . $kindOf[$k3] . ")"
$step0 = $st[$k0]
$step1 = $st[$k1]
$step2 = $st[$k2]
$step3 = $st[$k3]

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
      <p><span class="term-label">(a) First steps.</span> Each kind of information needs a different first move.</p>
      <p>' . $name0 . ': ' . $step0 . '.<br>' . $name1 . ': ' . $step1 . '.<br>' . $name2 . ': ' . $step2 . '.<br>' . $name3 . ': ' . $step3 . '.</p>
      <p>Standard form is not the first step for any of them. The goal is the slope m and the y-intercept b.</p>
      <p><span class="term-label">(b) The equation.</span> The packet with a slope and a point gives b directly: `' . $ya . ' = ' . $slopeText . '(' . $xa . ') + b`, so `' . $ya . ' = ' . $mxa . ' + b` and `b = ' . $bb . '`. The two-points packet gives the same slope: `(' . $yb2 . ' - (' . $yb1 . '))/(' . $xb2 . ' - (' . $xb1 . ')) = ' . $dyb . '/' . $dxb . ' = ' . $slopeText . '`. The table gives it too: `(' . $yt1 . ' - (' . $yt0 . '))/(' . $xt1 . ' - ' . $xt0 . ') = ' . $slopeText . '`. So `m = ' . $slopeText . '` and `b = ' . $bb . '`. The equation is `' . $eqText . '`.</p>
      <p><span class="term-label">(c) Use it.</span> At `x = ' . $xq . '`: `' . $slopeText . '(' . $xq . ')' . $bTerm . ' = ' . $mq . $bTerm . ' = ' . $yq . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Four crews each hand you a packet that describes a line. All four packets describe the same line.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter a slope as a fraction, or as an exact decimal. A decimal that has been rounded is marked wrong.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Choose the best first step for each packet. One of the steps is not the best first step for any packet.<br><br>
    $packetA<br>First step: $answerbox[0]<br><br>
    $packetB<br>First step: $answerbox[1]<br><br>
    $packetC<br>First step: $answerbox[2]<br><br>
    $packetD<br>First step: $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Write the equation of the line in the form `y = mx + b`.<br><br>
    <b>m</b> = $answerbox[4] &nbsp; <b>b</b> = $answerbox[5]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Use your equation to find `y` when `x = $xq`. $answerbox[6]
  </div>
</div>

// === ANSWER ===

$sol
