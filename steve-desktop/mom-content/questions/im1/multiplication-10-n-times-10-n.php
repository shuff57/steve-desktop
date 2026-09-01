// === NAME - DESCRIPTION: Multiplication (10^-n times 10^n) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$x=randfrom("x,y,z,a,b,c")
$variables="$x"

$n=rand(1,3)
$d1=10^$n
$d2=1/$d1

$answer="$x"
$requiretimes="$d1,<1"

// === QUESTION TEXT ===

Simplify:

`$d1 ($d2 $x) = ` $answerbox

// === ANSWER ===

Notice that `$d1` and `$d2 = 1/$d1` are multiplicative inverses.

So their product is `1`.

By the identity property, `1 $x = $x`.

So we can write

`$d1 ($d2 $x) = $x`.
