// === NAME - DESCRIPTION: 2-102.2 Given table, write equation (linear)  ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc");  // Two parts: one for the equation and one for choosing between linear/exponential

// Define variables for the table values
$a = rand(3, 10);  // This will be the initial value for the function
$d = array(2, 4, 5);  // Possible slope or growth rates
$r = rand(0, 2);
$m1 = $d[$r];  // Select the slope or growth rate

// Calculate table values based on the selected type
$c1 = $a - $m1;  // Value for x = -3
$c2 = $a + $m1;  // Value for x = 3
$c3 = $c2 + $m1; // Value for x = 6
$c4 = $c3 + $m1; // Value for x = 9

// Define the correct equation (assumed to be linear for this example)
$m = "$m1/3";  // Slope calculation (linear)
$answer[0] = "$m * x + $a";  // Equation for y in terms of x

// Set up the multiple-choice options (linear vs exponential)

// Set the size of the answer box for the equation
$answerboxsize[0] = 15;  // Provide enough space for students to input the equation

// Display correct answers after submission
$showanswer[0] = "`y = $m x + $a`";  // Show the correct equation

// === QUESTION TEXT ===

<p>14. Write the equation for each table and determine if the function is linear or exponential.</p>

<table class='stats'>
  <tbody>
    <tr> <td> x </td> <td> y </td></tr>
    <tr> <td> -3 </td> <td> $c1 </td></tr>
    <tr> <td> 0 </td> <td> $a </td></tr>
    <tr> <td> 3 </td> <td> $c2 </td></tr>
    <tr> <td> 6 </td> <td> $c3 </td></tr>
    <tr> <td> 9 </td> <td> $c4 </td></tr>
  </tbody>
</table>
<p>Equation of the line: `y =` $answerbox[0]</p>
