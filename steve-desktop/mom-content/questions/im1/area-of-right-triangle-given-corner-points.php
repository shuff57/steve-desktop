// === NAME - DESCRIPTION: Area of right triangle given corner points (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "draw,numfunc";

$answerformat[0] = "twopoint,lineseg,dot";
$snaptogrid[0] = 1;
$grid[0] = "-10,10,-10,10,5:1,5:1,400,400";

// Step 1: Random base point (A)
$x1 = rand(-8, 5);
$y1 = rand(-8, 5);

// Step 2: Horizontal and vertical leg offsets (non-zero ensures unique points)
$dx = nonzerorand(-5, 5);
$dy = nonzerorand(-5, 5);

// Step 3: Compute remaining points (right angle at A)
$x2 = $x1 + $dx; $y2 = $y1;         // B: horizontal from A
$x3 = $x1;       $y3 = $y1 + $dy;   // C: vertical from A

// Point strings
$p1 = "$x1,$y1"; // A
$p2 = "$x2,$y2"; // B
$p3 = "$x3,$y3"; // C

// Segment AB (horizontal)
$seg1 = "$y1," . min($x1, $x2) . "," . max($x1, $x2);

// Segment AC (vertical)
$seg2 = "x=$x1," . min($y1, $y3) . "," . max($y1, $y3);

// Segment BC (hypotenuse)
if ($x2 == $x3) {
  $seg3 = "x=$x2," . min($y2, $y3) . "," . max($y2, $y3);
} else {
  $m = ($y3 - $y2) / ($x3 - $x2);
  $b = $y2 - $m * $x2;
  $seg3 = "$m*x+$b," . min($x2, $x3) . "," . max($x2, $x3);
}

// Full drawing answer
$answer[0] = array(
  $p1, $p2, $p3,
  $seg1, $seg2, $seg3
);

// Area calculation: 0.5 × base × height
$base = abs($x2 - $x1);
$height = abs($y3 - $y1);
$area = 0.5 * $base * $height;
$answer[1] = $area;

$answerbox[0] = "[DRAW]";

// === QUESTION TEXT ===
<p>Use the graphing tool below to construct a triangle using the given points.</p>

<ul style="list-style-type:none; padding-left:0;">
  <li><b>A</b> `= (2, 3)`</li>
  <li><b>B</b> `= (7, 3)`</li>
  <li><b>C</b> `= (7, 6)`</li>
</ul>

<p>Use the <b>dot</b> tool to mark each point, and the <b>line segment</b> tool to connect the points to form the triangle.</p>

<p>$answerbox[0]</p>

<p>What is the area of the triangle?</p>
<p>$answerbox[1]</p>
