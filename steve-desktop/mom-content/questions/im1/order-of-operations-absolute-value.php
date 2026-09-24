// === NAME - DESCRIPTION: Order of Operations with Absolute Value - evaluate a sum of two absolute values, one of which flips a negative ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$a = rand(1, 9)
$b = $a + rand(1, 9)
$c = rand(2, 10)
$dmax = $c - 1
$d = rand(1, $dmax)
$e = rand(2, 9)

$diff1 = $a - $b
$diff2 = $c - $d
$abs1 = abs($diff1)
$abs2 = abs($diff2)
$prod = $abs2 * $e
$answer = $abs1 + $prod

$solutionguide = "<p>Absolute value bars are grouping symbols, so evaluate inside each pair first.</p><p>In the first pair, `$a - $b = $diff1`, which is negative. Absolute value reports distance from zero, so it comes back positive: `abs($a - $b) = $abs1`.</p><p>In the second pair, `$c - $d = $diff2`, which is already positive, so `abs($c - $d) = $abs2`.</p><p>Multiplication comes before the addition: `$abs2 xx $e = $prod`.</p><p>Add: `$abs1 + $prod = $answer`.</p><p>The first difference is negative on every version of this question, so the bars always have work to do.</p>"

// === QUESTION TEXT ===

<p>Evaluate the expression.</p>

<p>`abs($a - $b) + abs($c - $d) * $e` = $answerbox</p>

// === ANSWER ===

$solutionguide
