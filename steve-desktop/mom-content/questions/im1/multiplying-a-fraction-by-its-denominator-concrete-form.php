// === NAME - DESCRIPTION: Multiplying a fraction by its denominator (concrete form)  ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$x,$y=diffrands(20,99,2) where (gcd($x,$y)==1 && gcd($x,30)==1 && gcd($y,30)==1)

$answer="$x"
$requiretimes="$y,<1"

// === QUESTION TEXT ===

Simplify.  

`$y ($x/$y) = `$answerbox

// === ANSWER ===

Remember that as long as the denominator isn't 0, 

`b (a/b) = a`
