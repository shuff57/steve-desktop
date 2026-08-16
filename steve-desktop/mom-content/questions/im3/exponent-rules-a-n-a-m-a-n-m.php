// === NAME - DESCRIPTION: Exponent rules: a^n*a^m, (a^n)^m (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("number","number")

$m,$n = diffrands(2,8,2)

$answerboxsize[0]=4
$answerboxsize[1]=4

$answer[0] = $m+$n
$answer[1] = $m*$n

// === QUESTION TEXT ===
a) `x^$m * x^$n = x^p` where `p`=$answerbox[0]

b) `(x^$m)^$n = x^r` where `r`=$answerbox[1]
