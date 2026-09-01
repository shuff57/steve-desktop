// === NAME - DESCRIPTION: 1-10 substituting values ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Randomize the values for the equations ensuring correct answers
$a1 = rand(1, 10);
$b1 = rand(1, 5);

$a2 = rand(1, 5);
$b2 = rand(0, 5);  // Ensure no subtraction leads to a negative square root

$a3_num = rand(1, 50); // Numerator for the fraction in part c
$b3_den = rand(2, 10); // Denominator (x) for the fraction in part c

$x = $b3_den; // Set x to the denominator to ensure the division results in a proper or improper fraction

// Define the equations
$expression_a = "`y = $a1 - $b1 x`";
$expression_b = "`y = x^2 - $b2`";
$expression_c = "`y = $a3_num / x`";

// Calculate the correct answers
$answer_a = $a1 - $b1 * $x;
$answer_b = $x ** 2 - $b2;

// Reduce the fraction for part c
$gcd_ac = 1;
for ($i=1..min($a3_num, $x)) {
    if (($a3_num % $i == 0) && ($x % $i == 0)) {
        $gcd_ac = $i;
    }
}

$numerator_c = $a3_num / $gcd_ac;
$denominator_c = $x / $gcd_ac;

if ($denominator_c < 0) {
    $numerator_c = -$numerator_c;
    $denominator_c = -$denominator_c;
}

$answer_c = "$numerator_c/$denominator_c"; // Store as a reduced fraction

// Define the answer types, ensuring the last one expects a fraction
$anstypes = "numfunc,numfunc,numfunc";

// Assign the correct answers
$answer[0] = $answer_a;
$answer[1] = $answer_b;
$answer[2] = $answer_c; // Answer stored as a reduced fraction

// Display the correct answers for each box
$showanswer[0] = "$answer_a";
$showanswer[1] = "$answer_b";
$showanswer[2] = "$answer_c";

// === QUESTION TEXT ===

<p>For each equation below, determine the value of `y` if `x = $x`.</p>

<p style="margin-bottom: 15px;">a. $expression_a = $answerbox[0]</p>
<p style="margin-bottom: 15px;">b. $expression_b = $answerbox[1]</p>
<p style="margin-bottom: 15px;">c. $expression_c = $answerbox[2]</p>
