// === NAME - DESCRIPTION: Apply law of cosines (srw6_5_27.pg) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("calculated")


$a1=rrand(210,300,1);
$a=$a1/100;
$b1=rrand(310,400,1);
$b=$b1/100;
$ac1=rrand(401,420,1);
$ac=$ac1/10;

$ans1=sqrt($a^2+$b^2-2*$a*$b*cos(pi*$ac/180));

$answerboxsize[0]=15

$answer[0]=$ans1

// === QUESTION TEXT ===
Find `c` if `a=$a` mi, `b=$b` mi and `\angle C =$ac ` degrees.
<br/>
`c=` $answerbox[0] mi (round to 4 decimal places)

Assume `\angle A` is opposite side `a`, `\angle B` is opposite side `b`, and `\angle C` is opposite side `c`.
