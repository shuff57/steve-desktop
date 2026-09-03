// === NAME - DESCRIPTION: Find the Mistake in Simplifying a Fraction - a worked simplification divides only the first term of the numerator by the denominator; identify the step, give the corrected expression, and name the habit ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* Book 0.5. The planted error is the most durable one in the whole chapter: dividing a sum by
   a number and only carrying the division to the first term. The seed guarantees $a divides
   $b exactly, so the corrected constant is a whole number and part (b) has one unambiguous
   answer.

       student:   ($a x + $b)/$a  ->  x + $b      <-- wrong, $b never divided
       correct:   ($a x + $b)/$a  ->  x + $k      where $b = $a * $k

   The error is in the LAST step on purpose. Steps 1 and 2 are true on every seed and nothing
   downstream inherits the mistake, so part (a) has exactly one defensible answer. */
$anstypes = array("choices", "numfunc", "choices")
$noshuffle[0] = "all"
$answerboxsize = [0,16,0]
$answeights = [1,2,1]

$a = rand(2, 6)
$k = rand(2, 8) where ($k != $a)
$b = $a * $k

$step1 = "Split the fraction over the sum: `($a x + $b)/$a = ($a x)/$a + $b/$a`."
$step2 = "The first piece simplifies: `($a x)/$a = x`."
$step3 = "The second piece is `$b`, so the simplified form is `x + $b`."

$questions[0] = array("Step 1", "Step 2", "Step 3")
$answer[0] = 2

$variables[1] = "x"
$answer[1] = "x + $k"

$questions[2] = array(
  "Dividing only the first term of the numerator by $a and leaving the second term alone",
  "Splitting the fraction over the sum, which is not allowed",
  "Cancelling the `x` instead of the $a",
  "Multiplying by $a instead of dividing by it"
)
$answer[2] = 0

$solutionguide = "<p><b>Step 1 is fine</b>, and it is worth saying so, because a lot of people think it is the illegal move. Dividing a <i>sum</i> by a number really does split: `(p + q)/r = p/r + q/r`. (What does not split is a sum in the <i>denominator</i>: `r/(p+q)` is not `r/p + r/q`.)</p><p><b>Step 2 is fine.</b> `($a x)/$a` really is `x`.</p><p><b>Step 3 is where it breaks.</b> The second piece is `$b/$a`, and that has not been worked out. It was written down as `$b`, as though the denominator only applied to the first term. Since `$b = $a xx $k`, the second piece is `$b/$a = $k`.</p><p>The corrected simplified form is `x + $k`.</p><p><b>Check it.</b> Multiply the answer back by $a: `$a(x + $k) = $a x + $b`, which is the numerator we started with. Doing the same to the student's answer gives `$a(x + $b) = $a x + " . ($a * $b) . "`, which is not.</p><p>The habit worth naming: a denominator under a sum divides <b>every</b> term, including the ones it does not visibly cancel with. The `$a` was easy to see against the `$a x` and easy to forget against the `$b`.</p>"

// === QUESTION TEXT ===
<p>A student simplifies `($a x + $b)/$a` and shows this work:</p>

<ol>
  <li>$step1</li>
  <li>$step2</li>
  <li>$step3</li>
</ol>

<p>One of those three steps is wrong. Which one?</p>

$answerbox[0]

///

<p>Write the <b>correctly</b> simplified expression. Use `x`.</p>

<p>Answer: $answerbox[1]</p>

///

<p>What habit caused the mistake?</p>

$answerbox[2]

// === ANSWER ===
$solutionguide
