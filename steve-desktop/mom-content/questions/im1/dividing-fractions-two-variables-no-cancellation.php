// === NAME - DESCRIPTION: Dividing Fractions (two variables, no cancellation)  ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$x,$y=diffrandsfrom("x,y,z,a,b,c",2)
$variables="$x,$y"

$a,$b,$c=rands(2,12,4)where (gcd($a*$c,$b)==1)

$ac=$a*$c

$answer="($ac $y)/($b $x)"
$requiretimes="$ac,>=1,$bd,>=1,/,<2"

// === QUESTION TEXT ===

Divide.  

`($a $y)/$b div $x/$c = ` $answerbox (Enter your answer in reduced form)

// === ANSWER ===

To divide two fractions, invert the divisor and multiply:

<table>
  <tr><td align=right height=50>`($a $y)/$b div $x/$c`</td><td align=center>`=`</td><td align=left>`($a $y)/$b cdot $c/$x`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($a $y cdot $c)/($b cdot $x)`</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($ac $y)/($b $x)`</td></tr>
</table>
