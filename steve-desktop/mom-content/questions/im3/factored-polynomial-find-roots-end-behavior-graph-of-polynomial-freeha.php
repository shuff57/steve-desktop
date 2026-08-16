// === NAME - DESCRIPTION: Factored polynomial find roots, end behavior, graph of polynomial (Freehand) (copy by Edward Co) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = listtoarray("calculated, choices, choices, draw")

$answeights = array(.5, 0.5, 0.5, 2.5)

$a, $b = diffrands(2, 6, 2)

$eqn = makexxprettydisp("f(x) = -x(x - $a)(x + $b)^2")

$answer[0] = "0, $a, -$b"
$answerformat[0] = "list"
$answerformat[3] = "dot,freehand"

$choices[1] = array("up", "down")
$choices[2] = array("up", "down")
$noshuffle[1] = "all"
$noshuffle[2] = "all"
$displayformat[1] = "select"
$displayformat[2] = "select"

$answer[1] = 1
$answer[2] = 1
$answer[3] = array("-x(x-$a)(x+$b)^2","$a,0","-$b,0","0,0")
$grid = "-7,7,-8,8,1,1,500,300"
$snaptogrid = .25

// === QUESTION TEXT ===
<p>Find the roots and end behavior of the graph.&nbsp; Then graph it.</p>
<p>$eqn</p>
<p>A.&nbsp; roots = $answerbox[0]&nbsp; List the x-values only.&nbsp; Do not give ordered pairs.</p>
<p>B.&nbsp; end behavior:&nbsp; ($answerbox[1], $answerbox[2]</p>
<p>C.&nbsp; Draw the graph on your work paper.</p>
$answerbox[3]
