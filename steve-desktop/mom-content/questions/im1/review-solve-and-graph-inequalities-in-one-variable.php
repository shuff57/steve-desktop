// === NAME - DESCRIPTION: Review Solve and Graph inequalities in one variable.  ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("interval")

// Define arrays to hold multiple inequality versions
$ineqtext = array();
$intervalans = array();
$inequalityans = array();
$drawans = array();

// === Version 0: Basic form: a(x - b) <= c ===
$a0 = rand(1,4);
$b0 = rand(-5,5);
$xsol0 = rand(-5,5);
$c0 = $a0 * ($xsol0 - $b0);
$ineqtext[0] = makepretty("{$a0}(x - ({$b0})) \\le {$c0}");
$inequalityans[0] = makepretty("x \\le {$xsol0}");
$intervalans[0] = "(-oo,{$xsol0}]";
$drawans[0] = intervalstodraw($intervalans[0], -10, 10);

// === Version 1: Sign flips: -a(x - b) > c ===
$a1 = rand(1,4);
$b1 = rand(-5,5);
$xsol1 = rand(-5,5);
$c1 = -1 * $a1 * ($xsol1 - $b1);
$ineqtext[1] = makepretty("-{$a1}(x - ({$b1})) > {$c1}");
$inequalityans[1] = makepretty("x < {$xsol1}");
$intervalans[1] = "(-oo,{$xsol1})";
$drawans[1] = intervalstodraw($intervalans[1], -10, 10);

// === Version 2: Distribute both sides: ax + b > c - dx ===
$a2 = rand(1,4);
$d2 = rand(1,4);
$b2 = rand(-10,10);
$xsol2 = rand(-5,5);
$coeff2 = $a2 + $d2;
$rhs2 = $coeff2 * $xsol2;
$c2 = $rhs2 + $b2;
$ineqtext[2] = makepretty("{$a2}x + {$b2} > {$c2} - {$d2}x");
$inequalityans[2] = makepretty("x > {$xsol2}");
$intervalans[2] = "({$xsol2},oo)";
$drawans[2] = intervalstodraw($intervalans[2], -10, 10);

// === Pick one at random ===
$pick = rand(0,2);

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
