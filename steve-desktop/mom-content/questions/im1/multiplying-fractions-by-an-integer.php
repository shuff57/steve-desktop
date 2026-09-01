// === NAME - DESCRIPTION: Multiplying Fractions by an Integer ===
// === SET QUESTION TYPE TO: calculated ===

// === COMMON CONTROL ===

$a0,$c0,$d0=rands(2,10,3)where (gcd($a0*$c0,$d0)==1)
$b0=1

$r=1
if($r==1){
  $q=1
  $p=rand(2,10) where(gcd($p,$b0*$c0)==1)
}else{
  $p=1
  $q=rand(2,10) where(gcd($q,$a0*$d0)==1)
}
$a=$a0*$p
$b=1
$c=$c0*$q
$d=$d0*$p

$ac=$a0*$c0
$bd=$b0*$d0

if($p==1){
  $fact1="`$b` and `$c` have a common factor of `$q`, so `$b = $b0 times $q` and `$c = $c0 times $q`"
  $eqn1="($a times $c0 times $q)/($b0 times $q times $d)"  
  $fact2=$q
}else{
  $fact1="`$a` and `$d` have a common factor of `$p`, so `$a = $a0 times $p` and `$d = $d0 times $p`"
  $eqn1="($a0 times $p times $c)/($b times $d0 times $p)"
  $fact2=$p
}

$answer="$ac/$bd"

// === QUESTION TEXT ===

Multiply.  

`$a times $c/$d = ` $answerbox

// === ANSWER ===

When multiplying a fraction by an integer, it helps to remember that `n = n/1`, so any integer can be treated as a fraction with a denominator of `1`. 

To multiply two fractions, multiply the numerators together to form the new numerator, and multiply the denominators together to form the new denominator:

<table>
  <tr><td align=right height=50>`$a/$b times $c/$d`</td><td align=center>`=`</td><td align=left>`($a times $c)/($b times $d)`</td><td></td></tr>
  <tr><td colspan=4>Before multiplying, it may be helpful to eliminate any common factor.  Note that $fact1, so:</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`$eqn1`</td></tr>
  <tr><td colspan=4>Dropping the factor `$fact2` common to both numerator and denominator:</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($a0 times $c0)/($b0 times $d0)`</td></tr>
  <tr><td colspan=4>Multiplying numerator by numerator to get the new numerator, and denominator by denominator to get the new denominator:</td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`($ac)/($bd)`</td></tr>
</table>
