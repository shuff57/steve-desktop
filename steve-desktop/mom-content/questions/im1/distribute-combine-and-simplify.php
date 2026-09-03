// === NAME - DESCRIPTION: Distribute, Combine and Simplify - expand a product, subtract a parenthesised expression from it, then clear a fraction by its denominator, as one continuous piece of work ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* Book 0.5. Three steps of one procedure rather than three separate drills: expand, then
   subtract a parenthesised expression from what you expanded, then clear a denominator.
   Part (b) is the one that catches people. The minus sign in front of the parentheses has
   to reach BOTH terms, and a student who distributes it to the first term only lands on a
   predictable wrong answer rather than a random one. */
$anstypes = array("numfunc", "numfunc", "numfunc")
$noshuffle[0] = "all"
$answerboxsize = [18,18,18]
$answeights = [1,2,1]

$a = rand(2, 6)
$b = rand(2, 7)
$c = rand(2, 9)
$d = rand(2, 7)
$e = rand(2, 9)

$ab = $a * $b
$ac = $a * $c

$coef = $ab - $d
$const = $ac - $e

$k = rand(2, 6)
$m = rand(2, 8)

$variables[0] = "x"
$variables[1] = "x"
$variables[2] = "x"

$answer[0] = "$ab*x + $ac"
$answer[1] = "$coef*x + $const"
$answer[2] = "$k*x + $m"

$wrongconst = $ac + $e

$solutionguide = "<p><b>(a)</b> Distributing means the $a multiplies <i>both</i> terms inside: `$a($b x + $c) = $ab x + $ac`.</p><p><b>(b)</b> Now subtract `($d x + $e)` from that. The minus sign in front of the parentheses applies to both terms as well, so `-($d x + $e)` becomes `-$d x - $e`:</p><p style='margin-left:1.5em'>`$ab x + $ac - $d x - $e = $coef x + $const`</p><p>The common wrong answer here is `$coef x + $wrongconst`, which comes from subtracting the `$d x` but then <i>adding</i> the $e, because the minus was only carried to the first term. A subtraction sign in front of parentheses is a `-1` being distributed; it reaches everything inside.</p><p><b>(c)</b> Multiplying a fraction by its own denominator cancels the denominator completely: `($k x + $m)/$k xx $k`. The two `$k`s divide out and the numerator is left whole, so the answer is `$k x + $m`. Both terms of the numerator survive the division.</p>"

// === QUESTION TEXT ===
<p>Work through the three steps. Use `x` in every answer.</p>

<p><b>(a)</b> Expand: `$a($b x + $c)` = $answerbox[0]</p>

///

<p><b>(b)</b> Now subtract `($d x + $e)` from your answer to (a), and combine like terms:</p>

<p style="margin-left:1.5em">`$a($b x + $c) - ($d x + $e)` = $answerbox[1]</p>

///

<p><b>(c)</b> Simplify by clearing the denominator:</p>

<p style="margin-left:1.5em">`(($k x + $m)/$k) * $k` = $answerbox[2]</p>

// === ANSWER ===
$solutionguide
