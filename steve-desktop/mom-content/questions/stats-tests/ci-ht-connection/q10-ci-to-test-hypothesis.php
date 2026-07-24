// === NAME - DESCRIPTION: Confidence interval to test hypothesis - interval given. ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL (paste into Common Control) ===

if(!$included) {
  $story=rand(0,2)
	$change=rand(0,2)
	//change 0 means the hypothesized value is above the confidence interval
	//change 1 means the hypothesized value is below the confidence interval
	//change 2 means the hypothesized value is inside the confidence interval, but not equal to the point estimate
}

//confidence level of 90%, 95%, or 99%
$con=randfrom("90,95,99")
//hard-coding z-scores to avoid including the stats library
if($con==95) {$z=1.959963986}
elseif($con==90) {$z=1.644853626}
else {$z=2.5759829303}

//************************************************************************************************************************
//generate the hypothesized percentage, which varies by story. Sticking to values between 10 and 90,
//to ensure conditions for confidence interval are met. Need to do this before we generate the data.
if($story==0) {
  $old=rand(50,70)
} elseif($story==1) {
  $old=rrand(10,25,5)
} elseif($story==2) {
  $old=rand(65,85)
}
//************************************************************************************************************************

//generate the sample size
$n=rrand(2000,3000,50)
$delta=round($n/50,0)

//generate the number of "yes" answers to ensure that the desired change will occur, and conditions will be met
if($change==0) {
  //we need an upper bound on p-hat
  $phat_max=(($z+sqrt($n))-sqrt(($z+sqrt($n))^2-4*$z*($old-0.1)/100*sqrt($n)))/(2*$z)
  $x_max=floor($phat_max*$n)-1
  $x_min=max(20,$x_max-$delta)
} elseif($change==1) {
  //we need a lower bound on p-hat
  $phat_min=(($z-sqrt($n))+sqrt(($z-sqrt($n))^2+4*$z*($old+0.1)/100*sqrt($n)))/(2*$z)
  $x_min=ceil($phat_min*$n)+1
  $x_max=min($n-20,$x_min+$delta)
} else {
  //we need upper and lower bounds on p-hat
  $phat_min_a=(($z+sqrt($n))-sqrt(($z+sqrt($n))^2-4*$z*($old+0.1)/100*sqrt($n)))/(2*$z)
  $phat_min_b=(($z-sqrt($n))-sqrt(($z-sqrt($n))^2+4*$z*($old+0.1)/100*sqrt($n)))/(2*$z)
  $phat_max_a=(($z+sqrt($n))+sqrt(($z+sqrt($n))^2-4*$z*($old-0.1)/100*sqrt($n)))/(2*$z)
  $phat_max_b=(($z-sqrt($n))+sqrt(($z-sqrt($n))^2+4*$z*($old-0.1)/100*sqrt($n)))/(2*$z)
  $phat_min=max($phat_min_a,$phat_min_b)
  $phat_max=min($phat_max_a,$phat_max_b)
  $x_min=max(ceil($phat_min*$n)+1,20)
  $x_max=min(floor($phat_max*$n)-1,$n-20)
}

$ct=0
for($i=$x_min..$x_max) {
  if(abs($i/$n-$old/100)>0.002) {
    $legalx[]=$i
    $ct=$ct+1
  }
}

if($ct==0) {
  $legalx[0]=$x_min
  $legalx[1]=$x_max
}

$x=randfrom($legalx)
$phat=$x/$n
$sigma=$phat*(1-$phat)/sqrt($n)
$point=prettyreal($phat*100,1)
$low=prettyreal(($phat-$z*$sigma)*100,1)
$high=prettyreal(($phat+$z*$sigma)*100,1)

//************************************************************************************************************************
//generate the actual story text. Need to do this after the data is generated.
if($story==0) {
//************************************************************************************************************************
  $time=ucfirst(numtowords(rand(10,20)))
  $group="town government"
  $individuals="all town residents"
  $variable="have a college degree"
  $storytext="$time years ago, a town estimated that $old% of its residents had a college degree. This year, the town government conducted a survey of $n randomly selected residents, $x of whom had college degrees."
} elseif ($story==1) {
//************************************************************************************************************************
  $group="company"
  $individuals="customers"
  $variable="have referred a friend"
  $storytext="A company is testing a new marketing campaign. Their goal is that $old% of their customers will refer a friend to their web site. To check the effectiveness of the campaign, they survey $n randomly selected customers, and find that $x of them have referred a friend."
} elseif ($story==2) {
//************************************************************************************************************************
  $group="district"
  $individuals="any students who participate in the program"
  $variable="will graduate from high school"
  $storytext="A large school district is considering a program designed to increase its high school graduation rate, which is currently $old%. The district randomly selects $n students to participate in a pilot of the program. Of the students participating in the pilot, $x graduate from high school."
}
//************************************************************************************************************************

$noshuffle="all"
$answer=$change*2+1
$questions[0]="Because $point% is less than $old%, the $group can conclude that the percentage of $individuals that $variable is less than $old%."
$questions[1]="Because all values in the interval ($low%,$high%) are less than $old%, the $group can conclude that the percentage of $individuals that $variable is less than $old%."
$questions[2]="Because $point% is more than $old%, the $group can conclude that the percentage of $individuals that $variable is more than $old%."
$questions[3]="Because all values in the interval ($low%,$high%) are more than $old%, the $group can conclude that the percentage of $individuals that $variable is more than $old%."
$questions[4]="Because $old% is inside the interval ($low%,$high%) (that is, $old% is between $low% and $high%), the $group can conclude that the percentage of $individuals that $variable is equal to $old%."
$questions[5]="Because $old% is inside the interval ($low%,$high%) (that is, $old% is between $low% and $high%), the $group does not have sufficient evidence to conclude that the percentage of $individuals that $variable is not equal to $old%."

//text for the detailed solution
//number line showing the relationship between the hypothesized value and the
if($old<$low) {
  $a=1
  $b=3
  $lp=2
  $rp=4
} elseif ($old>$high) {
  $lp=1
  $rp=3
  $b=2
  $a=4
} else {
  $lp=1
  $rp=4
  $b=2.5
  $a=3/($high-$low)*($old-$low)+1
}
$ht=0.25
$lpl=$lp;
$rpl=$rp;
$oldlbl='"'.$old.'%"'
$pointlbl='"'.$point.'%"'
$lowlbl='"'.$low.'%"'
$highlbl='"'.$high.'%"'
$pos='"below"'
$picwidth=500
$picheight=$picwidth/5*(1+$ht)
$nlpicstring="initPicture(0,5);".&
     'marker="arrow";line([2.5,0],[5,0]);line([2.5,0],[0,0]);marker="none";'.&
     "arc([$lpl,$ht/2],[$lpl,-$ht/2],$ht);text([$lp,-$ht/2],$lowlbl,$pos);".&
     "arc([$rpl,-$ht/2],[$rpl,$ht/2],$ht);text([$rp,-$ht/2],$highlbl,$pos);".&
     "line([$b,-$ht/3],[$b,$ht/3]);text([$b,-$ht/3],$pointlbl,$pos);".&
     'marker="arrow";'.&
     "line([$a,-$ht],[$a,0]);text([$a,-$ht],$oldlbl,$pos);".&
     'marker="none";'
$alttext="A number line showing the values $low%, $point%, and $high% (in that order). The value $old% is indicated by an arrow pointing at the number line "
if($old<$low) {$alttext=$alttext."to the left of $low%."}
elseif ($old>$high) {$alttext=$alttext."to the right of $high%."}
elseif ($old<$point) {$alttext=$alttext."between $low% and $point%."}
else {$alttext=$alttext."between $point% and $high%."}
$nlpic=showasciisvg($nlpicstring,$picwidth,$picheight,$alttext)

if($old<$low) {
  $relat="to the left of the confidence interval"
  $altrelat="all values in the confidence interval are greater than $old%"
  $conclusion="the $group can conclude that the percentage of $individuals that $variable is greater than $old%"
} elseif ($low>$high) {
  $relat="to the right of the confidence interval"
  $altrelat="all values in the confidence interval are less than $old%"
  $conclusion="the $group can conclude that the percentage of $individuals that $variable is less than $old%"
} else {
  $relat="inside the confidence interval"
  $altrelat="$old% is between $low% and $high%"
  $conclusion="the $group does not have sufficient evidence to conclude that the percentage of $individuals that $variable is not equal to $old%"
}

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$storytext</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">Analyzing the data, they find that approximately `$point%` of $individuals $variable, with a `$con%` confidence interval of `($low%, $high%)`.</p>
    <p style="margin:0;">How should the $group interpret the results of this survey?</p>
  </div>
</div>

///

<b>Step 1: Identify what to compare.</b><br>
We compare the hypothesized population proportion `$old%` to the `$con%` confidence interval `($low%, $high%)`. We don't focus on the point estimate `$point%` because a point estimate is rarely exactly equal to the population value.<br><br>

<b>Step 2: Place the hypothesized value on a number line.</b><br>
The hypothesized value `$old%` relative to `$low%`, `$point%`, and `$high%`:<br>
$nlpic<br><br>

<b>Step 3: Apply the CI-to-HT rule.</b><br>
The hypothesized value is $relat, so $altrelat. Therefore $conclusion.
