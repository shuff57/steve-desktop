// === NAME - DESCRIPTION: Determine rigid transformation (reflection) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices");

$answerboxsize = 10;

// Set up a multiple-choice question with options "yes" and "no"
$questions[0] = array("rotation", "reflection", "translation", "none");
$answer[0] = 1;  // Correct answer is "no"

// Randomizes whether the shape opens right or left
$r = rand(1, 4);  // Randomly selects one of the four cases

// Randomize x-coordinates for the three points
if ($r == 1) {
    $x1, $x2, $x3 = diffrands(-5, 5, 3, "inc");
} elseif ($r == 2) {
    $x1, $x3, $x2 = diffrands(-5, 5, 3, "inc");
} elseif ($r == 3) {
    $x2, $x3, $x1 = diffrands(-5, 5, 3, "inc");
} else {
    $x3, $x2, $x1 = diffrands(-5, 5, 3, "inc");
}

// Randomize y-coordinates for the three points
$y3, $y1, $y2 = diffrands(-5, 5, 3, "inc");

// Calculate the slopes for the three lines to form a closed shape
$mx12 = ($x1 - $x2);
$my12 = ($y1 - $y2);
$m12 = $my12 / $mx12;
$l12 = "$m12(x - $x1) + $y1";  // Line between points (x1, y1) and (x2, y2)

$mx13 = ($x1 - $x3);
$my13 = ($y1 - $y3);
$m13 = $my13 / $mx13;
$l13 = "$m13(x - $x1) + $y1";  // Line between points (x1, y1) and (x3, y3)

$mx23 = ($x2 - $x3);
$my23 = ($y2 - $y3);
$m23 = $my23 / $mx23;
$l23 = "$m23(x - $x2) + $y2";  // Line between points (x2, y2) and (x3, y3)

// Plot the original shape, coloring each line differently
$pic = showplot(array(
    "$l12,blue,$x1,$x2,,closed",     // Blue for line 1
    "$l13,green,$x1,$x3,closed,closed",    // Green for line 2
    "$l23,orange,$x2,$x3,,closed"    // Orange for line 3
), -6, 6, -6, 6);

// Reflection of the shape across the y-axis (negate the x-values for the reflection)
$x1r = -$x1;
$x2r = -$x2;
$x3r = -$x3;

$y1r = $y1;
$y2r = $y2;
$y3r = $y3;

// Reflect each line by applying the negated x-coordinates
// Recompute slopes after negating the x-values
$m12r = ($y1r - $y2r) / ($x1r - $x2r);  // Slope of reflected line 1
$m13r = ($y1r - $y3r) / ($x1r - $x3r);  // Slope of reflected line 2
$m23r = ($y2r - $y3r) / ($x2r - $x3r);  // Slope of reflected line 3

$l12r = "$m12r(x - $x1r) + $y1r";  // Reflection of line between points (x1r, y1r) and (x2r, y2r)
$l13r = "$m13r(x - $x1r) + $y1r";  // Reflection of line between points (x1r, y1r) and (x3r, y3r)
$l23r = "$m23r(x - $x2r) + $y2r";  // Correct reflection of line between points (x2r, y2r) and (x3r, y3r)

// Plot the reflected shape, coloring each line to match the original
$reflection_pic = showplot(array(
    "$l12r,blue,$x1r,$x2r,,closed",     // Blue for line 1 reflection
    "$l13r,green,$x1r,$x3r,closed,closed",    // Green for line 2 reflection
    "$l23r,orange,$x2r,$x3r,,closed"    // Corrected orange for line 3 reflection
), -6, 6, -6, 6);

$plot = mergeplots($pic, $reflection_pic)

// === QUESTION TEXT ===

<p>Below is a triangle and its transformation. What type of transformation has occurred?</p>
$plot

<p>Select the correct transformation:</p>
$answerbox[0]
