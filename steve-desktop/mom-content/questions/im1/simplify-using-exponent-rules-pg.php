// === NAME - DESCRIPTION: Simplify using exponent rules (.pg) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes=array("calculated","calculated")

$a = rrand(2,9,1);
$b = rrand(5,7,1);
$b1 = $b*$a;
$c = rrand(2,4,1);
$d = rrand(2,4,1);
$dd = rrand(9,12,1);

$ans1 = $b;
$ans2 = -$c-$d+$dd;

$answerboxsize[0]=10
$answerboxsize[1]=10

$answer[0]=$ans1
$answer[1]=$ans2

$hint = "<ul>
    <li>Remember `x^n` is the same as `x^n / 1`.</li>
    <li>Next you need to multiply 3 fractions.</li>
    <li>Lastly, you need to simplify the expression and cancel all that you can (it'll look something like ` # / x^exp`</li>
</ul>";

// Create the inline hint button
$form_inlinebutton = forminlinebutton("Hint", $hint, "button", "block");

// === QUESTION TEXT ===
The expression
<br/>` x^{$c}(\frac{x^{$d}}{$a} )(\frac{ $b1}{x^{$dd}}) `<br/>
equals  `c/x^e` where <br/>
the coefficient `c` is  $answerbox[0] ,
the exponent `e` is  $answerbox[1] .
<p></p>
<p>$form_inlinebutton</p>
