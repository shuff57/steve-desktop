// === NAME - DESCRIPTION: Solve system by graphing y=mx+b; drawing (local for James Sousa) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===
$anstypes=array("number","number")
$m1,$m2 = nonzerodiffrands(-2,2,2)
$x,$y = nonzerodiffrands(-4,4,2)

$b1 = $y-$m1*$x
$b2 = $y-$m2*$x

$expr1 = makexxpretty("$m1 x + $b1")
$expr2 = makexxpretty("$m2 x + $b2")

$answers[0] = "$m1*x+$b1"
$answers[1] = "$m2*x+$b2"
$answers[2] = "$x,$y"

$answerformat = "twopoint,line,dot"

$snaptogrid = .5

// === QUESTION TEXT ===
Solve the system of equations by graphing:

`{(y=, $expr1),(y=,$expr2):}`

First graph each line, then place a dot indicating the solution to the system.
