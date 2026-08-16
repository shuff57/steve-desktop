// === NAME - DESCRIPTION: Graph `y = 2x + b`. Give table of values, graph at least 3 points, and graph line.  (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
//Graph a Linear Equation given a point and its slope
$anstypes = array("number","number","number","number","number","number","number","number","number","draw");
$answerboxsize = 4;

$hidetips=true;

$a = 2
$b = nonzerorand(-6,6) where (abs($b)!=$a)

$c = -$b*$md
$ymin=min(-5*$a+$b,-10)
$ymax=max(5*$a+$b,10)

$f = makexxprettydisp("y = $a x + $b");
$x = consecutive(-4,4)
$y = calconarray($x,"$a*x+$b")


$answer[0] = $y[0]
$answer[1] = $y[1]
$answer[2] = $y[2]
$answer[3] = $y[3]
$answer[4] = $y[4]
$answer[5] = $y[5]
$answer[6] = $y[6]
$answer[7] = $y[7]
$answer[8] = $y[8]

$answers[9] = array("$a x+$b","$x[3],$y[3]","$x[4],$y[4]","$x[5],$y[5]","optional,$x[0],$y[0]","optional,$x[1],$y[1]","optional,$x[2],$y[2]","optional,$x[6],$y[6]","optional,$x[7],$y[7]","optional,$x[8],$y[8]")

$answerformat[9] = "twopoint,line,dot"
$grid[9] = "-8,8,$ymin,$ymax,2:1:x,2:1:y,450,450"
$snaptogrid[9] = 1

// === QUESTION TEXT ===
$calc<br/>
<table border=1 width=700 cellpadding=10>
  <thead>
    <tr><th colspan=2 align="center">Graphing functions by plotting at least the three points when `x=-1`, `x=0`, and `x=1`. </th></tr>
    <tr><td colspan=2><i>Complete the table below for the function $f.  Then graph the function.</i></td></tr>
  <tbody>
    <tr><td align="center">$f<br><br>
      <table border="1"><tr><td align="center">`x`</td><td align="center">`y`</td></tr>
        <tr><td  align="center">-4</td><td align="center">$answerbox[0]$fb[0]</td></tr>
        <tr><td align="center">-3</td><td align="center">$answerbox[1]$fb[2]</td></tr>
        <tr><td align="center">-2</td><td align="center">$answerbox[2]$fb[4]</td></tr>
        <tr><td align="center">-1</td><td align="center">$answerbox[3]$fb[6]</td></tr>
        <tr><td align="center">0</td><td align="center">$answerbox[4]$fb[8]</td></tr>
        <tr><td align="center">1</td><td align="center">$answerbox[5]$fb[8]</td></tr>
        <tr><td align="center">2</td><td align="center">$answerbox[6]$fb[8]</td></tr>
        <tr><td align="center">3</td><td align="center">$answerbox[7]$fb[8]</td></tr>
        <tr><td align="center">4</td><td align="center">$answerbox[8]$fb[8]</td></tr>
      </table>
      </td><td align="left">$answerbox[9]$fb[9]</td></tr>
  </tbody>
</table>
