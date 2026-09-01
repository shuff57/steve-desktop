// === NAME - DESCRIPTION: Order of operations  a(b - cd) - e^n  ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$a,$b,$c,$d=rands(2,7,4)
$e=rand(2,7)
$n=rand(2,5) where ($e^$n<100)

$cd=$c*$d
$bcd=$b-$cd
$en=$e^$n

$abcd=$a*$bcd
$answer=$abcd-$en

// === QUESTION TEXT ===

Evaluate:

`$a ($b - $c cdot $d) - $e^$n = ` $answerbox

// === ANSWER ===

First, we must evaluate the expression in the parentheses.

Since the expression inside the parentheses includes a product and a sum, we do the product first, then add:

<table>
  <tr><td align=right>`$a ($b - $c cdot $d) - $e^$n`</td><td align=center>`=`</td><td align=left>`$a ($b - $cd) - $e^$n`</td><td></td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$a ($bcd) - $e^$n`</td><td></td></tr>
  <tr><td colspan=4>There is a product and a power, so we do the power next:</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$a ($bcd) - $en`</td><td></td></tr>
  <tr><td colspan=4>Multiply:</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$abcd - $en`</td><td></td></tr>
  <tr><td colspan=4>Subtract:</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$answer`</td><td></td></tr>
</table>
