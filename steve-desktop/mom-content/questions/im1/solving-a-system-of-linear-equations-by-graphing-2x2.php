// === NAME - DESCRIPTION: Solving a System of Linear Equations by Graphing (2x2) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$answerformat="twopoint,line,dot"

$anstypes = array("draw","ntuple")

$x1, $y1 = rands(-4,4,2)

$a, $b, $c, $d = nonzerodiffrands(-3,3,4) where (($a*$x1 + $b*$y1)%$b == 0 && ($c*$x1 + $d*$y1)%$d == 0 && ($a*$x1 + $b*$y1)/$b <= 5 && ($c*$x1 + $d*$y1)/$d <= 5 && $b != 0 && $d !=0 && $c/$a != $d/$b)

$e = $a*$x1 + $b*$y1

$f = $c*$x1 + $d*$y1

$eq1 = makexxprettydisp("$a x + $b y = $e")

$eq2 = makexxprettydisp("$c x + $d y = $f")

//$answers[0] = ("-($a/$b)x+($e/$b)")

$answers[0] = array("-($a/$b)x+($e/$b)","-($c/$d)x+($f/$d)","$x1,$y1")

$answerboxsize[1] = 10
$answer[1] = "($x1,$y1)"
$snaptogrid = 1

// === QUESTION TEXT ===
Given the system of linear equations, determine the solution to the system by graphing.

$eq1

$eq2

<b>(a)</b> Graph the system of linear equations on the given grid. Then plot the solution.

$answerbox[0]

<b>(b)</b> What is the solution to the system? <i>Be sure to enter your answer as an ordered-pair, i.e., as `(x,y)`.</i>

The solution to the system is $answerbox[1].
