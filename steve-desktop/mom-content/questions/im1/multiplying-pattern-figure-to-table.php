// === NAME - DESCRIPTION: Multiplying Pattern - Figure to Table - count a block pattern that grows by multiplying, complete the table of values, and state the constant ratio ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = "number,number,number,number,choices"
$answerboxsize = [5,5,5,5,5]

/* Growing, Growing Dots (book 1.2.3), drawn as blocks so it matches the house
   block-pattern questions. Structure t holds $a * $r^t blocks.
   $a in {1,2} and $r in {2,3} keeps the largest DRAWN figure at 2*3^2 = 18 blocks,
   while the table still runs out to t = 4. */
$color = randfrom("transgreen,transred,transpurple,transpink")
$a = rand(1,2)
$r = rand(2,3)

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
  $description[$i] = "Structure number $i, made of $n[$i] shaded square blocks."
  $im[$i] = showasciisvg("setBorder(12); initPicture(-0.5,$cols+0.5,-0.5,$rows[$i]+0.5); $code[$i]; text([$cols/2,0],'t = $i','below');",120,120*(($rows[$i]+1)/($cols+1)),$description[$i])
}

$t3 = $a * $r^3
$t4 = $a * $r^4

$answer[0] = $n[2]
$answer[1] = $t3
$answer[2] = $t4
$answer[3] = $r

$questions[4] = array("It grows by adding the same number each minute.","It grows by multiplying by the same number each minute.","It grows by adding a bigger number each minute than the minute before, with no constant multiplier.")
$answer[4] = 1

$solutionguide = "<p>Read the count off each figure first: at `t=0` there are `$a` blocks, at `t=1` there are `$n[1]`, at `t=2` there are `$n[2]`.</p><p>Divide each count by the one before it: `$n[1] div $a = $r` and `$n[2] div $n[1] = $r`. The same number every time, so the <b>constant ratio</b> is `$r`.</p><p>Keep multiplying to extend the table: `$n[2] * $r = $t3` at `t=3`, and `$t3 * $r = $t4` at `t=4`.</p><p>This is what makes it a multiplying pattern rather than an adding one. An adding pattern has a constant <i>difference</i> &mdash; you would subtract to find it. Here the differences are `$n[1]-$a`, then `$n[2]-$n[1]`, which are not equal, so there is no constant difference to find.</p>"

// === QUESTION TEXT ===
<p>Someone is building structures out of square blocks. Here are the first three, at `t=0`, `t=1` and `t=2` minutes:</p>

<p style="text-align:center">$im[0] &nbsp;&nbsp; $im[1] &nbsp;&nbsp; $im[2]</p>

<p>Count the blocks in the figures and use the pattern to fill in the rest of the table.</p>

<table class="stats">
  <tbody>
    <tr><td>`t` (minutes)</td><td>0</td><td>1</td><td>2</td><td>3</td><td>4</td></tr>
    <tr><td>blocks</td><td>$a</td><td>$n[1]</td><td>$answerbox[0]</td><td>$answerbox[1]</td><td>$answerbox[2]</td></tr>
  </tbody>
</table>

///

<p>What is the <b>constant ratio</b> of this pattern? (What do you multiply by to get from one structure to the next?)</p>

<p>Answer: $answerbox[3]</p>

///

<p>Which sentence describes how this pattern grows?</p>

$answerbox[4]

// === ANSWER ===
$solutionguide
