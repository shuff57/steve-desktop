// === NAME - DESCRIPTION: Dividing Fractions (ax divided by c/d) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$x=randfrom("x,y,z,a,b,c")
$variables="$x"

$b=1
$a,$b,$c=rands(2,12,3)where (gcd($a*$c,$b)==1)

$ac=$a*$c

$answer="($ac $x)/($b)"
$requiretimes="$ac,>=1,$bd,>=1,/,<2"

// === QUESTION TEXT ===

Divide.  

`$a $x div $b/$c = ` $answerbox (Enter your answer in reduced form)

// === ANSWER ===

Remember `$a $x= ($a $x)/1`, so we can rewrite this as a quotient of fratcions:

`($a $x)/1 div $b/$c`

To divide two fractions, invert the divisor and multiply:

<table>
  <tr><td align=right height=50>`($a $x)/1 div $b/$c`</td><td align=center>`=`</td><td align=left>`($a $x)/1 cdot $c/$b`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($a $x cdot $c)/(1 cdot $b)`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($ac $x)/($b)`</td></tr>
</table>
