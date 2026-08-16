// === NAME - DESCRIPTION: Evaluating absolute value expression:  | a - b| - |c - d|, a < b, c < d  (local for Steven Huff) ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===
$a,$b,$c,$d=rands(1,10,4) where ($a<$b && $d > $c)
$ab=$a-$b
$cd=$c-$d

$absab=abs($ab)
$abscd=abs($cd)

$answer=$absab-$abscd

// === QUESTION TEXT ===
Evaluate:  

`abs($a - $b) - abs($c - $d) = `$answerbox

// === ANSWER ===
The absolute value symbols act like a set of parentheses, so we should evaluate the expressions inside the absolute value symbols first:
<table>
  <tr><td align=right>`abs($a-$b)-abs($c-$d)`</td><td align=center>`=`</td><td align=left>`abs($ab) - abs($cd)`</td><td></td></tr>
  <tr><td colspan=4>Now find the absolute values:</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$absab - $abscd`</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$answer`</td></tr>
</table>
