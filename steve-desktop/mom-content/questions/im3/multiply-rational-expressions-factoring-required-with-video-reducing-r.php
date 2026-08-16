// === NAME - DESCRIPTION: Multiply Rational Expressions, Factoring Required (with video). Reducing required. Require variables occur =1 and coefficients >0 (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("numfunc")
$variables = "x"

//multiply

$a,$b,$c,$d,$e = nonzerodiffrands(-5,5,5)

$apb = $a+$b
$ab = $a*$b
$apc = $a+$c
$ac = $a*$c
$dc = $d*$c
$bpe = $b+$e
$be = $b*$e

$n1 = polymakepretty("x^2+$apb x+$ab")
$n2 = polymakepretty("$d x+$dc")
$d1 = polymakepretty("x^2+$apc x+$ac")
$d2 = polymakepretty("x^2+$bpe x+$be")

$exp0 = "($n1)/($d1)*($n2)/($d2)"
$answer[0] = makepretty("($d)/(x+$e)")

$requiretimes[0]="x,=1,$d,>0,$e,>0"
//divide

$a,$b,$c,$d,$e = nonzerodiffrands(-5,5,5)

$apb = $a+$b
$ab = $a*$b
$apc = $a+$c
$ac = $a*$c
$dc = $d*$c
$bpe = $b+$e
$be = $b*$e

$n2 = polymakepretty("x^2+$apb x+$ab")
$n1 = polymakepretty("$d x+$dc")
$d2 = polymakepretty("x^2+$apc x+$ac")
$d1 = polymakepretty("x^2+$bpe x+$be")

$exp1 = "($n1)/($d1)-:($d2)/($n2)"
//$answer[1] = makepretty("($d)/(x+$e)")

//$requiretimes[1]="x,=1,$d,>0,$e,>0"

// === QUESTION TEXT ===
Simplify Each. Make sure to reduce the fractions

`$exp0=`$answerbox[0]
