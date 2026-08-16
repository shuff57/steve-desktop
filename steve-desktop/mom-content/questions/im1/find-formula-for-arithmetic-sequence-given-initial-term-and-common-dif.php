// === NAME - DESCRIPTION: Find formula for arithmetic sequence given initial term and common difference (ur_sq_4_6.pg) (copy by Janell Woods) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("numfunc")



$a = rrand(1,10,1);
$d = rrand(2,10,1);
$ans = "$a + $d * (n-1)";


$variables[0]="n"

$answerboxsize[0]=10

$answer[0]=$ans
$showanswer[0]= makeprettydisp($ans)

// === QUESTION TEXT ===
Find the nth term of the arithmetic sequence whose initial term is `a_1 = $a `
and common difference is ` $d `.

`t(n) = ` $answerbox[0] (Your answer must be a function of ` n `.)
