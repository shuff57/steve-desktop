// === NAME - DESCRIPTION: Graphing randomized Linear Inequalities (Two Variables). ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = ["draw"];
$answerformat[0] = "inequality"; // This just tells MyOpenMath to use inequality graphing format
$grid = "-10,10,-10,10";
$snaptogrid = 1;

// Random slope and y-intercept
$r1 = rand(-5, 5);
$r2 = nonzerorand(-3, 3);
$r3 = rand(-5, 5);

$m = makereducedfraction($r1, $r2); // slope as a reduced fraction
$b = $r3;

$ineqs = array("<", "<=", ">", ">=");
$type = rand(0, 3);
$comp = $ineqs[$type];

// Define inequality expression y < mx + b
$expr = "$m*x" . ($b < 0 ? " - " . abs($b) : " + $b");
$ineqdisplay = makexxpretty("y $comp $expr");

// Store the full inequality string for drawing
$answer[0] = "$comp $m*x + $b";

// === QUESTION TEXT ===

<p>Graph the solution to the following linear inequality:</p>

<p>`$ineqdisplay`</p>

<p><b>Graph:</b></p>

$answerbox[0]
