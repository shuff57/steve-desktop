// === NAME - DESCRIPTION: Cancellation (a/b) (c/d) (b/a)  ===
// === SET QUESTION TYPE TO: calculated ===

// === COMMON CONTROL ===

$a,$b=rands(2,20,2) where (gcd($a,$b)==1)
$c,$d=rands(2,20,2) where (gcd($c,$d)==1 && $b != $d)
$answer="$c/$d"

// === QUESTION TEXT ===

Simplify.

`$a/$b cdot $c/$d cdot $b/$a = `$answerbox

// === ANSWER ===

When we multiply fractions, we can multiply the numerators together and the denominators together.

<table>
  <tr><td align=right>`$a/$b $c/$d $b/$a`</td><td align=center>`=`</td><td align=left>`($a cdot $c cdot $b)/($b cdot $d cdot $a)`</td><td></td></tr>
  <tr><td colspan=4>We can remove common factors.</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$c/$d`</td><td></td></tr>
</table>
