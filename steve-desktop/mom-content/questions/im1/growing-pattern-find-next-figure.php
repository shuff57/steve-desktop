// === NAME - DESCRIPTION: Growing block pattern, find the next two figures ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "number,number"
$answerboxsize = [6,6]
$a = rand(2,10)
$d = rand(1,6)
$f1 = $a
$f2 = $a + $d
$f3 = $a + 2*$d
$f4 = $a + 3*$d
$f5 = $a + 4*$d
$answer[0] = $f4
$answer[1] = $f5

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

$solutionguide = '<p>The counts are ' . $f1 . ', ' . $f2 . ', and ' . $f3 . '.</p><p><b>The constant difference.</b> ' . $f2 . ' minus ' . $f1 . ' is ' . $d . ', and ' . $f3 . ' minus ' . $f2 . ' is ' . $d . '. The same number each time, so each figure adds ' . $d . ' blocks.</p><p><b>Figure 4.</b> ' . $f3 . ' plus ' . $d . ' equals ' . $f4 . '.</p><p><b>Figure 5.</b> ' . $f4 . ' plus ' . $d . ' equals ' . $f5 . '.</p>'

// === QUESTION TEXT ===
<p>A pattern of blocks grows by the same number of blocks from one figure to the next. The first three figures are shown below.</p>
<p style="text-align:center">$im[1] $im[2] $im[3]</p>
<p>How many blocks are in Figure 4?</p>
<p>Answer: $answerbox[0]</p>

///

<p>How many blocks are in Figure 5?</p>
<p>Answer: $answerbox[1]</p>

// === ANSWER ===
$solutionguide
