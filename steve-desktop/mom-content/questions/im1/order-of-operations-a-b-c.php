// === NAME - DESCRIPTION: Order of Operations (a - b - c) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes=array("number","number","number")
$b,$c=diffrands(2,10,2) where ($b > $c + 1)
$a=rand($b+$c+2,$b+$c+10)
$ab=$a-$b
$answer[0]=$ab - $c

$bc=$b-$c
$answer[1]=$a - $bc
$answer[2]=$answer[0]

// === QUESTION TEXT ===

Evaluate:  

`$a - $b - $c = `$answerbox[0]

`$a - ($b - $c) = `$answerbox[1]

`($a - $b) - $c = `$answerbox[2]

// === ANSWER ===

Remember the basic rule of order of operations is that operations are performed from left to right <i>unless</i>...

In `$a - $b - $c`, the only operation is a subtraction, so we perform the subtractions from left to right:

<table>
  <tr><td align=left>`$a - $b - $c`</td><td align=center>`=`</td><td align=left>`$ab - $c`</td></tr>
  <tr><td align=left></td><td align=center>`=`</td><td align=left>`$answer[0]`</td></tr>
</table>

In `$a - ($b - $c)`, we have a parentheses, so the operation inside must be done first:

<table>
  <tr><td align=left>`$a - ($b - $c)`</td><td align=center>`=`</td><td align=left>`$a - $bc`</td></tr>
  <tr><td align=left></td><td align=center>`=`</td><td align=left>`$answer[1]`</td></tr>
</table>


In `($a - $b) - $c`, we have a parentheses, so the operation inside must be done first:

<table>
  <tr><td align=left>`($a - $b) - $c`</td><td align=center>`=`</td><td align=left>`$ab - $c`</td></tr>
  <tr><td align=left></td><td align=center>`=`</td><td align=left>`$answer[0]`</td></tr>
</table>
