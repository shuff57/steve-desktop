// === NAME - DESCRIPTION: Function machine, double then subtract, evaluate and find the input ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "number,number"
$answerboxsize = [6,6]
$b = rand(1,10)
{
  $x1 = rand(1,12)
  $x0 = rand(1,12)
} where ($x0 != $x1)
$y1 = 2*$x1 - $b
$out = 2*$x0 - $b
$answer[0] = $y1
$answer[1] = $x0

$code = "setBorder(6); initPicture(-0.6,4.6,-0.2,3);"
$code .= "rect([1,0.8],[3.2,2.2]);"
$code .= "line([0,1.5],[1,1.5]);"
$code .= "line([3.2,1.5],[4.2,1.5]);"
$code .= "line([4.05,1.34],[4.2,1.5]);"
$code .= "line([4.05,1.66],[4.2,1.5]);"
$code .= "text([0.5,1.5],'Input','below');"
$code .= "text([3.7,1.5],'Output','below');"
$code .= "text([2.1,1.75],'double the input');"
$code .= "text([2.1,1.25],'then subtract $b');"
$machineimg = showasciisvg($code,320,210,'A function machine. An input line enters a box labelled double the input then subtract ' . $b . ', and an output line leaves the box.');

$solutionguide = '<p>The machine rule is: double the input, then subtract ' . $b . '.</p><p><b>Part 1.</b> Double ' . $x1 . ' to get ' . (2*$x1) . ', then subtract ' . $b . ' to get ' . $y1 . '.</p><p><b>Part 2.</b> Work backwards from the output. The last step was subtracting ' . $b . ', so add ' . $b . ' back: ' . $out . ' plus ' . $b . ' equals ' . (2*$x0) . '. The first step was doubling, so divide by 2: ' . (2*$x0) . ' divided by 2 equals ' . $x0 . '. The input was ' . $x0 . '.</p>'

// === QUESTION TEXT ===
<p>A function machine follows one rule: double the input, then subtract $b.</p>
<p style="text-align:center">$machineimg</p>
<p>What is the output when the input is $x1?</p>
<p>Answer: $answerbox[0]</p>

///

<p>The machine produces an output of $out. What input produced it?</p>
<p>Answer: $answerbox[1]</p>

// === ANSWER ===
$solutionguide
