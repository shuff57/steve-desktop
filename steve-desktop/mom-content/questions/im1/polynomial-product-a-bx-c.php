// === NAME - DESCRIPTION: Polynomial product -a(bx + c)  (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$var1=randfrom("x,y,z,a,b,c")
$variables="$var1"
$a=rand(-5,-2)
$b,$c=diffrands(2,10,2)

$ab=$a*$b
$ac=$a*$c

$answer=makepretty("$ab $var1 + $ac")
$requiretimes="(,=0"

// === QUESTION TEXT ===
Expand.

`$a ($b $var1 + $c) =` $answerbox

// === ANSWER ===
The distributive property allows us to expand

`a(b + c) = ab + ac`

So:
<table>
  <tr>
    <td>`$a ($b $var1 + $c)`</td>
    <td>`=`</td>
    <td>`($a) $b $var1 + ($a) $c`</td>
  </tr>
  <tr>
    <td></td>
    <td>`=`</td>
    <td>`$answer`</td>
  </tr>
  </tbody>
</table>
