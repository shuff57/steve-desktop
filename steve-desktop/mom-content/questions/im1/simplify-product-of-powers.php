// === NAME - DESCRIPTION: Simplify a product of powers with the same base by adding the exponents ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===
$m = rand(-4, 5)
$n = rand(-4, 5)
$sum = $m + $n
$answer = $sum

$solutionguide = '<p>When you multiply two powers that share the same base, the exponents add. The base `x` does not change.</p><p>`x^' . $m . ' * x^' . $n . ' = x^(' . $m . ' + ' . $n . ') = x^' . $sum . '`</p><p>So `k = ' . $sum . '`.</p>'

// === QUESTION TEXT ===
<p>Simplify `x^$m * x^$n` to the form `x^k`. What is `k`?</p>

<p>`k =` $answerbox</p>

// === ANSWER ===
$solutionguide
