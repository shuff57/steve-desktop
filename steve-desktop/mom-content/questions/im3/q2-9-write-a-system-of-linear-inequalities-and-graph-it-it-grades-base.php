// === NAME - DESCRIPTION: Q2-9 Write a system of linear inequalities and graph it. It grades based on correct inequalities AND if their graph matches THEIR inequalities 7pts (level 4) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("numfunc","numfunc","draw")

loadlibrary("ineq")

$firstpart1 = "x"
$firstpart2 = "2x"
$lastpart1 = "y"
$lastpart2 = "2y"
$f1 = "x"
$f2 = "y"

$hidetips[0] = true
$hidepreview[0] = true
$hidetips[1] = true
$hidepreview[1] = true
//$answer = 0 
$variables[0] = "x,y"
$answerformat[0] = "inequality"
$variables[1] = "x,y"
$answerformat[1] = "inequality"

//need to figure a way to stop them from putting the same thing twice..
//not exact, but close enough
$f1 = $firstpart1."-".$lastpart1
$f2 = $firstpart2."-".$lastpart2
if (comparefunctions($f1,$f2,'x,y')==1){$answer+=-.25
 $flag1 = "<font color=red>Your second inequality shouldn't be that similar</font>"}
//set the stage
$maxhours = rand(14,20)
$payyint = rand(4,8)
$payxint = rand(4,8) where ($payxint != $payyint)
$minmoney = $payyint*$payxint*rand(1,2)
$dogpay = $minmoney/$payxint
$carpay = $minmoney/$payyint
$myx11 = 0
$myy11 = $maxhours
$myx12 = $maxhours
$myy12 = 0
$myx13 = 1
$myy13 = 1
$myx21 = 0
$myy21 = $payyint
$myx22 = $payxint
$myy22 = 0
$myx23 = 20
$myy23 = 20
//time to check if theirs agrees with mine
//going to mimic what i did with the graph to compare to theirs inequal



//from here down sets the graph and pulls the "three point" data for the inequality
$answerformat[2] = "inequality"
$answers[2] = array("<=$maxhours-x",">=$minmoney/$carpay-$dogpay/$carpay*x")
$xmin = -1
$xmax = 21
$ymin = -1
$ymax = 21
$xscl = "2:1"
$yscl = "2:1"
//$imagewidth = 450
//$imageheight = 450
$snaptogrid[2] = 1
$grid[2] = "$xmin,$xmax,$ymin,$ymax,$xscl,$yscl,450,450"
$background[2] = array("text,11,-1.5,hours walking dogs,black,below,0","text,-1.5,11,hours at car wash,black,left,90")



//let's show the correct answers
$answer[0] = "x+y<=$maxhours" // or  $dogpay x+$carpay y>=$minmoney
$showanswer[0] = makexxprettydisp("x+y<=$maxhours")
$answer[1] = "$dogpay x+$carpay y>=$minmoney" //if ($stuanswers[0]=="x+y<=$maxhours")
//$answer[1] = "x+y<=$maxhours" if ($stuanswers[0]=="$dogpay x+$carpay y>=$minmoney")
$showanswer[1] = makexxprettydisp("$dogpay x+$carpay y>=$minmoney")

$funcstring = array("($maxhours-x),below,blue,solid,2,","($minmoney-$dogpay*x)/$carpay,above,red,solid,2,red","x=0,right","0,above")
$showgraph = ineqbetweenplot($funcstring,$xmin,$xmax,$ymin,$ymax,"2:2",1,450,450)
$g = showplot(array("text,11,-1.5,hours walking dogs,black,below,0","text,-1.5,11,hours at car wash,black,left,90"))

$showanswer[2] = mergeplots($showgraph,$g)
//$showanswer = "<br>$correctineq1<br>$correctineq2<br>$showgraph"

// === QUESTION TEXT ===
<p>You can work at most $maxhours hours next week. You need to earn at least $$minmoney to cover your weekly expenses. Your dog walking job pays $$dogpay per hour and your car wash attendant job pays $$carpay per hour. Write a system of linear inequalities to model the situation. Let `x` be he number of hours you walk dogs and `y` be the number of hours you work at the car wash.<br>Your hours inequality: $answerbox[0] <br><br>Your income inequality: $answerbox[1] <br><br>Now, graph your system of inequalties below <br><br>$answerbox[2] <br><br><br></p>
