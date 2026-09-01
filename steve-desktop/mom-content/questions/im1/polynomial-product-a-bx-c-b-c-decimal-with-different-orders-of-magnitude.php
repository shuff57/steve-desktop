// === NAME - DESCRIPTION: Polynomial product a(bx + c) (b, c decimal with different orders of magnitude) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$var1=randfrom("x,y,z,a,b,c")
$variables="$var1"
$n=rand(1,3)
$db,$dc=diffrands(0,1,2)
$a=10^$n
$b=rand(2,10)/$a*10^$db
$c=nonzerorand(-5,5)/$a*10^$dc

$ab=$a*$b
$ac=$a*$c

$exp=polymakepretty("$b $var1 + $c")
$ans=polymakepretty("$ab $var1 + $ac")
$answer="$ans"
$requiretimes="(,=0"

// === QUESTION TEXT ===

Expand.

`$a ($exp) =` $answerbox

// === ANSWER ===

The distributive property allows us to expand

`a(b + c) = ab + ac`

So:
<table>
  <tr>
    <td>`$a ($b $var1 + $c)`</td>
    <td>`=`</td>
    <td>`$a ($b) $var1 + $a ($c)`</td>
  </tr>
  <tr>
    <td></td>
    <td>`=`</td>
    <td>`$answer`</td>
  </tr>
  </tbody>
</table>
