// === NAME - DESCRIPTION: Exponential Growth!!   Horizontal Table ~ Complete Table and Find the Rule y = a(b)^x  a = 2 to 7 b = 2 to 5 by 1's  Eric Petterson   (local for Steven Huff) ===
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
Complete the Table and Find the Rule

<center>
  <table class="stats">
    <tbody>
    </th><th><b>Input (x)</th>`</td><td>`0`</td><td><center>`1`<center></td><td><center>`2`<center></td><td><center>`3`<center></td><td><center>`4`<center></td><td><center>`5`<center></td><td><center>`6`<center></td></tr>
      <th>Output (y)</b></th>`</td><td><center>$ex[0]</center></td><td><center>$ex[1]</center></td><td><center>$ex[2]</center></td><td><center>$ex[3]</center></td><td><center>$answerbox[0]</center></td><td><center>$answerbox[1]</center></td><td><center>$answerbox[2]</center></td></tr>

      </tbody></table>

      What is the multiplier? $answerbox[4]        

      What is the initial value? $answerbox[3]

      RULE: y = $answerbox[5]
