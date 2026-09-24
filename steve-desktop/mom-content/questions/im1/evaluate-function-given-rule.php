// === NAME - DESCRIPTION: Evaluate a linear function given its rule - substitute two different inputs into f(x) = ax + b ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("number", "number")
$answerboxsize = [8, 8]
$answeights = [1, 1]

$a = rand(2, 9)
$b = nonzerorand(-10, 10)
$x1, $x2 = diffrands(-6, 6, 2)

$rule = makepretty("$a x + $b")
$ax1 = $a * $x1
$ax2 = $a * $x2
$fx1 = $ax1 + $b
$fx2 = $ax2 + $b

$answer[0] = $fx1
$answer[1] = $fx2

$solutionguide = "<p><b>(a)</b> Replace every `x` in the rule with $x1: `f($x1) = $a($x1) + $b`. Multiply first, then add: `$a($x1) = $ax1`, and `$ax1 + $b = $fx1`.</p><p><b>(b)</b> The same rule, a different input. Replace `x` with $x2: `f($x2) = $a($x2) + $b`. Multiply first: `$a($x2) = $ax2`, then `$ax2 + $b = $fx2`.</p><p>The rule does not change between the two parts. Only the input changes, so each part is the same two steps with a different number in the same place.</p>"

// === QUESTION TEXT ===
<p>Consider the function `f(x) = $rule`.</p>

<p><b>(a)</b> Find `f($x1)`. $answerbox[0]</p>

///

<p><b>(b)</b> Find `f($x2)`. $answerbox[1]</p>

// === ANSWER ===
$solutionguide
