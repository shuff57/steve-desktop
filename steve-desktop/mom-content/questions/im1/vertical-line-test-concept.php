// === NAME - DESCRIPTION: Vertical line test - choose which crossing description means the graph is a function ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===
$correct = "A vertical line crosses the graph in exactly one point everywhere."
$d1 = "A vertical line crosses the graph in two points somewhere."
$d2 = "A horizontal line crosses the graph in exactly one point everywhere."
$d3 = "A vertical line misses the graph entirely somewhere."

$ind = rand(0, 3)
$questions = array($correct, $d1, $d2, $d3) if ($ind == 0)
$questions = array($d3, $correct, $d1, $d2) if ($ind == 1)
$questions = array($d2, $d3, $correct, $d1) if ($ind == 2)
$questions = array($d1, $d2, $d3, $correct) if ($ind == 3)
$answer = $ind

$gfun = showplot("x^2 - 3,blue,-3,3,closed,closed", -4, 4, -6, 6, 1, 1, 240, 240)
$gfun = replacealttext($gfun, "A parabola opening upward. Every vertical line meets it in exactly one point, so this graph is a function.")
$gnon = showplot("[t^2 - 3,t],blue,-3,3,closed,closed", -6, 4, -4, 4, 1, 1, 240, 240)
$gnon = replacealttext($gnon, "A parabola opening to the right. A vertical line to the right of its vertex meets it in two points, so this graph is not a function.")

$solutionguide = "<p>The vertical line test says a graph represents a function when every vertical line meets the graph at most once. The first graph passes the test: every vertical line touches it in exactly one point, so each input has one output. The second graph fails the test: a vertical line through the wide part of the sideways parabola hits it twice, so one input would have two outputs, which no function allows. A horizontal line tells you nothing about whether a graph is a function, and a vertical line that misses the graph is not a problem.</p>"

// === QUESTION TEXT ===
<p>The two pictures below show one graph that is a function and one graph that is not.</p>
<p style="text-align:center">$gfun $gnon</p>
<p>Which description means a graph represents a function?</p>
$answerbox

// === ANSWER ===
$solutionguide
