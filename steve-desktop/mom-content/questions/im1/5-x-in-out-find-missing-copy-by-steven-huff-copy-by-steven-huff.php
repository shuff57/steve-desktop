// === NAME - DESCRIPTION: .5^x in-out, find missing (copy by Steven Huff) (copy by Steven Huff) ===
// === SET QUESTION TYPE TO: calculated ===

// === COMMON CONTROL ===

$b = rand(1,6)
$exp = .5
$y = array($exp^(-1+$b),$exp^($b),$exp^(1+$b),$exp^(2+$b),"`n`",$exp^(4+$b),)

$sa = showarrays("Input",array(-1,0,1,2,3,4),"Output",$y)

$answer = $exp^(3+$b)

$answerboxsize=5

// === QUESTION TEXT ===

What is `n`?

$sa
