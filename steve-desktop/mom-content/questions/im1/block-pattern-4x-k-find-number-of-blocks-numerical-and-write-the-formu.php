// === NAME - DESCRIPTION: Block pattern `4x+k`. Find number of blocks (numerical) and write the formula. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "number,number,numfunc"
$answerboxsize = [6,6,20]
$answeights = [1,1,3]
$abstolerance[0] = 0.5
$abstolerance[1] = 0.5
$color = randfrom("transgreen,transred,transpurple,transpink")
$a,$b = diffrands(2,6,2)
$aplus2 = $a + 2
$bplus2 = $b + 2
$n = diffrands(40,100,2,'inc') where ($n[1]-$n[0] > 10)

for ($i=0..2) {
  $snum[$i] = $i+1
  $endhoriz = $a+$i
  $endvert = $b+$i
  $rowword = numtowords($endvert)
  $rowwordinside = numtowords($endvert-2)
  $rowsplural = ifthen($endvert == 3,"row","rows")
  $colword = numtowords($endhoriz)
  $colwordinside = numtowords($endhoriz-2)
  $columnsplural = ifthen($endhoriz == 3,"column","columns")
  $code[$i] = "fill='$color';"
  for ($j=1..$endvert) {
    if ($j==1 || $j==$endvert) {
      for ($k=1..$endhoriz) {
        $code[$i] .= "rect([$k-1,$j-1],[$k,$j]);"
      }
    } else {
      $code[$i] .= "rect([0,$j-1],[1,$j]); rect([$endhoriz-1,$j-1],[$endhoriz,$j]);"
    }
  }
  [$horizdim,$vertdim] = [$aplus2+2,$bplus2+2]
  if (($a==2 || $b==2) && $i==0) {
    $description[$i] = "A shaded rectangular grid containing $rowword rows of squares and $colword columns of squares."
  } else {
    $description[$i] = "A figure made of shaded squares that forms the boundary of an empty, rectangular space. The empty space could fit $rowwordinside $rowsplural of squares and $colwordinside $columnsplural of squares. Image is labeled 'Structure number $snum[$i]'."
  }  $im[$i] = showasciisvg("setBorder(10); initPicture(-1,$aplus2+1,-1,$bplus2+1); $code[$i]; text([$endhoriz/2,0],'Structure \#$snum[$i]','below');",160,160*($vertdim/$horizdim),$description[$i])
  $answer[$i] = 2*($a+$n[$i]-1) + 2*($b+$n[$i]-1) - 4 if ($i < 2)
  $const = 2*$a+2*$b-4
  $answer[$i] = "4 x + $const" if ($i == 2)
}
$structA = $n[0]
$structB = $n[1]

if (stuansready($stuanswers,$thisq,[2])) {
  $stu = getstuans($stuanswers,$thisq,2)
  if (comparefunctions($stu,"4 x + $const")) {
    $answer[2] = $stu
  }
}

// === QUESTION TEXT ===
Someone is using a pattern to build structures out of square blocks. The first three structures are shown below:
<p style="text-align:center">$im[0] $im[1] $im[2]</p>

How many blocks (the smallest squares) would be needed for Structure `#$structA`?

Answer: $answerbox[0]

///

How many blocks (the smallest squares) would be needed for Structure `#$structB`?

Answer: $answerbox[1]

///

How many blocks (the smallest squares) would be needed for Structure `x` (what is the expression? example `2x + 1`)?

Answer: $answerbox[2]
