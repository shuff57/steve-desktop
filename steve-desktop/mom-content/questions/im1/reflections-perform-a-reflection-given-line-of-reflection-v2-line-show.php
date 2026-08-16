// === NAME - DESCRIPTION: Reflections - perform a reflection given line of reflection (v2) line shown (local for Steven Huff) ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===
$type=rand(1,2)
$lor=rand(-2,4)

$x1=rand(1,2)
$y1=rand(2,5)
$x2=$x1
$y2=rand(6,9)
$x3=rand(8,9)
$y3=$y1

$x=array($x1,$x2,$x3,$x1)
$y=array($y1,$y2,$y3,$y1)
$tri=connectthedots($x,$y,"blue")

$x4=-$x1+2*$lor if ($type==1)
$x5=-$x2+2*$lor if ($type==1)
$x6=-$x3+2*$lor if ($type==1)

$y4=$y1 if ($type==1)
$y5=$y2 if ($type==1)
$y6=$y3 if ($type==1)

$x4=$x1 if ($type==2)
$x5=$x2 if ($type==2)
$x6=$x3 if ($type==2)

$y4=-$y1+2*$lor if ($type==2)
$y5=-$y2+2*$lor if ($type==2)
$y6=-$y3+2*$lor if ($type==2)

$grid="-20,20,-20,20,1,1,600,600"
$background=$tri
$background[]="x= $lor,red,,,,,,dash" if ($type==1)
$background[]="$lor,red,,,,,,dash" if ($type==2)

$answerformat="polygon, dot"
$snaptogrid=1

$exp=makepretty("x=$lor") if ($type==1)
$exp=makepretty("y=$lor") if ($type==2)

$ans=array("$x4,$y4","$x5,$y5","$x6,$y6","$x4,$y4")

$answers=$ans
$abstolerance =1

// === QUESTION TEXT ===
<p><span style="text-decoration: underline;">Perform a Reflection</span>
<br /><br />
Reflect the figure below over the line&nbsp;`$exp`.
<br /><br />
$answerbox</p>
