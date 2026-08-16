// === NAME - DESCRIPTION: 9th Grade Concept #33 - Identify the graph of a sequence as linear, exponential, or neither - Arithmetic With Equation (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$a=rrand(2,12,2)
$b=rrand(4,8,2)
$a0 = $a-$b

$x = consecutive(0,8)
$xSpace=1
$y = calconarray($x,"$a+$b(x-1)")
$ymin = $a
$ymax = max($y)
$ySpace = $b/2

$anstypes = array("numfunc","choices","draw")

$answer[0] = "f(x) = $b x + $a0"
$answerformat[0] = "equation"
$variables[0]= "f,x"

$choices[1] = array("Linear","Exponential","Neither")
$displayformat[1] = "select"
$answer[1] = 0

$answerformat[2] = "twopoint, dot"
$answer[2] =  arraystodots(subarray($x,"0:5"),subarray($y,"0:5"))
$grid[2] = "-1,5.5,$ymin-2*$b,$y[5]+$b,$xSpace,4:2,400,500"
$snaptogrid[2] = "1"

// === QUESTION TEXT ===
<p>Consider the sequence `$y[1]`, `$y[2]`, `$y[3]`, `$y[4]`, `$y[5]`,...&nbsp;</p>
<p>Convert the sequence to a <b>function</b>.</p>
$answerbox[0]
<p>The graph of this sequence will be $answerbox[1].</p>
<p>Construct a plot of this sequence.</p>
<p>$answerbox[2]</p>
