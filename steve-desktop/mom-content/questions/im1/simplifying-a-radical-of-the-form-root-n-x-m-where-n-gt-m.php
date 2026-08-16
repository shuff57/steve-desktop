// === NAME - DESCRIPTION: Simplifying a radical of the form `root(n)(x^m)` where `n&gt;m`. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$a=rand(2,6)
$b=$a+1
$c=rand(2,5)

$r=$b*$c
$p=$a*$c

$expr=makepretty("root($r)(x^$p)")

$answer="root($b)(x^$a)"

$requiretimes="$b,=1"

// === QUESTION TEXT ===
Simplify `$expr`.

</br>
Assume all variables represent positive values.

$answerbox
