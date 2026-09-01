// === NAME - DESCRIPTION: Solve and Graph inequalities in one variable. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("interval")

// Arrays to store multiple versions
$ineqtext = array();
$intervalans = array();
$inequalityans = array();
$drawans = array();

// === Version 0: General form: ax + b > c - dx ===
$a0 = rand(1,4);
$d0 = rand(1,4);
$coeff0 = $a0 + $d0;
$xsol0 = rand(-5,5);
$b0 = rand(-10,10);
$rhs0 = $coeff0 * $xsol0;
$c0 = $rhs0 + $b0;
$ineqtext[0] = makepretty("{$a0}x + {$b0} > {$c0} - {$d0}x");
$inequalityans[0] = makepretty("x > {$xsol0}");
$intervalans[0] = "({$xsol0},oo)";
$drawans[0] = intervalstodraw($intervalans[0], -10, 10);

// === Version 1: a(x - b) ≤ c ===
$a1 = rand(1,4);
$b1 = rand(-5,5);
$xsol1 = rand(-5,5);
$c1 = $a1 * ($xsol1 - $b1);
$ineqtext[1] = makepretty("{$a1}(x - {$b1}) \\le {$c1}");
$inequalityans[1] = makepretty("x \\le {$xsol1}");
$intervalans[1] = "(-oo,{$xsol1}]";
$drawans[1] = intervalstodraw($intervalans[1], -10, 10);

// === Version 2: -a(x - b) > c (inequality flips) ===
$a2 = rand(1,4);
$b2 = rand(-5,5);
$xsol2 = rand(-5,5);
$c2 = -1 * $a2 * ($xsol2 - $b2);
$ineqtext[2] = makepretty("-{$a2}(x - {$b2}) > {$c2}");
$inequalityans[2] = makepretty("x < {$xsol2}");
$intervalans[2] = "(-oo,{$xsol2})";
$drawans[2] = intervalstodraw($intervalans[2], -10, 10);

// === Version 3: ax + b < c ===
$a3 = rand(1,4);
$xsol3 = rand(-5,5);
$b3 = rand(-10,10);
$c3 = $a3 * $xsol3 + $b3;
$ineqtext[3] = makepretty("{$a3}x + {$b3} < {$c3}");
$inequalityans[3] = makepretty("x < {$xsol3}");
$intervalans[3] = "(-oo,{$xsol3})";
$drawans[3] = intervalstodraw($intervalans[3], -10, 10);

// === Randomly select a version ===
$pick = rand(0,3);

$ineq = $ineqtext[$pick];
$answer[0] = $intervalans[$pick];
$answer[1] = $drawans[$pick];

$answerformat[0] = "inequality";
$answerformat[1] = "numberline";
$anstypes = ["calcinterval", "draw"];
$grid = "-10,10";

// === QUESTION TEXT ===

<p>Solve and graph the inequality: `$ineq`
  <br /><br />
  Solution: $answerbox[0]
  <br /><br />
  Graph: $answerbox[1]</p>
