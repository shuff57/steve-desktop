// === NAME - DESCRIPTION: graph mx+b  #3 (slope=m/3) ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===

// Graph a Linear Equation given a point and its slope

$answerboxsize = 4;
$hidetips = true;

// Define the slope and intercept
$a = nonzerorand(-4, 4);
$b = nonzerorand(-5, 5) where (abs($b) != $a);

// Define the numerator and denominator for the slope
$n = randfrom("-1,-2,-4,1,2,4");
$d = 3;  // Set denominator for slope
$m = $n / $d;  // Calculate the slope as a fraction

// Display the slope as a reduced fraction
$mfd = dispreducedfraction($n, $d);  // For display purposes
$mf = makereducedfraction($n, $d);  // Reduced fraction for calculations

// Calculate ymin and ymax for graph window
$ymin = min(-5 * $a + $b, -10);
$ymax = max(5 * $a + $b, 10);

// Generate the equation of the line for display
$equ = "`y = $mfd x + $b`";  // Equation displayed with slope as a fraction

// Define x values and calculate corresponding y values
$x = consecutive(-5, 5);  // Generate consecutive x values from -4 to 4
$y = calconarray($x, "$mf * x + $b");  // Calculate corresponding y values using reduced slope

// Define the answer format (two points or line with optional dots)
$answer = array(
    "$mf * x + $b",                // Equation of the line with reduced slope
    "optional,$x[3],$y[3]",        // Optional point 1
    "optional,$x[4],$y[4]",        // Optional point 2
    "optional,$x[5],$y[5]",        // Optional point 3
    "optional,$x[0],$y[0]",        // Optional point 4
    "optional,$x[1],$y[1]",        // Optional point 5
    "optional,$x[2],$y[2]",        // Optional point 6
    "optional,$x[6],$y[6]",        // Optional point 7
    "optional,$x[7],$y[7]",        // Optional point 8
    "optional,$x[8],$y[8]",        // Optional point 9
  	"optional,$x[9],$y[9]",				 // Optional point 10
  	"optional,$x[10],$y[10]",				 // Optional point 11
);

// Set the answer format for plotting
$answerformat = "twopoint,dot,line";

// Define the grid settings
$grid = "-5,5,-5,5,1,1,450,450" // Grid boundaries and aspect ratio
$snaptogrid = .5;  // Snap to grid resolution of 0.5 units

// Display the equation of the line using the $equ variable
$equ = makepretty("y = $mf x + $b")

// === QUESTION TEXT ===

<p>Graph the following linear equation:</p>

Plot the line  `$equ`

$answerbox$fb
