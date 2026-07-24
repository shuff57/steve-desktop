// === NAME - DESCRIPTION: 4.2: Use a regression equation to make predictions; interpret slope/y-intercept. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number", "number", "choices")

$r = -1*rrand(.6,.99,.001)
$m = rrand(-1.4,-.6,.001)
$b = rrand(20,40,.001)
$n = -1*$m

$x = rrand(.5,14,.5)

$questions[0] = array("number of sit-ups", "number of hours of TV watched")
$answer[0] = "1"

$answer[1] = round($m*$x+$b,0)
$answer[2] = round($b)

$questions[3]= array("On average, a person can do $n fewer sit-ups for every additional hour of TV they watch.", "On average, a person can do $n  more sit-ups for every additional hour of TV they watch.", "On average, a person can do $b more sit-ups for every additional hour of TV they watch.", "On average, a person watches $n fewer hours of TV for every additional sit-up they can do.")
$answer[3]="0"
$answerboxsize=5

// === QUESTION TEXT ===

<p>The linear regression equation&nbsp; `hat{y} = $b $m x`&nbsp; models the number of sit-ups, `y`, that a person can do.</p>
<p>In the equation, `x`&nbsp; represents the number of hours of TV the person watches per day. <br><br>(a) Which of the variables serves as the explanatory variable in this scenario? $answerbox[0]</p>
<p>(b) Use the regression equation to predict the number of sit-ups a person who watches $x hours of TV can do. <em>Round to the nearest whole sit-up.</em>&nbsp;</p>
<p>$answerbox[1] sit-up(s)</p>
<p>(c) How many sit-ups are predicted for a person who watches no TV? <em>Round to the nearest whole sit-up.</em>&nbsp;</p>
<p>$answerbox[2] sit-up(s)</p>
<p>(d) Which of these best explains the meaning of the <em>slope</em> in this scenario? <em>Choose one</em>. $answerbox[3]</p>


// === ANSWER ===

$solutionguide
