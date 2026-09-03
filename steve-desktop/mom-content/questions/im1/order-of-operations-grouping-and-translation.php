// === NAME - DESCRIPTION: Order of Operations, Grouping and Translation - evaluate an expression, evaluate it again with grouping symbols added, then translate the grouped form into algebra ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* Book 0.1. One arc in three parts, which is the point of the item: the same three numbers
   are evaluated without grouping, then with grouping, then written symbolically. A student
   who does not know that grouping outranks the exponent gets (a) and (b) the same, and the
   pair makes that visible instead of hiding it in a single answer. */
$anstypes = array("number", "number", "numfunc")
$noshuffle[0] = "all"
$answerboxsize = [8,8,18]
$answeights = [1,1,2]

$a = rand(3, 9)
$b = rand(2, 6)
$c = rand(2, 4)

$csq = $c * $c
$plain = $a + $b * $csq
$grouped = ($a + $b) * $csq

$answer[0] = $plain
$answer[1] = $grouped

$variables[2] = "x"
$answer[2] = "($a + x) * $csq"

$sum = $a + $b

$solutionguide = "<p><b>(a)</b> With no grouping symbols, the exponent goes first, then the multiplication, then the addition. `$c^2 = $csq`, then `$b xx $csq = " . ($b * $csq) . "`, and finally `$a + " . ($b * $csq) . " = $plain`.</p><p><b>(b)</b> Parentheses outrank everything, so the addition happens first this time: `$a + $b = $sum`, then `$sum xx $csq = $grouped`.</p><p>Those two are different, $plain against $grouped, from the same three numbers. The grouping symbols are not decoration; they change which operation happens first.</p><p><b>(c)</b> &quot;The sum of `x` and $a&quot; is `x + $a`, and that whole sum is what gets multiplied, so it needs parentheses: `($a + x) xx $csq`. Writing `$a + x xx $csq` would multiply only the `x`, which is part (a)'s mistake in symbols.</p>"

// === QUESTION TEXT ===
<p>Evaluate each expression. Watch what the grouping symbols do.</p>

<p><b>(a)</b> `$a + $b * $c^2` = $answerbox[0]</p>

///

<p><b>(b)</b> Now the same three numbers, with parentheses added:</p>

<p>`($a + $b) * $c^2` = $answerbox[1]</p>

///

<p><b>(c)</b> Write an expression for this phrase, using `x` for the unknown number:</p>

<p style="margin-left:1.5em"><i>the sum of `x` and $a, all multiplied by $csq</i></p>

<p>Expression: $answerbox[2]</p>

// === ANSWER ===
$solutionguide
