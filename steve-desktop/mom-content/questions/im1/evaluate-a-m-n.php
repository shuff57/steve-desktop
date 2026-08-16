// === NAME - DESCRIPTION: Evaluate a^(m/n) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: calculated ===

// === COMMON CONTROL ===
$anstypes= "numfunc"
$a = rand(2,5)
$m,$n = diffrands(2,4,2) where (gcd($m,$n)==1)

$base = $a^$n

$exp = "$base^($m//$n)"

$answer = $a^$m

// === QUESTION TEXT ===
Evaluate without a calculator:

`$exp`
