// === NAME - DESCRIPTION: Solving a system of linear equations by graphing (local for Doli Bambhania) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$answerformat="twopoint,line,dot"

$anstypes = array("draw","ntuple")

// Existing variables
$x1, $y1 = rands(-4,4,2)

// Safe random assignment with all division checks
$a, $b, $c, $d = diffrands(-3,3,4) where ( $a != 0 && $b != 0 && $c != 0 && $d != 0
            && ($a*$x1 + $b*$y1) == $b * intval(($a*$x1 + $b*$y1)/$b)
            && ($c*$x1 + $d*$y1) == $d * intval(($c*$x1 + $d*$y1)/$d)
            && abs(($a*$x1 + $b*$y1)/$b) <= 5
            && abs(($c*$x1 + $d*$y1)/$d) <= 5 
            && $c*$b != $d*$a)

$e = $a*$x1 + $b*$y1
$f = $c*$x1 + $d*$y1

$eq1 = makexxpretty("$a x + $b y = $e")
$eq2 = makexxpretty("$c x + $d y = $f")

//$answers[0] = ("-($a/$b)x+($e/$b)")
$answers[0] = array("-($a/$b)x+($e/$b)","-($c/$d)x+($f/$d)","$x1,$y1")
$answer[1] = "($x1,$y1)"
$snaptogrid = 1

// === QUESTION TEXT ===
Graph the following system of equations and find the solution. Graph each line and plot the solution as a piont on the graph. Enter the solution in the answer box as an ordered pair.

$eq1

$eq2

$answerbox[0]

Solution = $answerbox[1]
