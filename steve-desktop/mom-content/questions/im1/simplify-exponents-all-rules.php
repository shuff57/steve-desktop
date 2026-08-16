// === NAME - DESCRIPTION: Simplify exponents all rules (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("numfunc", "numfunc", "numfunc");  // Define answer types for each part

// Generate random values for the first part
$a = rand(3,9);
$b = rand(2,4);
$ab = $b * $a;
$n, $m = diffrands(1,5,2) where ($n + $m < 8);
$npm = $n + $m;

// Expression and answer for the first part
$answer[0] = "1/$b^$npm";

// Variables for times requirement
$requiretimes1 = "x,=0,-,=0";

// Generate random values for the second part
$a2, $b2, $c2, $d2 = diffrands(2,9,4) where ($b2 > $d2);

$ac2 = $a2 * $c2;
$bd2 = $b2 - $d2;

// Variables and answer for the second part
//$variables2 = randfrom("a,c,w,x,y,z");
$answer[1] = "$a2 x^ ($bd2)";

// Generate random values for the third part
$a3, $b3, $c3 = diffrands(2,5,3);

$ac3 = $a3^$c3;
$bc3 = $b3 * $c3;

// Variables and answer for the third part
$requiretimes3 = "^$b3,=0";
//$variables3 = randfrom("a,c,w,x,y,z");
$answer[2] = "$ac3 x^($bc3)";
$showanswer[2] = "`$ac3 x^($bc3)` or `$a3^$c3 x^$bc3`";

// === QUESTION TEXT ===
<p>Simplify the following expressions</p>
<p>a. ` (($a x)^$n($a x)^$m)/(($ab x)^$npm)` = $answerbox[0] </p>

<p>b. `($ac2 x^$b2)/($c2 x^$d2) = ` $answerbox[1] </p>

<p>c. `($a3 x^$b3)^$c3`= $answerbox[2] </p>
