// === NAME - DESCRIPTION: Graphing randomized Linear Inequalities (Two Variables).  ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = ["numfunc", "number", "number", "number", "number", "number", "draw"];
$answerformat[0] = "equation";
$answerformat[1] = "decimal";
$answerformat[2] = "decimal";
$answerformat[3] = "decimal";
$answerformat[4] = "decimal";
$answerformat[5] = "decimal";
$answerformat[6] = "inequality";

$grid = "-10,10,-10,10";
$snaptogrid = .5;
$variables = "x,y";

// Step 1: Generate random standard form inequality
$ineqs = array("<", "<=", ">", ">=");
$type = rand(0, 3);
$comp = $ineqs[$type];

$a = nonzerorand(-5, 5);

// Choose b so y-intercept is a multiple of 0.5
$bchoices = array(-4, -2, -1, 1, 2, 4);
$b = $bchoices[rand(0, count($bchoices) - 1)];

// Pick y-intercept as multiple of 0.5
$k = rand(-10, 10);
$yint = $k / 2;

// So c = b * yint
$c = $b * $yint;

// Display standard form Ax + By < C
$ineqdisplay = makexxpretty("$a x " . ($b < 0 ? "- " . abs($b) . " y" : "+ $b y") . " $comp $c");

// Solve for y: y = (-a/b)x + c/b
$slope = -$a / $b;

// Pretty slope-intercept RHS for expression
$rhs_expr = makereducedfraction(-$a, $b) . "*x" . ($yint < 0 ? " - " . abs($yint) : " + $yint");
$answer[0] = "y = $rhs_expr";

// Now build table values using exactly this expression
// Choose x-values centered at x = 0
$xvals = array(-2, -1, 0, 1, 2);

// Use the same slope and y-intercept from solved inequality
for ($i = 0..4) {
  $x = $xvals[$i];
  $y = $slope * $x + $yint;
  $answer[$i + 1] = round($y, 6); // round for safety
}

// Graph inequality in same form
$answer[6] = "$comp $slope*x + $yint";

// === QUESTION TEXT ===

<p>The inequality below is given in standard form:</p>
<p>`$ineqdisplay`</p>
Rewrite the inequality as an equation in slope-intercept form:$answerbox[0]

Use the boundary line to fill in the y-values for these x-values:
<table border="1" cellpadding="5" style="border-collapse:collapse; text-align:center;">
  <tr><th>x</th><th>y</th></tr>
  <tr><td>-2</td><td>$answerbox[1]</td> </tr>
  <tr><td>-1</td><td>$answerbox[2] </td></tr>
  <tr><td>0</td><td>$answerbox[3]</td></tr>
  <tr><td>1</td><td>$answerbox[4]</td></tr>
  <tr><td>2</td><td>$answerbox[5]</td></tr>
</table>

Graph the inequality:
$answerbox[6]
