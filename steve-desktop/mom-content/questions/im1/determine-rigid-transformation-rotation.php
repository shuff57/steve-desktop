// === NAME - DESCRIPTION: Determine rigid transformation (rotation) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices");

$answerboxsize = 10;

// Set up a multiple-choice question with options "rotation", "reflection", "translation", "none"
$questions[0] = array("rotation", "reflection", "translation", "none");
$answer[0] = 0;  // Correct answer is "reflection"

// Randomizes whether the shape opens right or left
$r = rand(1, 4);  // Randomly selects one of the four cases

// Randomize x-coordinates for the three points
$x_vals = diffrands(-5, 5, 3, "inc");
$x1 = $x_vals[0];
$x2 = $x_vals[1];
$x3 = $x_vals[2];

// Randomize y-coordinates for the three points
$y_vals = diffrands(-5, 5, 3, "inc");
$y1 = $y_vals[0];
$y2 = $y_vals[1];
$y3 = $y_vals[2];

// Calculate the slopes for the three lines to form a closed shape
$m12 = ($y1 - $y2) / ($x1 - $x2);
$l12 = "$m12*(x - $x1) + $y1";  // Line between points (x1, y1) and (x2, y2)

$m13 = ($y1 - $y3) / ($x1 - $x3);
$l13 = "$m13*(x - $x1) + $y1";  // Line between points (x1, y1) and (x3, y3)

$m23 = ($y2 - $y3) / ($x2 - $x3);
$l23 = "$m23*(x - $x2) + $y2";  // Line between points (x2, y2) and (x3, y3)

// Plot the original shape, coloring each line differently
$pic = showplot(array(
    "$l12,blue,$x1,$x2,,closed",     // Blue for line 1
    "$l13,green,$x1,$x3,closed,closed",    // Green for line 2
    "$l23,orange,$x2,$x3,,closed"    // Orange for line 3
), -6, 6, -6, 6);

// Define two non-parallel reflection lines
$reflection_line1_slope = 1;  // Slope of first reflection line (diagonal)
$reflection_line1 = "x";  // First reflection line: y = x

$reflection_line2_slope = -1;  // Slope of second reflection line (diagonal)
$reflection_line2 = "-x";  // Second reflection line: y = -x

// Reflect the shape over the first line y = x (swap x and y coordinates)
$x1r1 = $y1;
$y1r1 = $x1;
$x2r1 = $y2;
$y2r1 = $x2;
$x3r1 = $y3;
$y3r1 = $x3;

// Calculate slopes for the first reflection
$m12r1 = ($y1r1 - $y2r1) / ($x1r1 - $x2r1);
$m13r1 = ($y1r1 - $y3r1) / ($x1r1 - $x3r1);
$m23r1 = ($y2r1 - $y3r1) / ($x2r1 - $x3r1);

$l12r1 = "$m12r1*(x - $x1r1) + $y1r1";  // Reflected line 1
$l13r1 = "$m13r1*(x - $x1r1) + $y1r1";  // Reflected line 2
$l23r1 = "$m23r1*(x - $x2r1) + $y2r1";  // Reflected line 3

// Reflect the shape over the second line y = -x (negate and swap x and y coordinates)
$x1r2 = -$y1r1;
$y1r2 = -$x1r1;
$x2r2 = -$y2r1;
$y2r2 = -$x2r1;
$x3r2 = -$y3r1;
$y3r2 = -$x3r1;

// Calculate slopes for the second reflection
$m12r2 = ($y1r2 - $y2r2) / ($x1r2 - $x2r2);
$m13r2 = ($y1r2 - $y3r2) / ($x1r2 - $x3r2);
$m23r2 = ($y2r2 - $y3r2) / ($x2r2 - $x3r2);

$l12r2 = "$m12r2*(x - $x1r2) + $y1r2";  // Second reflection line 1
$l13r2 = "$m13r2*(x - $x1r2) + $y1r2";  // Second reflection line 2
$l23r2 = "$m23r2*(x - $x2r2) + $y2r2";  // Second reflection line 3

// Plot the doubly reflected shape
$reflection_pic = showplot(array(
    "$l12r2,blue,$x1r2,$x2r2,,closed",     // Blue for line 1 reflection
    "$l13r2,green,$x1r2,$x3r2,closed,closed",    // Green for line 2 reflection
    "$l23r2,orange,$x2r2,$x3r2,,closed"    // Orange for line 3 reflection
), -6, 6, -6, 6);

// Merge the original and reflected shapes
$plot = mergeplots($pic, $reflection_pic);

// === QUESTION TEXT ===

<p>Below is a triangle and its transformation. What type of transformation has occurred?</p>
$plot

<p>Select the correct transformation:</p>
$answerbox[0]
