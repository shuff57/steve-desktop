// === NAME - DESCRIPTION: 1-47 determine if the relationship is a function ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = "choices,choices,choices,choices";
$questions = array("Function","Not a Function");

// Graph for part a (previous functional graph example)
$a1 = rand(-3,-1);
$b1 = rand(1,3);
$y1_1 = 4 - $a1^2;
$y1_2 = 4 - $b1^2;
$graph[0] = showplot("-x^2 + 4,blue,$a1,$b1,closed,open",-5,5,-5,5);


{
  $r = rand(2,5)
	$x,$y = rands(-5,5,2)
  } where (abs($x)+$r<5 && abs($y)+$r<5 ) 

$edge = max(abs($x)+$r,abs($y)+$r) + 0.5

$graph[1] = showplot("[$x+ $r*cos(t),$y+$r*sin(t)],blue,,,,,",-$edge,$edge,-$edge,$edge,1,1,250,250)

// Assign correct answers
$answer[0] = 0;  // For part a
$answer[1] = 0;  // For part b
$answer[2] = 1;  // For part c
$answer[3] = 1;  // For part d (non-functional graph)

// === QUESTION TEXT ===

<p>Which of the relationships below are functions? If a relationship is not a function, select "Not a Function".</p>

<p><b>a.</b> Consider the following graph:</p>
<p>$graph[0]</p>
<p>Is this relationship a function? $answerbox[0]</p>

<p><b>b.</b> Consider the table with the following inputs and outputs:</p>
<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <th>input (x)</th>
    <th>-3</th>
    <th>5</th>
    <th>19</th>
    <th>0</th>
  </tr>
  <tr>
    <th>output (y)</th>
    <th>19</th>
    <th>19</th>
    <th>0</th>
    <th>-3</th>
  </tr>
</table>
<p>Is this relationship a function? $answerbox[1]</p>

<p><b>c.</b> Consider the table with the following inputs and outputs:</p>
<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <th>input (x)</th>
    <th>7</th>
    <th>-2</th>
    <th>0</th>
    <th>7</th>
    <th>4</th>
  </tr>
  <tr>
    <th>output (y)</th>
    <th>10</th>
    <th>0</th>
    <th>10</th>
    <th>3</th>
    <th>0</th>
  </tr>
</table>
<p>Is this relationship a function? $answerbox[2]</p>

<p><b>d.</b> Consider the following graph:</p>
<p>$graph[1]</p>
<p>Is this relationship a function? $answerbox[3]</p>
