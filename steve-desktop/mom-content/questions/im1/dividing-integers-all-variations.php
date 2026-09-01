// === NAME - DESCRIPTION: Dividing Integers (all variations) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes="number,number,number,number"

$a,$b=diffrands(2,9,2)
$p=$a*$b

$answer[0]=$b
$answer[1]=-$b
$answer[2]=-$b
$answer[3]=$b

// === QUESTION TEXT ===

Divide:  

`$p div $a` = $answerbox[0]

`$p div (-$a)` = $answerbox[1]

`-$p div $a` = $answerbox[2]

`-$p div (-$a)` = $answerbox[3]

// === ANSWER ===

When dividing signed numbers, we can compute using the (unsigned) values and determine the sign aftewrds: 

If both dividend and divisor have the same sign, the quotient is positive; while the dividend and divisor have opposite signs, the quotient is negative.

`$p div $a = $answerbox[0]`.

Since the dividend and divisor in `$p div (-$a)` have opposite signs, the quotient is negative.

So `$p div (-$a) = $answerbox[1]`.

Likewise, the dividend and divsor in `-$p div $a` have opposite signs, so `-$p div $a = $answerbox[2]`.

Finally in `-$p div (-$a)`, both dividend and divisor have the same sign, the quotient is positive, so `-$p div (-$a) = $answerbox[3]`.
