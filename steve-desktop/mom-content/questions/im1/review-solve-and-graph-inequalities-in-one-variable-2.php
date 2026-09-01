// === NAME - DESCRIPTION: Review Solve and Graph inequalities in one variable.   ===
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

$snaptogrid = 1;
$grid = "-15,15";
$answerboxsize = 4;

// === Step 1: Predefined (a, k) pairs where k % a == 0 ===
$a_vals = array(2, 3, 4, 5);
$k_vals = array(-10, -9, -8, -6, -5, 5, 6, 8, 9, 10);

// Build list of valid (a, k) where k % a == 0 and k ≠ 0
$valid_pairs = array();
for ($i=0..count($a_vals)-1) {
  for ($j=0..count($k_vals)-1) {
    if ($k_vals[$j] % $a_vals[$i] == 0) {
      $valid_pairs[] = array($a_vals[$i], $k_vals[$j]);
    }
  }
}

// Pick one valid (a, k) pair
$pick = rand(0, count($valid_pairs) - 1);
$a = $valid_pairs[$pick][0];
$k = $valid_pairs[$pick][1];
$b = abs($k / $a);

$ineq_display = "abs(x/$a) > $b";

// Compute boundaries
$negk = -1 * $k;
if ($k > 0) {
  $left = "(-oo,$negk)";
  $right = "($k,oo)";
  $interval = "(-oo,$negk)U($k,oo)";
  $leftineq = "x < $negk";
  $rightineq = "x > $k";
} else {
  $left = "(-oo,$k)";
  $right = "($negk,oo)";
  $interval = "(-oo,$k)U($negk,oo)";
  $leftineq = "x < $k";
  $rightineq = "x > $negk";
}

$answer[0] = $left;
$answer[1] = 1; // "OR"
$answer[2] = $right;
$answer[3] = intervalstodraw($interval, -15, 15);

// === QUESTION TEXT ===

<p>Solve and graph `$ineq_display`
  <br /><br />
  Solution: $answerbox[0] $answerbox[1] $answerbox[2]
  <br /><br />
  Graph: $answerbox[3]</p>
