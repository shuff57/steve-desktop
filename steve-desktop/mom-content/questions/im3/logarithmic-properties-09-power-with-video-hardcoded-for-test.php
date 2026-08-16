// === NAME - DESCRIPTION: Logarithmic Properties-09 power (with video) (local for Steven Huff) hardcoded for test ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$dp = 3;
$abstolerance = 5*10^(-$dp-1);

// Hardcoded values
$vars = "x";
$base = 7;
$number1 = 4;

// Formatting the display text
$text = makeprettydisp("log_$base ($vars^$number1) =");

// Answers and validation logic
$requiretimes = "^,=0";
$variables = $vars;
$answer = "$number1 log_$base($vars)";
$showanswer = "`$answer`";

// format
$answerboxsize = 15;
$hidetips = true;

// === QUESTION TEXT ===
Use the power property to rewrite each expression. Assume all variables are positive.
<br/>
<br/>
$text $answerbox
<br/>
<br/>
The answer format in lowercase characters is: log_base (number)<br/>
Spaces in the answer are optional.
