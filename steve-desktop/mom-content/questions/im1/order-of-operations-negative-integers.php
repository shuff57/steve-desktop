// === NAME - DESCRIPTION: Order of Operations with Negative Integers - evaluate a negative product added to a negative and then reduced ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$a = rand(1, 12)
$b = rand(1, 12)
$c = rand(1, 12)
$d = rand(1, 12)

$bc = $b * $c
$answer = -$a - $bc - $d

$step1 = -$a - $bc

$solutionguide = "<p>The multiplication happens before the additions and subtractions. A negative times a positive is negative: `(-$b) xx $c = -$bc`.</p><p>Now the expression reads `-$a + (-$bc) - $d`. Adding a negative moves left on the number line: `-$a + (-$bc) = $step1`.</p><p>Subtracting $d moves left again: `$step1 - $d = $answer`.</p><p>Every term here pushes the result further below zero, so the answer is negative.</p>"

// === QUESTION TEXT ===

<p>Evaluate the expression.</p>

<p>`-$a + (-$b) * $c - $d` = $answerbox</p>

// === ANSWER ===

$solutionguide
