// === NAME - DESCRIPTION: Solve simple equations involving logs (problem5.pg) (local for Steven Huff) hardcoded for test ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("calculated","calculated");

// Hardcoded values based on rrand ranges
$a1 = 2;
$b1 = 3;
$c1 = 6; 
$d1 = 3;

// Calculation logic remains intact
$ans1 = ($a1)^($b1);
$ans2 = ($c1)^($d1);

$answerboxsize[0]=10;
$answerboxsize[1]=10;

$answer[0]=$ans1;
$answer[1]=$ans2;

// === QUESTION TEXT ===
(a) If ` \log_ ( $a1 ) x = $b1 `, then ` x = ` $answerbox[0] .
<br/>
(b)  If ` \log_ ( $c1 ) x = $d1 `, then `x = ` $answerbox[1] .
