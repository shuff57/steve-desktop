// === NAME - DESCRIPTION: LOG.2 - LA(10) Condense with Quotient Property  - rewrite  (local for Steven Huff) hardcoded for test ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes="numfunc";

// Hardcoded values
$a = 5;
$c = "x";

$variables=$c;

// Preserving the display logic
$eqn = makexxprettydisp ("log($a)-log($c)");

// Preserving answer requirements and logic
$requiretimes[0]="log,=1,$ev,=1,log(1),=0";
$answer[0]= ("log($a/$c)");

// === QUESTION TEXT ===
<p>Condense the following logarithm expression into 1 log.</p>
<p><span ><strong>&nbsp; &nbsp; &nbsp; &nbsp;$eqn</strong></span></p>
<p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;$answerbox[0]</strong></p>
