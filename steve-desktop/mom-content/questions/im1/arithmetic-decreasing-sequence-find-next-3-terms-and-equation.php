// === NAME - DESCRIPTION: Arithmetic Decreasing Sequence find next 3 terms and equation (local for Martha Allen) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("number","number","number","numfunc")

$a = rand(1,30)
$d = rand(3,4)
$l[0] = $a
for ($i=1..4){
  $l[$i] = $l[$i-1]-$d
}

$answerboxsize[0] = 3
$answerboxsize[1] = 3
$answerboxsize[2] = 3
$answerformat[3] = "equation"
$answer[0] = $a-5*$d
$answer[1] = $a-6*$d
$answer[2] = $a-7*$d

$answerboxsize[3] = 6
$variables[3] = "t,n"
$answer[3] = "t(n) = -$d n+$a"

// === QUESTION TEXT ===
<p>For the following sequence, state the next three terms.</p>
<p>$l[1], $l[2], $l[3], $l[4], $answerbox[0], $answerbox[1], $answerbox[2]</p>
<p>State a formula for the nth term: $answerbox[3]</p>
