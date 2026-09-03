// === NAME - DESCRIPTION: Fraction Operations - multiply, divide and raise to a power, all on the same pair of fractions, so the three procedures are compared rather than drilled apart ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* Book 0.3. One fraction pair carried through three operations, so "invert and multiply" in (b)
   is visibly not the same move as (a) rather than a rule recited beside it.

   The seed constraints make the MULTIPLY and the POWER land in lowest terms, but they cannot do
   the same for the DIVIDE: (a*d)/(b*c) is in lowest terms only if b and d are coprime and a and c
   are, and forcing all four pairwise coprime over 2..9 leaves almost no seeds (a=7,b=6 admits only
   c or d = 5). Measured live: 7/6 div 5/8 = 56/30, which reduces to 28/15. So the divide answer is
   REDUCED here explicitly with gcd() instead, and the solution guide shows that reduction as a step
   rather than claiming it never happens. `calculated` compares by value, so an unreduced entry
   still grades correct. This is about the worked explanation being true, not about the key. */
$anstypes = array("calculated", "calculated", "calculated")
$noshuffle[0] = "all"
$answeights = [1,1,1]

$a,$b = rands(2,9,2) where (gcd($a,$b)==1)
$c,$d = rands(2,9,2) where (gcd($c,$d)==1 && gcd($a,$d)==1 && gcd($c,$b)==1 && $a*$c != $b*$d)

$mulnum = $a * $c
$mulden = $b * $d
$rawdivnum = $a * $d
$rawdivden = $b * $c
$g = gcd($rawdivnum, $rawdivden)
$divnum = $rawdivnum / $g
$divden = $rawdivden / $g
$pownum = $a * $a
$powden = $b * $b

$answer[0] = "$mulnum/$mulden"
$answer[1] = "$divnum/$divden"
$answer[2] = "$pownum/$powden"

$solutionguide = "<p><b>(a) Multiplying</b> needs no common denominator at all. Multiply straight across: `$a/$b xx $c/$d = ($a xx $c)/($b xx $d) = $mulnum/$mulden`.</p><p><b>(b) Dividing</b> is that same move with one step in front of it: invert the second fraction, then multiply. `$a/$b -: $c/$d = $a/$b xx $d/$c = $rawdivnum/$rawdivden = $divnum/$divden`. The fraction that flips is the one you are dividing <i>by</i>; flipping the first one instead is the usual slip. Notice this one needed reducing and the other two did not. Division is where a common factor usually appears.</p><p><b>(c) A power</b> applies to the numerator and the denominator separately: `($a/$b)^2 = ($a xx $a)/($b xx $b) = $pownum/$powden`. It is not `$a^2/$b` and it is not `($a xx 2)/($b xx 2)`. Squaring is not doubling.</p><p>Parts (a) and (c) come out in lowest terms on their own; part (b) is the one to check for a common factor before you write it down.</p>"

// === QUESTION TEXT ===
<p>Use the fractions `$a/$b` and `$c/$d` for all three parts. Enter each answer as a fraction.</p>

<p><b>(a)</b> Multiply: `$a/$b xx $c/$d` = $answerbox[0]</p>

///

<p><b>(b)</b> Divide: `$a/$b -: $c/$d` = $answerbox[1]</p>

///

<p><b>(c)</b> Raise the first fraction to a power: `($a/$b)^2` = $answerbox[2]</p>

// === ANSWER ===
$solutionguide
