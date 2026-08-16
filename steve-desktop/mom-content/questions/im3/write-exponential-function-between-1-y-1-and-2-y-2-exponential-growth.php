// === NAME - DESCRIPTION: Write exponential function between `(1,y_1)` and `(2,y_2)`. Exponential growth. (local for Steven Huff) hardcoded for test ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
// Hardcoded values based on rrand(0.5, 4, 0.5) and rrand(1.5, 3.5, 0.5)
$a = 3;
$b = 4;

// Calculation logic for y-coordinates preserved
$y1 = $a*$b;
$y2 = $a*$b^2;

$answer = "$a $b^x";
$showanswer = "`$a*($b^x)`";
$variables = "x";

// === QUESTION TEXT ===
Write an exponential function `f(x)=ab^x` that passes through the points `(1,$y1)` and `(2,$y2)`.

`f(x)=` $answerbox
