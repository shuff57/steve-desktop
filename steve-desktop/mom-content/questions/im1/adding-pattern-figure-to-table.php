// === NAME - DESCRIPTION: Adding Pattern - Figure to Table - count a block pattern that grows by adding, complete the table of values, state the constant difference, and write the explicit rule ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = "number,number,number,numfunc,choices"
$answerboxsize = [5,5,5,18,0]
$answeights = [1,1,1,3,1]

/* Growing Dots (book 1.2.2), drawn as blocks. Structure t holds $m*t + $b blocks:
   a central column of $b, with $m new blocks added each minute.
   Largest DRAWN figure is 4*2+5 = 13 blocks, so the picture stays countable. */
$color = randfrom("transgreen,transred,transpurple,transpink")
$m = rand(2,4)
$b = rand(1,5)
$far = rand(20,40)

$cols = 5

for ($i=0..2) {
  $n[$i] = $m*$i + $b
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
  $im[$i] = showasciisvg("setBorder(12); initPicture(-0.5,$cols+0.5,-0.5,$rows[$i]+0.5); $code[$i]; text([$cols/2,0],'t = $i','below');",150,150*(($rows[$i]+1)/($cols+1)),$description[$i])
}

$t3 = $m*3 + $b
$t4 = $m*4 + $b
$farvalue = $m*$far + $b

$answer[0] = $t3
$answer[1] = $t4
$answer[2] = $m

$answerformat[3] = "equation"
$variables[3] = "t,B"
$answer[3] = "B = $m * t + $b"

$questions[4] = array("Divide each count by the one before it and check the answers match.","Subtract each count from the one after it and check the answers match.","Add the first two counts together.")
$answer[4] = 1

$solutionguide = "<p>Read the counts off the figures: `$b` blocks at `t=0`, `$n[1]` at `t=1`, `$n[2]` at `t=2`.</p><p><b>The constant difference.</b> Subtract consecutive counts: `$n[1] - $b = $m` and `$n[2] - $n[1] = $m`. The same number each time, so the pattern grows by <b>adding `$m`</b> every minute. That subtraction is the test: dividing is the test for a <i>multiplying</i> pattern, and on this table it would not give the same answer twice.</p><p><b>Extending the table.</b> Keep adding `$m`: `$n[2] + $m = $t3` at `t=3`, and `$t3 + $m = $t4` at `t=4`.</p><p><b>The explicit rule.</b> The count starts at `$b` when `t=0` and gains `$m` per minute, so `B = $m t + $b`. In the picture, `$m` is the blocks added each minute and `$b` is the part that was there before the pattern started growing: it never moves.</p><p>Check it against a figure you can see: `B(2) = $m xx 2 + $b = $n[2]`, which matches the third picture.</p>"

// === QUESTION TEXT ===
<p>Someone is building structures out of square blocks, one new structure each minute. Here are the first three:</p>

<p style="text-align:center">$im[0] &nbsp;&nbsp; $im[1] &nbsp;&nbsp; $im[2]</p>

<p>Count the blocks in the figures and use the pattern to fill in the rest of the table.</p>

<table class="stats">
  <tbody>
    <tr><td>`t` (minutes)</td><td>0</td><td>1</td><td>2</td><td>3</td><td>4</td></tr>
    <tr><td>blocks</td><td>$b</td><td>$n[1]</td><td>$n[2]</td><td>$answerbox[0]</td><td>$answerbox[1]</td></tr>
  </tbody>
</table>

///

<p>What is the <b>constant difference</b> of this pattern? (How many blocks are added each minute?)</p>

<p>Answer: $answerbox[2]</p>

///

<p>Write the <b>explicit rule</b> for the number of blocks `B` after `t` minutes. Start your answer with `B =` .</p>

<p>Answer: $answerbox[3]</p>

///

<p>How would you check that a pattern grows by adding rather than by multiplying?</p>

$answerbox[4]

// === ANSWER ===
$solutionguide
