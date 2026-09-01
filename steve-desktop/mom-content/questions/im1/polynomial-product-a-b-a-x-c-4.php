// === NAME - DESCRIPTION: Polynomial product a(b/a x - c) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$var1=randfrom("x,y,z,a,b,c")
$variables="$var1"
$a=rand(2,5)
$b=rand(2,10) where gcd($a,$b)==1
$c=nonzerorand(-10,-1)

$ab=$b
$ac=$a*$c
$fact1=polymakepretty("$b/$a $var1 + $c")
$cabs=abs($c)
if($c>0){
  $fact2=polymakepretty("$a ($b/$a) $var1 + $a ($c)")
}else{
  $fact2=polymakepretty("$a ($b/$a) $var1 - $a ($cabs)")
}
$ans=polymakepretty("$ab $var1 + $ac")
$answer=$ans
$requiretimes="(,=0"

// === QUESTION TEXT ===

Expand.

`$a ($fact1) =` $answerbox

// === ANSWER ===

The distributive property allows us to expand

`a(b + c) = ab + ac`

So:
<table>
  <tr>
    <td>`$a ($fact1)`</td>
    <td>`=`</td>
    <td>`$fact2`</td>
  </tr>
  <tr>
    <td></td>
    <td>`=`</td>
    <td>`$answer`</td>
  </tr>
  </tbody>
</table>
