// === NAME - DESCRIPTION: Review Solve and Graph inequalities in one variable. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("interval");

$anstypes = ["calcinterval", "choices", "calcinterval", "draw"];
$answerformat[0] = "inequality";
$answerformat[1] = "select";
$answerformat[2] = "inequality";
$answerformat[3] = "numberline";

$displayformat[1] = "select";
$questions[1] = array("AND", "OR");

$answerboxsize = 4;
$snaptogrid = 1;

// Step 1: Build list of valid (a, b, c) where bounds are integers within [-12, 12]
$a_vals = array(1, 2, 3, 4, 5);
$b_vals = array(-10, -8, -6, -4, -2, 0, 2, 4, 6, 8, 10);
$c_vals = array(6, 8, 10, 12, 14, 16, 18, 20);

$valid_triples = array();

for ($i=0..count($a_vals)-1) {
  for ($j=0..count($b_vals)-1) {
    for ($k=0..count($c_vals)-1) {
      $a = $a_vals[$i];
      $b = $b_vals[$j];
      $c = $c_vals[$k];

      $left = (-$c - $b) / $a;
      $right = ($c - $b) / $a;

      if (($left == floor($left)) && ($right == floor($right)) && abs($left) <= 12 && abs($right) <= 12) {
        $valid_triples[] = array($a, $b, $c);
      }
    }
  }
}

// Step 2: Pick one valid (a, b, c)
$pick = rand(0, count($valid_triples) - 1);
$a = $valid_triples[$pick][0];
$b = $valid_triples[$pick][1];
$c = $valid_triples[$pick][2];

// Step 3: Pick inequality type and logic
$type = rand(0,3); // 0: >, 1: <, 2: ≥, 3: ≤
$symbols = array(">", "<", "≥", "≤");
$comp = $symbols[$type];
$logic = ($type % 2 == 0) ? 1 : 0; // 1: OR, 0: AND

// Step 4: Compute bounds (already guaranteed integers)
$leftbound = (-$c - $b) / $a;
$rightbound = ($c - $b) / $a;

// Step 5: Display format for MathJax
$ineq_display = "|$a x " . ($b < 0 ? "- " . abs($b) : "+ $b") . "| $comp $c";

// Step 6: Construct inequality parts and intervals
if ($type == 0) { // >
  $leftineq = "x < $leftbound";
  $rightineq = "x > $rightbound";
  $left = "(-oo,$leftbound)";
  $right = "($rightbound,oo)";
  $interval = "$left U $right";
} elseif ($type == 1) { // <
  $leftineq = "x > $leftbound";
  $rightineq = "x < $rightbound";
  $left = "($leftbound,oo)";
  $right = "(-oo,$rightbound)";
  $interval = "($leftbound,$rightbound)";
} elseif ($type == 2) { // ≥
  $leftineq = "x <= $leftbound";
  $rightineq = "x >= $rightbound";
  $left = "(-oo,$leftbound]";
  $right = "[$rightbound,oo)";
  $interval = "$left U $right";
} else { // ≤
  $leftineq = "x >= $leftbound";
  $rightineq = "x <= $rightbound";
  $left = "[$leftbound,oo)";
  $right = "(-oo,$rightbound]";
  $interval = "[$leftbound,$rightbound]";
}

// Step 7: Final answers and graph
$answer[0] = $left;
$answer[1] = $logic;
$answer[2] = $right;
$answer[3] = intervalstodraw($interval, -15, 15, 250);
$grid = "-15,15";

// === QUESTION TEXT ===

<p>Solve and graph: `$ineq_display`
  <br /><br />
  Solution: $answerbox[0] $answerbox[1] $answerbox[2]
  <br /><br />
  Graph: $answerbox[3]</p>
