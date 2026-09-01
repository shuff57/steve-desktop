// === NAME - DESCRIPTION: I HW Unit 4 Review Solve and Graph inequalities in one variable. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("interval")

// Step 1: Pick positive a (we'll negate it in the expression)
$a = rand(1,4);

// Step 2: Choose random shift and boundary
$b = rand(-5,5);
$xsol = rand(-5,5);

// Step 3: Compute c so that solution becomes x < xsol
// From solving: -a(x - b) > c → x < -c/a + b → So c = -a(xsol - b)
$c = -1 * $a * ($xsol - $b);

// Step 4: Build inequality expression
$ineq = makepretty("-{$a}(x - {$b}) > {$c}");

// Step 5: Define solution
$intervalans = "(-oo,$xsol)";
$answer[0] = $intervalans;
$answer[1] = intervalstodraw($intervalans, -10, 10);

// Set types and formats
$answerformat[0] = "inequality";
$answerformat[1] = "numberline";
$anstypes = ["calcinterval", "draw"];
$grid = "-10,10";

// === QUESTION TEXT ===

<p>Solve and graph the inequality: `$ineq`
  <br /><br />
  Solution: $answerbox[0]
  <br /><br />
  Graph: $answerbox[1]</p>
