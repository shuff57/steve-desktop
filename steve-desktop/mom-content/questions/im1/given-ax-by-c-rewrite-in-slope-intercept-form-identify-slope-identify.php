// === NAME - DESCRIPTION: Given `ax + by = c`, rewrite in slope intercept form. Identify slope. Identify `y`-intercept. Graph the line.  (Slope rational, `y`-intercept integer) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "numfunc,calculated,calculated,calculated,draw"
$variables = "x,y,f"

$scoremethod = "singlescore"



// ax + by = c

$b= nonzerorand(-9,9) where (abs($a) != 1 && abs($b) != 1 )

$a = nonzerorand(-9,9) where (abs($a) != abs($b) && $a%$b != 0  && abs($a) != 1)

$c = $b*nonzerorand(-15,15) where (abs($c/$b) < 7 && abs($c) != abs($a) && abs($c) != abs($b))

$eq = makexxpretty("$a x + $b y = $c")


$m = makereducedfraction($a,$b)
$d = makereducedfraction($c,$b) // y-int
$k = makereducedfraction($c,$a) // x-int

$eqplot = "-$m*x+$d"

$answerformat[0] = "equation"
$answer[0] = makexxpretty("f(x) = - $m x + $d ")
$requireitems[0] = "f(x),=1"
$answerboxsize[0] = 8

$answerboxsize[1] = 3
$answer[1] = makexxpretty("-$m")

$answerboxsize[2] = 3
$answer[2] = makexxpretty("$d")

$answer[3] = makexxpretty("$k")
$answerboxsize[3] = 3

$grid[4] = "-10,10,-10,10,1,1,350,350"
$snaptogrid[4] = .5
$answerformat[4] = "twopoint,line"
$answer[4] = "$eqplot"

// === QUESTION TEXT ===
<p>Rewrite the given <em>standard</em> form linear equation using function notation. Identify the slope, `y`-intercept, and `x`-intercept. Sketch the graph of the linear equation.
<br /><br />
`$eq`
<br /><br />
Writing the equation in function notation `(f(x))`, the equivalent function notation form of the equation is:
<br /><br />
$answerbox[0]
<br /><br />
The slope of the line is
<br /><br />
`m=`$answerbox[1]
<br /><br />
The `y`-intercept of the line is
<br /><br />
`y=`$answerbox[2]
<br /><br />
The `x`-intercept of the line is
<br /><br />
`x=`$answerbox[3]</p>
<p>Sketch the graph of the linear equation.
<br /><br />
$answerbox[4]</p>
