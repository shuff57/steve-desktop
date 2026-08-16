// === NAME - DESCRIPTION: Use the quotient property to expand log_b((x-y)/z) (local for Steven Huff) hardcoded for test ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
// Hardcoded variables instead of diffrandsfrom
$var = array("x", "y", "z");
$variables = "$var[0],$var[1],$var[2]";

// Hardcoded base
$b = 6;

// Expression and logic preserved
$expr = "log_$b(($var[0] + $var[1])/$var[2])";

$answer = "log_$b($var[0] + $var[1]) - log_$b($var[2])";
$requiretimes = "/,=0";

$answerformat = "nodecimal";
$answerboxsize = 30;

// === QUESTION TEXT ===
Use the product, quotient, and/or power properties of logarithms to expand the following. Assume all variables are positive.
<br /><br />
`$expr=` $answerbox<br>
