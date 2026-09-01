// === NAME - DESCRIPTION: 1-38 functions, given input ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Randomize inputs
$x_a = rand(5, 10);  // Random value between 5 and 10
$x_b = rand(1, 4);   // Random value between 1 and 4
$k_c = rand(-10, -1);  // Random value between -10 and -1
$x_d = rand(1, 6);   // Random value between 1 and 6

// Define the functions and inputs for each part
$anstypes = "numfunc,numfunc,numfunc,numfunc";  // Answer types for each part (numeric function)

// Part a: f(x) = |x|
$f_a = abs($x_a);  // Calculate the absolute value of x
$answer[0] = $f_a;  // Correct answer for part a

// Part b: f(x) = 3^x + 1
$f_b = 3**$x_b + 1;  // Calculate 3^x + 1
$answer[1] = $f_b;  // Correct answer for part b

// Part c: f(k) = (k/2) + 1
$f_c_numerator = ($k_c + 2);  // Calculate the numerator of the fraction
$f_c_denominator = 2;  // Denominator remains 2
if ($f_c_numerator % $f_c_denominator == 0) {
    $answer[2] = $f_c_numerator / $f_c_denominator;  // Store the integer result
} else {
    $answer[2] = "$f_c_numerator/$f_c_denominator";  // Store the fraction as a string
}

// Part d: f(x) = sqrt(x - 5)
if ($x_d < 5) {
    $answer[3] = "DNE";  // Set the answer to undefined if the input is invalid
} else {
    $f_d = sqrt($x_d - 5);  // Calculate sqrt(x - 5)
    $answer[3] = $f_d;  // Correct answer for part d
}

// Show the correct answers for each part
$showanswer[0] = "$answer[0]";
$showanswer[1] = "$answer[1]";
$showanswer[2] = "$answer[2]";
$showanswer[3] = "$answer[3]";

// === QUESTION TEXT ===

<p>Determine the corresponding outputs for the given inputs of the following functions. If there is no solution, explain why not. Be careful: In some cases, there may be no solution or more than one possible solution.</p>

<p><b>a.</b> If <b>`x = $x_a`</b> and <b>`f(x) = |x|`</b>, what is <b>`f($x_a)`</b>? $answerbox[0]</p>
<p>&nbsp;</p> <!-- Add space between questions -->
<p><b>b.</b> If <b>`x = $x_b`</b> and <b>`f(x) = 3^x + 1`</b>, what is <b>`f($x_b)`</b>? $answerbox[1]</p>
<p>&nbsp;</p>
<p><b>c.</b> If <b>`k = $k_c`</b> and <b>`f(k) = (k/2) + 1`</b>, what is <b>`f($k_c)`</b>? $answerbox[2]</p>
<p><i>Note: Ensure your answer is a reduced fraction.</i></p>
<p>&nbsp;</p>
<p><b>d.</b> If <b>`x = $x_d`</b> and <b>`f(x) = sqrt(x - 5)`</b>, what is <b>`f($x_d)`</b>? $answerbox[3]</p>
<p><i>Note: If the answer is not possible, write "DNE"</i></p>
