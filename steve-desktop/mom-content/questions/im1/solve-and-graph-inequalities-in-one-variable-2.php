// === NAME - DESCRIPTION: Solve and Graph inequalities in one variable. ===
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
$grid = "-25,25";
$answerboxsize = 4;

// Step 1: Predefined (a, k) where k % a == 0
$a_vals = array(2, 3, 4, 5);
$k_vals = array(-10, -9, -8, -6, -5, 5, 6, 8, 9, 10);

$valid_pairs = array();
for ($i=0..count($a_vals)-1) {
  for ($j=0..count($k_vals)-1) {
    if ($k_vals[$j] % $a_vals[$i] == 0 && $k_vals[$j] != 0) {
      $valid_pairs[] = array($a_vals[$i], $k_vals[$j]);
    }
  }
}

// Step 2: Pick (a, k), compute b
$pick = rand(0, count($valid_pairs) - 1);
$a = $valid_pairs[$pick][0];
$k = $valid_pairs[$pick][1];
$b = abs($k / $a);

// Step 3: Random negative multiplier
$m_vals = array(-2, -3, -4, -5, 2, 3, 4, 5);
$m = $m_vals[rand(0, count($m_vals) - 1)];
$rhs = $m * $b;

// Step 4: Randomly choose one of the 4 inequality types
// 0 = ">", 1 = "<", 2 = "≥", 3 = "≤"
$type = rand(0,3);
$symbols = array(">", "<", "\\ge", "\\le");
$comp = $symbols[$type];

$leftbound = -1 * $a * $b;
$rightbound = $a * $b;

if($m < 0 ){
  $oldtype = $type
  if ($type == 0) {
    $type = 1; 
  }
  elseif ($type == 1) { 
    $type = 0; 
  }
  elseif ($type == 2) { 
    $type = 3; 
  }
  elseif ($type == 3) { 
    $type = 2; 
  }
  $newtype = $type
  
  if ($type == 1) {
  // abs(x/a) > b → x < -k OR x > k
  $ineq_display = "$m abs(x/$a) > $rhs";
  $logic = 1;
  $leftineq = "x > $leftbound";
  $rightineq = "x < $rightbound";
  $left = "($leftbound,oo)";
  $right = "(-oo,$rightbound)";
  $interval = "($leftbound,$rightbound)";
} elseif ($type == 0) {
  // abs(x/a) < b → x > -k AND x < k
  $ineq_display = "$m abs(x/$a) < $rhs";
  $logic = 0;
  $leftineq = "x < $leftbound";
  $rightineq = "x > $rightbound";
  $left = "(-oo,$leftbound)";
  $right = "($rightbound,oo)";
  $interval = "$left U $right";
} elseif ($type == 3) {
  // abs(x/a) ≥ b → x <= -k OR x >= k
  $ineq_display = "$m abs(x/$a) \\ge $rhs";
  $logic = 1;
  $leftineq = "x <= $leftbound";
  $rightineq = "x >= $rightbound";
  $left = "(-oo,$leftbound]";
  $right = "[$rightbound,oo)";
  $interval = "$left U $right";
} else {
  // abs(x/a) ≤ b → x ≥ -k AND x ≤ k
  $ineq_display = "$m abs(x/$a) \\le $rhs";
  $logic = 0;
  $leftineq = "x >= $leftbound";
  $rightineq = "x <= $rightbound";
  $left = "[$leftbound,oo)";
  $right = "(-oo,$rightbound]";
  $interval = "[$leftbound,$rightbound]";
}
  
}
if($m > 0){
if ($type == 1) {
  // abs(x/a) > b → x < -k OR x > k
  $ineq_display = "$m abs(x/$a) > $rhs";
  $logic = 1;
  $leftineq = "x < $leftbound";
  $rightineq = "x > $rightbound";
  $left = "(-oo,$leftbound)";
  $right = "($rightbound,oo)";
  $interval = "$left U $right";
} elseif ($type == 0) {
  // abs(x/a) < b → x > -k AND x < k
  $ineq_display = "$m abs(x/$a) < $rhs";
  $logic = 0;
  $leftineq = "x > $leftbound";
  $rightineq = "x < $rightbound";
  $left = "($leftbound,oo)";
  $right = "(-oo,$rightbound)";
  $interval = "($leftbound,$rightbound)";
} elseif ($type == 3) {
  // abs(x/a) ≥ b → x <= -k OR x >= k
  $ineq_display = "$m abs(x/$a) \\ge $rhs";
  $logic = 1;
  $leftineq = "x <= $leftbound";
  $rightineq = "x >= $rightbound";
  $left = "(-oo,$leftbound]";
  $right = "[$rightbound,oo)";
  $interval = "$left U $right";
} else {
  // abs(x/a) ≤ b → x ≥ -k AND x ≤ k
  $ineq_display = "$m abs(x/$a) \\le $rhs";
  $logic = 0;
  $leftineq = "x >= $leftbound";
  $rightineq = "x <= $rightbound";
  $left = "[$leftbound,oo)";
  $right = "(-oo,$rightbound]";
  $interval = "[$leftbound,$rightbound]";
}
}

// Step 5: Final answers
$answer[0] = $left;
$answer[1] = $logic;
$answer[2] = $right;
$maxbound = max(abs($leftbound), abs($rightbound));
$zoom = $maxbound + 2;
$answer[3] = intervalstodraw($interval, -$zoom, $zoom, 250);
$grid = "-$zoom,$zoom";

// === QUESTION TEXT ===

$oldtype
$newtype
<p>Solve and graph `$ineq_display`
  <br /><br />
  Solution: $answerbox[0] $answerbox[1] $answerbox[2]
  <br /><br />
</p>
<p><em>Use the number line below to visualize the solution:</em></p>
<div style="width: 700px;">$answerbox[3]</div>
