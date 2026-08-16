// === NAME - DESCRIPTION: System of Linear Equations (by graphing): Word Problem (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "choices,draw,number,number"
$answeights="0.1,0.5,0.2,0.2"
$displayformat[0]="horiz"
$answerformat[1] = "twopoint, line"
$snaptogrid[1] = 0.5

$name =randfemalename()
$item1,$item2=diffrandsfrom("wind chimes,wall hangings,Christmas ornaments,pot holders,barrettes,ponytail holders,handmade dolls,afghans,doll dresses,potpourri sachets,wreaths,large scented candles,small scented candles",2)
$where=randfrom("county fair,state fair,rodeo,renaissance festival,convention,fall festival,flea market")
$x=rrand(4,10,2)
$y=rrand(4,10,2) where ($x!=$y)
$ntotal=$x+$y
$p1=rrand(2,5,.5)
$p2=$p1*randfrom("0.5,0.25,0.75,2,1.25,1.5,1.75,3,4,4.5") where ($p2<10 && $p2>2.5 && $p2==round($p2,2))
$showp1=prettyreal($p1,2)
$showp2=prettyreal($p2,2)
$P=$x*$p1 + $y*$p2
$showP=$P
if (round($P,0)!=$P) {$showP=prettyreal($P,2)}

$eqns = " `{(x+y = $P),($p1 x + $p2 y = $ntotal):}` "
$eqns2=" `{(x+y = $ntotal),($p1 x + $p2 y = $P):}` "
$eqns3=" `{($p1 x+y = $ntotal),( x + $p2 y = $P):}` "
$eqns4=" `{(x+y = $ntotal),($p2 x + $p1 y = $P):}` "
$eqnchoice=array("$eqns","$eqns2","$eqns3","$eqns4")

$grid = "-4,36,-4,36,2,2,500,500"

$questions[0] = $eqnchoice
$answer[0] = 1
$answers[1] = array("-1*x+$ntotal","($P-$p1*x)/$p2")
$answer[2] = $x
$answer[3] = $y

// === QUESTION TEXT ===
$name sells $item1 and $item2 at a $where. She wants to sell $ntotal total items to reduce her current stock and to make room for incoming orders. She sells the $item1 for $$showp1 and the $item2 for $$showp2. She needs to sell a minimum of $$showP worth of items in order to earn a profit.

a.) Choose which system of equations matches the scenario where `x` is the number of $item1 and `y` is the number of $item2.

$answerbox[0]

b.) Graph the system. $answerbox[1]
$showanswerloc[1]

c.) How many $item1 should $name sell? $answerbox[2]

d.) How many $item2 should $name sell? $answerbox[3]
