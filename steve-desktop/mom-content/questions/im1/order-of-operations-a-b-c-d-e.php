// === NAME - DESCRIPTION: Order of operations (a + b - c * (d + e)) ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$a=rand(2,10)
$b=rand(2,5)
$c=rand($b+1,$b+4)
$d=rand(2,5)
$e=rand(2,15) where ($c*($d+$e)<100 && $c($d+$e)>$a+$b)

$ans1=$d + $e
$ans2=$ans1*$c
$ans3=$a + $b
$ans4=$ans2-$ans3

$answer=-$ans4

// === QUESTION TEXT ===

Evaluate:

`$a + $b - $c( $d + $e) = ` $answerbox

// === ANSWER ===

Since `$d + $e` is in parentheses, it must be done first:

<table>
  <tr>
    <td align=right>`$a + $b - $c( $d + $e)`</td>
    <td>`=`</td>
    <td align=left>`$a + $b - $c ($ans1)`</td>
  </tr>
  <tr>
    <td colspan=3>Next, multiplication is done before addition, so we evaluate `$c ($ans1)`:</td></tr>
  <tr>
    <td>
    </td>
    <td>`=`</td>
    <td align=left>`$a + $b -$ans2`</td>
  </tr>
  <tr>
    <td colspan=3>Since the only operations left are addition and subtraction we evaluate from left to right:
  </tr>
  <tr>
    <td>
    </td>
    <td>`=`</td>
    <td align=left>`$ans3 - $ans2`</td>
  </tr>
  <tr>
    <td colspan=3>Remember that `a - b = -(b - a)`:</td>
  </tr>
  <tr>
    <td>
    </td>
    <td>`=`</td>
    <td align=left>`-($ans2 - $ans3)`</td>
  </tr>
  <tr>
    <td>
    </td>
    <td>`=`</td>
    <td align=left>`-($ans4)`</td>
  </tr>
  <tr>
    <td>
    </td>
    <td>`=`</td>
    <td align=left>`$answer`</td>
  </tr>
  </tbody>
</table>
