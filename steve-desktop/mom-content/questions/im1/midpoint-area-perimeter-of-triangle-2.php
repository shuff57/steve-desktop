// === NAME - DESCRIPTION: midpoint, area, perimeter of triangle  ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("draw", "number", "number", "string", "string", "string");

$answerformat[0] = "twopoint,lineseg,dot";
$snaptogrid[0] = 0.5;
$scoremethod = "ignoreoverlap";
$grid[0] = "-11,11,-11,11,5:1,5:1,400,400";

for ($i=1..10) {
  $x1 = rand(-5, 5); $y1 = rand(-5, 5);

  // Use even-numbered offsets to guarantee integer midpoints
  $dx1 = 2 * rrand(-3, 3,1);
  $dy1 = 2 * rrand(-3, 3,1);
  $x2 = $x1 + $dx1;
  $y2 = $y1 + $dy1;

  $dx2 = 2 * rrand(-3, 3,1);
  $dy2 = 2 * rrand(-3, 3,1);
  $x3 = $x1 + $dx2;
  $y3 = $y1 + $dy2;

  // Skip if any point is out of bounds
  if (
    $x2 < -11 || $x2 > 11 || $y2 < -11 || $y2 > 11 ||
    $x3 < -11 || $x3 > 11 || $y3 < -11 || $y3 > 11
  ) {
    continue;
  }

  // Skip if any points are repeated
  if (
    ($x1 == $x2 && $y1 == $y2) ||
    ($x1 == $x3 && $y1 == $y3) ||
    ($x2 == $x3 && $y2 == $y3)
  ) {
    continue;
  }

  // Skip if triangle is degenerate (collinear)
  $det = $x1 * ($y2 - $y3) + $x2 * ($y3 - $y1) + $x3 * ($y1 - $y2);
  if ($det == 0) {
    continue;
  }

  // Skip if vertical range (height) is too small
  $miny = min($y1, $y2, $y3);
  $maxy = max($y1, $y2, $y3);
  if (($maxy - $miny) < 2) {
    continue;
  }

  // ✅ Ensure at least one side is horizontal or vertical
  $has_horz_or_vert = (
    $x1 == $x2 || $x2 == $x3 || $x3 == $x1 ||
    $y1 == $y2 || $y2 == $y3 || $y3 == $y1
  );
  if (!$has_horz_or_vert) {
    continue;
  }

  // All good!
  break;
}

// Midpoints
$mx1 = ($x1 + $x2)/2; $my1 = ($y1 + $y2)/2;
$mx2 = ($x2 + $x3)/2; $my2 = ($y2 + $y3)/2;
$mx3 = ($x1 + $x3)/2; $my3 = ($y1 + $y3)/2;

// Segments
if ($x1 == $x2) {
  $seg1 = "x=$x1," . min($y1,$y2) . "," . max($y1,$y2);
} else {
  $m1 = ($y2 - $y1) / ($x2 - $x1); $b1 = $y1 - $m1 * $x1;
  $seg1 = "$m1*x+$b1," . min($x1,$x2) . "," . max($x1,$x2);
}

if ($x2 == $x3) {
  $seg2 = "x=$x2," . min($y2,$y3) . "," . max($y2,$y3);
} else {
  $m2 = ($y3 - $y2) / ($x3 - $x2); $b2 = $y2 - $m2 * $x2;
  $seg2 = "$m2*x+$b2," . min($x2,$x3) . "," . max($x2,$x3);
}

if ($x3 == $x1) {
  $seg3 = "x=$x3," . min($y3,$y1) . "," . max($y3,$y1);
} else {
  $m3 = ($y1 - $y3) / ($x1 - $x3); $b3 = $y3 - $m3 * $x3;
  $seg3 = "$m3*x+$b3," . min($x1,$x3) . "," . max($x1,$x3);
}

// Draw + Midpoints
$answers[0] = array(
  "$x1,$y1", "$x2,$y2", "$x3,$y3",
  $seg1, $seg2, $seg3,
  "$mx1,$my1", "$mx2,$my2", "$mx3,$my3"
);

// Area
$area = abs(($x1*($y2 - $y3) + $x2*($y3 - $y1) + $x3*($y1 - $y2)) / 2);
$answer[1] = round($area, 2);

// Perimeter
$AB = sqrt(($x2 - $x1)^2 + ($y2 - $y1)^2);
$BC = sqrt(($x3 - $x2)^2 + ($y3 - $y2)^2);
$CA = sqrt(($x1 - $x3)^2 + ($y1 - $y3)^2);
$answer[2] = round($AB + $BC + $CA, 2);

// Midpoint of AB as string
$answer[3] = "($mx1,$my1)";  // AB midpoint
$answer[4] = "($mx2,$my2)";  // BC midpoint
$answer[5] = "($mx3,$my3)";  // CA midpoint

$answerbox[0] = "[DRAW]";

// === QUESTION TEXT ===

Use the graphing tool to construct triangle `ABC` using the following points:
<ul style="list-style-type:none;">
  <li><b>A</b> `({$x1}, {$y1})`</li>
  <li><b>B</b> `({$x2}, {$y2})`</li>
  <li><b>C</b> `({$x3}, {$y3})`</li>
</ul>

<p>Use the <b>dot</b> tool to plot each vertex, and the <b>line segment</b> tool to connect them.</p>
<p>Also plot the <b>midpoints</b> of each side using the dot tool.</p>

<p>$answerbox[0]</p>

What is the <b>area</b> of triangle `ABC`?
<p>$answerbox[1]</p>

What is the <b>perimeter</b> of triangle `ABC`?
<p>$answerbox[2]</p>

What is the midpoint of side `AB`?
<b>A</b> `({$x1}, {$y1})` `-&gt;` <b>B</b> `({$x2}, {$y2})`
<p>$answerbox[3]</p>

What is the midpoint of side `BC`?
<b>B</b> `({$x2}, {$y2})` `-&gt;` <b>C</b> `({$x3}, {$y3})`
<p>$answerbox[4]</p>

What is the midpoint of side `CA`?
<b>C</b> `({$x3}, {$y3})` `-&gt;` <b>A</b> `({$x1}, {$y1})`
<p>$answerbox[5]</p>
