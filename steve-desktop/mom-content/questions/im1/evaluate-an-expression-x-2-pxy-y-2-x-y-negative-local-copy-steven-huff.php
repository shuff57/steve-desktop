// === NAME - DESCRIPTION: Evaluate an expression (x^2 + pxy + y^2) (x, y negative)(local copy Steven Huff) ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$p=nonzerorand(-5,5)
$x,$y=diffrandsfrom("x,y,z,a,b,c",2)
$variables="$x,$y"
$x0,$y0=diffrands(-3,3,2) where ($x0*$y0 <0)
$expr=polymakepretty("$x^2 + $p $x $y + $y^2")

$answer=$x0^2+$p*$x0*$y0+$y0^2

$eval1=polymakepretty("($x0)^2 + $p ($x0)($y0) +($y0)^2")

$x02=$x0^2
$px0y0=$p*$x0*$y0
$y02=$y0^2

$eval2=polymakepretty("$x02 + $px0y0 + $y02")

// === QUESTION TEXT ===

When `$x = $x0, $y = $y0`,

`$expr = `$answerbox

// === ANSWER ===

To evaluate, we'l replace every occurrence of `$x` with `$x0`, and every occurrence of `$y` with `$y0`:

<table>
  <tr><td align=right>`$expr`</td><td align=center>`=`</td><td align=left>`$eval1`</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$eval2`</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$answer`</td></tr>
</table>
