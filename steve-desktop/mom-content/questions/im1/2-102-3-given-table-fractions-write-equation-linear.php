// === NAME - DESCRIPTION: 2-102.3 Given table (fractions), write equation (linear)  ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc");  // Equation part

// Define variables for the table values
$a = rand(3, 10);  // Initial value for the function
$d = array(2, 4, 5);  // Possible slope or growth rates
$r = rand(0, 2);
$m1 = $d[$r];  // Select the slope or growth rate

// Calculate x-values with random offsets (within ±2 units)
$x1 = -3 + rand(-2, 2);
$x2 = 0 + rand(-2, 2);
$x3 = 3 + rand(-2, 2);
$x4 = 6 + rand(-2, 2);
$x5 = 9 + rand(-2, 2);

// Manually calculate the numerators and denominators for y-values
$c1_num = $m1 * $x1 + 3 * $a;  // Numerator for c1
$c1_den = 3;

$c2_num = $m1 * $x2 + 3 * $a;  // Numerator for c2
$c2_den = 3;

$c3_num = $m1 * $x3 + 3 * $a;  // Numerator for c3
$c3_den = 3;

$c4_num = $m1 * $x4 + 3 * $a;  // Numerator for c4
$c4_den = 3;

$c5_num = $m1 * $x5 + 3 * $a;  // Numerator for c5
$c5_den = 3;

// Now manually reduce each fraction if possible

// For c1
if ($c1_num % $c1_den == 0) {
    $c1 = "`" . ($c1_num / $c1_den) . "`";  // If divisible, it's a whole number
} else {
    $c1 = "`$c1_num/$c1_den`";  // Otherwise, leave it as a fraction
}

// For c2
if ($c2_num % $c2_den == 0) {
    $c2 = "`" . ($c2_num / $c2_den) . "`";  // If divisible, it's a whole number
} else {
    $c2 = "`$c2_num/$c2_den`";  // Otherwise, leave it as a fraction
}

// For c3
if ($c3_num % $c3_den == 0) {
    $c3 = "`" . ($c3_num / $c3_den) . "`";  // If divisible, it's a whole number
} else {
    $c3 = "`$c3_num/$c3_den`";  // Otherwise, leave it as a fraction
}

// For c4
if ($c4_num % $c4_den == 0) {
    $c4 = "`" . ($c4_num / $c4_den) . "`";  // If divisible, it's a whole number
} else {
    $c4 = "`$c4_num/$c4_den`";  // Otherwise, leave it as a fraction
}

// For c5
if ($c5_num % $c5_den == 0) {
    $c5 = "`" . ($c5_num / $c5_den) . "`";  // If divisible, it's a whole number
} else {
    $c5 = "`$c5_num/$c5_den`";  // Otherwise, leave it as a fraction
}

// Define the correct equation (assumed to be linear for this example)
$m = "$m1 / 3";  // Slope calculation (linear)
$answer[0] = "$m * x + $a";  // Equation for y in terms of x

// Set the size of the answer box for the equation
$answerboxsize[0] = 15;  // Provide enough space for students to input the equation

// === QUESTION TEXT ===

<p>Write the equation for the table.</p>

<table class='stats' style='border-spacing: 10px 10px;'>  <!-- Add spacing between table cells -->
  <tbody>
    <tr> <td style='padding: 10px;'> `x` </td> <td style='padding: 10px;'> `y` </td></tr>
    <tr> <td style='padding: 10px;'> `$x1` </td> <td style='padding: 10px;'> $c1 </td></tr>
    <tr> <td style='padding: 10px;'> `$x2` </td> <td style='padding: 10px;'> $c2 </td></tr>
    <tr> <td style='padding: 10px;'> `$x3` </td> <td style='padding: 10px;'> $c3 </td></tr>
    <tr> <td style='padding: 10px;'> `$x4` </td> <td style='padding: 10px;'> $c4 </td></tr>
    <tr> <td style='padding: 10px;'> `$x5` </td> <td style='padding: 10px;'> $c5 </td></tr>
  </tbody>
</table>

<p>Equation of the line: `y =`$answerbox[0]</p>
