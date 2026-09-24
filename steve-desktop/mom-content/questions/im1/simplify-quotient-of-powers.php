// === NAME - DESCRIPTION: Simplify a quotient of powers with the same base by subtracting the exponents ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===
$m = rand(-3, 6)
$n = rand(-3, 6)
$diff = $m - $n
$answer = $diff

$solutionguide = '<p>When you divide two powers that share the same base, the exponents subtract. Subtract the exponent in the denominator from the exponent in the numerator, and keep the base `x`.</p><p>`x^' . $m . ' -: x^' . $n . ' = x^(' . $m . ' - (' . $n . ')) = x^' . $diff . '`</p><p>So `k = ' . $diff . '`. A negative result is allowed here, and it means the power belongs in the denominator.</p>'

// === QUESTION TEXT ===
<p>Simplify `x^$m -: x^$n` to the form `x^k`. What is `k`?</p>

<p>`k =` $answerbox</p>

// === ANSWER ===
$solutionguide
