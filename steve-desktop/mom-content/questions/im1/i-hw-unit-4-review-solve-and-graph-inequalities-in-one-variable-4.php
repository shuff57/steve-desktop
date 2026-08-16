// === NAME - DESCRIPTION: I HW Unit 4 Review Solve and Graph inequalities in one variable. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
loadlibrary("interval")

// Random coefficients
$a = rand(1,4);         // Coefficient on (x - b)
$b = rand(-5,5);         // The shift inside the parentheses
$xsol = rand(-5,5);      // Desired integer solution boundary

// Back-solve to ensure clean integer solution
$c = $a * ($xsol - $b);

// Build inequality expression
$ineq = makepretty("{$a}(x - {$b}) \le {$c}");

// Create answer forms
$intervalans = "(-oo,$xsol]";
$answer[0] = $intervalans;
$answer[1] = intervalstodraw($intervalans, -10, 10);

// Define formats
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
