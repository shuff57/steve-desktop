// === NAME - DESCRIPTION: Dividing Fractions (fraction by a variable, no cancellation)   ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$x=randfrom("x,y,z,a,b,c")
$variables="$x"
$c=1
$a,$b=rands(2,12,2)where (gcd($a,$b)==1)

$ac=$a*$c

$answer="$ac/($b $x)"
$requiretimes="$ac,>=1,$bd,>=1,/,<2"

// === QUESTION TEXT ===

Divide.  

`$a/$b div $x = ` $answerbox (Enter your answer in reduced form)

// === ANSWER ===

Remember that `$x = $x/1`, so this can be written as 

`$a/$b div $x/1`.

To divide two fractions, invert the divisor and multiply

<table>
  <tr><td align=right height=50>`$a/$b div $x/$c`</td><td align=center>`=`</td><td align=left>`$a/$b cdot $c/$x`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($a cdot $c)/($b cdot $x)`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($ac)/($b $x)`</td></tr>
</table>
