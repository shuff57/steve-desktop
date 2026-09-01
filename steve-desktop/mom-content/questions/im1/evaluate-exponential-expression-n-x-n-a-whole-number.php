// === NAME - DESCRIPTION: Evaluate exponential expression  (n^x, n a whole number) ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$x=randfrom("x,y,z,a,b,c")
$x0,$n=rands(2,10,2) where ($n^$x0<100) && $x0 != $n
$answer=$n^$x0

// === QUESTION TEXT ===

Evaluate, when `$x = $x0`.

`$n^$x = `$answerbox

// === ANSWER ===

To evaluate `$n^$x` when `$x = $x0`, replace every occurrence of `$x` with `$x0`.  Use parentheses to keep everything organized:
<table>
  <tr><td align=right>`$n^$x`</td><td align=center>`=`</td><td align=left>`($n)^$x0`</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$answer`</td></tr>
</table>
