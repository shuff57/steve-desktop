// === NAME - DESCRIPTION: Full hypothesis test for difference of two population means with randomized story contexts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")
$abstolerance = .0001
$answerboxsize = 4
$noshuffle="all"
$anstypes=array("choices","number","number","choices","choices","choices","choices")

// Randomized story contexts
$ci = rand(0,4)
$group1_labels = array("male college students","urban commuters","morning exercisers","full-time employees","students using app A")
$group2_labels = array("female college students","suburban commuters","evening exercisers","part-time employees","students using app B")
$measures = array("energy drinks consumed per month","minutes spent commuting per day","calories burned per session","hours worked overtime per week","study hours logged per week")
$claims = array("male college students consume more energy drinks on average than female college students","urban commuters spend more time commuting on average than suburban commuters","morning exercisers burn more calories on average than evening exercisers","full-time employees work more overtime on average than part-time employees","students using app A log more study hours on average than students using app B")

$g1 = $group1_labels[$ci]
$g2 = $group2_labels[$ci]
$measure = $measures[$ci]
$claim = $claims[$ci]

$alpha = randfrom(".10,.05,.01")
$a = $alpha*100
$n1 = rrand(400,425,1)
$n2 = rrand(325,375,1)
$x1 = rrand(2.40,2.50,.01)
$x2 = rrand(1.20,1.40,.01)
$s1 = rrand(4.0,4.85,.01)
$s2 = rrand(3.0,3.85,.01)
$v1 = $s1^2
$v2 = $s2^2

//Tailed
$choices[0] = array("=","<",">","&#x2260")
$displayformat[0]="select"
$answer[0] = 2

//Test Statistics
$answer[1] = round(($x1-$x2)/sqrt($v1/$n1+$v2/$n2),4)

//p-value
$df = ($v1/$n1+$v2/$n2)^2/(($v1/$n1)^2/($n1-1)+($v2/$n2)^2/($n2-1))
$answer[2] = round(1-tcdf($answer[1],$df,8),4)

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
$choices[6] = array ("that the true average $measure is less for $g1 than $g2.","that the true average $measure is the same for $g1 and $g2.","that the true average $measure is more for $g1 than $g2.")
$displayformat[6]="select"
$answer[6] = "2"

// Plain-text versions of choices for use in the Detailed Solution
// (text interpolation does not support $choices[i][$answer[i]] nesting)
$decision_text = "Reject the Null"
if($answer[2]>=$alpha) {$decision_text = "Fail to Reject the Null"}
$evidence_text = "is"
if($answer[2]>=$alpha) {$evidence_text = "is not"}
$conclusion_text = "that the true average $measure is more for $g1 than $g2."
$pvssign = "<"
if($answer[2]>=$alpha) {$pvssign = "&ge;"}

// === QUESTION TEXT ===

A survey reported that in a random sample of $n1 $g1, the average number of $measure was $x1 with a standard deviation of $s1, and in a sample of $n2 $g2, the average was $x2 with a standard deviation of $s2. Conduct a test to decide if $claim. Use $a% significance to decide.<br>
Group 1: $g1<br>
Group 2: $g2<br>

Use the <b>Welch-Satterthwaite</b> formula to compute the degrees of freedom for the p-value.<br>
Round to the fourth<br>
<br>
Select the correct alternative sign: `mu_1` $answerbox[0] `mu_2`<br>
Test Statistic: $answerbox[1]<br>
p-value: $answerbox[2]<br>
Decision Rule: $answerbox[3]<br>
Did Significance Happen? $answerbox[4]<br>
There $answerbox[5] enough evidence to conclude $answerbox[6]

///

<b>Step 1: State the hypotheses.</b><br>
We are testing whether $g1 have a higher average $measure than $g2, so this is right-tailed:<br>
`H_0: mu_1 = mu_2` &nbsp;&nbsp; `H_1: mu_1 > mu_2`<br><br>

<b>Step 2: Compute the test statistic.</b><br>
We use a two-sample t-test (unequal variances):<br>
`t = (bar x_1 - bar x_2) / sqrt(s_1^2/n_1 + s_2^2/n_2) = ($x1 - $x2) / sqrt($v1/$n1 + $v2/$n2)`<br>
`t =` <b>$answer[1]</b><br><br>

<b>Step 3: Find the degrees of freedom and p-value.</b><br>
Using the Welch-Satterthwaite formula: df = $df<br>
Since this is right-tailed: p-value = P(T > $answer[1]) = <b>$answer[2]</b><br><br>

<b>Step 4: Make a decision.</b><br>
Compare p-value ($answer[2]) to `alpha` = $alpha.<br>
Since p-value $pvssign `alpha`, we <b>$decision_text</b>.<br>
There <b>$evidence_text</b> enough evidence to conclude $conclusion_text
