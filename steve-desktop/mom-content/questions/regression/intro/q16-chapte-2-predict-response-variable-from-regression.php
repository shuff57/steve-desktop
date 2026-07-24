// === NAME - DESCRIPTION: Chapte 2  Predict response variable from regression eqn (stats) [4400 GH] (copy by Samantha Homier) changed independent/dependent to explanatory/response ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

loadlibrary("stats")
$hidetips = true
$reqdecimals = 1

$xbar = rand(20,80) + rand(1,9)/10
$ybar = rand(20,80) + rand(1,9)/10

$pick = 2
// 1 = non-sig, use y-bar
// 2 = sig, use reg eqn

$n = rand(10,120)

$tmprnd = rand(1,2)
$neg = 1 if ($tmprnd == 1)
$neg = -1 if ($tmprnd == 2)

$t = -1*invtcdf(0.05/2,$n-2,6) if ($pick == 1)
$cv = $t/sqrt($t^2+$n-2) if ($pick == 1)
$cv = round($cv,3) if ($pick == 1)
$r = $neg*rand(10,$cv*1000-3)/1000 if ($pick == 1)

$t = -1*invtcdf(0.01/2,$n-2,6) if ($pick == 2)
$cv = $t/sqrt($t^2+$n-2) if ($pick == 2)
$cv = round($cv,3) if ($pick == 2)
$r = $neg*rand($cv*1000+3,999)/1000 if ($pick == 2)

$a = $neg*(rand(20,450)/100 + rand(1,9)/1000)
$b = nonzerorand(-6200,6200)/100 + rand(1,9)/1000
$x = rand(1,19)*10
$y = $x*$a+$b

$answer = round($y,1) if ($pick == 2)
$answer = round($ybar,1) if ($pick == 1)

$eqn = makeprettydisp("y = $b + $a x")

// === QUESTION TEXT ===

The line of best fit through a set of data is
<center>
  $eqn
</center>

According to this equation, what is the predicted value of the response variable when the explanatory variable has value $x?

<br>
<i>y</i> = $answerbox  Round to 1 decimal place.


// === ANSWER ===

$solutionguide
