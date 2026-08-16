// === NAME - DESCRIPTION: Rotations - ID AoR in 90 deg increments & CoR (origin) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("choices","calculated","calculated")

$type=randfrom("1,2,3") 
// 1 is 90ccw  2 is 90cw   3 is 180

$x1=rand(1,2)
$y1=rand(2,6)
$x2=rand(5,6)
$y2=rand(5,8)
$x3=rand(8,9)
$y3=rand(1,3)

$x=array($x1,$x2,$x3,$x1)
$y=array($y1,$y2,$y3,$y1)
$tri=connectthedots($x,$y,"blue")

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

$x4=-$x1 if $type==3
$x5=-$x2 if $type==3
$x6=-$x3 if $type==3

$y4=-$y1 if $type==3
$y5=-$y2 if $type==3
$y6=-$y3 if $type==3

$xx=array($x4,$x5,$x6,$x4)
$yy=array($y4,$y5,$y6,$y4)
$triimg=connectthedots($xx,$yy,"red")

$init = "setBorder(5); initPicture(-10,11,-10,11);"
$axes = "axes(1,1,1,1,1);"

$tri = "stroke=\"blue\";path([[$x1,$y1],[$x2,$y2],[$x3,$y3],[$x1,$y1]]);"
$triimg = "stroke=\"red\";path([[$x4,$y4],[$x5,$y5],[$x6,$y6],[$x4,$y4]]);"



$stuff=$init.$axes.$tri.$triimg
$g = showasciisvg($stuff,600,600)
$g=addlabel($g,$x1,$y1,"A","blue","left")
$g=addlabel($g,$x2,$y2,"B","blue","above")
$g=addlabel($g,$x3,$y3,"C","blue","right")
$g=addlabel($g,$x4,$y4,"A′","red","left")
$g=addlabel($g,$x5,$y5,"B′","red","left")
$g=addlabel($g,$x6,$y6,"C′","red","right")

$choices[0]=array("90 degrees counterclockwise","90 degrees clockwise","180 degrees")
$answer[0]=0 if $type==1
$answer[0]=1 if $type==2
$answer[0]=2 if $type==3
$displayformat[0]="select"
$noshuffle[0]="all"

$answer[1]=0
$answer[2]=0

$answerboxsize[1]=4
$answerboxsize[2]=4
$hidepreview[1]="true"
$hidepreview[2]="true"

//$answeights=".5,.25,.25"
$scoremethod="allornothing"

// === QUESTION TEXT ===
<p>Describe the rotation transformation shown below. <em>Be sure to give </em>both<em> the angle of rotation and the center of rotation.</em><br /><span style="color: #0000ff;">Pre-image = blue</span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span style="color: #ff0000;">Image=red</span>
<br /><br />
The pre-image is rotated $answerbox[0] around ( $answerbox[1] , $answerbox[2] )
<br /><br />
$g</p>
