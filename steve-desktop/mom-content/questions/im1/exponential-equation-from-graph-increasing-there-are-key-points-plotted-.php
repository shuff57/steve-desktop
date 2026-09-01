// === NAME - DESCRIPTION: Exponential Equation from Graph - Increasing (There are key points plotted on the graph no vert shift) (local for Scott Cater) (copy by Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===

$a = rand(2,6)
$b = nonzerorrand(.1,2,.1) where ($b!=1)
$k = rand(2,10)
$ab = $a($b^1)
$p1 = $a
$graphs[0] = "dot,0,$p1,closed,black,   (0⍪ $p1) ,right" 
$graphs[1] = "dot,1,$ab,closed,black,   (1⍪ $ab) ,right" 
$graphs[2] = "$a($b^x),blue,,,,,2" 
$graphs[3] = "0,black,,,hello,,2,dash,,,,,,,hello" 
if ($ab > $p1) {
  $top = $ab +5 
} else {
  $top = $p1 + 5
}

$plot = showplot($graphs,-8,8,-1,$top,1,1,500,500)
$variables = "y,x"
$answerformat="equation"
$answer = "y=$a($b)^x"

$answerboxsize=16

// === QUESTION TEXT ===

<p>$plot
  <br /><br />
  <strong>Write the equation for the graph above. </strong>
  <br /><br />
  &nbsp; $answerbox</p>
<p><span style="color: #ff0000;"><em>** Make sure you start your equation with "y="</em></span></p>
