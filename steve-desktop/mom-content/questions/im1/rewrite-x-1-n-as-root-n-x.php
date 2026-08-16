// === NAME - DESCRIPTION: Rewrite `x^(1/n)` as `root(n)(x)`. (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$n=rand(2,11)

$expr=makepretty("x^(1/$n)")

if ($n==2) {    
   $answer="sqrt(x)"  
}
{  $answer="root($n)(x)"
  
}if ($n!=2)



$requiretimes = "/,=0"

// === QUESTION TEXT ===
Rewrite `$expr \ ` in radical form.

`$expr =\ ` $answerbox
