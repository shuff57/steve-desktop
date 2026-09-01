// === NAME - DESCRIPTION: Graph a perpendicular line through a given point #2  ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = "draw,numfunc";
$answerformat[0] = "twopoint,line,dot";  // Allows two points, a line, and optional dots
$snaptogrid[0] = 1;
$hidetips = "true";

$mlist = array(2,4);

$dir = nonzerodiffrands(-1,1,2);
$m = randfrom("2,4") * $dir[0];
$m2 = -1 / $m;
$m2p = decimaltofraction($m2);
$b0 = rand(7,10);
$b1 = rrand(2,8,2);
$b = array($b0, $b1);
$b[0] = $b[0] * $dir[1];
$b[1] = $b[1] * $dir[0];

$x1 = nonzerorand(-7,7) where ((100 * $m2 * $x1) % 100 == 0);
$y1 = $m2 * $x1 + $b[1];

$eq1 = "$m x + $b[0]";
$p1 = "`($x1, $y1)`";

$eq1p = makexxprettydisp("`y = $m x + $b[0]`");
$grid[0] = "-15,15,-15,15,5:1,5:1,400,400";
$background[0] = "$eq1";

// Optional points for plotting along the line from x = -15 to x = 15
$x_values = consecutive(-15, 15);  // Generate x values from -15 to 15
$y_values = calconarray($x_values, "$m2 * x + $b[1]");  // Calculate corresponding y values using the slope

// Define the main answer as the line equation, with every optional point explicitly listed
$answers[0] = array(
    "$m2 x + $b[1]",                // Equation of the line with calculated slope
    "optional,$x_values[0],$y_values[0]",    // Optional point at x = -15
    "optional,$x_values[1],$y_values[1]",    // Optional point at x = -14
    "optional,$x_values[2],$y_values[2]",    // Optional point at x = -13
    "optional,$x_values[3],$y_values[3]",    // Optional point at x = -12
    "optional,$x_values[4],$y_values[4]",    // Optional point at x = -11
    "optional,$x_values[5],$y_values[5]",    // Optional point at x = -10
    "optional,$x_values[6],$y_values[6]",    // Optional point at x = -9
    "optional,$x_values[7],$y_values[7]",    // Optional point at x = -8
    "optional,$x_values[8],$y_values[8]",    // Optional point at x = -7
    "optional,$x_values[9],$y_values[9]",    // Optional point at x = -6
    "optional,$x_values[10],$y_values[10]",  // Optional point at x = -5
    "optional,$x_values[11],$y_values[11]",  // Optional point at x = -4
    "optional,$x_values[12],$y_values[12]",  // Optional point at x = -3
    "optional,$x_values[13],$y_values[13]",  // Optional point at x = -2
    "optional,$x_values[14],$y_values[14]",  // Optional point at x = -1
    "optional,$x_values[15],$y_values[15]",  // Optional point at x = 0
    "optional,$x_values[16],$y_values[16]",  // Optional point at x = 1
    "optional,$x_values[17],$y_values[17]",  // Optional point at x = 2
    "optional,$x_values[18],$y_values[18]",  // Optional point at x = 3
    "optional,$x_values[19],$y_values[19]",  // Optional point at x = 4
    "optional,$x_values[20],$y_values[20]",  // Optional point at x = 5
    "optional,$x_values[21],$y_values[21]",  // Optional point at x = 6
    "optional,$x_values[22],$y_values[22]",  // Optional point at x = 7
    "optional,$x_values[23],$y_values[23]",  // Optional point at x = 8
    "optional,$x_values[24],$y_values[24]",  // Optional point at x = 9
    "optional,$x_values[25],$y_values[25]",  // Optional point at x = 10
    "optional,$x_values[26],$y_values[26]",  // Optional point at x = 11
    "optional,$x_values[27],$y_values[27]",  // Optional point at x = 12
    "optional,$x_values[28],$y_values[28]",  // Optional point at x = 13
    "optional,$x_values[29],$y_values[29]",  // Optional point at x = 14
    "optional,$x_values[30],$y_values[30]"   // Optional point at x = 15
);

// Set the answer for the equation and formatting
$answer[1] = "y = $m2 x + $b[1]";
$answerformat[1] = "equation";
$showanswer[1] = makexxprettydisp("y = $m2p x + $b[1]");
$variables[1] = "x,y";

// === QUESTION TEXT ===

<p>The equation $eq1p is graphed below. </p>
<p>Draw a line perpendicular to $eq1p that goes through the point $p1. Then write the equation for that line in slope-intercept form.</p>
$answerbox[0]
<p>Write the equation of the line that is perpendicular to $eq1p and passes through the point $p1:</p>
$answerbox[1]
$previewloc[1]
