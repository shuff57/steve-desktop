// === NAME - DESCRIPTION: Evaluate x^n when x is a fraction (local for Steven Huff) ===
// === SET QUESTION TYPE TO: calculated ===

// === COMMON CONTROL ===
$a, $b=rands(2,5,2) where gcd($a,$b)==1
$n=rand(2,6) where ($a^$n < 126 && $b^$n <126)

$num=$a^$n
$den=$b^$n
$answer= "$num/$den"

$deteq=""
$requiretimes="^,<1"
for($i=1..$n){
  $deteq=$deteq."($a/$b)"
}

// === QUESTION TEXT ===
Evaluate: `x^$n`, when `x = $a/$b`.

`x^$n`, when `x = $a/$b`, is $answerbox

// === ANSWER ===
To evaluate `x^$n` when `x = $a/$b`, replace `x` with `$a/$b`:

<table>
  <tr><td align=right>`x^$n`</td><td align=center>`=`</td><td align=left>`($a/$b)^$n`</td><td></td></tr>
  <tr><td colspan=4>Remember that `a^n` is the product of `n` factors of `a`, so</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$deteq`</td></tr>
  <tr><td></td><td align=center>`=`</td><td align=left>`$answer`</td></tr>
</table>
