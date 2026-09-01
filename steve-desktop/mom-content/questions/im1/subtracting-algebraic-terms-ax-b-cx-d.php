// === NAME - DESCRIPTION: Subtracting Algebraic terms:  ax + b - (cx + d) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$var=randfrom("x,y,z,a,b,c")
$variables="$var"
$a1,$a0,$b1,$b0=nonzerorands(-10,10,4) where (abs($a1)>1 && abs($b1)>1 && $a1 != $b1 && $a0 != $b0 )
$sum1=makepretty("$a1 $var + $a0")
$sum2=makepretty("$b1 $var + $b0")

$eqn1=makepretty("$a1 $var + $a0 - $b1 $var - $b0")

$c1=$a1-$b1
$c0=$a0-$b0

$eqn2=makepretty("$c1 $var + $c0")
$answer=$eqn2
$requiretimes="$var,<2"

// === QUESTION TEXT ===

Subtract:

`$sum1 - ($sum2) = ` $answerbox

// === ANSWER ===

To subtract a polynomial expression, add the additive inverses of the individual terms:
<table>
  <tr><td align=right>`$sum1 - ($sum2)`</td><td align=center>`=`</td><td align=left>`$eqn1`</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$eqn2`</td></tr>
</table>
