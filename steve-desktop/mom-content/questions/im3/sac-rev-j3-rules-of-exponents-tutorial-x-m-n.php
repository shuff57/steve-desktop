// === NAME - DESCRIPTION: SAC Rev J3 Rules of Exponents TUTORIAL: `x^(m/n)` (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc", "numfunc", "numfunc", "numfunc")
$hidetips = true
$answerboxsize = 6
$variables = "m,n,p,a,c,b,r,h,w,x,y"

$var1,$var2,$var3,$var4 = diffrandsfrom("m,n,p,a,c,b,r,h,w,x,y",4)
$a,$b,$c,$d = diffrands(3,12,4,"inc") where (gcd($a,$b)==1 && gcd($a,$c)==1)
$e = randfrom("13,17,29,31")

$answer[0] = "$var1^($a/$b)"
$answer[1] = "$e^(1/$d)"
$answer[2] = "$var3^(-$c/$var2)"
$answer[3] = "$var4^(-$e/2)"

$answer[4] = "root($c) ($var1^$b)"
$answer[5] = "1/sqrt($var2)"
$requiretimes[5] = "2,=0"
$answer[6] = "1/(root($a) ($d^$c))"

// === QUESTION TEXT ===
<table bgcolor = "#FFFF66"><tr><td><p align = center>`root(n) (x^m) = x^(m/n)` <br><br> where `m` is any integer, <br> `n` is a positive integer and `n&gt;=2`</p></tr></td>
<tr><td><p>The nth root of `x^m` is the same as `x` raised to the `m/n` power.</p></td></tr></table>

Rewrite the following in exponential form.
<ol type = a>
  <li> &nbsp; `root($b) ($var1^$a) =` $answerbox[0] </li><br>
  <li> &nbsp; `root($d) ($e) =` $answerbox[1] </li><br>
  <li> &nbsp; `1/(root($var2) ($var3^$c)) =` $answerbox[2] </li><br>
  <li> &nbsp; `1/(sqrt($var4^$e)) =` $answerbox[3] </li><br>
  <p style="text-indent: -40px"> Rewrite the following in radical form. </p>
  <li> &nbsp; `$var1^($b/$c) =` $answerbox[4] </li><br>
  <li> &nbsp; `$var2^(-1/2) =` $answerbox[5] </li><br>
  <li> &nbsp; `$d^(-$c/$a) =` $answerbox[6] </li></ol>
