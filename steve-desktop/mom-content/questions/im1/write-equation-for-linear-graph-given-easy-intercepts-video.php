// === NAME - DESCRIPTION: write equation for linear graph given - easy intercepts (video) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
$mf = makereducedfraction(nonzerorand(-5,5),2);
$b = nonzerorand(-3,3);
$variables = "x";
$ansprompt = "`y=`"

$plot = showplot("{$mf}*x + $b",-3,3,-5,5);

$answer = makexxpretty("$mf x+$b")

// === QUESTION TEXT ===
<p>Write an equation for the graph below in terms of `x` <br /> $plot</p>
