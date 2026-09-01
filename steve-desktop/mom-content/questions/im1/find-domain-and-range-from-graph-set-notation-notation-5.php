// === NAME - DESCRIPTION: find domain and range from graph set notation notation #5 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("calcinterval", "calcinterval");

$variables[0] = "x"
$variables[1] = "y"

$a = rand(-3,-1);
$b = rand(1,3) where ($a+$b!=0);

$y1 = 5 - $a^2;
$y2 = 5 - $b^2;

$graph = showplot("-x^2 +5,red,$a,$b,closed,open",-10,10,-10,10);

$answer[0] = "[$a,$b)";
$showanswer[0] = "`$a le x lt $b`";
$answer[1] = "[$y1,5]" if($y1<$y2); 
$showanswer[1] = "$y1 `le y le` 5" if ($y1<$y2);
$answer[1] = "($y2,5]" if($y1>$y2); 
$showanswer[1] = "$y2 `lt y le` 5" if ($y1>$y2);

$answerformat[0] = "inequality";
$answerformat[1] = "inequality";

// === QUESTION TEXT ===

Find the domain and range of the function graphed below.

<p><b>Note:</b> All answers should be written in set notation</p>

$graph

Domain: $answerbox[0] <br/>
Range: $answerbox[1]
