// === NAME - DESCRIPTION: Area of trapezoid given coordinates of corner points (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "draw,number";

$answerformat[0] = "twopoint,lineseg,dot";
$snaptogrid[0] = 1;
$grid[0] = "-10,10,-10,10,5:1,5:1,400,400";

// Step 1: Left vertical base (P1 → P2)
$x1 = rand(-5, 1);
$y1 = rand(-5, 0);
$base1 = rand(3, 5);
$x2 = $x1 + rand(4, 6);
$y2 = $y1 + $base1;
$P1 = array($x1, $y1);
$P2 = array($x1, $y2);

// Step 2: Right non-vertical side (P3 → P4) → NOT parallel to P1 → P2
for ($i = 0..4) {
  $y3 = rand(-5, 5);
  $y4 = $y3 + rand(1, 4); // different vertical height
  if (abs(($y4 - $y3) - $base1) > 0) {
    break; // ✅ guaranteed not parallel
  }
}
$P3 = array($x2, $y3);
$P4 = array($x2, $y4);

// Step 3: Determine perimeter order (bottom to top)
if ($P1[1] < $P2[1]) {
  $leftBot = $P1;
  $leftTop = $P2;
} else {
  $leftBot = $P2;
  $leftTop = $P1;
}

if ($P3[1] < $P4[1]) {
  $rightBot = $P3;
  $rightTop = $P4;
} else {
  $rightBot = $P4;
  $rightTop = $P3;
}

// Final points A → B → C → D
$xa = $leftBot[0]; $ya = $leftBot[1];
$xb = $leftTop[0]; $yb = $leftTop[1];
$xc = $rightTop[0]; $yc = $rightTop[1];
$xd = $rightBot[0]; $yd = $rightBot[1];

// Line segments

// A → B
if ($xa == $xb) {
  $seg1 = "x=$xa," . min($ya, $yb) . "," . max($ya, $yb);
} else {
  $m1 = ($yb - $ya)/($xb - $xa);
  $b1 = $ya - $m1 * $xa;
  $seg1 = "$m1*x+$b1," . min($xa, $xb) . "," . max($xa, $xb);
}

// B → C
if ($xb == $xc) {
  $seg2 = "x=$xb," . min($yb, $yc) . "," . max($yb, $yc);
} else {
  $m2 = ($yc - $yb)/($xc - $xb);
  $b2 = $yb - $m2 * $xb;
  $seg2 = "$m2*x+$b2," . min($xb, $xc) . "," . max($xb, $xc);
}

// C → D
if ($xc == $xd) {
  $seg3 = "x=$xc," . min($yc, $yd) . "," . max($yc, $yd);
} else {
  $m3 = ($yd - $yc)/($xd - $xc);
  $b3 = $yc - $m3 * $xc;
  $seg3 = "$m3*x+$b3," . min($xc, $xd) . "," . max($xc, $xd);
}

// D → A
if ($xd == $xa) {
  $seg4 = "x=$xd," . min($yd, $ya) . "," . max($yd, $ya);
} else {
  $m4 = ($ya - $yd)/($xa - $xd);
  $b4 = $yd - $m4 * $xd;
  $seg4 = "$m4*x+$b4," . min($xd, $xa) . "," . max($xd, $xa);
}

// Final answer
$answers[0] = array(
  "$xa,$ya", "$xb,$yb", "$xc,$yc", "$xd,$yd",
  $seg1, $seg2, $seg3, $seg4
);

// Area = average height × width
$h1 = abs($yb - $ya); // left
$h2 = abs($yc - $yd); // right
$trapheight = abs($x2 - $x1);
$area = round(0.5 * ($h1 + $h2) * $trapheight, 2);

$answer[1] = $area;
$showanswer[1] = makepretty("Area = 0.5 × ($h1 + $h2) × $trapheight = $area");

$answerbox[0] = "[DRAW]";

// === QUESTION TEXT ===
<p>Use the graphing tool to draw a trapezoid.</p>
<p>Plot the following four points, then connect them with line segments to form a rectangle:</p>
<ul style="list-style-type:none; padding-left:0;">
  <li><b>A</b> ` ({$xa}, {$ya})`</li>
  <li><b>B</b> ` ({$xb}, {$yb})`</li>
  <li><b>C</b> ` ({$xc}, {$yc})`</li>
  <li><b>D</b> ` ({$xd}, {$yd})`</li>
</ul>
<p>You may start with any point. Use the <b>dot</b> tool to plot the points, and the <b>line segment</b> tool to connect them.</p>
<p>{$answerbox[0]}</p>
<p>What is the area of the trapezoid?</p>
<p>$answerbox[1]</p>
