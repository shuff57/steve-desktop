// === NAME - DESCRIPTION: Signed Numbers, Absolute Value and Exponents - evaluate an absolute value, then a difference of two absolute values, then the pair -a^2 against (-a)^2 on the same digits ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* Book 0.2. Four parts that build to the one distinction the section exists for: whether the
   minus sign is inside the base or outside it. Parts (c) and (d) share the same digits and
   differ only in a pair of parentheses, so a student who answers them the same has shown the
   misconception rather than hidden it. $a < $b in part (a) on every seed, so the first
   absolute value genuinely has to flip a negative. */
$anstypes = array("number", "number", "number", "number")
$noshuffle[0] = "all"
$answerboxsize = [8,8,8,8]
$answeights = [1,1,1,1]

$a = rand(2, 8)
$b = $a + rand(3, 9)
$c = rand(10, 18)
$d = rand(2, 9)

$p = rand(2, 6)

$absone = $b - $a
$abstwo = $c - $d
$partb = $absone - $abstwo

$psq = $p * $p
$negpsq = -1 * $psq

$answer[0] = $absone
$answer[1] = $partb
$answer[2] = $negpsq
$answer[3] = $psq

$solutionguide = "<p><b>(a)</b> `$a - $b = -$absone`, and absolute value reports distance from zero, so it comes back positive: `|$a - $b| = $absone`.</p><p><b>(b)</b> The second one is already positive: `|$c - $d| = $abstwo`. Then `$absone - $abstwo = $partb`. Work each absolute value out completely before subtracting. The bars are grouping symbols, not a sign you can distribute.</p><p><b>(c) and (d) are the whole point.</b> They use the same digits and differ by one pair of parentheses.</p><p>`-$p^2` has no parentheses, so the exponent belongs to `$p` alone. Square first, negate second: `$p^2 = $psq`, then the minus gives `$negpsq`.</p><p>`(-$p)^2` puts the minus <i>inside</i> the base. The base is `-$p`, and squaring a negative gives a positive: `(-$p)(-$p) = $psq`.</p><p>If those two came out the same, the parentheses were read as decoration. They decide what is being squared.</p>"

// === QUESTION TEXT ===
<p>Evaluate each expression.</p>

<p><b>(a)</b> `|$a - $b|` = $answerbox[0]</p>

///

<p><b>(b)</b> `|$a - $b| - |$c - $d|` = $answerbox[1]</p>

///

<p><b>(c)</b> `-$p^2` = $answerbox[2]</p>

///

<p><b>(d)</b> `(-$p)^2` = $answerbox[3]</p>

<p>If your answers to (c) and (d) are the same, look again at what each exponent is attached to.</p>

// === ANSWER ===
$solutionguide
