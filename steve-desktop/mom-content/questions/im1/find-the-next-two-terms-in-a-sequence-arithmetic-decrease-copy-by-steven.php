// === NAME - DESCRIPTION: Find the next two terms in a Sequence ARITHMETIC DECREASE (copy by Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number","number", "number","choices", "number")



$m = rand(-6,-3)
$b = rand(7,11)

$x = consecutive(0,7)
$y = calconarray($x,"$m*x+$b")

$answer[0] = $y[5]
$answer[1] = $y[6]
$answer[2] = $y[7]

$choices[3] = array("common difference","common ratio","neither")
$answer[3] = "0"

$answer[4] = "$m"

$answerboxsize = 2

// === QUESTION TEXT ===

<p>Find the next three terms of the sequence:</p>

<p>$y[0], $y[1], $y[2], $y[3], $y[4], $answerbox[0], $answerbox[1], $answerbox[2]</p>

<p>Identify if a Sequence has a Common Ratio, Common Difference, or Neither
  $answerbox[3]</p>
<p>The common difference/ratio is $answerbox[4]</p>
