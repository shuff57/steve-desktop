// === NAME - DESCRIPTION: Logarithmic Properties-03: log(x) + log(x+n) (with video) (local for Steven Huff) hardcoded for test ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$dp = 3;
$abstolerance = 5*10^(-$dp-1);

// Hardcoded variable and numbers
$vars = "x";
$number1 = 5;
$number2 = 3; // Defined to prevent script errors
$numberans = $number1 * $number2;

// The makeprettydisp remains to handle the formatting
$text = makeprettydisp("log_3 ($vars)+log_3 ($vars+$number1) =");

// Answers and variables
$variables = $vars;

// Calculation logic preserved
$answer = "log_3($vars^2+$number1$vars)";

$answerboxsize = 15;
$hidetips = true;

// === QUESTION TEXT ===
Write the following sum as a single logarithm.  Assume all variables are positive.
<br /><br />
$text $answerbox
<br /><br />
The answer format in lowercase characters is: log_base (number)<br/>
Spaces in the answer are optional.
