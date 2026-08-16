// === NAME - DESCRIPTION: x+a=b one step (includes inverse operation) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "numfunc"
$v = randfrom("a,b,c,d,e,f,g,h,j,k,m,n,p,r,s,t,u,v,w,x,y,z")
$w = $v
$a,$b = nonzerodiffrands(0,50,2) where ($b>$a)
$answer = $b-$a
$answerboxsize = 2

// === QUESTION TEXT ===
<p><span style="font-size: large;">Solve for the variable. Don't forget to show your work and balance the equation.</span></p>
<p><span style="font-size: large;">$w + $a = $b &nbsp;</span></p>
<p><span style="font-size: large;">$w = $answerbox[0]</span></p>
