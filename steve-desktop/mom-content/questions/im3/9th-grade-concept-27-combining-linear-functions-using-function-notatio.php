// === NAME - DESCRIPTION: 9th Grade Concept #27 - Combining Linear Functions using function notation With Multiplication (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("calculated","calculated","calculated")

$x=nonzerorand(-5,5)

$a = rrand(2,15,1)
$b = rrand(1,15,1) where ($x+$b != 0)

$am1 = $a-1
$ap1 = $a+1

$answer[0] = ($x)^2-$am1*$x+$b
$answer[1] = ($x)^2-$ap1*$x-$b
$answer[2] = (($x)^2-$a($x))*($x+$b)

$answerboxsize = 5
$hidepreview = true

// === QUESTION TEXT ===
Given two functions &nbsp; `color(red)(f(x)=x^2-$a x)` &nbsp; and &nbsp; `color(blue)(g(x)=x + $b)`,&nbsp; find the following.
<br /><br />
<strong>a)</strong> &nbsp; &nbsp; `color(red)(f)($x)+color(blue)(g)($x)=`$answerbox[0]
<br /><br />
<strong>b)</strong> &nbsp; &nbsp; `color(red)(f)($x)-color(blue)(g)($x)=`$answerbox[1]
<br /><br />
<strong>c)</strong> &nbsp; &nbsp; `color(blue)(g)($x)*color(red)(f)($x)=`$answerbox[2]
<br /><br />
</p>
