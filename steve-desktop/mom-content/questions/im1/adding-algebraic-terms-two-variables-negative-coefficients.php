// === NAME - DESCRIPTION: Adding algebraic terms (two variables, negative coefficients)  (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$x,$y=diffrandsfrom("x,y,z",2)
$variables="$x,$y"
$a,$b,$c,$d=nonzerorands(2,10,4) where ($a !=$b)

$a1=$a - $b
$a2=$c + $d
$eqn=polymakepretty("$a $x + (-$c $y) + (-$b $x) + (-$d $y)")
$requiretimes="$x,=1, $y, = 1"
$answer="$a1 $x - $a2 $y"

// === QUESTION TEXT ===
Add:

`$eqn = ` $answerbox

// === ANSWER ===
To add like terms, add the coefficient and keep the variable portion.

In the expression

`$eqn`

the terms `$a $x` and `-$b $x` are like terms, as are the terms `-$c $y` and `-$d $y`, so we can add their coefficients:

<table>
  <tr><td align=right>`$a $x + (-$c $y)+ (-$b $x) + (-$d $y)`</td><td align=center>`=`</td><td align=left>`$a $x + (-$b $x) + (-$c $y) + (-$d $y)`</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`($a -$b)$x + (-$c - $d)$y`</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$a1 $x  - $a2 $y`</td></tr>
</table>
