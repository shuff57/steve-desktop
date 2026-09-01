// === NAME - DESCRIPTION: Determine the transformation (translation) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices");

$answerboxsize = 5;

// Set up a multiple-choice question with options "rotation", "reflection", "translation", "none"
$questions[0] = array("rotation", "reflection", "translation", "none");
$answer[0] = 2;  // Correct answer is "translation"

// Randomize the shape coordinates, ensuring the triangle stays within the plot
$x1 = rand(-6, 3);  // Random x-coordinate of the first point (leaving space for translation)
$x2 = $x1 + rand(1, 3);  // Second point x-coordinate
$y1 = rand(-6, 3);  // Random y-coordinate of the first point
$y2 = $y1 + rand(1, 3);  // Second point y-coordinate

// Translation vector, ensuring the shape stays within the plot
$tx = rand(1, 7 - max($x1, $x2));  // Translate by this much in x direction
$ty = rand(1, 7 - max($y1, $y2));  // Translate by this much in y direction

// Generate original shape (red triangle)
$commands = "initPicture(-10,10,-10,10);";
$commands .= "axes(1,1,1,1,1);";
$commands .= "fill = 'red';";
$commands .= "path([[$x1,$y1],[$x2,$y1],[$x2,$y2],[$x1,$y1]]);";  // Create the triangle using path commands

// Apply the translation to the shape
$commands .= "fill = 'blue';";
$commands .= "path([[".($x1+$tx).",".($y1+$ty)."],[".($x2+$tx).",".($y1+$ty)."],[".($x2+$tx).",".($y2+$ty)."],[".($x1+$tx).",".($y1+$ty)."]]);";  // Translated shape

$answerboxsize = 5;

// Generate the plot for both shapes (original and translated)
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
