// === NAME - DESCRIPTION: Arithmetic Increasing Sequence with table. Explicit formula (copy by Steve Lang) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array ("number","number","numfunc")
$x = array(1,2,3,4,5)
$m = "x"

$a = rand (2,10)
$b = rand (2,9)


$y1 = $a
$y2 = $a+($b*$x[0])
$y3 = $a+($b*$x[1])
$y4 = $a+($b*$x[2])
$y5 = $a+($b*$x[3])
$y6 = $a+($b*$x[4])

$start = $a - $b


$answer[0] = $y1
$answer[1] = $y2
$answer[2] = "$b x + $start"


$answerboxsize[0] = 4
$answerboxsize[1] = 4
$answerboxsize[2] = 10

// === QUESTION TEXT ===
<p>Complete the table and write the equation.</p>
<table class="stats" style="height: 105px; width: 96px;">
  <thead>
    <tr style="height: 17px;">
      <th style="height: 17px; width: 35.2px;">&nbsp;x&nbsp;</th>
      <th style="height: 17px; width: 36px;">y</th>
    </tr>
  </thead>
  <tbody>
    <tr style="height: 20px;">
      <td style="height: 20px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[0]</span></td>
      <td style="height: 20px; width: 36px; text-align: center;"><span style="font-family: georgia, palatino;">$answerbox[0]</span></td>
    </tr>
    <tr style="height: 17px;">
      <td style="height: 17px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[1]</span></td>
      <td style="height: 17px; width: 36px; text-align: center;"><span style="font-family: georgia, palatino;">$answerbox[1]</span></td>
    </tr>
    <tr style="height: 17px;">
      <td style="height: 17px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[2]</span></td>
      <td style="height: 17px; width: 36px; text-align: center;"><span style="font-family: georgia, palatino;">$y3</span></td>
    </tr>
    <tr style="height: 17px;">
      <td style="height: 17px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[3]</span></td>
      <td style="height: 17px; width: 36px; text-align: center;"><span style="font-family: georgia, palatino;">$y4</span></td>
    </tr>
    <tr style="height: 17px;">
      <td style="height: 17px; width: 35.2px; text-align: center;"><span style="font-family: georgia, palatino;">$x[4]</span></td>
      <td style="height: 17px; width: 36px; text-align: center;"><span style="font-family: georgia, palatino;">$y5</span></td>
    </tr>
  </tbody>
</table>
<p>&nbsp;</p>
<p>y = $answerbox[2]</p>
<p>&nbsp;</p>
