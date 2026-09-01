// === NAME - DESCRIPTION: 1-41 simplify expressions ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Randomize numerators and denominators for each fraction
$frac_a1_num = rand(1, 5);  // Numerator for the first term in part a
$frac_a1_den = rand(2, 6);  // Denominator for the first term in part a
$frac_a2_num = rand(1, 5);  // Numerator for the second term in part a
$frac_a2_den = rand(2, 6);  // Denominator for the second term in part a

$frac_b1_num = rand(1, 5);  // Numerator for the first term in part b
$frac_b1_den = rand(2, 6);  // Denominator for the first term in part b
$frac_b2_num = rand(1, 5);  // Numerator for the second term in part b
$frac_b2_den = rand(2, 6);  // Denominator for the second term in part b

$frac_c1_num = rand(1, 5);  // Numerator for the first term in part c
$frac_c1_den = rand(2, 6);  // Denominator for the first term in part c
$frac_c2_num = rand(1, 5);  // Numerator for the second term in part c
$frac_c2_den = rand(2, 6);  // Denominator for the second term in part c

$frac_d_num = rand(1, 5);   // Numerator for the fraction in part d
$frac_d_den = rand(2, 6);   // Denominator for the fraction in part d

// Handle expressions
$frac_a = "-$frac_a1_num/$frac_a1_den + (-$frac_a2_num/$frac_a2_den)";
$frac_b = "-$frac_b1_num/$frac_b1_den - $frac_b2_num/$frac_b2_den";
$frac_c = "-$frac_c1_num/$frac_c1_den * (-$frac_c2_num/$frac_c2_den)";
$frac_d = "-2 ÷ $frac_d_num/$frac_d_den";

// Manually reduce the correct answers without using a function
$gcd = 1;  // Initialize GCD

// For answer 0 (part a)
$num_a = -$frac_a1_num * $frac_a2_den + (-$frac_a2_num * $frac_a1_den);
$den_a = $frac_a1_den * $frac_a2_den;
for ($i = 1..min(abs($num_a), abs($den_a))) {
    if ($num_a % $i == 0 && $den_a % $i == 0) {
        $gcd = $i;
    }
}
$answer[0] = ($num_a / $gcd) . "/" . ($den_a / $gcd);

// For answer 1 (part b)
$num_b = -$frac_b1_num * $frac_b2_den - $frac_b2_num * $frac_b1_den;
$den_b = $frac_b1_den * $frac_b2_den;
for ($i = 1..min(abs($num_b), abs($den_b))) {
    if ($num_b % $i == 0 && $den_b % $i == 0) {
        $gcd = $i;
    }
}
$answer[1] = ($num_b / $gcd) . "/" . ($den_b / $gcd);

// For answer 2 (part c)
$num_c = -$frac_c1_num * (-$frac_c2_num);
$den_c = $frac_c1_den * $frac_c2_den;
for ($i = 1..min(abs($num_c), abs($den_c))) {
    if ($num_c % $i == 0 && $den_c % $i == 0) {
        $gcd = $i;
    }
}
$answer[2] = ($num_c / $gcd) . "/" . ($den_c / $gcd);

// For answer 3 (part d)
$num_d = -2 * $frac_d_den;
$den_d = $frac_d_num;
for ($i = 1..min(abs($num_d), abs($den_d))) {
    if ($num_d % $i == 0 && $den_d % $i == 0) {
        $gcd = $i;
    }
}
$answer[3] = ($num_d / $gcd) . "/" . ($den_d / $gcd);

// Define the answer types
$anstypes = "numfunc,numfunc,numfunc,numfunc";

// Show answers
$showanswer[0] = "$answer[0]";
$showanswer[1] = "$answer[1]";
$showanswer[2] = "$answer[2]";
$showanswer[3] = "$answer[3]";

// === QUESTION TEXT ===

<p>Perform the indicated operation to evaluate each expression.</p>

<p><b>a.</b> `-$frac_a1_num/$frac_a1_den + (-$frac_a2_num/$frac_a2_den)` = $answerbox[0]</p>
<p>&nbsp;</p> <!-- Add space between questions -->
<p><b>b.</b> `-$frac_b1_num/$frac_b1_den - $frac_b2_num/$frac_b2_den` = $answerbox[1]</p>
<p>&nbsp;</p>
<p><b>c.</b> `-$frac_c1_num/$frac_c1_den * (-$frac_c2_num/$frac_c2_den)` = $answerbox[2]</p>
<p>&nbsp;</p>
<p><b>d.</b> `-2 ÷ $frac_d_num/$frac_d_den` = $answerbox[3]</p>
