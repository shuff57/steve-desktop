// === NAME - DESCRIPTION: Order of Operations with Parentheses - evaluate a product of a sum and then subtract, following the order of operations ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$a = rand(2, 9)
$b = rand(2, 9)
$c = rand(2, 9)
$d = rand(1, 20)

$sum = $b + $c
$prod = $a * $sum
$answer = $prod - $d

$wrong = $a * $b + $c - $d

$solutionguide = "<p>Parentheses come first, so add inside them: `$b + $c = $sum`.</p><p>Then multiply: `$a xx $sum = $prod`.</p><p>Finally subtract: `$prod - $d = $answer`.</p><p>The common mistake is multiplying before adding, which gives `$a xx $b + $c - $d = $wrong`. The parentheses are what make the addition happen first.</p>"

// === QUESTION TEXT ===

<p>Evaluate the expression.</p>

<p>`$a($b + $c) - $d` = $answerbox</p>

// === ANSWER ===

$solutionguide
