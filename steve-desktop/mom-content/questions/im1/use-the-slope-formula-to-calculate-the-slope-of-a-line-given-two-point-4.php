// === NAME - DESCRIPTION: Use the slope formula to calculate the slope of a line given two points. Slope is 0 or undefined. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("calculated","calculated")

$x,$y,$x1,$y1,$x2,$y2 = nonzerodiffrands(-5,10,6)

$type = rand(0,1)

if($type==0) {
$answer[0] = "0"
$answer[1] = "DNE"
$pt1 = "`($x1,$y)` and `($x2,$y)`"
$pt2 = "`($x,$y1)` and `($x,$y2)`"
}
if($type==1) {
$answer[1] = "0"
$answer[0] = "DNE"
$pt2 = "`($x1,$y)` and `($x2,$y)`"
$pt1 = "`($x,$y1)` and `($x,$y2)`"
}
$answerboxsize = 3

$hints[0] = "If the slope is undefined, enter DNE for does not exist."

// === QUESTION TEXT ===
If the slope is undefined enter `DNE`

The slope between $pt1 is $answerbox[0]

The slope between $pt2 is $answerbox[1]

$hintloc
