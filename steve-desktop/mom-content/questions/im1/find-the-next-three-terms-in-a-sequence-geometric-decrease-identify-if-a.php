// === NAME - DESCRIPTION: Find the Next Three Terms in a Sequence - GEOMETRIC DECREASE
Identify if a Sequence has a Common Ratio, Common Difference, or Neither (copy by Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices", "number")
$b = randfrom("2,3,4,5")
$a = rand(1,5)*$b^5
$r = 1/$b
$x = consecutive(0,7)
$y = calconarray($x,"$a*$r^x")

$answer[0] = $y[5]
$answer[1] = $y[6]
$answer[2] = $y[7]

$choices[3] = array("common difference", "common ratio", "neither")
$answer[3] = 1

$answer[4] = $r

$answerboxsize = 2

// === QUESTION TEXT ===

Find the Next Three Terms in a Sequence

Find the next three terms of the sequence:
$y[0], $y[1], $y[2], $y[3], $y[4], $answerbox[0], $answerbox[1], $answerbox[2]

Identify if a Sequence has a Common Ratio, Common Difference, or Neither
$answerbox[3]

The common difference/ratio is $answerbox[4]
