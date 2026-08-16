// === NAME - DESCRIPTION: Arithmetic decreasing Sequence  Find explicit equation and terms (copy by Stephen Lang) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array ("choices","numfunc","number","number","number")
$x = array(1,2,3,4,5,6,7,8)
$variables = "t,n"

$a = rand (2,10)
$b = rand (2,9)


$y7 = $a
$y6 = $a+($b*$x[0])
$y5 = $a+($b*$x[1])
$y4 = $a+($b*$x[2])
$y3 = $a+($b*$x[3])
$y2 = $a+($b*$x[4])
$y1 = $a+($b*$x[5])
$y0 = $a+($b*$x[6])

$questions[0] = array("Arithmetic","Geometric")
$noshuffle[0]= "all"
$answer[0] = 0
$displayformat[0] = "select"

$answerformat[1]="equation"
$answer[1] = "t(n)= -$b n + $y1"
$answer[2] = $y1
$answer[3] = $y6
$answer[4] = $y7

$answerboxsize[0] = 5
$answerboxsize[1] = 15
$answerboxsize[2] = 6
$answerboxsize[3] = 6
$answerboxsize[4] = 6



$answeights=array(.2,.5,.1,.1,.1)

// === QUESTION TEXT ===
<p>Answer the following questions using the sequence:</p>
<p>$y2, $y3, $y4 ......</p>
<p>Is the sequence arithmetic or geometric? $answerbox[0]</p>
<p>Explicit Formula for the <b>sequence</b>: $answerbox[1]</p>
<p>t(0) = $answerbox[2]</p>
<p>t(5) = $answerbox[3]</p>
<p>t(6) = $answerbox[4]</p>
<p>&nbsp;</p>
<p>$pic</p>
<p>&nbsp;</p>
