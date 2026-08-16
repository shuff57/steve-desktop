// === NAME - DESCRIPTION: PGCC: Find inverse of quadratic function `f(x) = (x-a)^2`. Write inverse, domain and range of original and inverse functions. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "numfunc,interval,interval,interval,interval"
$variables[0] = "x"

$shift = rand(1, 5)
$func = "(x - $shift)^2"

//inverse function
$answer[0] = "sqrt(x)+$shift"

//domain of f
$answer[1] = "(-oo,oo)"
//range of f
$answer[2] = "[0,oo)"
//domain if f^-1
$answer[3] = $answer[2]
//range of f^-1
$answer[4] = "[$shift,oo)"

//$variables[2] = "y"
//$variables[4] = "y"

$displayformat = "select"

$answerboxsize[0] = 12
$answerboxsize[1] = 10
$answerboxsize[2] = 10
$answerboxsize[3] = 10
$answerboxsize[4] = 10

$stu = getstuans($stuanswers,$thisq,1)

$fb = "Your domain is correct, BUT the instructions state to <b>restrict the domain of function `f` so that the function is one-to-one!" if ($stu=="(-oo,oo)")

$hidepreview = "true"

//for detailed solution
$temp1 = makeprettydisp("y = $func")
$temp2 = makeprettydisp("x = (y - $shift)^2")
$temp3 = makeprettydisp("pm sqrt(x) = (y - $shift)")
$temp4 = makeprettydisp("y - $shift = sqrt(x)")

// === QUESTION TEXT ===
Given the function `f(x)=$func`.
<br><b>Restrict the domain</b> of the function  `f(x)`  so that the function is one-to-one and has an inverse function.   
<hr>a. Find the inverse function `f^(-1)(x)`
<br>`f^(-1)(x)=`$answerbox[0]
<hr>b. State the domain and range of `f(x)` in interval notation.
<br>Remember that you must restrict the domain so that the function is one-to-one and has an inverse function.
<br>Domain: $answerbox[1] &nbsp;&nbsp;&nbsp; Range: $answerbox[2]
<hr>c. State the domain and range of `f^(-1)(x)` in interval notation.
<br>Domain: $answerbox[3] &nbsp;&nbsp;&nbsp; Range: $answerbox[4]

// === ANSWER ===
Given the function `f(x)=$func`.
<br><b>Restrict the domain</b> of the function  `f`  so that the function is one-to-one and has an inverse function.   
<hr>b. State the domain and range of `f(x)` in interval notation.
<br>The graph of `f(x)` is a parabola that opens up with vertex at `($shift, 0)`
<br>Restrict domain to the interval to the right of the vertex i.e. $answer[1]
<br>Parabola opens up so vertex is the minimum point, so range is $answer[2]
<hr>a. Find the inverse function `f^(-1)(x)`
<br>Write original function: $temp1
<br>Interchange `x` and `y`: $temp2
<br>Solve for y: $temp3
<br>Use positive square root since domain is restricted to positive values.
<br>So $temp4 : `f^(-1)(x)= $answer[0]`
<hr>c. State the domain and range of `f^(-1)(x)` in interval notation.
<br>The domain of the inverse function is the range of the original function.
<br>So the domain of `f^(-1)(x)` is $answer[3]
<br>The range of the inverse function is the domain of the original function.
<br>So the range of `f^(-1)(x)` is $answer[4]
