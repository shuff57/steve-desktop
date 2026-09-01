// === NAME - DESCRIPTION: Find the next two terms in a Sequence - Geometric Increasing - Identify if a Sequence has a Common Ratio, Common Difference, or Neither (copy by Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number","number","number", "choices", "number")

$a = rand(2,7)
$b = rand(2,3)
$l[0] = $a
for ($i=1..4){
  $l[$i] = $l[$i-1]*$b
}

$answerboxsize[0] = 3
$answerboxsize[1] = 3
$answerboxsize[2] = 3
$answer[0] = $a*$b^5
$answer[1] = $a*$b^6
$answer[2] = $a*$b^7





$choices[3] = array("common difference","common ratio","neither")
$answer[3] = 1

$answer[4] = "$b"

// === QUESTION TEXT ===

<p>For the following sequence, state the next three terms.</p>
<p>$l[1], $l[2], $l[3], $l[4], $answerbox[0], $answerbox[1], $answerbox[2]</p>
<p>Identify if a Sequence has a Common Ratio, Common Difference, or Neither
  $answerbox[3]</p>
<p>The common difference/ratio is $answerbox[4]</p>
