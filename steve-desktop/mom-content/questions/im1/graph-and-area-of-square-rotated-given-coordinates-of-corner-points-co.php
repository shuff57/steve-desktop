// === NAME - DESCRIPTION: Graph and Area of square (rotated) given coordinates of corner points (local for Steven Huff) (copy by Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "draw,numfunc";

$answerformat[0] = "twopoint,lineseg,dot";
$snaptogrid[0] = 1;
$grid[0] = "-10,10,-10,10,5:1,5:1,400,400";

// Step 1: Pick direction vector for square side
$dx = rand(1, 3);
$dy = rand(1, 3);

// Step 2: Random base point
$x1 = rand(-5, 5);
$y1 = rand(-5, 5);

// Step 3: Build the other 3 corners using rotation logic
$x2 = $x1 + $dx;
$y2 = $y1 + $dy;

$x3 = $x2 - $dy;
$y3 = $y2 + $dx;

$x4 = $x1 - $dy;
$y4 = $y1 + $dx;

// Step 4: Manually compute each line segment
// Segment from P1 to P2
if ($x1 == $x2) {
  $seg1 = "x=$x1," . min($y1,$y2) . "," . max($y1,$y2);
} else {
  $m1 = ($y2 - $y1)/($x2 - $x1);
  $b1 = $y1 - $m1 * $x1;
  $seg1 = "$m1*x+$b1," . min($x1,$x2) . "," . max($x1,$x2);
}

// Segment from P2 to P3
if ($x2 == $x3) {
  $seg2 = "x=$x2," . min($y2,$y3) . "," . max($y2,$y3);
} else {
  $m2 = ($y3 - $y2)/($x3 - $x2);
  $b2 = $y2 - $m2 * $x2;
  $seg2 = "$m2*x+$b2," . min($x2,$x3) . "," . max($x2,$x3);
}

// Segment from P3 to P4
if ($x3 == $x4) {
  $seg3 = "x=$x3," . min($y3,$y4) . "," . max($y3,$y4);
} else {
  $m3 = ($y4 - $y3)/($x4 - $x3);
  $b3 = $y3 - $m3 * $x3;
  $seg3 = "$m3*x+$b3," . min($x3,$x4) . "," . max($x3,$x4);
}

// Segment from P4 to P1
if ($x4 == $x1) {
  $seg4 = "x=$x4," . min($y4,$y1) . "," . max($y4,$y1);
} else {
  $m4 = ($y1 - $y4)/($x1 - $x4);
  $b4 = $y4 - $m4 * $x4;
  $seg4 = "$m4*x+$b4," . min($x4,$x1) . "," . max($x4,$x1);
}

// Final answer array
$answers[0] = array(
  "$x1,$y1",
  "$x2,$y2",
  "$x3,$y3",
  "$x4,$y4",
  $seg1,
  $seg2,
  $seg3,
  $seg4
);

// Area calculation
$s = round(sqrt($dx**2 + $dy**2), 4);
$area = round($s * $s, 2);
$answer[1] = $area;
//$showanswer[1] = makepretty("$s*$s = $area");

$answerbox[0] = "[DRAW]";

// === QUESTION TEXT ===
<p>Use the graphing tool to draw a square by plotting and connecting the following points:</p>
<p>Plot the following four points, then connect them with line segments to form a rectangle:</p>
<ul style="list-style-type:none; padding-left:0;">
  <li><b>A</b> ` ({$x1}, {$y1})`</li>
  <li><b>B</b> ` ({$x2}, {$y2})`</li>
  <li><b>C</b> ` ({$x3}, {$y3})`</li>
  <li><b>D</b> ` ({$x4}, {$y4})`</li>
</ul>
<p>You may start with any point. Use the <b>dot</b> tool to plot the points, and the <b>line segment</b> tool to connect them.</p>
<p>{$answerbox[0]}</p>
<p>What is the area of the square?</p>
<p>$answerbox[1]</p>
