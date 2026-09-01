// === NAME - DESCRIPTION: 1-39 find value of exponential ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Randomize values for the base and exponent
$base = rand(2, 5);  // Random value for the base of the function
$exponent_f4 = rand(3, 6);  // Random exponent for f(4)
$f4_value = $base^ $exponent_f4;  // Calculate f(4) using ^ for exponentiation

// Generate values for x1, x2, x3 manually to ensure they are unique
$rand_values = array(rand(1, 5), rand(1, 5), rand(1, 5));
$x1 = $rand_values[0];
$x2 = $rand_values[1];
$x3 = $rand_values[2];

// Manually adjust to ensure uniqueness
if ($x2 == $x1) {
    $x2 = ($x2 % 5) + 1;  // Adjust x2 to be different
}
if ($x3 == $x1 || $x3 == $x2) {
    $x3 = ($x3 % 5) + 2;  // Adjust x3 to be different from both x1 and x2
}

// Calculate f(x) values for parts a, b, and c using ^
$f_x1 = $base^$x1;
$f_x2 = $base^$x2;
$f_x3 = $base^$x3;

// Ensure the answers are presented as reduced fractions
$anstypes = "numfunc,numfunc,numfunc";

// Assign the correct answers
$answer[0] = $f_x1;  // Answer for part a
$answer[1] = $f_x2;  // Answer for part b
$answer[2] = $f_x3;  // Answer for part c

// Display the correct answers for each box
$showanswer[0] = "$answer[0]";
$showanswer[1] = "$answer[1]";
$showanswer[2] = "$answer[2]";

// === QUESTION TEXT ===

<p>Find the value of: `$base^ x`</p>

<p>a. `f($x1)` = $answerbox[0]</p>
<p>b. `f($x2)` = $answerbox[1]</p>
<p>c. `f($x3)` = $answerbox[2]</p>
