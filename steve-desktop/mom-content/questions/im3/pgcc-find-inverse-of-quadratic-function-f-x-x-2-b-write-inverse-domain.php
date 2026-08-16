// === NAME - DESCRIPTION: PGCC: Find inverse of quadratic function `f(x) = x^2+b`. Write inverse, domain and range of original and inverse functions. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "numfunc,interval,interval,interval,interval"

$shift = rand(1, 8)
$func = makeprettydisp("x^2 + $shift")

$variables[0] = "x"
$answer[0] = "sqrt(x - $shift)"
$showanswer[0] = makeprettydisp("sqrt(x - $shift)")

//domain of f
$answer[1] = "(-oo,oo)"
//range of f
$answer[2] = "[$shift, oo)"
//domain of f^-1
$answer[3] = $answer[2]
//range of f^-1
$answer[4] = "[0,oo)"

$formatfeedbackon = "true"
$displayformat = "select"

$answerboxsize[0] = 12
$answerboxsize[1] = 10
$answerboxsize[2] = 10
$answerboxsize[3] = 10
$answerboxsize[4] = 10

//for detailed solution
$temp1 = makeprettydisp("y = $func")
$temp2 = makeprettydisp("x = y^2 + $shift")
$temp3 = makeprettydisp("x - $shift = y^2")
$temp4 = makeprettydisp("y = pm sqrt(x - $shift)")
$temp5 = makeprettydisp("y = sqrt(x - $shift)")

// === QUESTION TEXT ===
Given the function `f(x) = `$func
<br><b>Restrict the domain</b> of the function  `f`  so that the function is one-to-one and has an inverse function.   
<br><br>a. Find the inverse function `f^(-1)(x)`
<br>`f^(-1)(x)=`$answerbox[0]
<br><br>b. State the domain and range of `f(x)` in interval notation.
<br>Domain: $answerbox[1] &nbsp;&nbsp;&nbsp; Range: $answerbox[2]
<br><br>c. State the domain and range of `f^(-1)(x)` in interval notation.
<br>Domain: $answerbox[3] &nbsp;&nbsp;&nbsp; Range: $answerbox[4]

// === ANSWER ===
Given the function `f(x) = `$func
<br><b>Restrict the domain</b> of the function  `f`  so that the function is one-to-one and has an inverse function.   
<hr>b. State the domain and range of `f(x)` in interval notation.
<br>The graph of `f(x)` is a parabola that opens up with vertex at `(0, $shift)`
<br>Restrict domain to the interval to the right of the vertex i.e. $answer[1]
<br>Parabola opens up so vertex is the minimum point, so range is $answer[2]
<hr>a. Find the inverse function `f^(-1)(x)`
<br>Write original function: `$temp1`
<br>Interchange `x` and `y`: $temp2
<br>Solve for y: $temp3 : $temp4
<br>Use positive square root since domain is restricted to positive values.
<br>So $temp5 : `f^(-1)(x)= $answer[0]`
<hr>c. State the domain and range of `f^(-1)(x)` in interval notation.
<br>The domain of the inverse function is the range of the original function.
<br>So the domain of `f^(-1)(x)` is $answer[3]
<br>The range of the inverse function is the domain of the original function.
<br>So the range of `f^(-1)(x)` is $answer[4]
