// === NAME - DESCRIPTION: Determining the Linear Equation from a graph -  Fraction Slope (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes ="calculated,ntuple,numfunc";
$answerformat[0]="reducedfraction"
$hidetips = true;
$dir = nonzerorand(-1,1)
$slopes = array(.2,.25,.4,.5,.6,.75,.8,1.25,1.5,1.75);
$m = decimaltofraction(randfrom($slopes)*$dir);
$b = rand(-5,-1)*$dir;

$plot = showplot("$m x + $b",-10,10,-10,10,1,1,400,400);

$answer[0] = $m;
$answer[1] = "(0,$b)";
$answer[2] = "y=$m x + $b";
$showanswer[2] = makexxprettydisp("y=$m x + $b");
$variables[2] = "x,y"
$answerformat[2] = "equation"
$requiretimes[2] = "$m,=1";

// === QUESTION TEXT ===
<table width=700 border=1 cellpadding="10">
  <thead><tr><th colspan=4 align="center">Writing Linear Equations</th></tr>
    <tr><td colspan=4><i>Determine the equation of the linear function that generates the following graph. Write
      your answer in slope-intercept form (`y=mx+b`).
      </i></p></td></tr>
</thead>
<tbody>
  <tr><td align="center">$plot</td><td align="center">Determine the Slope<br><br>`m = `$answerbox[0]<br><br>Determine the starting point (as an `(x,y)` ordered pair)<br><br>$answerbox[1]<br><br>Write the equation<br><br> $answerbox[2]<br>$previewloc[2]</td></tr>
</tbody>
</table>
