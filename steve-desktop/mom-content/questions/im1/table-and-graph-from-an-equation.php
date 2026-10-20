// === NAME - DESCRIPTION: Table and Graph from an Equation - Complete a table of values from y = mx + b, decide whether a point is on the line, and pick the matching graph from four ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.7 (2.7.3 Table and Graph from an Equation). Slope p/q with q from 1 to 3 and gcd(p, q) = 1;
// slope 1 is excluded so the upside-down slope is always a different line. b is a nonzero integer from
// -4 to 4. Every table x is a multiple of q, so every y is a whole number. The four graphs share one
// window, x from -6 to 6 and y from -8 to 8, with a gridline at every integer. The wrong graphs flip the
// sign of the slope, flip the sign of b, or turn the slope upside down (the three errors the page names).
// Each line is clipped to the window by solving for where it leaves it, so no endpoint is outside.
$anstypes = array("calculated", "calculated", "calculated", "calculated", "choices", "choices")
$abstolerance = 0.0001
$answerboxsize = [6, 6, 6, 6, 0, 0]
$noshuffle[5] = "all"

$q = rand(1, 3)
$p = rand(1, 4)
if (gcd($p, $q) != 1) {
  $p = 1
}
if ($q == 1 && $p == 1) {
  $q = 2
}
$p = $p * randfrom(array(-1, 1))
$bb = rand(1, 4) * randfrom(array(-1, 1))
$sg = 1
if ($p < 0) {
  $sg = -1
}
$slopeText = $p
if ($q > 1) {
  $slopeText = $p . "/" . $q
}
$slopeTerm = $p . "x"
if ($q > 1) {
  $slopeTerm = $p . "/" . $q . " x"
}
$bTerm = " + " . $bb
if ($bb < 0) {
  $bTerm = " - " . abs($bb)
}
$eq = "y = " . $slopeTerm . $bTerm
$vertText = "up " . $p
if ($p < 0) {
  $vertText = "down " . abs($p)
}

$jt[0] = rand(-4, -2)
$jt[1] = $jt[0] + rand(1, 2)
$jt[2] = $jt[1] + rand(1, 2)
$jt[3] = $jt[2] + rand(1, 2)
$tblSol = ""
for ($k=0..3) {
  $xt[$k] = $q * $jt[$k]
  $mt[$k] = $p * $jt[$k]
  $yt[$k] = $mt[$k] + $bb
  $answer[$k] = $yt[$k]
  $tblSol = $tblSol . 'At `x = ' . $xt[$k] . '`: `' . $slopeText . '(' . $xt[$k] . ')' . $bTerm . ' = ' . $mt[$k] . $bTerm . ' = ' . $yt[$k] . '`. '
}
$x0 = $xt[0]
$x1 = $xt[1]
$x2 = $xt[2]
$x3 = $xt[3]

$jp = rand(-5, 5)
$px = $q * $jp
$onLine = rand(0, 1)
$py = $p * $jp + $bb
$pyTrue = $py
if ($onLine == 0) {
  $py = $py + randfrom(array(-2, -1, 1, 2))
}
$mp = $p * $jp
$answer[4] = 1 - $onLine
$questions[4] = array("Yes, the point is on the line", "No, the point is not on the line")
$verdict = "The point is not on the line."
if ($onLine == 1) {
  $verdict = "The point is on the line."
}

$dn = array(0 - $p, $p, $sg * $q)
$dd = array($q, $q, abs($p))
$db = array($bb, 0 - $bb, $bb)
$c = rand(0, 3)
$used = 0
for ($k=0..3) {
  if ($k == $c) {
    $gn[$k] = $p
    $gd[$k] = $q
    $gb[$k] = $bb
  } else {
    $gn[$k] = $dn[$used]
    $gd[$k] = $dd[$used]
    $gb[$k] = $db[$used]
    $used = $used + 1
  }
}
$letters = array("A", "B", "C", "D")
for ($k=0..3) {
  $sl = $gn[$k] / $gd[$k]
  $ic = $gb[$k]
  if ($sl > 0) {
    $xa = (0 - 8 - $ic) / $sl
    $xb = (8 - $ic) / $sl
  } else {
    $xa = (8 - $ic) / $sl
    $xb = (0 - 8 - $ic) / $sl
  }
  $xl = max(-6, $xa)
  $xr = min(6, $xb)
  $ux1 = round(124 + 15 * $xl, 2)
  $uy1 = round(150 - 15 * ($sl * $xl + $ic), 2)
  $ux2 = round(124 + 15 * $xr, 2)
  $uy2 = round(150 - 15 * ($sl * $xr + $ic), 2)
  $s = '<svg viewBox="0 0 230 296" width="100%" style="max-width:230px; display:block; margin:0 auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Graph ' . $letters[$k] . '">'
  for ($g=0..12) {
    $gx = 34 + 15 * $g
    $s = $s . '<line x1="' . $gx . '" y1="30" x2="' . $gx . '" y2="270" stroke="#e5e7eb" stroke-width="1"/>'
  }
  for ($g=0..16) {
    $gy = 30 + 15 * $g
    $s = $s . '<line x1="34" y1="' . $gy . '" x2="214" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  }
  $s = $s . '<line x1="124" y1="30" x2="124" y2="270" stroke="#374151" stroke-width="1.5"/>'
  $s = $s . '<line x1="34" y1="150" x2="214" y2="150" stroke="#374151" stroke-width="1.5"/>'
  for ($g=0..6) {
    $tv = 2 * $g - 6
    if ($tv != 0) {
      $tx = 124 + 15 * $tv
      $s = $s . '<text x="' . $tx . '" y="284" font-size="10" fill="#374151" text-anchor="middle">' . $tv . '</text>'
    }
  }
  for ($g=0..8) {
    $tv = 2 * $g - 8
    if ($tv != 0) {
      $ty = 154 - 15 * $tv
      $s = $s . '<text x="30" y="' . $ty . '" font-size="10" fill="#374151" text-anchor="end">' . $tv . '</text>'
    }
  }
  $s = $s . '<line x1="' . $ux1 . '" y1="' . $uy1 . '" x2="' . $ux2 . '" y2="' . $uy2 . '" stroke="#1865f2" stroke-width="2.5" stroke-linecap="round"/>'
  $s = $s . '<text x="124" y="16" font-size="14" font-weight="700" fill="#21242c" text-anchor="middle">Graph ' . $letters[$k] . '</text>'
  $s = $s . '</svg>'
  $gsvg[$k] = $s
}
$svgA = $gsvg[0]
$svgB = $gsvg[1]
$svgC = $gsvg[2]
$svgD = $gsvg[3]
$questions[5] = array("Graph A", "Graph B", "Graph C", "Graph D")
$answer[5] = $c
$cl = $letters[$c]

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
      <p><span class="term-label">(a) The table.</span> Put each x into `' . $eq . '`. ' . $tblSol . '</p>
      <p><span class="term-label">(b) The point.</span> At `x = ' . $px . '` the line gives `' . $slopeText . '(' . $px . ')' . $bTerm . ' = ' . $mp . $bTerm . ' = ' . $pyTrue . '`. The point has `y = ' . $py . '`. ' . $verdict . '</p>
      <p><span class="term-label">(c) The graph.</span> The y-intercept is ' . $bb . ', so the line crosses the vertical axis at `(0, ' . $bb . ')`. The slope is `' . $slopeText . '`, so from that point the line goes ' . $q . ' to the right and ' . $vertText . '. Graph ' . $cl . ' is the one that does this. The other three have the sign of the slope wrong, the sign of b wrong, or the slope upside down.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A line has the equation `$eq`.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter each answer as a whole number.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Complete the table of values.
    <table style="border-collapse:collapse; margin:10px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">x</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x0</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$x3</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">y</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$answerbox[0]</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$answerbox[1]</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$answerbox[2]</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$answerbox[3]</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Is the point `($px, $py)` on the line?<br><br>$answerbox[4]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Each graph below has a gridline at every whole number. Which graph shows the line?
    <table style="border-collapse:collapse; margin:10px auto; width:100%; max-width:480px;">
      <tr><td style="width:50%; padding:4px;">$svgA</td><td style="width:50%; padding:4px;">$svgB</td></tr>
      <tr><td style="width:50%; padding:4px;">$svgC</td><td style="width:50%; padding:4px;">$svgD</td></tr>
    </table>
    $answerbox[5]
  </div>
</div>

// === ANSWER ===

$sol
