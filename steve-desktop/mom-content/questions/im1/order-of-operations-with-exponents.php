// === NAME - DESCRIPTION: Order of Operations with Exponents - evaluate a sum, a square, and a product in the correct order ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$a = rand(1, 20)
$b = rand(2, 4)
$c = rand(2, 9)
$d = rand(2, 9)

$bsq = $b ** 2
$cd = $c * $d
$answer = $a + $bsq - $cd

$asum = $a + $bsq

$solutionguide = "<p>Exponents come before multiplication and addition, so square first: `$b^2 = $bsq`.</p><p>Then multiply: `$c xx $d = $cd`.</p><p>Now work left to right through the addition and subtraction: `$a + $bsq = $asum`, then `$asum - $cd = $answer`.</p><p>The exponent tells you how many copies of the base to multiply together, so `$b^2` means `$b xx $b`. Reading it as the base times the exponent is the mistake this order of operations is there to prevent.</p>"

// === QUESTION TEXT ===

<p>Evaluate the expression.</p>

<p>`$a + $b^2 - $c * $d` = $answerbox</p>

// === ANSWER ===

$solutionguide
