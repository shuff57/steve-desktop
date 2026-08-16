// === NAME - DESCRIPTION: Find the formula for an arithmetic sequence given the first term `a_1` and the common difference `d`.  Help video from James Sousa. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$variables = "n"

$a1 = rand(-7,15) where (abs($a1)>1)
$d = rand(-3,5) where (abs($d)>1)

$b = $a1 - $d

$seq = makexxpretty("$a1 + $d(n-1)")
$simp = makexxpretty("$b n + $a1")

$answer = $seq

$showanswer = "`t(n) = $d n + $b`"
$answerboxsize = 20

// === QUESTION TEXT ===
Find a formula for the general term of an arithmetic sequence where `a_1=$a1` and  `d=$d` (common difference).
<div style="margin:1.2em;">`t(n) = `$answerbox</div>
