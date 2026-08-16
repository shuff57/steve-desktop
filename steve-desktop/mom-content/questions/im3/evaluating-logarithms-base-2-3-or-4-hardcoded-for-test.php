// === NAME - DESCRIPTION: Evaluating Logarithms Base 2, 3, or 4 (local for Steven Huff)hardcoded for test ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("number", "number", "number","number","number");

// Hardcoded base
$b = 3;

// Original exponents
$e = array(0,1,2,3,4);

// Calculations based on the fixed base
$a[0]=1;
$a[1]=$b;
$a[2]=$b^2;
$a[3]=$b^3;
$a[4]=$b^4;

// Replaced jointshuffle with fixed manual mapping
$exp = array(2, 0, 4, 1, 3);
$arg = array($a[2], $a[0], $a[4], $a[1], $a[3]);

$answer[0]=$exp[0];
$answer[1]=$exp[1];
$answer[2]=$exp[2];
$answer[3]=$exp[3];
$answer[4]=$exp[4];

$hidetips;
$answerboxsize=10;

// === QUESTION TEXT ===
<b>Evalulate each of the following:</b><br><br>
`log_$b ($arg[0])=` $answerbox[0] <br><br>
`log_$b ($arg[1])=` $answerbox[1] <br><br>
`log_$b ($arg[2])=` $answerbox[2] <br><br>
`log_$b ($arg[3])=` $answerbox[3] <br><br>
`log_$b ($arg[4])=` $answerbox[4]
