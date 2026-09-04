// === NAME - DESCRIPTION: Find the Mistake in a Growing Pattern Rule - a worked count of an X-shaped block pattern double-counts the shared centre; identify the step, give the corrected rule, and name the habit ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "numfunc", "choices")
$noshuffle[0] = "all"
$answerboxsize = [0,16,0]
$answeights = [1,2,1]

/* Book 1.2, problem 21. The X pattern is two diagonals crossing at one shared centre
   block. Each diagonal is 2*$k*t + 1 blocks long. A student who counts each diagonal
   in full and doubles has counted the centre block TWICE.
       student:  2(2kt + 1)        = 4kt + 2     <-- wrong
       correct:  2(2kt + 1) - 1    = 4kt + 1
   Only Step 3 is wrong; Steps 1 and 2 are true on every seed. */
$color = randfrom("transgreen,transred,transpurple,transpink")
$k = rand(1,2)
$tshown = 2

$arm = $k * $tshown
$diaglen = 2*$arm + 1
$span = $arm + 1

$twok = 2*$k
$fourk = 4*$k
$wrongconst = 2
$rightconst = 1

/* draw the X at t = $tshown: a shared centre block plus $arm blocks along each of four diagonals */
$code = "fill='$color';"
$code .= "rect([-0.5,-0.5],[0.5,0.5]);"
for ($j=1..$arm) {
  $code .= "rect([$j-0.5,$j-0.5],[$j+0.5,$j+0.5]);"
  $code .= "rect([-$j-0.5,$j-0.5],[-$j+0.5,$j+0.5]);"
  $code .= "rect([$j-0.5,-$j-0.5],[$j+0.5,-$j+0.5]);"
  $code .= "rect([-$j-0.5,-$j-0.5],[-$j+0.5,-$j+0.5]);"
}
$alt = "An X made of shaded square blocks: one centre block with $arm blocks running out from it along each of the four diagonals."
$im = showasciisvg("setBorder(12); initPicture(-$span-0.5,$span+0.5,-$span-0.5,$span+0.5); $code;",170,170,$alt)

$step1 = "Each diagonal is `$twok t + 1` blocks long."
$step2 = "The X is made of 2 diagonals."
$step3 = "So the total is `2($twok t + 1) = $fourk t + $wrongconst`."

$questions[0] = array("Step 1","Step 2","Step 3")
$answer[0] = 2

$answerformat[1] = "equation"
$variables[1] = "t,B"
$answer[1] = "B = $fourk * t + $rightconst"

$questions[2] = array(
  "Counting the block where the two diagonals overlap twice, once for each diagonal",
  "Multiplying the arm length by 4 instead of by 2",
  "Forgetting that the pattern starts at `t = 0` rather than `t = 1`",
  "Adding the two diagonals instead of multiplying them"
)
$answer[2] = 0

$solutionguide = "<p><b>Steps 1 and 2 are both fine.</b> Each diagonal really is `$twok t + 1` blocks, `$k t` going out each way plus the centre, and there really are two diagonals.</p><p><b>Step 3 is where it goes wrong.</b> The two diagonals are not separate piles of blocks: they <i>cross</i>, and the block in the middle belongs to both. Counting `2($twok t + 1)` counts that one centre block once for each diagonal, so the total is too big by exactly 1, every time, on every figure.</p><p>The corrected rule is `B = 2($twok t + 1) - 1 = $fourk t + $rightconst`.</p><p><b>Check it against the picture.</b> At `t = $tshown` the figure above has `$arm` blocks on each of the four arms plus one centre, which is `4 xx $arm + 1`. The wrong rule would predict one more block than is actually there.</p><p>The habit worth naming: whenever two parts of a figure <b>share</b> a piece, adding the parts up counts the shared piece twice. Subtract the overlap once.</p>"

// === QUESTION TEXT ===
<p>Here is a block pattern shaped like an X. It is made of two diagonals that cross at a single shared block in the middle. This is the figure at `t = $tshown` minutes:</p>

<p style="text-align:center">$im</p>

<p>A student works out a rule for the number of blocks `B` at `t` minutes:</p>

<ol>
  <li>$step1</li>
  <li>$step2</li>
  <li>$step3</li>
</ol>

<p>One of those three steps is wrong. Which one?</p>

$answerbox[0]

///

<p>Write the <b>corrected</b> rule. Use `t` for the minutes and start your answer with `B =` .</p>

<p>Answer: $answerbox[1]</p>

///

<p>What habit caused the mistake?</p>

$answerbox[2]

// === ANSWER ===
$solutionguide
