// === NAME - DESCRIPTION: Is it a function from a table - decide whether a table of inputs and outputs represents a function ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===
$isFunc = rand(0, 1)
$xs = diffrands(-6, 6, 4)
$ys = diffrands(-9, 9, 4)
$x1 = $xs[0]
$x2 = $xs[1]
$x3 = $xs[2]
$x4 = $xs[3]
$y1 = $ys[0]
$y2 = $ys[1]
$y3 = $ys[2]
$y4 = $ys[3]

$x4 = $x1 if ($isFunc == 0)
$y4 = $y1 + nonzerorand(-4, 4) if ($isFunc == 0)

$questions = array(
  "Yes, it is a function because each input has exactly one output.",
  "No, it is not a function because one input has two different outputs.",
  "Yes, it is a function because each output appears at least once.",
  "No, it is not a function because two inputs share the same output."
)
$answer = 0 if ($isFunc == 1)
$answer = 1 if ($isFunc == 0)

$solutionguide = "<p>Every input in the table is paired with exactly one output, so this table represents a function.</p>" if ($isFunc == 1)
$solutionguide = "<p>The input $x1 appears twice. It is paired with $y1 in one row and with $y4 in another. One input with two different outputs means this table does not represent a function.</p>" if ($isFunc == 0)

$dots = array(
  "dot,$x1,$y1,closed,blue",
  "dot,$x2,$y2,closed,blue",
  "dot,$x3,$y3,closed,blue",
  "dot,$x4,$y4,closed,blue"
)
$scatter = showplot($dots,-9,9,-11,11,2,2,260,260)
$scatter = replacealttext($scatter,"A scatter plot of the four input and output pairs from the table, plotted as points on a coordinate plane.")

// === QUESTION TEXT ===
<p>Consider the table of inputs and outputs below. The same pairs are plotted to the right.</p>
<p style="text-align:center">$scatter</p>

<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <th>input</th>
    <th>output</th>
  </tr>
  <tr>
    <td>$x1</td>
    <td>$y1</td>
  </tr>
  <tr>
    <td>$x2</td>
    <td>$y2</td>
  </tr>
  <tr>
    <td>$x3</td>
    <td>$y3</td>
  </tr>
  <tr>
    <td>$x4</td>
    <td>$y4</td>
  </tr>
</table>

<p>Does this table represent a function?</p>

// === ANSWER ===
$solutionguide
