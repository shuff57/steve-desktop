// === NAME - DESCRIPTION: Finding sequence formula from table (arithmetic) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("numfunc","draw")
$variables = "f,x,a"

$m,$b = nonzerodiffrands(-15,15,2)

for ($i=0..3) {
  $x[$i] = $i+1
  $y[$i] = $m*($i+1)+$b
}


$answerboxsize[0]=10
$answerformat[0] = "equation"
$answerboxsize[1]=10
$answerformat[1] = "equation"
$y1 = $b-1
$y2 = $y[3]+1
$y2 = $b+1 if ($m<0)
$y1 = $y[3]-1 if ($m<0)
$table = showarrays("`x`",$x,"`f(x)`",$y)

$start_x = 0
$start_y = $b

$answer[0] = makepretty("f(x) = $m x + $b")
$answer[1] = array("$start_x, $start_y","$x[0],$y[0]","$x[1],$y[1]","$x[2],$y[2]","$x[3],$y[3]")
$grid = "-.5,5,$y1,$y2,1,1,800,700"
$answerformat[1] = "twopoint, dot, line"
$snaptogrid = 1

// === QUESTION TEXT ===
With the information given write the explicit function.
<p>$table</p>
<p>Explicit <b>Function</b>: $answerbox[0]</p>
Graph the <b>sequence</b>.
<p>$answerbox[1]</p>
