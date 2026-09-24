// === NAME - DESCRIPTION: Identify which pattern grows by adding the same amount each time ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===
$swap = rand(0,1)
$d = rand(2,6)
$a = rand(1,9)
$k = rand(0,5)
$t1 = $a
$t2 = $a + $d
$t3 = $a + 2*$d
$t4 = $a + 3*$d
$s1 = $k + 1
$s2 = $k + 4
$s3 = $k + 9
$s4 = $k + 16
if ($swap == 0) {
  $A1 = $t1
  $A2 = $t2
  $A3 = $t3
  $A4 = $t4
  $B1 = $s1
  $B2 = $s2
  $B3 = $s3
  $B4 = $s4
  $linLabel = "Pattern A"
  $sqLabel = "Pattern B"
} else {
  $A1 = $s1
  $A2 = $s2
  $A3 = $s3
  $A4 = $s4
  $B1 = $t1
  $B2 = $t2
  $B3 = $t3
  $B4 = $t4
  $linLabel = "Pattern B"
  $sqLabel = "Pattern A"
}
$correct = "$linLabel grows by adding the same amount each time."
$d1 = "$sqLabel grows by adding the same amount each time."
$d2 = "Both patterns grow by adding the same amount each time."
$d3 = "Neither pattern grows by adding the same amount each time."
$questions = array($correct, $d1, $d2, $d3)
$answer = 0

$code = "setBorder(4); initPicture(-0.5,8.8,-3,30); fill='lightblue';"
$code .= "rect([0,0],[0.8,$A1]);"
$code .= "rect([1,0],[1.8,$A2]);"
$code .= "rect([2,0],[2.8,$A3]);"
$code .= "rect([3,0],[3.8,$A4]);"
$code .= "text([1.9,-2],'Pattern A');"
$code .= "fill='orange';"
$code .= "rect([5,0],[5.8,$B1]);"
$code .= "rect([6,0],[6.8,$B2]);"
$code .= "rect([7,0],[7.8,$B3]);"
$code .= "rect([8,0],[8.8,$B4]);"
$code .= "text([6.9,-2],'Pattern B');"
$patimg = showasciisvg($code,420,230,'Two bar patterns. Pattern A has four bars of height ' . $A1 . ', ' . $A2 . ', ' . $A3 . ', and ' . $A4 . '. Pattern B has four bars of height ' . $B1 . ', ' . $B2 . ', ' . $B3 . ', and ' . $B4 . '.');
$answer = 0
$solutionguide = '<p>Check each pattern by subtracting consecutive terms.</p><p><b>' . $linLabel . ':</b> the gaps are ' . $d . ', ' . $d . ', and ' . $d . '. The same amount every time, so this pattern grows by adding a constant.</p><p><b>' . $sqLabel . ':</b> the gaps are 3, 5, and 7. The gaps get larger, so this pattern does not add the same amount each time.</p><p>The pattern that adds the same amount each time is ' . $linLabel . '.</p>'

// === QUESTION TEXT ===
<p>Two patterns are shown below.</p>
<p style="text-align:center">$patimg</p>
<p><b>Pattern A:</b> $A1, $A2, $A3, $A4</p>
<p><b>Pattern B:</b> $B1, $B2, $B3, $B4</p>
<p>Which pattern grows by adding the same amount each time?</p>
$answerbox

// === ANSWER ===
$solutionguide
