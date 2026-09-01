// === NAME - DESCRIPTION: 1-9 Simplifying expressions ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc","numfunc","numfunc","numfunc","numfunc","numfunc","numfunc","numfunc","numfunc")

// Generate random numbers for the expressions
$a = rand(-50, -10);
$b = rand(-20, -1);
$c = rand(1, 10);
$d = rand(-30, -1);
$e = rand(2, 5);
$f = rand(-10, -5);
$g = rand(-10, -5);
$h = rand(1, 10);
$i = $h ** 2;

// Define the expressions
$expression_a = "`$a + ($b)`";
$expression_b = "`$c - ($d)`";
$expression_c = "`$c \\times ($d)`";
$expression_d = "`$a \\div ($d)`";
$expression_e = "`$f \\times ($g) \\times ($e)`";
$expression_f = "`-($f - $h)`";
$expression_g = "`($g)^2`";
$expression_h = "`-$h^2`";
$expression_i = "`\\sqrt{$i}`";

// Calculate the correct answers
$answer_a = $a + $b;
$answer_b = $c - $d;
$answer_c = $c * $d;

// Calculate GCD manually for reduction using MyOpenMath-compatible loop syntax
$gcd_ad = 1;
for ($j=1..min(abs($a), abs($d))) {
    if (($a % $j == 0) && ($d % $j == 0)) {
        $gcd_ad = $j;
    }
}

$num = $a / $gcd_ad;
$den = $d / $gcd_ad;
if ($den < 0) {
    $num = -$num;
    $den = -$den;
}
$answer_d = "$num/$den"; // Store as a fraction string

$answer_e = $f * $g * $e;
$answer_f = -($f - $h);
$answer_g = $g ** 2;
$answer_h = -($h ** 2);
$answer_i = sqrt($i);

// Define the answer types
$anstypes = array("numfunc","numfunc","numfunc","numfunc","numfunc","numfunc","numfunc","numfunc","numfunc")

// Assign the correct answers
$answer[0] = $answer_a;
$answer[1] = $answer_b;
$answer[2] = $answer_c;
$answer[3] = $answer_d; // Fraction stored as a string
$answer[4] = $answer_e;
$answer[5] = $answer_f;
$answer[6] = $answer_g;
$answer[7] = $answer_h;
$answer[8] = $answer_i;

// Display the correct answers for each box
$showanswer[0] = "$answer_a";
$showanswer[1] = "$answer_b";
$showanswer[2] = "$answer_c";
$showanswer[3] = "$num/$den"; // Display the fraction properly
$showanswer[4] = "$answer_e";
$showanswer[5] = "$answer_f";
$showanswer[6] = "$answer_g";
$showanswer[7] = "$answer_h";
$showanswer[8] = "$answer_i";

// === QUESTION TEXT ===

<p>Simplify each expression.</p>

<p style="margin-bottom: 15px;">a. $expression_a = $answerbox[0]</p>
<p style="margin-bottom: 15px;">b. $expression_b = $answerbox[1]</p>
<p style="margin-bottom: 15px;">c. $expression_c = $answerbox[2]</p>
<p style="margin-bottom: 15px;">d. $expression_d = $answerbox[3]</p>
<p style="margin-bottom: 15px;">e. $expression_e = $answerbox[4]</p>
<p style="margin-bottom: 15px;">f. $expression_f = $answerbox[5]</p>
<p style="margin-bottom: 15px;">g. $expression_g = $answerbox[6]</p>
<p style="margin-bottom: 15px;">h. $expression_h = $answerbox[7]</p>
<p style="margin-bottom: 15px;">i. $expression_i = $answerbox[8]</p>
