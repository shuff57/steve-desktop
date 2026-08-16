// === NAME - DESCRIPTION: Exponential Growth!!  Equations from a Table ~ Vertical Table  y = a(b)^x  a = 2 to 7 b = 2 to 5 by 1's  Eric Petterson    (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$q = "numfunc"


$anstypes = "$q,$q,$q,$q,$q,$q" 


$a = rand (2,7)
$b = rand (2,5)

$ex[0] = makepretty($a($b)^0)

$ex[1] = makepretty($a($b)^1)

$ex[2]= makepretty ($a($b)^2)

$ex[3]= makepretty ($a($b)^3)

$ex[4]= makepretty ($a(1) + $b)

$ex[5]= makepretty ($a(2) + $b)

$answer[0]= makepretty($a($b)^4)

$answer[1] = makepretty($a($b)^5)

$answer[2] = makepretty($a($b)^6)

$answerformat = "number"

$answer[3]= makepretty ("$a")

$answer[4]= makepretty ("$b")

$answer[5] = makepretty ("$a($b)^x")

$answerboxsize[0] = 4
$answerboxsize[1] = 4
$answerboxsize[2] = 4

// === QUESTION TEXT ===
Table to Rule

Fill in the output (y) for the each input (x).

<center>
  <table class="stats">
    </th><th><b>Input (x)</th><th>Output (y)</b></th></tr></thead>
<tbody>
</td><td><center>0</td><td><center>$ex[0]</center></td></tr>
</td><td><center>1</td><td><center>$ex[1]</center></td></tr>
</td><td><center>2</td><td><center>$ex[2]</center></td></tr>
</td><td><center>3</td><td><center>$ex[3]</center></td></tr>
</td><td><center>4</td><td><center>$answerbox[0]</center></td></tr>
</td><td><center>5</td><td><center>$answerbox[1]</center></td></tr>
</td><td><center>6</td><td><center>$answerbox[2]</center></td></tr>
</tbody></table>

What is the Initial Value? $answerbox[3]

What is the Multiplier? $answerbox[4]

RULE : y = $answerbox[5]
