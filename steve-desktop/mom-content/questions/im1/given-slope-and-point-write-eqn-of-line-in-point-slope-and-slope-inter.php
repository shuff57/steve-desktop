// === NAME - DESCRIPTION: Given slope and point, write eqn of line in point-slope and slope-intercept forms. Accepts either of the point-slope forms (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("numfunc","numfunc")
$m = nonzerorand(-10,10) where (abs($m)!=1)
$x1,$y1= nonzerodiffrands(-10,10,2) where ($m*$x1!=$y1)

$ax1 = abs($x1)
$ay1 = abs($y1)
$answer[0] = "y-$y1=$m(x-$x1) or y=$m(x-$x1)+$y1"
$answerformat[0] = "equation"
$requiretimes[0] = "(,=1,),=1,#,=3"
$variables[0] = "x,y"
$answerboxsize[0] = 10

$b = -$m*$x1+$y1
$answer[1] = "y=$m x+$b"
$answerformat[1] = "equation"
$variables[1] = "x,y"
$requiretimes[1] = "#,=2,(,=0,y=,>0,+y,=0,-y,=0"
$answerboxsize[1] = 10

// === QUESTION TEXT ===
Find the equation of the line with slope = $m and passing through ($x1,$y1). Write the equation in point-slope form AND slope-intercept forms. Include the full equation in your answers.

point-slope form: $answerbox[0]

slope-intercept form: $answerbox[1]
