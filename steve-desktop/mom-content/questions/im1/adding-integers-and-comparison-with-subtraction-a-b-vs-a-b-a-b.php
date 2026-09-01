// === NAME - DESCRIPTION: Adding integers and comparison with subtraction:  a + (-b) vs. a - b (a > b) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes="number,number"
$a,$b=rands(10,30,2) where ($a > $b + 5)

$ans=$a-$b
$answer[0]=$ans
$answer[1]=$ans

// === QUESTION TEXT ===

Evaluate.

`$a + (-$b)` = $answerbox[0]

`$a - $b` = $answerbox[1]

// === ANSWER ===

Remember that for any real numbers, `a + (-b) = a - b`.

So `$a + (-$b) = $a - $b = $answer[0]`
