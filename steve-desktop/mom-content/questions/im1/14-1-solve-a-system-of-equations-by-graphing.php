// === NAME - DESCRIPTION: 14.1 Solve a system of equations by graphing (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("draw","calcntuple")
$answerformat[0] = "twopoint,line"
$grid[0] = "-10,10,-10,10,,,600,600"

$mn,$md,$b = nonzerodiffrands(-9,9,3) where ($md>1 && gcd($mn,$md)==1) //y1 = mn/md x+b

$x1 = $md*nonzerorand(-4,4) where (abs($x1)<10 && abs($mn/$md*$x1+$b)<10)
$y1 = $mn/$md*$x1+$b

$exp1 = makepretty("y=$mn/$md x+$b")

$bb = rand(-9,9) where ($b!=$bb && $y1-$bb!=0)
$m = makereducedfraction($y1-$bb,$x1)
$exp2 = makepretty("y=$m x+$bb")

$answers[0] = array("$m x+$bb","$mn/$md x+$b")
$answer[1] = "($x1,$y1)"
$answerboxsize[1]=5
$snaptogrid = 1

// === QUESTION TEXT ===
Solve the system by graphing. Type your solution as an ordered pair into the box at the bottom.

`$exp1`

`$exp2`

$answerbox[0]

The solution of the system is $answerbox[1]
