// === NAME - DESCRIPTION: Evaluating absolute value expression:  | a - b| - |c - d|, a < b, c < d  ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$x,$y=rands(2,5,2)

$a,$b,$c,$d=rands(2,10,4)
$bx=$b*$x
$cy=$c*$y
$ab=$a-$bx
$cd=$cy-$d

$absab=abs($ab)
$abscd=abs($cd)

$answer=$absab-$abscd

// === QUESTION TEXT ===

Evaluate:  

`abs($a - $b($x)) - abs($c($y) - $d) = `$answerbox

// === ANSWER ===

The absolute value symbols act like a set of parentheses, so we should evaluate the expressions inside the absolute value symbols first:
<table>
  <tr><td align=right>`abs($a-$b($x))-abs($c($y)-$d)`</td><td align=center>`=`</td><td align=left>`abs($a - $bx) - abs($cy - $d)`</td><td></td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`abs($ab) - abs($abscd)`</td></tr>

  <tr><td colspan=4>Now find the absolute values:</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$absab - $abscd`</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$answer`</td></tr>
</table>
