// === NAME - DESCRIPTION: Use the slope formula to calculate the slope of a line given two points.  All coordinates are positive. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: calculated ===

// === COMMON CONTROL ===
$x1,$x2,$y1,$y2 = diffrands(1,10,4)

$answer = ($y2 - $y1)/($x2 - $x1)

$showanswer = dispreducedfraction($y2-$y1,$x2-$x1)

$answerboxsize = 5

#$hints[0] = "Use the slope formula `m=(y_2-y_1)/(x_2-x_1)` and simplify your answer."

// === QUESTION TEXT ===
Find the slope of the line between the points `($x1, $y1)` and `($x2, $y2)`.
<div style="margin-left:2em;margin-top:1.2em;">`m =` $answerbox</div>$hintloc
