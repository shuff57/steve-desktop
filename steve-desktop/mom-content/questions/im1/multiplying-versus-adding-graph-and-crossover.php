// === NAME - DESCRIPTION: Multiplying Versus Adding - Graph and Crossover - compare a multiplying pattern with an adding one, find the minute the multiplying one first passes it, and identify the shape of each graph ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = "number,number,number,choices,choices"
$answerboxsize = [5,7,7,0,0]
$answeights = [2,1,1,1,2]

/* Book 1.2.3 / problem 11: Growing Dots (adding) against Growing, Growing Dots
   (multiplying). The multiplying one starts BEHIND and overtakes.
   Adding:      D(t) = $m*t + $b
   Multiplying: N(t) = $a * $r^t
   Seeds are chosen so the crossover lands at an integer minute inside 1..7 and the
   two counts are never equal at the crossover, so "first passes" is unambiguous. */
$sc_m = array(4, 4, 5, 3, 6, 5)
$sc_b = array(1, 2, 2, 4, 1, 3)
$sc_a = array(1, 1, 1, 2, 1, 1)
$sc_r = array(2, 3, 2, 2, 3, 3)

$picked = jointrandfrom($sc_m, $sc_b, $sc_a, $sc_r)
$m = $picked[0]
$b = $picked[1]
$a = $picked[2]
$r = $picked[3]

/* Walk t upward until the multiplying count is strictly greater. */
$cross = 0
$found = 0
for ($t=1..12) {
  $dv = $m*$t + $b
  $nv = $a * $r^$t
  if ($found == 0 && $nv > $dv) {
    $cross = $t
    $found = 1
  }
}

$prev = $cross - 1
$dcross = $m*$cross + $b
$ncross = $a * $r^$cross
$dprev = $m*$prev + $b
$nprev = $a * $r^$prev

for ($t=0..5) {
  $drow[$t] = $m*$t + $b
  $nrow[$t] = $a * $r^$t
}

$answer[0] = $cross
$answer[1] = $dcross
$answer[2] = $ncross

$questions[3] = array("A straight line.","A curve that bends upward, getting steeper as it goes.","A curve that bends downward, flattening out.","A flat horizontal line.")
$answer[3] = 1

$questions[4] = array("The multiplying pattern was behind at first because it starts smaller, but multiplying beats adding once the numbers get big enough.","The multiplying pattern was behind because it grows more slowly, and it only passes by luck of these particular numbers.","The two patterns cross because the adding pattern eventually stops growing.","The multiplying pattern is always ahead; the table just starts too late to show it.")
$answer[4] = 0

$solutionguide = "<p><b>Where they cross.</b> Read the two rows across. At `t=$prev` the adding pattern has `$dprev` and the multiplying pattern has `$nprev`, so the adding one is still ahead. At `t=$cross` the adding pattern has `$dcross` and the multiplying pattern has `$ncross`: that is the first minute the multiplying pattern is in front.</p><p><b>Why it happens.</b> Adding `$m` each minute moves the first pattern up by the same step every time, so its graph is a straight line. Multiplying by `$r` each minute makes every step bigger than the one before it, so its graph is a curve that keeps getting steeper. A constant step can win early, but a growing step wins eventually: and once it does, it never gives the lead back.</p><p>This is the real difference between the two kinds of pattern, and it is why the shape of the graph is worth looking at and not just the numbers.</p>"

// === QUESTION TEXT ===
<p>Two patterns start at the same time, one minute apart from each other in style.</p>

<ul>
  <li><b>Pattern A</b> grows by <b>adding</b>: it starts at `$b` blocks and adds `$m` blocks every minute.</li>
  <li><b>Pattern B</b> grows by <b>multiplying</b>: it starts at `$a` blocks and multiplies by `$r` every minute.</li>
</ul>

<table class="stats">
  <tbody>
    <tr><td>`t` (minutes)</td><td>0</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr>
    <tr><td>Pattern A (adding)</td><td>$drow[0]</td><td>$drow[1]</td><td>$drow[2]</td><td>$drow[3]</td><td>$drow[4]</td><td>$drow[5]</td></tr>
    <tr><td>Pattern B (multiplying)</td><td>$nrow[0]</td><td>$nrow[1]</td><td>$nrow[2]</td><td>$nrow[3]</td><td>$nrow[4]</td><td>$nrow[5]</td></tr>
  </tbody>
</table>

<p>At which minute does Pattern B <b>first</b> have more blocks than Pattern A?</p>

<p>Answer: `t =` $answerbox[0]</p>

///

<p>At that minute, how many blocks does <b>Pattern A</b> have?</p>

<p>Answer: $answerbox[1]</p>

///

<p>And how many does <b>Pattern B</b> have?</p>

<p>Answer: $answerbox[2]</p>

///

<p>If you plotted <b>Pattern B</b> on a graph, what shape would you get?</p>

$answerbox[3]

///

<p>Pattern B was behind for the first few minutes and then overtook Pattern A. Which explanation is right?</p>

$answerbox[4]

// === ANSWER ===
$solutionguide
