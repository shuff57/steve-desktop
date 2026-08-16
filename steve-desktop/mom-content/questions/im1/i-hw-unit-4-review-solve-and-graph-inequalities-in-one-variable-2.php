// === NAME - DESCRIPTION: I HW Unit 4 Review Solve and Graph inequalities in one variable.   (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
loadlibrary("interval")

$anstypes = ["calcinterval", "choices", "calcinterval", "draw"];
$answerformat[0] = "inequality";
$answerformat[1] = "select";
$answerformat[2] = "inequality";
$answerformat[3] = "numberline";

$displayformat[1] = "select";
$questions[1] = array("AND", "OR");

$answerboxsize = 4;
$snaptogrid = 1;

// Step 1: Build list of valid (A, B, C, D) values
$A_vals = array(4, 5, 6, 8, 10);
$B_vals = array(1, 2, 3, 4);
$C_vals = array(-10, -8, -6, -4, -2, 0, 2, 4, 6, 8);
$D_vals = array(16, 18, 20, 22, 24, 26, 28);

$valid_sets = array();

for ($i=0..count($A_vals)-1) {
  for ($j=0..count($B_vals)-1) {
    for ($k=0..count($C_vals)-1) {
      for ($l=0..count($D_vals)-1) {
        $A = $A_vals[$i];
        $B = $B_vals[$j];
        $C = $C_vals[$k];
        $D = $D_vals[$l];

        $rhs = $D - $A;
        $left = (-$rhs - $C) / $B;
        $right = ($rhs - $C) / $B;

        if ($rhs > 0) {
          if (abs($left) <= 12) {
            if (abs($right) <= 12) {
              if ($left == floor($left)) {
                if ($right == floor($right)) {
                  $valid_sets[] = array($A, $B, $C, $D);
                }
              }
            }
          }
        }
      }
    }
  }
}

// Step 2: Pick one valid (A, B, C, D)
$pick = rand(0, count($valid_sets) - 1);
$A = $valid_sets[$pick][0];
$B = $valid_sets[$pick][1];
$C = $valid_sets[$pick][2];
$D = $valid_sets[$pick][3];
$rhs = $D - $A;

// Step 3: Pick inequality type
$type = rand(0, 3); // 0: >, 1: <, 2: ≥, 3: ≤
$symbols = array(">", "<", "≥", "≤");
$comp = $symbols[$type];
$logic = ($type % 2 == 0) ? 1 : 0;

// Step 4: Solve bounds
$leftbound = round((-$rhs - $C) / $B);
$rightbound = round(($rhs - $C) / $B);

// Optional: Display expression as plain text
if ($C < 0) {
  $signC = "- " . abs($C);
} else {
  $signC = "+ $C";
}
$ineq_display = $A . " + |" . $B . "x " . $signC . "| " . $comp . " " . $D;

// Step 5: Build inequalities and interval
if ($type == 0) {
  $leftineq = "x < $leftbound";
  $rightineq = "x > $rightbound";
  $left = "(-oo,$leftbound)";
  $right = "($rightbound,oo)";
  $interval = "$left U $right";
} elseif ($type == 1) {
  $leftineq = "x > $leftbound";
  $rightineq = "x < $rightbound";
  $left = "($leftbound,oo)";
  $right = "(-oo,$rightbound)";
  $interval = "($leftbound,$rightbound)";
} elseif ($type == 2) {
  $leftineq = "x <= $leftbound";
  $rightineq = "x >= $rightbound";
  $left = "(-oo,$leftbound]";
  $right = "[$rightbound,oo)";
  $interval = "$left U $right";
} else {
  $leftineq = "x >= $leftbound";
  $rightineq = "x <= $rightbound";
  $left = "[$leftbound,oo)";
  $right = "(-oo,$rightbound]";
  $interval = "[$leftbound,$rightbound]";
}

// Step 6: Final Answers
$answer[0] = $left;
$answer[1] = $logic;
$answer[2] = $right;
$answer[3] = intervalstodraw($interval, -15, 15, 250);
$grid = "-15,15";

// === QUESTION TEXT ===
<p>Solve and graph: `$ineq_display`</p>
<p>Solution: $answerbox[0] $answerbox[1] $answerbox[2]</p>
<p>Graph: $answerbox[3]</p>
