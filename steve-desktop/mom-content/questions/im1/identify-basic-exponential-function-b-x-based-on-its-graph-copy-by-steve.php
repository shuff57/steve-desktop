// === NAME - DESCRIPTION: Identify basic exponential function (b^x) based on its graph. (copy by Steven Huff) ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$ind = rand(0,7)
$b = array(2,3,4,5,.5,1/3,.25,.2)
$choices = array("`f(x)=2^x`","`f(x)=3^x`","`f(x)=4^x`","`f(x)=5^x`","`f(x)=(1/2)^x`","`f(x)=(1/3)^x`","`f(x)=(1/4)^x`","`f(x)=(1/5)^x`")

$p = showplot("$b[$ind]^x",-5,5,-5,15,1,1,200,300)

$answer = $ind

// === QUESTION TEXT ===

What function is graphed below?

$p
