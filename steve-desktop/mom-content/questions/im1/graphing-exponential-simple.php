// === NAME - DESCRIPTION: graphing exponential simple ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===

$answerboxsize = 4;
$hidetips = true;

// Define the base and coefficient for the exponential function
$a = randfrom(array(-4, -3.5, -3, -2.5, -2, -1.5, -1, -.5, 0.5, 1, 1.5, 2, 2.5, 3, 3.5 ,4)); ;  // Random coefficient for the exponential function
$b = rand(1,4,1)

// Display the equation of the exponential function
$equ = "`$a($b^x)`";  // Equation for display

// Define x values and calculate corresponding y values for plotting
$x = consecutive(-5, 5);  // Generate x values from -5 to 5
$y = calconarray($x, "$a * ($b^x)");  // Calculate corresponding y values

// Define the answer format (two points or curve with optional dots)
$answer = array(
    "$a * ($b^x)",            // Equation of the exponential function
    "optional,$x[3],$y[3]",   // Optional point 1
    "optional,$x[4],$y[4]",   // Optional point 2
    "         $x[5],$y[5]",   // Optional point 3
    "optional,$x[0],$y[0]",   // Optional point 4
    "optional,$x[1],$y[1]",   // Optional point 5
    "optional,$x[2],$y[2]",   // Optional point 6
    "         $x[6],$y[6]",   // Optional point 7
    "optional,$x[7],$y[7]",   // Optional point 8
    "optional,$x[8],$y[8]",   // Optional point 9
    "optional,$x[9],$y[9]",   // Optional point 10
    "optional,$x[10],$y[10]", // Optional point 11
);

// Set the answer format for plotting
$answerformat = "twopoint,dot, exp";

// Define the grid settings
$grid = "-5,5,-10,10,1,1,450,450";  // Grid boundaries and aspect ratio
$snaptogrid = 0.5;  // Snap to grid resolution of 0.5 units

// Adjust y-axis bounds based on the exponential function's range
$ymin = min(calconarray($x, "$a * ($b^x)"));  // Minimum y value
$ymax = max(calconarray($x, "$a * ($b^x)"));  // Maximum y value

// === QUESTION TEXT ===

<p>Graph `y = `$equ below:</p>
