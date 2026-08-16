// === NAME - DESCRIPTION: Tile Pattern - fill in table, find equation #6  (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array ("number","number","number","number","number","numfunc")

$a = 1
$b = $a+1
$c = $a+2


$figure1=textonimage($tile,"<span style='font-size:15px'>Fig.`$a`</span>",25,100,"<span style='font-size:15px'>Fig. `$b`</span>",120,100,"<span style='font-size:15px'>Fig. `$c`</span>",220,100)

$answer[0] = 3
$answer[1] = 6
$answer[2] = 9
$answer[3] = 12
$answer[4] = 15

$answerboxsize[0] = 4
$answerboxsize[1] = 4
$answerboxsize[2] = 4
$answerboxsize[3] = 4
$answerboxsize[4] = 4

$answerboxsize[5] = 14
$answerformat[5]="equation"
$variables[5]="x,y"
$answer[5] = "y=3 x + 3"

// === QUESTION TEXT ===
<p>Fill in the table and write the equation for the tile pattern below.
<br /><br />
$figure1</p>
<table class="stats" style="width: 50px;">
  <tbody>
    <tr>
      <td style="width: 9.6px;">x</td>
      <td style="width: 10px;">y</td>
    </tr>
    <tr>
      <td style="width: 9.6px;">0</td>
      <td style="width: 10px;">$answerbox[0]</td>
    </tr>
    <tr>
      <td style="width: 9.6px;">1</td>
      <td style="width: 10px;">$answerbox[1]</td>
    </tr>
    <tr>
      <td style="width: 9.6px;">2</td>
      <td style="width: 10px;">$answerbox[2]</td>
    </tr>
    <tr>
      <td style="width: 9.6px;">3</td>
      <td style="width: 10px;">$answerbox[3]</td>
    </tr>
    <tr>
      <td style="width: 9.6px;">4</td>
      <td style="width: 10px;">$answerbox[4]</td>
    </tr>
  </tbody>
</table>
<p>Equation : Start with y= in your equation $answerbox[5]
<br /><br />
</p>
