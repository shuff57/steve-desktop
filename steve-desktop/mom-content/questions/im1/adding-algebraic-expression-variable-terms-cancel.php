// === NAME - DESCRIPTION: Adding algebraic expression (variable terms cancel) ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$a,$b=nonzerorands(1,20,2) where($a>1)
$x=randfrom("x,y,z,a,b,c")
$variables="$x"

$eqn=polymakepretty("$a $x + $b - $a $x")
$eqn2=polymakepretty("$a $x - $a $x + $b")
$answer=$b

// === QUESTION TEXT ===

Simplify:

`$eqn = ` $answerbox

// === ANSWER ===

In the expression, notice that `$a $x` and `-$a $x` are additive inverses.

By the commutative property, `$eqn = $eqn2`.

By the property of additive inverses, `$eqn2 = $answer`.
