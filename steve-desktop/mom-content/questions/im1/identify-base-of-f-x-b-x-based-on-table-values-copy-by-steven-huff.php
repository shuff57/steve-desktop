// === NAME - DESCRIPTION: Identify Base of `f(x)=b^x` based on table values. (copy by Steven Huff) ===
// === SET QUESTION TYPE TO: calculated ===

// === COMMON CONTROL ===

$x = array(1,2)
$b = rand(2,10)
$recip = rand(0,1)

$f = calconarray($x,"$b^x")
$fd = array("1/$f[1]","1/$f[0]",1,$f[0],$f[1])
$fd = array("$f[1]","$f[0]","1","1/$f[0]","1/$f[1]") if ($recip)

$answer = $b
$answer = 1/$b if ($recip)

// === QUESTION TEXT ===

The table below shows values from the function `f(x)=b^x`.  Identify `b`.

<table border="1" cellpadding="8"><tr><td>`x`</td><td>-$x[1]</td><td>-$x[0]</td><td>0</td><td>$x[0]</td><td>$x[1]</td><tr><td>`f(x)`</td><td>`$fd[0]`</td><td>`$fd[1]`</td><td>`$fd[2]`</td><td>`$fd[3]`</td><td>`$fd[4]`</td></table>

`b =` $answerbox
