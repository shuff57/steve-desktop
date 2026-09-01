// === NAME - DESCRIPTION: Determine the transformation (rotation) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices");

$questions[0] = array("rotation", "reflection", "translation", "none");
$answer[0] = 0;  // Correct answer is "reflection"

$r1,$r2,$r3,$r4 = rands(1,3,4)
$x1 = rand(1,3)
$x2 = $x1+$r1
$y1 = rand(1,3)
$y2 = $y1+$r2
$commands = "initPicture(-7,7,-7,7);"
$commands .= "axes(1,1,1,1,1);"
$commands .= "fill = 'red';"
$commands .= "path([[$x1,$y1],[$x2,$y1],[$x2,$y2],[$x1,$y1]]);"
//$commands .= "text([$x1,$y1],'A',left);"
//$commands .= "text([$x2,$y1],'B',right);"
//$commands .= "text([$x2,$y2],'C',above);"
$x = rand(1,4)
$commands .= "fill = 'blue';"
$commands .= "path([[-$y1+$x,$x1-$x],[-$y1+$x,$x2-$x],[-$y2+$x,$x2-$x],[-$y1+$x,$x1-$x]]);"

//$answer = "($x,0)"
$answerboxsize = 5


$pic = showasciisvg($commands,400,400)

// === QUESTION TEXT ===

<table style="border-collapse: collapse; width: 100%; height: 23px;" border="1">
  <tbody>
    <tr style="height: 23px;">
      <td style="width: 100%; height: 23px;">
        <p>Below is a triangle and its transformation. What type of transformation has occurred?</p>
        <p>$pic</p>
      </td>
    </tr>
  </tbody>
</table>
<p>Select the correct transformation:<br />$answerbox[0]</p>
