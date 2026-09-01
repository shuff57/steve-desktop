// === NAME - DESCRIPTION: Order of operations (a + (b - c) * d + e, b - c < 0  ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$a=rand(2,10)
$b=rand(2,5)
$c=rand($b+1,$b+5)
$d=rand(2,10) where (abs($d*($b - $c))<100)
$e=rand(2,15)

$ans1=$b - $c
$ans2=$ans1*$d
$ans2a=-$ans2
$ans3=$a + $ans2
$ans4=$ans3+$e

$answer=$ans4

// === QUESTION TEXT ===

Evaluate:

`$a + ($b - $c) $d + $e = ` $answerbox

// === ANSWER ===

Since `$b - $c` is in parentheses, it must be done first:

<table>
  <tr>
    <td align=right>`$a + ($b - $c) $d + $e`</td>
    <td>`=`</td>
    <td align=left>`$a + ($ans1) $d + $e`</td>
  </tr>
  <tr>
    <td colspan=3>Next, multiplication is done before addition, so we evaluate `($ans1)$d`:</td></tr>
  <tr>
    <td>
    </td>
    <td>`=`</td>
    <td align=left>`$a + ($ans2) + $e`</td>
  </tr>
  <tr>
    <td colspan=3>Since the only operation left is addition, we evaluate from left to right.  Remember that `a + (-b) = a - b`, so we first add `$a + ($ans2) = $a - $ans2a`:</td>
  </tr>
  <tr>
    <td>
    </td>
    <td>`=`</td>
    <td align=left>`$a - $ans2a + $e`</td>
  </tr>
  <tr>
    <td>
    </td>
    <td>`=`</td>
    <td align=left>`$ans3 + $e`</td>
  </tr>
  <tr>
    <td>
    </td>
    <td>`=`</td>
    <td align=left>`$answer`</td>
  </tr>
  </tbody>
</table>
