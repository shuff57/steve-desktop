// === NAME - DESCRIPTION: Dividing Fractions (number by a variable fraction, no cancellation) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$x=randfrom("x,y,z,a,b,c")
$variables="$x"

$b=1
$a,$c=rands(2,12,2)where (gcd($a*$c,$b)==1)

$ac=$a*$c

$answer="$ac/($x)"
$requiretimes="$ac,>=1,$bd,>=1,/,<2"

// === QUESTION TEXT ===

Divide.  

`$a div $x/$c = ` $answerbox (Enter your answer in reduced form)

// === ANSWER ===

Remember `$a = $a/1`, so we can rewrite this as a quotient of fratcions:

`$a/1 div $x/$c`

To divide two fractions, invert the divisor and multiply:

<table>
  <tr><td align=right height=50>`$a/$b div $x/$c`</td><td align=center>`=`</td><td align=left>`$a/$b cdot $c/$x`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($a cdot $c)/($b cdot $x)`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($ac)/($x)`</td></tr>
</table>
