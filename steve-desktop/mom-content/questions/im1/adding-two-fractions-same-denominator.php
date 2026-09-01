// === NAME - DESCRIPTION: Adding two fractions, same denominator ===
// === SET QUESTION TYPE TO: calculated ===

// === COMMON CONTROL ===

$a,$c=rands(2,10,2) where ($a+$c<20)
$b=rand(2,20) where (($a + $c)<$b)

$d=$b

$num=$a+$c
$den=$d

$answer="$num/$den"
$requiretimes="+,=0"

// === QUESTION TEXT ===

Add.  Enter your answer as a fraction.  You do not need to reduce to lowest terms.

`$a/$b + $c/$d = `$answerbox

// === ANSWER ===

To add two fractions, they have to have the same denominator.

Since the fractions already have the same denominator, we can add their numerators:
<table>
  <tr><td align=right height=50>`$a/$b + $c/$d`</td><td align=center>`=`</td><td align=left>`($a + $c)/$den`</td><td></td></tr>
  <tr><td align=right height=50></td><td align=center>`=`</td><td align=left>`$num/$den`</td><td></td></tr>
</table>
