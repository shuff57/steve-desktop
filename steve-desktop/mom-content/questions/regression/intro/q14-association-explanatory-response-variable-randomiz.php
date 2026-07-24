// === NAME - DESCRIPTION: Association explanatory/response variable, randomized scenarios and order. V1 (sleep/mood, caffeine/sleep, movie tickets/concessions, temp/pool, rain/pool) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")
$a = rand(0,4)
if ($a==1) {$a=rand(2,4)}
$noshuffle = "all"

//(x,y)
if ($a==0) {
  $scen = singleshuffle(array("rating of mood on a 1 - 10 scale","hours of sleep the previous night"))
  $fac = array("sleep","mood")
  $extra = " from a random sample of people:"}

if ($a==1) {
  $scen = singleshuffle(array("hours of sleep the previous night","typical daily consumption of caffeine"))
  $fac = array("caffeine consumption","sleep")
  $extra = " from a random sample of people:"}

if ($a==2) {
  $scen = singleshuffle(array("number of movie tickets purchased per hour","level of concession stand sales"))
  $fac = array("tickets purchased","concession sales per hour")
  $extra = " for a movie theater:"}

if ($a==3) {
  $scen = singleshuffle(array("daily high temperature","daily admission revenue at a public pool"))
  $fac = array("temperature","pool admission revenue")
  $extra = ":"}

if ($a==4) {
  $scen = singleshuffle(array("daily precipitation level","daily admission revenue at a public pool"))
  $fac = array("precipitation","pool admission revenue")
  $extra = ":"}

$ans = array(0,1)
$quest, $ans = jointshuffle($fac,$ans)

$questions[0] = $quest
$answer[0] =  0
if ($ans[0]==1) {$answer[0] = 1}

$questions[1] = $quest
$answer[1] = 1
if ($ans[1]==0) {$answer[1] = 0}

$questions[2] = array("positive", "negative", "neither")
$ansdir = array(0 ,1, 0, 0, 1)
$answer[2] =  $ansdir[$a]

// === QUESTION TEXT ===

<p>Suppose you were to collect data for the following pair of variables$extra</p>
<ul>
  <li>$scen[0]</li>
  <li>$scen[1]</li>
</ul>
<p>You want to make a scatterplot.</p>
<p>a. Which variable would you use as the explanatory variable? $answerbox[0]</p>
<p>b. Which variable would you use as the response variable? $answerbox[1]</p>
<p>c. Would you expect to see a positive or negative association? $answerbox[2]</p>


// === ANSWER ===

$solutionguide
