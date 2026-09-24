// === NAME - DESCRIPTION: Evaluate a numeric expression that combines a negative exponent with a zero power ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$a = rand(2, 5)
$m = rand(1, 3)
$b = rand(2, 5)
$c = rand(1, 9)
$apow = $a^$m
$num = 1 + $c * $apow
$den = $apow
$answer = "$num/$den"
$abstolerance = 0.001

$solutionguide = '<p>Work the two powers first, then add.</p><p>The negative exponent gives a reciprocal: `' . $a . '^(-' . $m . ') = 1/' . $a . '^' . $m . ' = 1/' . $apow . '`.</p><p>The zero power equals 1, so `' . $b . '^0 xx ' . $c . ' = 1 xx ' . $c . ' = ' . $c . '`.</p><p>Now add the two pieces. Write ' . $c . ' as a fraction with denominator ' . $apow . ': `1/' . $apow . ' + ' . $c . ' = 1/' . $apow . ' + ' . ($c * $apow) . '/' . $apow . ' = ' . $num . '/' . $den . '`.</p><p>The answer is `' . $num . '/' . $den . '`, which is already in lowest terms. Enter it as a fraction or as its decimal, either is accepted.</p>'

// === QUESTION TEXT ===
<p>Evaluate `$a^(-$m) + $b^0 xx $c`.</p>

<p>$answerbox</p>

// === ANSWER ===
$solutionguide
