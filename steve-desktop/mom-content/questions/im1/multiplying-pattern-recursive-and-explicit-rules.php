// === NAME - DESCRIPTION: Multiplying Pattern - Recursive and Explicit Rules - from a block pattern that multiplies, give the constant ratio, choose the recursive rule, write the explicit rule, and use it to reach a far term ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = "number,choices,numfunc,number"
$answerboxsize = [5,0,18,9]
$answeights = [1,1,3,2]

/* Growing, Growing Dots (book 1.2.3), drawn as blocks to match the house
   block-pattern questions. Structure t holds $a * $r^t blocks.
   Largest DRAWN figure is 2*3^2 = 18 blocks; the far term is asked by rule, not by counting. */
$color = randfrom("transgreen,transred,transpurple,transpink")
$a = rand(1,3)
$r = rand(2,3)
$far = rand(7,10)

$cols = 3

for ($i=0..2) {
  $n[$i] = $a * $r^$i
  $rows[$i] = ceil($n[$i]/$cols)
  $code[$i] = "fill='$color';"
  $placed = 0
  for ($row=0..$rows[$i]-1) {
    for ($col=0..$cols-1) {
      if ($placed < $n[$i]) {
        $code[$i] .= "rect([$col,$row],[$col+1,$row+1]);"
        $placed = $placed + 1
      }
    }
  }
  $description[$i] = "Structure at t equals $i, made of $n[$i] shaded square blocks."
  $im[$i] = showasciisvg("setBorder(12); initPicture(-0.5,$cols+0.5,-0.5,$rows[$i]+0.5); $code[$i]; text([$cols/2,0],'t = $i','below');",120,120*(($rows[$i]+1)/($cols+1)),$description[$i])
}

$farvalue = $a * $r^$far

$answer[0] = $r

$questions[1] = array("Start at $a, then multiply by $r each minute.","Start at $a, then add $r each minute.","Start at $r, then multiply by $a each minute.","Start at $a, then add $r more than you added the minute before.")
$answer[1] = 0

$answerformat[2] = "equation"
$variables[2] = "t,N"
$answer[2] = "N = $a * $r^t"

$answer[3] = $farvalue
$abstolerance[3] = 0.5

$solutionguide = "<p><b>The constant ratio.</b> Divide each count by the one before it: `$n[1] div $a = $r` and `$n[2] div $n[1] = $r`. Same number both times, so the constant ratio is `$r`.</p><p><b>The recursive rule</b> says where to start and what to do to get the next term: start at `$a`, multiply by `$r` each minute. Recursive rules are the quick way to the <i>next</i> term and a slow way to a far one &mdash; to reach `t=$far` you would multiply `$far` separate times.</p><p><b>The explicit rule</b> goes straight to any term. Starting value `$a`, multiplied by `$r` once per minute, gives `N = $a * $r^t`. The starting value is the multiplier out front and the constant ratio is the base of the exponent &mdash; not the other way round.</p><p><b>The far term.</b> `N($far) = $a * $r^$far = $farvalue`. This is the whole reason the explicit rule is worth writing down.</p>"

// === QUESTION TEXT ===
<p>A structure is built out of square blocks, one new structure each minute. Here are the first three:</p>

<p style="text-align:center">$im[0] &nbsp;&nbsp; $im[1] &nbsp;&nbsp; $im[2]</p>

<p>What is the constant ratio of this pattern?</p>

<p>Answer: $answerbox[0]</p>

///

<p>Which of these is the <b>recursive</b> rule for this pattern?</p>

$answerbox[1]

///

<p>Write the <b>explicit</b> rule. Use `t` for the number of minutes and start your answer with `N =` .</p>

<p>Answer: $answerbox[2]</p>

///

<p>Use your explicit rule to find the number of blocks in the structure at `t = $far` minutes.</p>

<p>Answer: $answerbox[3]</p>

// === ANSWER ===
$solutionguide
