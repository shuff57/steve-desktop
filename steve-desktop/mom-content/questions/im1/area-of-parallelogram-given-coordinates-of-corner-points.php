// === NAME - DESCRIPTION: Area of parallelogram given coordinates of corner points (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "draw,number";

$answerformat[0] = "twopoint,lineseg,dot";
$snaptogrid[0] = 1;
$grid[0] = "-11,11,-11,11,5:1,5:1,400,400";

// Horizontal or vertical base?
$horizontal = rand(0,1);

// Base corner
$x1 = rand(-5, 3);
$y1 = rand(-5, 3);

// Base length
$base = rand(2, 5);

// Slant vector for height (off the base)
$dx = rand(1, 3);
$dy = rand(1, 3);

// Build points
if ($horizontal == 1) {
  // Horizontal base (x aligned)
  $x2 = $x1 + $base;
  $y2 = $y1;

  $x3 = $x2 + $dx;
  $y3 = $y2 + $dy;

  $x4 = $x1 + $dx;
  $y4 = $y1 + $dy;

  $base_length = abs($x2 - $x1);
  $height = abs($dy);
} else {
  // Vertical base (y aligned)
  $x2 = $x1;
  $y2 = $y1 + $base;

  $x3 = $x2 + $dx;
  $y3 = $y2 + $dy;

  $x4 = $x1 + $dx;
  $y4 = $y1 + $dy;

  $base_length = abs($y2 - $y1);
  $height = abs($dx);
}

// Build line segments using MyOpenMath format
if ($x1 == $x2) {
  $seg1 = "x=$x1," . min($y1,$y2) . "," . max($y1,$y2);
} else {
  $m1 = ($y2 - $y1)/($x2 - $x1);
  $b1 = $y1 - $m1 * $x1;
  $seg1 = "$m1*x+$b1," . min($x1,$x2) . "," . max($x1,$x2);
}

if ($x2 == $x3) {
  $seg2 = "x=$x2," . min($y2,$y3) . "," . max($y2,$y3);
} else {
  $m2 = ($y3 - $y2)/($x3 - $x2);
  $b2 = $y2 - $m2 * $x2;
  $seg2 = "$m2*x+$b2," . min($x2,$x3) . "," . max($x2,$x3);
}

if ($x3 == $x4) {
  $seg3 = "x=$x3," . min($y3,$y4) . "," . max($y3,$y4);
} else {
  $m3 = ($y4 - $y3)/($x4 - $x3);
  $b3 = $y3 - $m3 * $x3;
  $seg3 = "$m3*x+$b3," . min($x3,$x4) . "," . max($x3,$x4);
}

if ($x4 == $x1) {
  $seg4 = "x=$x4," . min($y4,$y1) . "," . max($y4,$y1);
} else {
  $m4 = ($y1 - $y4)/($x1 - $x4);
  $b4 = $y4 - $m4 * $x4;
  $seg4 = "$m4*x+$b4," . min($x4,$x1) . "," . max($x4,$x1);
}

// Final drawing answer: 4 dots, 4 segments
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

// Area = base × height
$area = round($base_length * $height, 2);
$answer[1] = $area;
$showanswer[1] = makepretty("base × height = {$base_length} × {$height} = {$area}");

$answerbox[0] = "[DRAW]";

// === QUESTION TEXT ===
<p>Use the graphing tool to draw a parallelogram using the four given points:</p>
<p>Plot the following four points, then connect them with line segments to form a rectangle:</p>
<ul style="list-style-type:none; padding-left:0;">
  <li><b>A</b> ` ({$x1}, {$y1})`</li>
  <li><b>B</b> ` ({$x2}, {$y2})`</li>
  <li><b>C</b> ` ({$x3}, {$y3})`</li>
  <li><b>D</b> ` ({$x4}, {$y4})`</li>
</ul>
<p>You may start with any point. Use the <b>dot</b> tool to plot the points, and the <b>line segment</b> tool to connect them.</p>
<p>{$answerbox[0]}</p>
<p>What is the area of the parallelogram?</p>
<p>$answerbox[1]</p>
