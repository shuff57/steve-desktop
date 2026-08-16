// === NAME - DESCRIPTION: x÷a=b one step (includes inverse operation) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "numfunc"
$v = randfrom("a,b,c,d,e,f,g,h,j,k,m,n,p,r,s,t,u,v,w,x,y,z")
$a,$b = nonzerodiffrands(0,15,2)
$answer = $a*$b

$answerboxsize = 2

// === QUESTION TEXT ===
<p><span style="font-size: large;">Solve for the variable. Don't forget to show your work and balance the equation.</span></p>
<p><span style="font-size: large;">$v &divide; $a = $b &nbsp;</span></p>
<p><span style="font-size: large;">$v=$answerbox[0]</span></p>
