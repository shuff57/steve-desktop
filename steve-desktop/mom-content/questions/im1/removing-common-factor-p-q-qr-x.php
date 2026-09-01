// === NAME - DESCRIPTION: Removing common factor (p/q) qr x  ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$x=randfrom("x,y,z,a,b,c")
$variables="$x"
$p,$q,$r=rands(2,10,3) where(gcd($q,$r)==1 && gcd($p,$q)==1)

$qr=$q*$r
$rp=$r*$p
$answer="$rp $x"
$requiretimes="/,<1"

// === QUESTION TEXT ===

Simplify.

`$qr ($p/$q)  $x =` $answerbox

// === ANSWER ===

To simplify, we can multiply the coefficients:

<table>
  <tr><td align=right>`$qr ($p/$q) $x`</td><td align=center>`=`</td><td align=left>`($qr cdot $p)/$q $x`</td><td></td></tr>
  <tr><td colspan=4>To simplify, we can try to write the numerator and denominator as products and see if we can remove any common factors:</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`($q cdot $r cdot $p)/(1 cdot $q( $x`</td></tr>
  <tr><td colspan=4>We see that we can remove a common factor of `$q` from numerator and denominator:</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`($r cdot $p)/1 $x`</td></tr>
  <tr><td colspan=4>Since `n/1 = n`, we can rewrite:</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$r cdot $p $x`</td></tr>
  <tr><td colspan=4>Finally we can multiply `$r cdot $p = $rp`:</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$r cdot $p $x`</td></tr>
</table>
