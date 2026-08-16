// === NAME - DESCRIPTION: Simple Rotation of polygon given a description (local for Steven Huff) ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===
$i = rand(2,3)
$l=$i+1
$letters = array("A","B","C","D","E")

$rotate = 90*nonzerorand(-3,3)

$arot = abs($rotate)

$direction = "clockwise" if $rotate<0
$direction = "anti-clockwise" if $rotate>0

for ($j=0..$i){
 $k = $j-1
 
 $x[$j],$y[$j] = nonzerorands(-5,5,2) where (abs($p+$x[$j])<5&&abs($q+$y[$j])<5&& ($j==0 || ($x[$k]!=$x[$j]&&$y[$k]!=$y[$j])))
 $object = "$letters[$j]($x[$j],$y[$j])" if $j==0
 $object = "$object, $letters[$j]($x[$j],$y[$j])" if $j!=0
 $image = "$letters[$j]'" if $j==0
 $image = "$image, $letters[$j]'" if $j!=0
 if ($rotate==-270||$rotate==90){
  $ximage = -$y[$j]
  $yimage = $x[$j]
 }elseif($rotate==-180||$rotate==180){
  $ximage = -$x[$j]
  $yimage = -$y[$j]
 }elseif($rotate==-90||$rotate==270){
  $ximage = $y[$j]
  $yimage = -$x[$j]
 }
 $answers[$j] = "$ximage,$yimage"
 $line[$j] = "($y[$k]-$y[$j])/($x[$k]-$x[$j])(x-$x[$j])+$y[$j],red,$x[$j],$x[$k]" if ($j!=0&&$x[$k]-$x[$j]!=0)
 $line[$j] = "[$x[$j],t],red,$y[$j],$y[$k]" if ($j!=0&&$x[$k]-$x[$j]==0)
 $coordinates[$j] = "{$y[$j]},red,{$x[$j]},{$x[$j]},closed"
}

$line[0] = "($y[$i]-$y[0])/($x[$i]-$x[0])(x-$x[$i])+$y[$i],red,$x[$i],$x[0]" if ($x[$i]-$x[0]!=0)
$line[0] = "[$x[0],t],red,$y[0],$y[$i]" if ($x[$i]-$x[0]==0)

$answers[$l]=$answers[0]

$ap = abs($p)
$aq = abs($q)

$lr = "right"
$lr = "left" if $p<0

$ud = "up"
$ud = "down" if $q<0

$bg = mergearrays($line,$coordinates)
$graph =showplot($bg)

$background = $bg

$background = addlabel($background,$x[0],$y[0],"A")
$answerformat = "polygon, dot"
$snaptogrid =1

// === QUESTION TEXT ===
<h3><span style="font-size: medium;">Consider the diagram below and plot `$image` , the image of `$object`&nbsp;&nbsp;if it is rotated&nbsp;`$arot^circ`&nbsp;$direction</span></h3>
<h3>&nbsp;</h3>
