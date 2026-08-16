// === NAME - DESCRIPTION: Parent Functions and Transformations #1: `f(x)=x^2` (local for DEYCI LOPEZ) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "numfunc,number,multans,calcinterval,calcinterval,calculated,calculated,calcinterval,calcinterval"

//build the function
// f(x) = a(x-h)^2+k
$a,$h,$k = nonzerodiffrands(-6,6,3) where(abs($a)!=1)
$funstr = makexxprettydisp("f(x) = $a (x - $h)^2 + $k")
$fundiv = "<div style='position: sticky; background-color: lightgreen; padding: 20px;'><center><big>$funstr</big></center></div>"

//part 1 - ID parent function
$answer[0] = "x^2"
$variables[0] = "x"

//part 2 - random solution point
$n = nonzerorand(-5,5) where($n!=$h)
$answer[1] = $a*($n-$h)^2+$k

//part 3 - ID Transformations
$ha = abs($h)
$ka = abs($k)
$aa = abs($a)
$c00 = "shifted $ha unit".($ha==1?"":"s")." left"
$c01 = "shifted $ha unit".($ha==1?"":"s")." right"
$c02 = "shifted $ka unit".($ka==1?"":"s")." left"
$c03 = "shifted $ka unit".($ka==1?"":"s")." right"
$c04 = "shifted $ha unit".($ha==1?"":"s")." up"
$c05 = "shifted $ha unit".($ha==1?"":"s")." down"
$c06 = "shifted $ka unit".($ka==1?"":"s")." up"
$c07 = "shifted $ka unit".($ka==1?"":"s")." down"
$c08 = "reflected across the x-axis"
$c09 = "NOT reflected across the x-axis"
$c10 = "stretched vertically by a factor of $aa"
$c11 = "NOT stretched vertically"
$c12 = "stretched vertically by a factor of $ka"
$c13 = "stretched vertically by a factor of $ha"
$questions[2] = [$c00,$c01,$c02,$c03,$c04,$c05,$c06,$c07,$c08,$c09,$c10,$c11,$c12,$c13]
$answers[2] = ($h>0?"1":"0").",".($k<0?"7":"6").",".($a<0?"8":"9").",".($aa==1?"11":"10")
//$noshuffle[2] = "all"
$displayformat[2] = "2column"

//part 4 - domain in interval notation
$answer[3] = "(-oo,oo)"

//part 5 - range in interval notation
$answer[4] = "(-oo,$k]" if($a<0)
$answer[4] = "[$k,oo)" if($a>0)


//part 8 - Increasing Interval(s)
$answer[7] = "($h,oo)" if($a>0)
$answer[7] = "(-oo,$h)" if($a<0)

//part 9 - Decreasing Interval(s)
$answer[8] = "($h,oo)" if($a<0)
$answer[8] = "(-oo,$h)" if($a>0)


$answerboxsize = [5,8,1,7,7,5,5,7,7]
$spc = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"

// === QUESTION TEXT ===
Answer the following questions about this function:
$fundiv
[A] What is the parent function?<br />$spc`f(x) = \ `[AB0]
<br /><br />
[B] What is the missing `y`-coordinate for `f(x)`?<br />$spc`(\  $n\  ,\  `[AB1]`\  )`
<br /><br />
[C] Check off any of the following transformations that were applied to the parent function.<br />[AB2]
<br /><br />
$fundiv
[D] Domain: <i>(use interval notation)</i><br />$spc [AB3]
<br /><br />
[E] Range: <i>(use interval notation)</i><br />$spc [AB4]
<br /><br />
$fundiv
[F] Increasing Interval(s): <i>(use interval notation, use DNE if there are none)</i><br />$spc [AB7]
<br /><br />
[G] Decreasing Interval(s): <i>(use interval notation, use DNE if there are none)</i><br />$spc [AB8]
<br /><br />
$tbl
