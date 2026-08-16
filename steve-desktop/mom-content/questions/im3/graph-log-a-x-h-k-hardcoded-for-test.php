// === NAME - DESCRIPTION: Graph log_a(x-h)+k (local for Steven Huff) hardcoded for test ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===
$a = 2;
$k = 2;
$ks = "+";
$h = -4;

// The makexxpretty function will process "x-4" correctly
$exp = makexxpretty("x+$h");

$grid = "-10,10,-10,10,1,1,400,400";
$answerformat = "twopoint,genlog";

// Calculation logic preserved for the answer key
$answers = "log_$a($exp) $ks $k";

$snaptogrid = .5;

// === QUESTION TEXT ===
Graph `y = log_$a($exp) $ks $k`
