// === NAME - DESCRIPTION: Function machine, multiply then add, find three outputs ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "number,number,number"
$answerboxsize = [6,6,6]
$m = rand(2,6)
$b = rand(1,10)
{
  $x0 = rand(1,9)
  $x1 = rand(1,9)
  $x2 = rand(1,9)
} where ($x0 != $x1 && $x0 != $x2 && $x1 != $x2)
$y0 = $m*$x0 + $b
$y1 = $m*$x1 + $b
$y2 = $m*$x2 + $b
$answer[0] = $y0
$answer[1] = $y1
$answer[2] = $y2

$code = "setBorder(6); initPicture(-0.6,4.6,-0.2,3);"
$code .= "rect([1,0.8],[3.2,2.2]);"
$code .= "line([0,1.5],[1,1.5]);"
$code .= "line([3.2,1.5],[4.2,1.5]);"
$code .= "line([4.05,1.34],[4.2,1.5]);"
$code .= "line([4.05,1.66],[4.2,1.5]);"
$code .= "text([0.5,1.5],'Input','below');"
$code .= "text([3.7,1.5],'Output','below');"
$code .= "text([2.1,1.75],'multiply by $m');"
$code .= "text([2.1,1.25],'then add $b');"
$machineimg = showasciisvg($code,320,210,'A function machine. An input line enters a box labelled multiply by ' . $m . ' then add ' . $b . ', and an output line leaves the box.');

$solutionguide = '<p>The machine rule is: multiply the input by ' . $m . ', then add ' . $b . '.</p><p>For input ' . $x0 . ': ' . $m . ' times ' . $x0 . ' equals ' . ($m*$x0) . ', and ' . ($m*$x0) . ' plus ' . $b . ' equals ' . $y0 . '.</p><p>For input ' . $x1 . ': ' . $m . ' times ' . $x1 . ' equals ' . ($m*$x1) . ', and ' . ($m*$x1) . ' plus ' . $b . ' equals ' . $y1 . '.</p><p>For input ' . $x2 . ': ' . $m . ' times ' . $x2 . ' equals ' . ($m*$x2) . ', and ' . ($m*$x2) . ' plus ' . $b . ' equals ' . $y2 . '.</p>'

// === QUESTION TEXT ===
<p>A function machine follows one rule: multiply the input by $m, then add $b.</p>
<p style="text-align:center">$machineimg</p>
<p>Find the output for each input.</p>
<p>Input $x0 gives output $answerbox[0]</p>
<p>Input $x1 gives output $answerbox[1]</p>
<p>Input $x2 gives output $answerbox[2]</p>

// === ANSWER ===
$solutionguide
