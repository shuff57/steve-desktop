// === NAME - DESCRIPTION: Determining recursive and explicit equations for sequences (from graphs) (local for David Waung) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("choices","numfunc")
$variables = "t,n"
$questions[0] = array("Arithmetic","Geometric","Neither")

$init = "setBorder(1); initPicture(-1,1,-1,1);"
$rect = "fill=\"black\";rect([-1,-1],[1,1]);"
$graphic = showasciisvg($init.$rect)
$rect1 = "fill=\"black\";rect([-1,-1],[0,0]);"
$rect2 = "fill=\"black\";rect([0,0],[1,1]);"
$graphic2 = showasciisvg($init.$rect1.$rect2)
$rect3 = "fill=\"black\";rect([-1,-1],[-.5,-.5]);"
$rect4 = "fill=\"black\";rect([-.5,-.5],[0,0]);"
$rect5 = "fill=\"black\";rect([0,0],[.5,.5]);"
$rect6 = "fill=\"black\";rect([.5,.5],[1,1]);"
$graphic3 = showasciisvg($init.$rect3.$rect4.$rect5.$rect6)




$answerformat[1] = "equation"  
$answer[0] = 1
$answer[1] = makepretty("t(n) = 2^(n)")
$noshuffle = "all"

// === QUESTION TEXT ===
Determine the explicit equations for the <b>sequence</b>. If&nbsp;the sequence is not arithmetic or geometric,identify it as neither and write "DNE" for the equations. Use&nbsp;`n` and&nbsp;`t(n)` to write your sequences.</strong></p>
<p>$graphic&nbsp;$graphic2 $graphic3</p>
<p>(The number of black tiles above)</p>
<p>Arithmetic or Geometric or Neither?: $answerbox[0]</p>
<p>Explicit equation for the <b>sequence</b>: $answerbox[1]</p>
