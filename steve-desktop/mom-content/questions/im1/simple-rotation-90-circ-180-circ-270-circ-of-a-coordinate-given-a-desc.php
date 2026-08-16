// === NAME - DESCRIPTION: Simple rotation `(90^circ,180^circ,270^circ)` of a coordinate given a description (local for Steven Huff) ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===
$x,$y = nonzerorands(-5,5,2)

$rotate = 90*nonzerorand(-3,3)

$arot = abs($rotate)

$direction = "clockwise" if $rotate>0
$direction = "anti-clockwise" if $rotate<0

if ($rotate==-270||$rotate==90){
 $ximage = $y
 $yimage = -$x
}elseif($rotate==-180||$rotate==180){
 $ximage = -$x
 $yimage = -$y
}elseif($rotate==-90||$rotate==270){
 $ximage = -$y
 $yimage = $x
}

$background = "$y,red,$x,$x,closed"
$answerformat = "dot"
$snaptogrid =1

$answers = "$ximage,$yimage"

// === QUESTION TEXT ===
<h3>The coordinate below represents the point `P($x, $y)`</h3>
<h3>Plot `P'`, the image of `P` if it is rotated `$arot^circ` $direction</h3>
<p>&nbsp;</p>
