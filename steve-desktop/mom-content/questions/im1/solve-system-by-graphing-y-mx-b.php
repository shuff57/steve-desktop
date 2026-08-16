// === NAME - DESCRIPTION: Solve system by graphing y=mx+b (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("draw","ntuple")
{$m1 = randfrom("2,-2")
$m2 = randfrom(".5,-.5")
$x,$y = nonzerodiffrands(-4,4,2)} where (abs($y-$m1*$x)<5 && abs($y-$m2*$x)<6 && $y-$m2*$x==floor($y-$m2*$x) && $y-$m1*$x!=0 && $y-$m2*$x!=0)
$b1 = $y-$m1*$x
$b2 = $y-$m2*$x

$m2f = "1/2"
$m2f = "-1/2" if ($m2==-.5)
$expr1 = polymakepretty("$m1 x + $b1")
$expr2 = polymakepretty("$m2f x + $b2")

$answerformat[0] = "twopoint,line"
$grid[0] = "-6,6,-6,6,1,1,250,250"
$answers[0] = array("$m1*x+$b1","$m2*x+$b2")
$snaptogrid[0] = 1

$answerboxsize[1] = 5
$displayformat[1] = "point"
$answer[1]="($x,$y)"

// === QUESTION TEXT ===
Solve the system of equations by graphing:

`{(y=,$expr1),(y=,$expr2):}`

$answerbox[0]

/////////

Solution = $answerbox[1]
