// === NAME - DESCRIPTION: Subtracting algebraic terms:  a(bx + c) - (dx + e) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$x=randfrom("x,y,z,a,b,c")
$variables="$x"
$a,$b,$c,$d,$e=nonzerorands(-10,10,5) where ($a*$b != $d && $a*$c != $e && $a>1 && $b>1 && $d>1)

$eqn=polymakepretty("$a ($b $x + $c) - ($d $x + $e)")

$ab=$a*$b
$ac=$a*$c

$abd=$ab-$d
$ace=$ac-$e
$eqn1=polymakepretty("$a ($b $x + $c) + (-1)($d $x + $e)")

$eqn2=polymakepretty("$ab $x + $ac - $d $x - $e")
$eqn3=polymakepretty("$ab $x - $d $x + $ac - $e")
$eqn4=polymakepretty("$abd $x + $ace")
$answer=$eqn4

// === QUESTION TEXT ===

Simplify.

`$eqn = `$answerbox

// === ANSWER ===

<p>We can use the distributive property, remembering that `a - b = a + (-1)b`:
  <br /><br />
</p>
<table>
  <tbody>
    <tr>
      <td align="right">`$eqn`</td>
      <td align="center">`=`</td>
      <td align="left">`$eqn1`</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td align="center">`=`</td>
      <td align="left">`$eqn2`</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td align="center">`=`</td>
      <td align="left">`$eqn3`</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td align="center">`=`</td>
      <td align="left">`$eqn4`</td>
    </tr>
  </tbody>
</table>
