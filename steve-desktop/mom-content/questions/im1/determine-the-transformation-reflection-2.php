// === NAME - DESCRIPTION: Determine the transformation (reflection)  ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices");

$answerboxsize = 5;

// Set up a multiple-choice question with options "rotation", "reflection", "translation", "none"
$questions[0] = array("rotation", "reflection", "translation", "none");
$answer[0] = 1;  // Correct answer is "reflection"

// Randomize the coordinates for the triangle's vertices
$x1 = rand(1, 3);  
$y1 = rand(1, 3);
$x2 = $x1 + rand(1, 3);  
$y2 = $y1 + rand(1, 3); 
$x3 = rand(1, 3);  
$y3 = rand(1, 3);

// Ensure the vertices are distinct and not collinear
$c = 1;  // A constant to adjust vertices if needed
if (($x1 == $x2 && $y1 == $y2) || ($x1 == $x3 && $y1 == $y3) || ($x2 == $x3 && $y2 == $y3)) {
    // If any of the points are identical, adjust the third vertex
    $x3 += 3*$c;
    $y3 -= 2*$c;
}

// Check for collinearity using the area formula: Area = 0 means points are collinear
$area = abs(($x1 * ($y2 - $y3) + $x2 * ($y3 - $y1) + $x3 * ($y1 - $y2)) / 2);
if ($area == 0) {
    // If the area is 0, adjust the third vertex to ensure the triangle is valid
    $x3 += 3*$c;
    $y3 -= 2*$c ;
}

// Randomize the slope (m) and y-intercept (b) for the reflection line
$m = randfrom(array(-2, -1, 1, 2));  // Random slope
$b = rand(-3, 3);  // Random y-intercept

// Compute the reflection of each point (x, y) across the line y = mx + b
// For point (x1, y1)
$d1 = ($x1 + ($y1 - $b) * $m) / (1 + $m**2);
$x1r = 2 * $d1 - $x1;
$y1r = 2 * $d1 * $m - $y1 + 2 * $b;

// For point (x2, y2)
$d2 = ($x2 + ($y2 - $b) * $m) / (1 + $m**2);
$x2r = 2 * $d2 - $x2;
$y2r = 2 * $d2 * $m - $y2 + 2 * $b;

// For point (x3, y3)
$d3 = ($x3 + ($y3 - $b) * $m) / (1 + $m**2);
$x3r = 2 * $d3 - $x3;
$y3r = 2 * $d3 * $m - $y3 + 2 * $b;

// Generate the commands to draw the original shape (red triangle)
$commands = "initPicture(-7,7,-7,7);";
$commands .= "axes(1,1,1,1,1);";
$commands .= "fill = 'red';";
$commands .= "path([[$x1,$y1],[$x2,$y2],[$x3,$y3],[$x1,$y1]]);";  // Draw the original triangle

// Draw the random reflection line y = mx + b
//$commands .= "stroke = 'black';";
//$commands .= "line([-7, " . (-7 * $m + $b) . "], [7, " . (7 * $m + $b) . "]);";  // Line from x = -7 to x = 7

// Draw the reflected shape (blue triangle)
$commands .= "fill = 'blue';";
$commands .= "path([[$x1r,$y1r],[$x2r,$y2r],[$x3r,$y3r],[$x1r,$y1r]]);";  // Draw the reflected triangle


$answerboxsize = 5;

// Generate the plot for both shapes (original and reflected)
$pic = showasciisvg($commands, 400, 400);

// === QUESTION TEXT ===

<table style="border-collapse: collapse; width: 100%; height: 23px;" border="1">
  <tbody>
    <tr style="height: 23px;">
      <td style="width: 100%; height: 23px;">
        <p>Below is a triangle and its transformation. What type of transformation has occurred?</p>
        <p>$pic</p>
      </td>
    </tr>
  </tbody>
</table>
<p>Select the correct transformation:<br />$answerbox[0]</p>
