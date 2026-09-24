// === NAME - DESCRIPTION: Order of Operations with Nested Groups - evaluate an inner difference inside an outer sum, then multiply and subtract ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$a = rand(1, 9)
$b = rand(3, 8)
$flip = rand(0, 1)
$cmax = $b - 1
$cmin = $b + 1
$c = rand(2, $cmax) if ($flip == 0)
$c = rand($cmin, 9) if ($flip == 1)
$d = rand(2, 9)
$e = rand(1, 20)

$inner = $b - $c
$outer = $a + $inner
$prod = $outer * $d
$answer = $prod - $e

$innerword = "positive" if ($inner > 0)
$innerword = "negative" if ($inner < 0)

$solutionguide = "<p>Work from the inside out. The inner parentheses hold `$b - $c`, which is $innerword: `$b - $c = $inner`.</p><p>That value replaces the inner group, so the outer parentheses now hold `$a + ($inner) = $outer`.</p><p>Multiply by $d: `$outer xx $d = $prod`.</p><p>Subtract $e: `$prod - $e = $answer`.</p><p>The inner group is not always positive. When the second number is larger, the difference is negative, and the outer sum shrinks instead of growing.</p>"

// === QUESTION TEXT ===

<p>Evaluate the expression.</p>

<p>`($a + ($b - $c)) * $d - $e` = $answerbox</p>

// === ANSWER ===

$solutionguide
