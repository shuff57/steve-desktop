// === NAME - DESCRIPTION: I HW Unit 4 Review Solve and Graph inequalities in one variable. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
loadlibrary("interval")

// Step 1: Randomize coefficients
$a = rand(1,4);
$d = rand(1,4);
$coeff = $a + $d; // Total x coefficient after moving terms

// Step 2: Pick integer solution for x
$xsol = rand(-5,5);

// Step 3: Compute RHS = coeff * xsol
$rhs = $coeff * $xsol;

// Step 4: Random b, then compute c
$b = rand(-10,10);
$c = $rhs + $b;

// Step 5: Construct full inequality
$lhs = "$a x + $b";
$rhs_expr = "$c - $d x";
$ineq = makepretty("$lhs > $rhs_expr");

// Step 6: Answers
$inequalityans = makepretty("x > $xsol");
$intervalans = "($xsol,oo)";

// Final answers
$answer[0] = $intervalans;
$answer[1] = intervalstodraw($intervalans, -10, 10);

// Formats
$answerformat[0] = "inequality";
$answerformat[1] = "numberline";
$anstypes = ["calcinterval", "draw"];
$grid = "-10,10";

// === QUESTION TEXT ===
Solve and graph the inequality: `$ineq`

<br><br>
Solution (as an inequality): $answerbox[0]

<br><br>
Graph the solution on the number line: $answerbox[1]
