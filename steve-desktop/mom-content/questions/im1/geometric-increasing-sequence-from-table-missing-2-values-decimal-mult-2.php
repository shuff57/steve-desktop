// === NAME - DESCRIPTION: Geometric Increasing Sequence from  table missing 2 values. decimal multiplier (copy by Steve Lang) (copy by Steve Lang) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array ("number","number","numfunc")
$x = array(1,2,3,4,5)
$variables="n" 

$a = randfrom("12,18,24,36")
$b = 1.5
$c = $a/1.5
$y1 = $a
$y2 = $a($b^$x[0])
$y3 = $a($b^$x[1])
$y4 = $a($b^$x[2])
$y5 = $a($b^$x[3])


$answer[0] = $y4
$answer[1] = $y5

$answer[2] = "$c($b)^n"


$answerboxsize[0] = 4
$answerboxsize[1] = 4
$answerboxsize[2] = 8

// === QUESTION TEXT ===
<p>Complete the table and write the equation.</p>
<table class="stats" style="height: 105px; width: 96px;">
  <thead>
    <tr style="height: 17px;">
      <th style="height: 17px; width: 35.2px;">&nbsp;n&nbsp;</th>
      <th style="height: 17px; width: 36px;">t(n)</th>
    </tr>
  </thead>
  <tbody>
    <tr style="height: 20px;">
      <td style="height: 20px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[0]</span></td>
      <td style="height: 20px; width: 36px; text-align: center;"><span style="font-family: georgia, palatino;">$y1</span></td>
    </tr>
    <tr style="height: 17px;">
      <td style="height: 17px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[1]</span></td>
      <td style="height: 17px; width: 36px; text-align: center;"><span style="font-family: georgia, palatino;">$y2</span></td>
    </tr>
    <tr style="height: 17px;">
      <td style="height: 17px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[2]</span></td>
      <td style="height: 17px; width: 36px; text-align: center;"><span style="font-family: georgia, palatino;">$y3</span></td>
    </tr>
    <tr style="height: 17px;">
      <td style="height: 17px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[3]</span></td>
      <td style="height: 17px; width: 36px; text-align: center;">$answerbox[0]</td>
    </tr>
    <tr style="height: 17px;">
      <td style="height: 17px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[4]</span></td>
      <td style="height: 17px; width: 36px; text-align: center;">$answerbox[1]</td>
    </tr>
  </tbody>
</table>
<p><br />`t(n) =` $answerbox[2]</p>
<p>&nbsp;</p>
