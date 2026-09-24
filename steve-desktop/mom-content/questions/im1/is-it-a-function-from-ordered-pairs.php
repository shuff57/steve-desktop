// === NAME - DESCRIPTION: Is it a function from ordered pairs - decide whether a set of ordered pairs represents a function ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===
$isFunc = rand(0, 1)
$xs = diffrands(-7, 7, 5)
$ys = diffrands(-9, 9, 5)
$x1 = $xs[0]
$x2 = $xs[1]
$x3 = $xs[2]
$x4 = $xs[3]
$x5 = $xs[4]
$y1 = $ys[0]
$y2 = $ys[1]
$y3 = $ys[2]
$y4 = $ys[3]
$y5 = $ys[4]

$x5 = $x1 if ($isFunc == 0)
$y5 = $y1 + nonzerorand(-4, 4) if ($isFunc == 0)

$questions = array(
  "Yes, it is a function because no x-value is paired with two different y-values.",
  "No, it is not a function because one x-value is paired with two different y-values.",
  "Yes, it is a function because every y-value is used at least once.",
  "No, it is not a function because two different x-values share the same y-value."
)
$answer = 0 if ($isFunc == 1)
$answer = 1 if ($isFunc == 0)

$solutionguide = "<p>No x-value is repeated with two different y-values, so this set of ordered pairs represents a function.</p>" if ($isFunc == 1)
$solutionguide = "<p>The x-value $x1 appears twice. It is paired with $y1 in one pair and with $y5 in another. One x-value with two different y-values means this set of ordered pairs does not represent a function.</p>" if ($isFunc == 0)

$dots = array(
  "dot,$x1,$y1,closed,blue",
  "dot,$x2,$y2,closed,blue",
  "dot,$x3,$y3,closed,blue",
  "dot,$x4,$y4,closed,blue",
  "dot,$x5,$y5,closed,blue"
)
$scatter = showplot($dots,-9,9,-11,11,2,2,260,260)
$scatter = replacealttext($scatter,"A scatter plot of the five ordered pairs, plotted as points on a coordinate plane.")

// === QUESTION TEXT ===
<p>Consider the set of ordered pairs below. They are plotted to the right.</p>
<p style="text-align:center">$scatter</p>

<p style="text-align:center; font-size: large">`($x1, $y1)`, `($x2, $y2)`, `($x3, $y3)`, `($x4, $y4)`, `($x5, $y5)`</p>

<p>Does this set of ordered pairs represent a function?</p>

// === ANSWER ===
$solutionguide
