// === NAME - DESCRIPTION: Evaluate exponents (x^n, n a whole number) ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$x=randfrom("x,y,z,a,b,c")
$x0,$n=rands(2,10,2) where ($x0^$n<100)
$answer=$x0^$n

// === QUESTION TEXT ===

Evaluate, when `$x = $x0`.

`$x^$n = `$answerbox

// === ANSWER ===

To evaluate `$x^$n` when `$x = $x0`, replace every occurrence of `$x` with `$x0`.  Use parentheses to keep everything organized:
<table>
  <tr><td align=right>`$x^$n`</td><td align=center>`=`</td><td align=left>`($x0)^$n`</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$answer`</td></tr>
</table>
