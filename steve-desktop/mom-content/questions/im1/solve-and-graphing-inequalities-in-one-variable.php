// === NAME - DESCRIPTION: Solve and graphing inequalities in one variable.  (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("calcinterval", "draw")
//$anstypes = array("calcinterval", "draw", "interval")
$a = rand(4,9)
$b = rand(10,20)
$c = rand(4,8)
$d = ($a-$b)*$c
$e = $a-$b 




$exp = makexxprettydisp("`$a x+ $e - $b x <= $d`")

$f = ($d-$e)/($a-$b)
$answer[0] = "[$f,oo)" 
$answerformat[0] = "inequality"

$answer[1] = array("$f,0,closed","0,$f,oo")
$answerformat[1] = 'numberline'
$snaptogrid[1] = 1
$grid = "0,12,1"

//$answer[2] = "[$f,oo)"
//$showanswer[2] = "`[$f,oo)`"

$answerboxsize = 10

// === QUESTION TEXT ===
<p>Solve, graph, and express the following inequality in interval notation.&nbsp;</p>
<table style="border-collapse: collapse; width: 100%;" border="1">
  <tbody>
    <tr>
      <td style="width: 50%; text-align: center; background-color: #cccccc;"><strong>Solve</strong></td>
      <td style="width: 50%; text-align: center; background-color: #cccccc;"><strong>Graph and Interval Notation</strong></td>
    </tr>
    <tr>
      <td style="width: 50%; text-align: center;" rowspan="2">
        <p>$exp</p>
        <p>[AB0]</p>
      </td>
      <td style="width: 50%;">
        <p>To graph an inequality first, place the appropriate dot on the value where the line begins. Then select the line tool and draw your line completely to the end of the graph.&nbsp;</p>
        <p>[AB1]</p>
      </td>
    </tr>
    <tr>
      
    </tr>
  </tbody>
</table>
