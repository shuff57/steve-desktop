// === NAME - DESCRIPTION: Full hypothesis test for difference of two proportions (successes given) with randomized story contexts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")
$abstolerance = .0001
$answerboxsize = 4
$noshuffle="all"
$anstypes=array("choices","number","number","choices","choices","choices","choices")

// Randomized story contexts
$ci = rand(0,4)
$group1_labels = array("men","urban residents","morning shift workers","college graduates","regular exercisers")
$group2_labels = array("women","rural residents","evening shift workers","non-graduates","non-exercisers")
$outcomes = array("elevated total cholesterol levels","high blood pressure","reported job satisfaction","had health insurance","met daily step goals")
$sources = array("the Archives of Internal Medicine","a public health survey","a workplace wellness report","a Census Bureau study","a fitness tracking study")

$g1 = $group1_labels[$ci]
$g2 = $group2_labels[$ci]
$outcome = $outcomes[$ci]
$source = $sources[$ci]

$n1 = rrand(200,400,50)
$n2 = rrand(200,400,50)
$x1 = rrand(58,70,1)
$x2 = rrand(45,60,1)
$phat = ($x1 + $x2)/($n1+$n2)
$qhat = 1 - $phat
$p1 = $x1/$n1
$p2 = $x2/$n2
$alpha = randfrom(".10,.05,.01")
$a = $alpha*100

//Tailed
$choices[0] = array("=","<",">","&#x2260")
$displayformat[0]="select"
$answer[0] = 3

//Test Statistics
$answer[1] = round(($p1-$p2)/sqrt($phat*$qhat*(1/$n1+1/$n2)),4)

//p-value
if($answer[1] < 0) {$answer[2]= round(2*normalcdf($answer[1],8),4)} else{$answer[2] =round(2*(1-normalcdf($answer[1],8)),4) }

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
$choices[6] = array ("that the true proportion of $g1 with $outcome doesn't differ from $g2.","that the true proportion of $g1 with $outcome differs from $g2.")
$displayformat[6]="select"
$answer[6] = "1"

// Plain-text versions of choices for use in the Detailed Solution
// (text interpolation does not support $choices[i][$answer[i]] nesting)
$decision_text = "Reject the Null"
if($answer[2]>=$alpha) {$decision_text = "Fail to Reject the Null"}
$evidence_text = "is"
if($answer[2]>=$alpha) {$evidence_text = "is not"}
$conclusion_text = "that the true proportion of $g1 with $outcome differs from $g2."
$pvssign = "<"
if($answer[2]>=$alpha) {$pvssign = "&ge;"}

// === QUESTION TEXT ===

An article in $source reported that in a random sample of $n1 $g1, $x1 had $outcome. In a random sample of $n2 $g2, $x2 had $outcome. Can you conclude that the proportion of people with $outcome differs between $g1 and $g2? Use a $a% significance to test.<br>
<br>
Group 1: $g1<br>
Group 2: $g2<br>

Round to the fourth<br>
<br>
Select the correct alternative sign: `p_1` $answerbox[0] `p_2`<br>
Test Statistic: $answerbox[1]<br>
p-value: $answerbox[2]<br>
Decision Rule: $answerbox[3]<br>
Did Significance Happen? $answerbox[4]<br>
There $answerbox[5] enough evidence to conclude $answerbox[6]

///

<b>Step 1: State the hypotheses.</b><br>
We are testing whether the proportions differ between $g1 and $g2, so this is a two-tailed test:<br>
`H_0: p_1 = p_2` &nbsp;&nbsp; `H_1: p_1 ne p_2`<br><br>

<b>Step 2: Compute the pooled proportion.</b><br>
`hat p = (x_1 + x_2)/(n_1 + n_2) = ($x1 + $x2)/($n1 + $n2) =` $phat<br><br>

<b>Step 3: Compute the test statistic.</b><br>
`hat p_1 = $x1/$n1 =` $p1 &nbsp;&nbsp; `hat p_2 = $x2/$n2 =` $p2<br>
`z = (hat p_1 - hat p_2) / sqrt(hat p cdot hat q cdot (1/n_1 + 1/n_2))`<br>
`z =` <b>$answer[1]</b><br><br>

<b>Step 4: Find the p-value.</b><br>
Since this is two-tailed, p-value = 2 &times; P(Z beyond test stat):<br>
p-value = <b>$answer[2]</b><br><br>

<b>Step 5: Make a decision.</b><br>
Compare p-value ($answer[2]) to `alpha` = $alpha.<br>
Since p-value $pvssign `alpha`, we <b>$decision_text</b>.<br>
There <b>$evidence_text</b> enough evidence to conclude $conclusion_text
