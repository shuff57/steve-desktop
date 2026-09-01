// === NAME - DESCRIPTION: 1/(ax^-n) and 1/ax^-n ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes=array("numfunc", "numfunc") // Two numeric answer boxes for the two expressions

// Expression 1 (no parentheses)
$var = "x";
$a = rand(2, 12);
$n = rand(-15, -2);
$N = -1 * $n;  // Convert to positive exponent

// Generate the first expression and answer
$expr = "1/($a $var^$n)";
$answer[0] = "$var^$N / $a";  // Main answer for the first expression (numerically valid)

$requiretimes = "/,=1,^,=1";  // Ensure correct formatting for expression 1

// Expression 2 (with parentheses)

  $e =  rand(2,5)
  $b1 = rand(2,9)
  $b2 = rand(2,9)

  $a1 = $b1*$e
  $a2 = $b2*$e

  $expr1 = "1/(x^($b1) y^($b2))^(-$e)"
  $variables = "x,y"
  $answer[1] =  "(x^$a1 y^$a2)"
  $requiretimes = "-,=0"

  // format
  //$answerboxsize = 30
  $hidetips = true

// === QUESTION TEXT ===

<p>Simplify the following expression:</p>

<p>a.` $expr =` $answerbox[0] </p>

<p>b.` $expr1 =` $answerbox[1] </p>
