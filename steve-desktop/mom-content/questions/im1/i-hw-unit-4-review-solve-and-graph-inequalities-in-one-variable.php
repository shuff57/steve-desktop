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

// Step 1: Choose m (≠ 0, ±1) and c
$m_vals = array(-5, -4, -3, -2, 2, 3, 4, 5);
$c_vals = array(5, 6, 8, 10, 12);

$m = $m_vals[rand(0, count($m_vals)-1)];
$c = $c_vals[rand(0, count($c_vals)-1)];
$abs_m = abs($m);

// Step 2: Generate (d - c) so (d - c) is divisible by abs(m)
$mults = array(8, 10, 12, 14, 16, 18, 20); // desired (d - c) values
$possible = array();
for ($i=0..count($mults)-1) {
  if ($mults[$i] % $abs_m == 0) {
    $possible[] = $mults[$i];
  }
}
$b = $possible[rand(0, count($possible)-1)];
$d = $b + $c;

// Step 3: Random inequality type
$type = rand(0,3); // 0: >, 1: <, 2: ≥, 3: ≤
$symbols = array(">", "<", "≥", "≤");
$comp = $symbols[$type];
$logic = ($type % 2 == 0) ? 1 : 0;

// Step 4: Solve for boundaries
$leftbound = -$b / $abs_m;
$rightbound = $b / $abs_m;

// Step 5: Display question
$ineq_display = "|$m x| + $c $comp $d";

// Step 6: Build inequalities and intervals
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

// Step 7: Final answers
$answer[0] = $left;
$answer[1] = $logic;
$answer[2] = $right;

$maxbound = max(abs($leftbound), abs($rightbound));
$zoom = $maxbound + 2;
$answer[3] = intervalstodraw($interval, -$zoom, $zoom, 250);
$grid = "-$zoom,$zoom";

// === QUESTION TEXT ===
<p>Solve and graph `$ineq_display`
<br /><br />
Solution: $answerbox[0] $answerbox[1] $answerbox[2]
<br /><br />
Graph: $answerbox[3]</p>
