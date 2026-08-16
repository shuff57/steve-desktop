// === NAME - DESCRIPTION: Basic arithmetic sequence: Identify, continue, and write the equation. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$a=rand(3,12)
$b=rand(4,8)

$T1 = $a+$b
$T2 = $a+2*$b
$T3 = $a+3*$b
$T4 = $a+4*$b
$T5 = $a+5*$b

$anstypes = array("number","number","number","numfunc")

$answerboxsize[0] = 4
$answerboxsize[1] = 4
$answerboxsize[2] = 4
$answerboxsize[3] = 30
$answerformat[3] = "equation"
$variables[3] = "t,n"
//$answerboxsize[4] = 10

$answer[0] = $a+6*$b
$answer[1] = $a+7*$b
$answer[2] = $a+8*$b
$answer[3] = "t(n)=$a+$b*n"
//$answer[4] = "arithmetic"

// === QUESTION TEXT ===
<h3> Consider the sequence `$T1`, `$T2`, `$T3`, `$T4`, `$T5`, `...`
  <h4>
    Find the next three terms:</h4>
  $answerbox[0], $answerbox[1], $answerbox[2]
  <h4>
    Write down an equation that models this <b>sequence</b>.
  </h4>
  $answerbox[3]
