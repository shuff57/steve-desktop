// === NAME - DESCRIPTION: Growing block pattern, find a later figure and write the rule ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "number,numfunc"
$answerboxsize = [6,20]
$a = rand(3,8)
$d = rand(2,5)
$c = $a - $d
$f1 = $a
$f2 = $a + $d
$f3 = $a + 2*$d
$n = rand(5,8)
$figN = $d*$n + $c
$answer[0] = $figN
if ($c >= 0) {
  $rule = "$d x + $c"
} else {
  $negc = -$c
  $rule = "$d x - $negc"
}
$answer[1] = $rule
$variables[1] = "x"

$cnt[1] = $f1
$cnt[2] = $f2
$cnt[3] = $f3
$maxf = $f3
$sq = 22
$W = $sq*$maxf + 16
$H = $sq + 38
for ($i=1..3) {
  $code = "setBorder(4); initPicture(0,$maxf,-0.8,1.2); fill='lightgreen';"
  for ($j=1..$cnt[$i]) {
    $code .= "rect([$j-1,0],[$j,1]);"
  }
  $halfcnt = $cnt[$i]/2
  $code .= "text([$halfcnt,0],'Figure " . $i . "','below');"
  $alt = "Figure " . $i . " of a growing block pattern, drawn as a row of " . $cnt[$i] . " unit squares."
  $im[$i] = showasciisvg($code,$W,$H,$alt)
}

$solutionguide = '<p>Read the counts: Figure 1 has ' . $f1 . ' blocks, Figure 2 has ' . $f2 . ', and Figure 3 has ' . $f3 . '.</p><p><b>The constant difference.</b> ' . $f2 . ' minus ' . $f1 . ' is ' . $d . ', and ' . $f3 . ' minus ' . $f2 . ' is ' . $d . '. The same number each time, so the pattern adds ' . $d . ' blocks per figure.</p><p><b>Figure ' . $n . '.</b> Start at Figure 1 with ' . $f1 . ' blocks and add ' . $d . ' for each step after that. There are ' . ($n-1) . ' steps from Figure 1 to Figure ' . $n . ', so the count is ' . $f1 . ' plus ' . $d . ' times ' . ($n-1) . ', which is ' . $figN . '.</p><p><b>The rule.</b> The count starts at ' . $f1 . ' when x is 1 and gains ' . $d . ' per figure, so y = ' . $rule . '. Check it on Figure 2: the rule gives ' . $f2 . ', which matches the figures.</p>'

// === QUESTION TEXT ===
<p>A pattern of blocks grows by the same number of blocks from one figure to the next. The first three figures are shown below.</p>
<p style="text-align:center">$im[1] $im[2] $im[3]</p>
<table class="stats">
  <tbody>
    <tr><td>Figure</td><td>1</td><td>2</td><td>3</td></tr>
    <tr><td>Blocks</td><td>$f1</td><td>$f2</td><td>$f3</td></tr>
  </tbody>
</table>
<p>How many blocks are in Figure $n?</p>
<p>Answer: $answerbox[0]</p>

///

<p>Write an expression for the number of blocks in Figure `x`.</p>
<p>Answer: $answerbox[1]</p>

// === ANSWER ===
$solutionguide
