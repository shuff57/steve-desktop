// === NAME - DESCRIPTION: Order of operations (a div b + c (d - e)), d > e ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$n,$b,$c,$d=rands(3,7,4)
$a=$n*$b
$e=rand(1,$d-1) where ($d - $e>1)

$de=$d-$e
$cde=$c*$de
$answer=$n+$cde

// === QUESTION TEXT ===

Evaluate:

`$a \div $b + $c ($d - $e) = ` $answerbox

// === ANSWER ===

First, we must evaluate the expression in the parentheses:

<table>
  <tr><td align=right>`$a \div $b + $c ($d - $e)`</td><td align=center>`=`</td><td align=left>`$a \div $b + $c ($de)`</td><td></td></tr>
  <tr><td colspan=4>Next, we perform the division,</td></tr>
  <tr><td align=right>``</td><td align=center>`=`</td><td align=left>`$n + $c ($de)`</td><td></td></tr>
  <tr><td colspan=4>Then the multiplication,</td></tr>
  <tr><td align=right>``</td><td align=center>`=`</td><td align=left>`$n + $cde`</td><td></td></tr>
  <tr><td colspan=4>And finally the addition:</td></tr>
  <tr><td align=right>``</td><td align=center>`=`</td><td align=left>`$answer`</td><td></td></tr>
</table>
