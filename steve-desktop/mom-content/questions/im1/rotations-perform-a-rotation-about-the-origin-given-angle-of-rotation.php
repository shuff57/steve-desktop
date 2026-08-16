// === NAME - DESCRIPTION: Rotations - perform a rotation about the origin given angle of rotation in 90 deg increments (v2) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===
$type=randfrom("1,2,3,4,5,5") 
// 1 is 90ccw  2 is 270ccw  3 is 90cw   4 is 270cw   5 is 180

$x1=rand(1,2)
$y1=rand(2,5)
$x2=$x1
$y2=rand(6,9)
$x3=rand(8,9)
$y3=rand(1,3)

$x=array($x1,$x2,$x3,$x1)
$y=array($y1,$y2,$y3,$y1)
$tri=connectthedots($x, $y, "blue")

$x4=-$y1 if $type==1
$x5=-$y2 if $type==1
$x6=-$y3 if $type==1

$y4=$x1 if $type==1
$y5=$x2 if $type==1
$y6=$x3 if $type==1

$x4=$y1 if $type==2
$x5=$y2 if $type==2
$x6=$y3 if $type==2

$y4=-$x1 if $type==2
$y5=-$x2 if $type==2
$y6=-$x3 if $type==2

$x4=$y1 if $type==3
$x5=$y2 if $type==3
$x6=$y3 if $type==3

$y4=-$x1 if $type==3
$y5=-$x2 if $type==3
$y6=-$x3 if $type==3

$x4=-$y1 if $type==4
$x5=-$y2 if $type==4
$x6=-$y3 if $type==4

$y4=$x1 if $type==4
$y5=$x2 if $type==4
$y6=$x3 if $type==4

$x4=-$x1 if $type==5
$x5=-$x2 if $type==5
$x6=-$x3 if $type==5

$y4=-$y1 if $type==5
$y5=-$y2 if $type==5
$y6=-$y3 if $type==5

$grid="-20,20,-20,20,1,1,600,600"
$background=$tri
$answerformat="polygon,dot"
$snaptogrid=1

$exp=makepretty("x=$lor") if $type==1
$exp=makepretty("y=$lor") if $type==2

$ans=array("$x4,$y4","$x5,$y5","$x6,$y6","$x4,$y4")

$dir="counterclockwise" if $type==1
$dir="counterclockwise" if $type==2
$dir="clockwise" if $type==3
$dir="clockwise" if $type==4
$dir=" " if $type==5

$ang="90" if $type==1
$ang="270" if $type==2
$ang="90" if $type==3
$ang="270" if $type==4
$ang="180" if $type==5

$answers=$ans
$abstolerance =1

// === QUESTION TEXT ===
<p><span style="text-decoration: underline;">Perform a Rotation</span>
<br /><br />
Rotate the figure&nbsp;`$ang^@`&nbsp; $dir&nbsp;about the origin&nbsp;`(0,0)`&nbsp;.
<br /><br />
$answerbox</p>
