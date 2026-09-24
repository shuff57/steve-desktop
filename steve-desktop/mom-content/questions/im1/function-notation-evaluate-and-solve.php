// === NAME - DESCRIPTION: Function notation, evaluate and solve - find f(c) and find the input that gives a stated output ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("number", "number")
$answerboxsize = [8, 8]
$answeights = [1, 1]

$a = rand(2, 9)
$b = nonzerorand(-10, 10)
$c, $x0 = diffrands(-6, 6, 2)

$rule = makepretty("$a x + $b")
$ac = $a * $c
$fc = $ac + $b
$d = $a * $x0 + $b
$bopcap = ifthen($b > 0, "Subtract", "Add")
$babs = abs($b)
$dminusb = $d - $b

$answer[0] = $fc
$answer[1] = $x0

$solutionguide = "<p><b>(a)</b> Replace every `x` in the rule with $c: `f($c) = $a($c) + $b`. Multiply first, then add: `$a($c) = $ac`, and `$ac + $b = $fc`.</p><p><b>(b)</b> The output is $d, so start from `$a x + $b = $d`. $bopcap $babs from both sides: `$a x = $dminusb`. Divide both sides by $a: `x = $x0`. Check it: `f($x0) = $a($x0) + $b = $d`, which matches the output you were given.</p>"

// === QUESTION TEXT ===
<p>Consider the function `f(x) = $rule`.</p>

<p><b>(a)</b> Find `f($c)`. $answerbox[0]</p>

///

<p><b>(b)</b> Find the value of `x` for which `f(x) = $d`. $answerbox[1]</p>

// === ANSWER ===
$solutionguide
