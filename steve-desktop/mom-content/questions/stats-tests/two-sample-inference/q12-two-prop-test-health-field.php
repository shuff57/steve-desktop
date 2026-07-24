// === NAME - DESCRIPTION: Full Test Difference of Proportions Proportions Given (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")
$abstolerance = .0001
$answerboxsize = 4
$noshuffle="all"
$anstypes=array("choices","number","number","choices","choices","choices","choices")
$n1 = 400
$n2 = 600
$p1 = .18
$p2 = rrand(.15,.17,.1)
$pp1 = $p1*100
$pp2 = $p2*100
$x1 = $p1*$n1
$x2 = $p2*$n2
$phat = ($x1 + $x2)/($n1+$n2)
$qhat = 1 - $phat
$alpha = randfrom(".10,.05,.01")
$a = $alpha*100

//Tailed
$choices[0] = array("=","<",">","&#x2260")
$displayformat[0]="select"
$answer[0] = 2

//Test Statistics
$answer[1] = round(($p1-$p2)/sqrt($phat*$qhat(1/$n1+1/$n2)),4)

//p-value
$answer[2]= round(1-normalcdf($answer[1],8),4)


//Decision Rule
$choices[3] = array ("Reject the Null","Accept the Null","Fail to Reject the Null")
$displayformat[3]="select"
if($answer[2]<$alpha) {$answer[3]=0} else {$answer[3]=2}

//Significance
$choices[4] = array ("Significance Happened","Nothing Significant Happened")
$displayformat[4]="select"
if($answer[2]<$alpha) {$answer[4]=0} else {$answer[4]=1}

//Conclusion Part a
$choices[5] = array ("is","is not")
$displayformat[5]="select"
if($answer[2]<$alpha) {$answer[5]=0} else {$answer[5]=1}

//Conclusion Part b
$choices[6] = array ("that the true proportion of students planning to study a health-related field in college is more now than 10 years ago","that the true proportion of students planning to study a health-related field in college is less now than 10 years ago","that the true proportion of students planning to study a health-related field in college is the same now as 10 years ago")
$displayformat[6]="select"
$answer[6] = "0"

// === QUESTION TEXT ===

In a survey of $n1 students taking the SAT, $pp1% were planning to study health-related fields in college.  In another survey of $n2 students taken 10 years prior, $pp2% were planning to study a health related field.  Test at $a% significance that the true proportion of students planning to study a health-related field in college is more now than 10 years ago. <br>
<br>
Group 1: Now<br>
Group 2: 10 Years Ago<br>

Round to the fourth<br>
<br>
Select the correct alternative sign: `p_1` $answerbox[0] `p_2`<br>
Test Statistic: $answerbox[1]<br>
p-value: $answerbox[2]<br>
Decision Rule: $answerbox[3]<br>
Did Significance Happen? $answerbox[4]<br>
There $answerbox[5] enough evidence to conclude $answerbox[6]
