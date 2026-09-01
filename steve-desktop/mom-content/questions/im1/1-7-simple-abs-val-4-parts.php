// === NAME - DESCRIPTION: 1-7 Simple abs val 4 parts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Generate random values for the expressions
$a1 = rand(-10, 10);
$b1 = rand(-10, 10);

$a2 = rand(-10, 10);
$b2 = rand(-10, 10);

$a3 = rand(-10, 10);
$b3 = rand(-10, 10);

$a4 = rand(-10, 10);
$b4 = rand(-10, 10);

// Randomly decide whether to include a minus sign outside the absolute value
$sign1 = rand(0, 1) == 1 ? "-" : "";
$sign2 = rand(0, 1) == 1 ? "-" : "";
$sign3 = rand(0, 1) == 1 ? "-" : "";
$sign4 = rand(0, 1) == 1 ? "-" : "";

// Create the expressions
$expression1 = "$sign1 abs($a1 + $b1)";
$expression2 = "$sign2 abs($a2 - $b2)";
$expression3 = "$sign3 abs($a3 + $b3)";
$expression4 = "$sign4 abs($a4 - $b4)";

// Evaluate the expressions
$answer[0] = ($sign1 == "-" ? -1 : 1) * abs($a1 + $b1);
$answer[1] = ($sign2 == "-" ? -1 : 1) * abs($a2 - $b2);
$answer[2] = ($sign3 == "-" ? -1 : 1) * abs($a3 + $b3);
$answer[3] = ($sign4 == "-" ? -1 : 1) * abs($a4 - $b4);

$anstypes = "number,number,number,number"; // Expect numeric answers for all four parts

// === QUESTION TEXT ===

<p>Evaluate each absolute value expression. Refer to the Math Notes box in this lesson for the definition of absolute value.</p>

<p><b>a)</b> `$sign1|$a1 + $b1|` = $answerbox[0]</p>
<p><b>b)</b> `$sign2|$a2 - $b2|` = $answerbox[1]</p>
<p><b>c)</b> `$sign3|$a3 + $b3|` = $answerbox[2]</p>
<p><b>d)</b> `$sign4|$a4 - $b4|` = $answerbox[3]</p>
