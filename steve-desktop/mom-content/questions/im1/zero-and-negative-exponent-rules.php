// === NAME - DESCRIPTION: Zero and negative exponent rules: evaluate a coefficient times a zero power, and a base raised to a negative exponent ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("numfunc", "numfunc")
$answerboxsize = [12, 12]

$a = rand(2, 9)
$c = rand(2, 9)
$answer[0] = $c

$b = rand(2, 5)
$n = rand(1, 3)
$apow = $b^$n
$answer[1] = "1/$apow"
$abstolerance[1] = 0.001

$solutionguide = '<p><b>Part (a).</b> Any nonzero base raised to the zero power equals 1, so `' . $a . '^0 = 1`. The coefficient in front stays where it is: `' . $c . ' xx ' . $a . '^0 = ' . $c . ' xx 1 = ' . $c . '`. The zero power does not make the whole expression zero, and it does not erase the ' . $c . ' in front.</p><p><b>Part (b).</b> A negative exponent means the reciprocal of the matching positive power: `' . $b . '^(-' . $n . ') = 1/' . $b . '^' . $n . '`. Since `' . $b . '^' . $n . ' = ' . $apow . '`, the value is `1/' . $apow . '`. Enter it as a fraction or as its decimal, either is accepted.</p>'

// === QUESTION TEXT ===
<p>Evaluate each expression.</p>

<p>a. `$c xx $a^0` = $answerbox[0]</p>

<p>b. `$b^(-$n)` = $answerbox[1]</p>

// === ANSWER ===
$solutionguide
