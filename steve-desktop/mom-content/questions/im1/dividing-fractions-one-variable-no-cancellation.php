// === NAME - DESCRIPTION: Dividing Fractions (one variable, no cancellation) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$x=randfrom("x,y,z,a,b,c")
$variables="$x"

$a,$b,$c=rands(2,12,3)where (gcd($a*$c,$b)==1)

$ac=$a*$c

$answer="$ac/($b $x)"
$requiretimes="$ac,>=1,$bd,>=1,/,<2"

// === QUESTION TEXT ===

Divide.  

`$a/$b div $x/$c = ` $answerbox (Enter your answer in reduced form)

// === ANSWER ===

To divide two fractions, invert the divisor and multiply:

<table>
  <tr><td align=right height=50>`$a/$b div $x/$c`</td><td align=center>`=`</td><td align=left>`$a/$b cdot $c/$x`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($a cdot $c)/($b cdot $x)`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($ac)/($b $x)`</td></tr>
</table>
