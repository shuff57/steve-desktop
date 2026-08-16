// === NAME - DESCRIPTION: Translating statement into mathematical expression (a more than b times) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$t=rand(0,5)
$t1=array("men","women","seniors","juniors","math majors","English majors")
$t2=array("women","men","juniors","seniors","English majors","math majors")
$t3=array("m","w","s","j","m","e")
$t4=array("w","m","j","s","e","m")
$x1=$t3[$t]
$x2=$t4[$t]

$variables="$x1,$x2"

$men="men"
$women="women"
$a,$b=diffrands(2,10,2)
$answer="$a $x2 + $b"
$aword=numtowords($a)

// === QUESTION TEXT ===
The number of $men in a statistics class is `$b` more than $aword times the number of $women.

Let `$x2` represent the number of $women.

The number of $men can be represented by $answerbox.

// === ANSWER ===
If `$x2` represents the number of $women, then:

<ul>
  <li>$aword times the number of $women is `$a $x2`,</li>
  <li>`$b` more than $aword times the number of $women is `$a $x2 + $b`</li>
</ul>
