// === NAME - DESCRIPTION: 9th Grade Concept #33 - Identify the graph of a sequence as linear, exponential, or neither - Arithmetic With No Equation (local for Steven Huff) ===
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

$anstypes = array("choices","draw")


$choices[0] = array("Linear","Exponential","Neither")
$displayformat[0] = "select"
$answer[0] = 0

$answerformat[1] = "twopoint,dot"
$answer[1] =  arraystodots(subarray($x,"0:5"),subarray($y,"0:5"))
$grid[1] = "-1,5.5,$ymin-2*$b,$y[5]+$b,$xSpace,4:2,400,500"
$snaptogrid[1] = "1"

// === QUESTION TEXT ===
<p>Identify the graph of a sequence as linear, exponential, or neither
<br /><br />
</p>
<p>Consider the sequence `$y[1]`, `$y[2]`, `$y[3]`, `$y[4]`, `$y[5]`,...&nbsp;</p>
<p style="padding-left: 40px;">
<p>The graph of this sequence will be $answerbox[0].</p>
<p>Construct a plot of this sequence.</p>
<p>$answerbox[1]</p>
