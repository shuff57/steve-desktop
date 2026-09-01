// === NAME - DESCRIPTION: Simpify (a)^-n ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc");  // Two answer boxes, each expecting a number

// First expression generation
$a1 = nonzerorand(-6, 6);  // Allow for negative values for the base
$n1 = rand(-3, -1);
$N1 = -1 * $n1;  // Convert the exponent to positive for denominator calculation
$A1 = $a1^ $N1;  // Calculate the base raised to the positive exponent

$expr1 = makepretty("$a1 ^ $n1");  // Display the first expression

// Check special cases for the first expression (1/1 or -1/1)
if ($a1 <= -1 && $n1%2 == 0) {
    // If the base is negative, ensure the result is negative
    $answer[0] = makepretty("-1/$A1");
} else if ($A1 == 1 || $A1 == -1) {
    $answer[0] = $A1;  // Set the answer as 1 or -1 if needed
} else {
    $answer[0] = makepretty("1/$A1");  // Otherwise, give the answer as 1/$A1
}

// Second expression generation
$a2 = nonzerorand(-6, 6);  // Generate a non-zero random number
$n2 = rand(-3, -1);
$N2 = -1 * $n2;  // Convert the exponent to positive for denominator calculation
$A2 = $a2^$N2;  // Calculate the base raised to the positive exponent

$expr2 = makepretty("($a2) ^ $n2");  // Display the second expression

// Check special cases for the second expression (1/1 or -1/1)
if ($A2 == 1 || $A2 == -1) {
    $answer[1] = $A2;  // Set the answer as 1 or -1 if needed
} else {
    $answer[1] = makepretty("1/$A2");  // Otherwise, give the answer as 1/$A2
}

// Additional formatting and settings
$requiretimes = "^,=0,=1";  // Ensure correct formatting
$hidetips = true;  // Hide tips

// === QUESTION TEXT ===

<p>Simplify the following expressions:</p>

<p>a. ` $expr1 ` =  $answerbox[0] </p>

<p>b. ` $expr2 ` =  $answerbox[1] </p>
