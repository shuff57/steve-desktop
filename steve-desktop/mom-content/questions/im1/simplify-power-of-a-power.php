// === NAME - DESCRIPTION: Simplify a power raised to a power by multiplying the exponents ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===
$m = rand(2, 5)
$n = rand(2, 4)
$prod = $m * $n
$answer = $prod

$solutionguide = '<p>When a power is raised to another power, the exponents multiply. The base `x` does not change.</p><p>`(x^' . $m . ')^' . $n . ' = x^(' . $m . ' * ' . $n . ') = x^' . $prod . '`</p><p>So `k = ' . $prod . '`.</p>'

// === QUESTION TEXT ===
<p>Simplify `(x^$m)^$n` to the form `x^k`. What is `k`?</p>

<p>`k =` $answerbox</p>

// === ANSWER ===
$solutionguide
