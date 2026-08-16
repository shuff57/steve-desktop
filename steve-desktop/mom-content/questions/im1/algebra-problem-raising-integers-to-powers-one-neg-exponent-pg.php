// === NAME - DESCRIPTION: Algebra problem: raising integers to powers (one neg exponent) (.pg) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("numfunc")


$a = rrand(2,4,1);
$b = rrand(2,3,1);
$c = rrand(5,6,1);
$d = rrand(2,4,1);

$ans = $a^(-$b)*$c^$d;

$answerboxsize[0]=10

$answer[0]=$ans

// === QUESTION TEXT ===
Evaluate the expression `$a ^{-$b }$c ^{$d }`.
<br/> <br/>
$answerbox[0]
