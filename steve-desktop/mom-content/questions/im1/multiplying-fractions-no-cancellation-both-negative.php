// === NAME - DESCRIPTION: Multiplying Fractions (no cancellation, both negative) ===
// === SET QUESTION TYPE TO: calculated ===

// === COMMON CONTROL ===

$a,$b,$c,$d=rands(2,12,4)where (gcd($a*$c,$b*$d)==1)

$ac=$a*$c
$bd=$b*$d

$answer="$ac/$bd"
$requiretimes="$ac,>=1,$bd,>=1"

// === QUESTION TEXT ===

Multiply.  You do not need to reduce.

`(-$a/$b) (-$c/$d) = ` $answerbox

// === ANSWER ===

To multiply signed numbers, multiply the unsigned numbers, then determine whether the product is positive or negative.

To multiply two fractions, multiply the numerators together to form the new numerator, and multiply the denominators together to form the new denominator:

<table>
  <tr><td align=right height=50>`$a/$b times $c/$d`</td><td align=center>`=`</td><td align=left>`($a times $c)/($b times $d)`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($ac)/($bd)`</td></tr>
</table>

Since we are trying to find the product of two negative numbers, the result should be positive.
