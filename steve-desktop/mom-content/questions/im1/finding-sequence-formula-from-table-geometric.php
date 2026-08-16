// === NAME - DESCRIPTION: Finding sequence formula from table (geometric) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("numfunc","draw")
$variables = "f,x,a"

$b = rand(2,3)
$a = nonzerorand(-3,3)
$x = diffrands(1,4,4)
$x = sortarray($x) if ($x!=0)

for ($i=0..3) {
  $y[$i] = $a*($b)^$i
}


$answerboxsize[0]=10
$answerformat[0] = "equation"
$answerboxsize[1]=10
$answerformat[1] = "equation"
$y1 = $y[0]-3
$y2 = $y[3]+3
$y2 = $y[0]+3 if ($a<0)
$y1 = $y[3]-3 if ($a<0)
$table = showarrays("`x`",$x,"`f(x)`",$y)

$start_x = 0
$start_y = $a*$b^-1

$answer[0] = makepretty("f(x) = $start_y*$b^(x)")
$answer[1] = array("$start_x,$start_y","$x[0],$y[0]","$x[1],$y[1]","$x[2],$y[2]","$x[3],$y[3]")

if ($y[3] > 50){
  $y1 = $y[0]-3
	$y2 = $y[2]+3
  $y2 = $y[0]+3 if ($a<0)
	$y1 = $y[2]-3 if ($a<0)
  $answer[1] = array("$start_x,$start_y","$x[0],$y[0]","$x[1],$y[1]","$x[2],$y[2]") 
}
if ($y[3] < -50){
  $y1 = $y[0]-3
	$y2 = $y[2]+3
  $y2 = $y[0]+3 if ($a<0)
	$y1 = $y[2]-3 if ($a<0)
  $answer[1] = array("$start_x,$start_y","$x[0],$y[0]","$x[1],$y[1]","$x[2],$y[2]") 
}

$grid = "-.5,5,$y1,$y2,1,1,800,700"

$answerformat[1] = "twopoint, exp, dot"

$snaptogrid = 1
$snaptogrid = .5 if($b%2 == 0)
$snaptogrid = 1/3 if($b%3 == 0)

// === QUESTION TEXT ===
With the information given write the explicit function for the table. 
<p>$table</p>
<p>Explicit <b>Function</b>: $answerbox[0]</p>
Graph the <b>sequence</b>.
<p>$answerbox[1]</p>
