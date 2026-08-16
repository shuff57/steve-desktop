// === NAME - DESCRIPTION: Murphy SM3.05.3: Inverse Function Tutorial (find inverse of sqrt function, including domain and range) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "numfunc,numfunc,numfunc,numfunc,numfunc,numfunc,numfunc,numfunc,numfunc,numfunc,numfunc,numfunc,numfunc,numfunc,calcinterval,calcinterval,calcinterval,calcinterval"
$answerformat[0] = "expression"
$answerformat[1] = "expression"
$answerformat[2] = "expression"
$answerformat[3] = "expression"
$answerformat[4] = "expression"
$answerformat[5] = "expression"
$answerformat[6] = "expression"
$answerformat[7] = "expression"
$answerformat[8] = "expression"
$answerformat[9] = "expression"
$answerformat[10] = "expression"
$answerformat[11] = "expression"
$answerformat[12] = "expression"
$answerformat[13] = "expression"
$variables = "x,y"
$answerboxsize = 5
$formatfeedbackon = true

$a,$b,$c = diffrands(2,9,3) where (gcd($a,$b)==1 && gcd($b,$c)==1 && gcd($a,$c)==1)
$myfunc = "`f(x)=sqrt($a x+$c)-$b`"
$myinv = "`f^-1(x)=((x+$b)^2-$c)/$a`"

$answer[0] = "y"
$showanswer[0] = "`y`"
$answer[1] = "`sqrt($a x+$c)-$b`"
$showanswer[1] = "`sqrt($a x+$c)-$b`"
$answer[2] = "`x`"
$showanswer[2] = "`x`"
$answer[3] = "`sqrt($a y+$c)-$b`"
$showanswer[3] = "`sqrt($a y+$c)-$b`"
$answer[4] = "`x+$b`"
$showanswer[4] = "`x+$b`"
$answer[5] = "`sqrt($a y+$c)`"
$showanswer[5] = "`sqrt($a y+$c)`"
$answer[6] = "`(x+$b)^2`"
$showanswer[6] = "`(x+$b)^2`"
$answer[7] = "`$a y+$c`"
$showanswer[7] = "`$a y+$c`"
$answer[8] = "`(x+$b)^2-$c`"
$showanswer[8] = "`(x+$b)^2-$c`"
$answer[9] = "`$a y`"
$showanswer[9] = "`$a y`"
$answer[10] = "`((x+$b)^2-$c)/$a`"
$showanswer[10] = "`((x+$b)^2-$c)/$a`"
$answer[11] = "`y`"
$showanswer[11] = "`y`"
$answer[12] = "`sqrt($a x+$c)-$b`"
$showanswer[12] = "`sqrt($a x+$c)-$b`"
$answer[13] = "`((x+$b)^2-$c)/$a`"
$showanswer[13] = "`((x+$b)^2-$c)/$a`"
$answer[14] = "[(-$c/$a),oo)"
$showanswer[14] = "`[-$c/$a,oo)`"
$answer[15] = "[-$b,oo)"
$showanswer[15] = "`[-$b,oo)`"
$answer[16] = "(-oo,oo)"
$showanswer[16] = "`(-oo,oo)`"
$answer[17] = "[-$c/$a,oo)"
$showanswer[17] = "`[-$c/$a,oo)`"

// === QUESTION TEXT ===
The following prompts will guide you through the process of finding an inverse.  The general strategy is easy:<br><br>
(1) Rewrite `f(x)` as `y`<br>
(2) Swap `x` and `y`<br>
(3) Solve for `y`<br>
(4) Rewrite `y` as `f^(-1)(x)`<br><br>
$myfunc

Step (1): Rewrite the above function in the two boxes.  Switch `f(x)` to `y` to make the math easier:

$answerbox[0]`=`$answerbox[1]

///

Step (2): Swap `x` and `y`.  The variable `y` now represents the inverse function:

$answerbox[2]`=`$answerbox[3]

///

Step (3a): We are trying to isolate `y` on one side of the equation.  Add `$b` to each side:

$answerbox[4]`=`$answerbox[5]

///

Step (3b): We are trying to isolate `y` on one side of the equation.  Square each side:

$answerbox[6]`=`$answerbox[7]

///

Step (3c): We are trying to isolate `y` on one side of the equation.  Subtract `$c` from each side:

$answerbox[8]`=`$answerbox[9]

///

Step (3d): We are trying to isolate `y` on one side of the equation.  Divide each side by `$a`:

$answerbox[10]`=`$answerbox[11]

///

Step (4): Remember that the variable `y` is the inverse of `f`.  Summarize what we know about `f(x)` and `f^-1(x)` below:<br>

`f(x)=`$answerbox[12]

`f^-1(x)=`$answerbox[13]

///

Be careful when dealing with the SQRT function, because it has domain restrictions.  Find the <b>DOMAIN</b> of `f(x)` by remembering that we cannot square root a negative number:

Domain of `f(x)`: $answerbox[14]

Find the <b>RANGE</b> of `f(x)` by remembering that a positive SQRT will never be less than zero:

Range of `f(x)`: $answerbox[15]

///

Now that we have domain and range of `f(x)`, we therefore have the domain and range of `f^-1(x)`:

Domain of `f^-1(x)`: $answerbox[16]

Range of `f^-1(x)`: $answerbox[17]<br><br>
