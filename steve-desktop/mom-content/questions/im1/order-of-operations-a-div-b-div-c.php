// === NAME - DESCRIPTION: Order of Operations (a div b div c) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes=array("number","number","number")
$c=rand(2,5)
$b=rrand(2*$c,5*$c,$c)
$a=rrand(2*$c*$b, 5*$c*$b,$c*$b)

$ab=$a/$b
$answer[0]=$ab/$c

$bc=$b/$c
$answer[1]=$a/$bc

$answer[2]=$answer[0]

// === QUESTION TEXT ===

Evaluate:

`$a div $b div $c` = $answerbox[0]

`$a div ($b div $c)` = $answerbox[1]

`($a div $b) div $c` = $answerbox[2]

// === ANSWER ===

Remember the basic rule of order of operations is that operations are performed from left to right <i>unless</i>...

In `$a div $b div $c`, the only operation is division, so we perform the divisions from left to right:

<table>
  <tr><td align=left>`$a div $b div $c`</td><td align=center>`=`</td><td align=left>`$ab div $c`</td></tr>
  <tr><td align=left></td><td align=center>`=`</td><td align=left>`$answer[0]`</td></tr>
</table>

In `$a div ($b div $c)`, we have a parentheses, so the operation inside must be done first:

<table>
  <tr><td align=left>`$a div ($b div $c)`</td><td align=center>`=`</td><td align=left>`$a div $bc`</td></tr>
  <tr><td align=left></td><td align=center>`=`</td><td align=left>`$answer[1]`</td></tr>
</table>

In `($a div $b) div $c`, we have a parentheses, so the operation inside must be done first:

<table>
  <tr><td align=left>`($a div $b) div $c`</td><td align=center>`=`</td><td align=left>`$ab div $c`</td></tr>
  <tr><td align=left></td><td align=center>`=`</td><td align=left>`$answer[0]`</td></tr>
</table>
