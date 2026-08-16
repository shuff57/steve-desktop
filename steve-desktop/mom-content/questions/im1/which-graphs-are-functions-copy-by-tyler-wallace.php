// === NAME - DESCRIPTION: Which graphs are functions? (copy by Tyler Wallace) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multans ===

// === COMMON CONTROL ===
$a,$b = nonzerorands(-2,2,2)

$p1 = showplot("$a*x+$b,blue")
$p2 = showplot("$a,blue")
$p3 = showplot("[$a*cos(t)+$b,$b*sin(t)+$a],blue")
$p4 = showplot("$a*sin(x),blue")
$p5 = showplot("x^3 - 4x + $a,blue")
$p6 = showplot("[t^3-3t+$b,t],blue")

$questions = array($p1,$p2,$p3,$p4,$p5,$p6)
$answers = "0,1,3,4"
$scoremethod = "answers"

$displayformat = "2column"

// === QUESTION TEXT ===
Select all of the following graphs which represent a function.
