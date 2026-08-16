// === NAME - DESCRIPTION: Continue an arithmetic sequence. Write an equation. Create a plot. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$a=rrand(2,12,1)
$b=rrand(4,8,2)
$a0 = $a-$b

$x = consecutive(0,8)
$xSpace=1
$y = calconarray($x,"$a+$b(x-1)")
$ymin = $a
$ymax = max($y)
$ySpace = $b/2

$anstypes = array("number","number","number","numfunc","draw")

$answerboxsize[0] = 4
$answerboxsize[1] = 4
$answerboxsize[2] = 4
$answerboxsize[3] = 30
$answerformat[3] = "equation"
$variables[3] = "t,n"
$answerboxsize[4] = 10

$answer[0] = $y[6]
$answer[1] = $y[7]
$answer[2] = $y[8]
$answer[3] = "t(n)=$a0+$b*n"
//$answer[4] = "arithmetic"

$answerformat[4] = "twopoint, dot"
$answer[4] =  arraystodots(subarray($x,"0:5"),subarray($y,"0:5"))
$grid[4] = "-1,5.5,$ymin-2*$b,$y[5]+$b,$xSpace,4:2,400,400"
$snaptogrid[4] = ".5
"

// === QUESTION TEXT ===
<h3>Consider the sequence `$y[1]`, `$y[2]`, `$y[3]`, `$y[4]`, `$y[5]`,`...`</h3>
<h4>Find the next three terms:</h4>
$answerbox[0], $answerbox[1], $answerbox[2]

<h4>Write down an equation that models this <b>sequence</b>.</h4>
$answerbox[3]

Construct a plot of this sequence.
<p>$answerbox[4]</p>
