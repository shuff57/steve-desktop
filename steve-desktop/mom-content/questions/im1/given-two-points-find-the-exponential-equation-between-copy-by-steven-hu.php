// === NAME - DESCRIPTION: Given two points find the exponential equation between (copy by Steven Huff) #1 ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

//$anstypes = array("number");  // Answer type for the equation

// Randomize values for a and b
$a = rand(1, 10);  // Randomize a
$b = rand(2, 5);   // Randomize b

// Calculate the second point based on a and b
$x1 = rand(-3, 0);
$y1 = $a * $b^ $x1;
$x2 = rand(1,4);
$y2 = $a * ($b^ $x2);  // y2 = a * b^3



// Construct the exponential equation
$answer = makeprettydisp("`$a * $b^x`");

// === QUESTION TEXT ===

<p>Find the exponential equation in the format `f(x) = a*b^x` that passes through the points `($x1, $y1)` and `($x2, $y2)`.</p>
<p>Your answer should be in the format `a*b^x`:</p>
<p>$answerbox</p>
