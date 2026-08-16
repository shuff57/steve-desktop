// === NAME - DESCRIPTION: Graph a/n on number line (-a/n between 1 and 2) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===
loadlibrary("interval")
$den=rand(2,5)
$dx=1/$den
$snaptogrid=$dx
$num=rand($den+1,2*$den-1) where (gcd($num,$den)==1)
$frac=-$num/$den
$frabs=abs($frac)
$ans="[$frac,$frac]"

$xmin = -2
$xmax = 2


$ans2 = "$frac,0"

$answerformat = "numberline,dot"
$grid= "$xmin,$xmax,0,0,,,400" //defines answer grid
$answers = $ans2
$det1=linegraph("($x,$x)",$xmin,$xmax)
$det2=linegraph($ans,$xmin,$xmax)

// === QUESTION TEXT ===
Plot `-$num/$den` on the number line shown:

$answerbox

// === ANSWER ===
Since `-$num/$den` is negative, our number will be to the left of `0` on the number line.

We can break each unit into `$den` parts, then move `$num` places away from `0`:

$det2
