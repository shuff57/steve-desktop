// === NAME - DESCRIPTION: Area of non-right triangle given corner points (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "draw,numfunc";

$answerformat[0] = "twopoint,lineseg,dot";
$snaptogrid[0] = 1;
$grid[0] = "-15,15,-15,15,5:1,5:1,400,400";

// Randomly choose if base is horizontal or vertical
$ishoriz = rand(0,1);  // 0 = vertical, 1 = horizontal

// Point A (base start)
$x1 = rand(-8, 5);
$y1 = rand(-8, 5);

// Point B (base end), must be at least 2 units away
if ($ishoriz == 1) {
  for ($i = 1..10) {
    $x2 = $x1 + nonzerorand(-6, 6);
    if (abs($x2 - $x1) >= 2) { break; }
  }
  $y2 = $y1;
} else {
  for ($i = 1..10) {
    $y2 = $y1 + nonzerorand(-6, 6);
    if (abs($y2 - $y1) >= 2) { break; }
  }
  $x2 = $x1;
}

// Generate point C (not collinear with A and B)
$x3 = $x1;
$y3 = $y1;

for ($i = 1..10) {
  $x3 = rand(-10, 10);
  $y3 = rand(-10, 10);

  if ($ishoriz == 1 && $y3 == $y1) { continue; }
  if ($ishoriz == 0 && $x3 == $x1) { continue; }

  $det = ($x3 - $x1)*($y2 - $y1) - ($y3 - $y1)*($x2 - $x1);
  if ($det != 0) { break; }
}

// Points
$p1 = "$x1,$y1";  // A
$p2 = "$x2,$y2";  // B
$p3 = "$x3,$y3";  // C

// Segment AB (base)
if ($ishoriz == 1) {
  $seg1 = "$y1," . min($x1, $x2) . "," . max($x1, $x2);
} else {
  $seg1 = "x=$x1," . min($y1, $y2) . "," . max($y1, $y2);
}

// Segment BC
if ($x2 == $x3) {
  $seg2 = "x=$x2," . min($y2, $y3) . "," . max($y2, $y3);
} else {
  $m2 = ($y3 - $y2)/($x3 - $x2);
  $b2 = $y2 - $m2 * $x2;
  $seg2 = "$m2*x+$b2," . min($x2, $x3) . "," . max($x2, $x3);
}

// Segment CA
if ($x3 == $x1) {
  $seg3 = "x=$x3," . min($y3, $y1) . "," . max($y3, $y1);
} else {
  $m3 = ($y1 - $y3)/($x1 - $x3);
  $b3 = $y3 - $m3 * $x3;
  $seg3 = "$m3*x+$b3," . min($x1, $x3) . "," . max($x1, $x3);
}

// Final drawing answer
$answers[0] = array(
  $p1, $p2, $p3,
  $seg1, $seg2, $seg3
);

// Area using determinant formula
$area = abs(($x1*($y2-$y3) + $x2*($y3-$y1) + $x3*($y1-$y2)) / 2);
$answer[1] = $area;

$answerbox[0] = "[DRAW]";

// === QUESTION TEXT ===
<p>Use the graphing tool to construct triangle `ABC` from the following points:</p>
<ul style="list-style-type:none; padding-left:0;">
  <li><b>A</b> `({$x1}, {$y1})`</li>
  <li><b>B</b> `({$x2}, {$y2})`</li>
  <li><b>C</b> `({$x3}, {$y3})`</li>
</ul>
<p>Use the <b>dot</b> tool to mark each point, and the <b>line segment</b> tool to connect them into triangle `ABC`.</p>
<p>$answerbox[0]</p>
<p>What is the area of triangle `ABC`?</p>
<p>$answerbox[1]</p>
