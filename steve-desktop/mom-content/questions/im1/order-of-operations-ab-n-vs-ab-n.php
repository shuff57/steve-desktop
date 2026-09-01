// === NAME - DESCRIPTION: Order of operations (ab^n vs. (ab)^n) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes=array("number","number")
$a,$b=diffrands(2,5,2)
$n=rand(2,5) where (($a*$b)^$n<500)

$bn=$b^$n
$answer[0]=$a*$bn

$ab=$a*$b

$answer[1]=$ab^$n

$exp1="$b"
$exp2="$ab"
for($i=2..$n){
	$exp1=$exp1."cdot $b"
	$exp2=$exp2."cdot $ab"
}

// === QUESTION TEXT ===

Evaluate.

`$a cdot $b^$n = `$answerbox[0]

`($a cdot $b)^$n = `$answerbox[1]

// === ANSWER ===

Remember that `a^n` means the product of `n` factors of `a`.

So
<table>
  <tr><td align=right>`$a cdot $b^$n`</td><td align=center>`=`</td><td align=left>`$a cdot $exp1`</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$answer[0]`</td></tr>
</table>

Equivalently, we should evaluate the exponential expressions first:
<table>
  <tr><td align=right>`$a cdot $b^$n`</td><td align=center>`=`</td><td align=left>`$a cdot $bn`</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$answer[0]`</td></tr>
</table>

Meanwhile, the parentheses in `($a cdot $b)^$n` means we have to evaluate the product inside first:
<table>
  <tr><td align=right>`($a cdot $b)^$n`</td><td align=center>`=`</td><td align=left>`$ab^$n`</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$exp2`</td></tr>
  <tr><td align=right></td><td align=center>`=`</td><td align=left>`$answer[1]`</td></tr>
</table>
