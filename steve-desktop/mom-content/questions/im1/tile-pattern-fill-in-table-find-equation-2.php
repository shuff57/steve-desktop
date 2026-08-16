// === NAME - DESCRIPTION: Tile Pattern - fill in table, find equation #2  (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array ("number","number","number","number","number","numfunc")

$a = 1
$b = $a+1
$c = $a+2


$figure1=textonimage($tile,"<span style='font-size:15px'>Fig.`$a`</span>",20,108,"<span style='font-size:15px'>Fig. `$b`</span>",110,108,"<span style='font-size:15px'>Fig. `$c`</span>",230,108)

$answer[0] = 1
$answer[1] = 3
$answer[2] = 5
$answer[3] = 7
$answer[4] = 9

$answerboxsize[0] = 4
$answerboxsize[1] = 4
$answerboxsize[2] = 4
$answerboxsize[3] = 4
$answerboxsize[4] = 4

$answerboxsize[5] = 8
$answer[5] = "2 x + 1"

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
<p>Equation : y = $answerbox[5]
<br /><br />
</p>
