// === NAME - DESCRIPTION: Find the missing value in the table (linear...calculate slope and then use slope to identify missing value) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("calculated","calculated")
$a = nonzerorand(-6,8)
$c = rand(1,2)
$m = $a/$c
$b = nonzerorand(-8,10)
$x = $variable
$x = array("1", "3", "6", "8", "11")
$y = calconarray($x,"$m*x+$b")


$tbl1 = horizshowarrays("x",$x,"y",$y)
$answerformat[0]="fracordec"

$answer[0] = $m
$answer[1]=$m*12+$b

// === QUESTION TEXT ===

Determine the slope of the linear relation given in the table: $tbl1

slope = $answerbox[0]

Determine the `y` when `x = 12`.
<p>`y =` $answerbox[1]</p>
