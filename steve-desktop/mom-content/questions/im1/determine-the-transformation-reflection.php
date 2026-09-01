// === NAME - DESCRIPTION: Determine the transformation (reflection) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices");

$answerboxsize = 5;

// Set up a multiple-choice question with options "rotation", "reflection", "translation", "none"
$questions[0] = array("rotation", "reflection", "translation", "none");
$answer[0] = 1;  // Correct answer is "reflection"

// Randomize the shape coordinates
$r1, $r2, $r3, $r4 = rands(1, 3, 4);  // Random values for reflection
$x1 = rand(1, 3);  // Random x-coordinate of first point
$x2 = $x1 + $r1;  // Second point x-coordinate
$y1 = rand(1, 3);  // Random y-coordinate of first point
$y2 = $y1 + $r2;  // Second point y-coordinate

// Generate original shape (red triangle)
$commands = "initPicture(-7,7,-7,7);";
$commands .= "axes(1,1,1,1,1);";
$commands .= "fill = 'red';";
$commands .= "path([[$x1,$y1],[$x2,$y1],[$x2,$y2],[$x1,$y1]]);";  // Create a triangle using path commands

// Apply a reflection to the shape across the y-axis (negating x-values)
$commands .= "fill = 'blue';";
$commands .= "path([[-$x1,$y1],[-$x2,$y1],[-$x2,$y2],[-$x1,$y1]]);";  // Reflected shape with negated x-coordinates


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
