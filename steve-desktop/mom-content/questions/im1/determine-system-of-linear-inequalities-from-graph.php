// === NAME - DESCRIPTION: Determine system of linear inequalities from graph  ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("JSXG");

$anstypes = array("numfunc", "numfunc");
$answerformat[0] = 'inequality';
$answerformat[1] = 'inequality';
$variables = 'x,y';

// Generate two distinct reduced rational slopes
for ($i = 0..10) {
  $num1 = rand(-4, 4);
  $den1 = rand(1, 4);
  $num2 = rand(-4, 4);
  $den2 = rand(1, 4);
  if ($num1 != 0 && $num2 != 0 && ($num1 / $den1) != ($num2 / $den2)) {
    break;
  }
}

// Slope 1
$gcd1 = gcd(abs($num1), $den1);
$numr1 = $num1 / $gcd1;
$denr1 = $den1 / $gcd1;
$m1 = $numr1 / $denr1;
$m1_str = ($denr1 == 1) ? "$numr1" : "$numr1/$denr1";

// Slope 2
$gcd2 = gcd(abs($num2), $den2);
$numr2 = $num2 / $gcd2;
$denr2 = $den2 / $gcd2;
$m2 = $numr2 / $denr2;
$m2_str = ($denr2 == 1) ? "$numr2" : "$numr2/$denr2";

// Intercepts
$b1 = rand(-4, 4);
$b2 = rand(-4, 4);

// Inequality types
$ineqs = array("<", "<=", ">", ">=");
$ineq1 = $ineqs[rand(0, 3)];
$ineq2 = $ineqs[rand(0, 3)];

// Define correct answers
$eqn1 = "y $ineq1 {$m1_str}x + $b1";
$eqn2 = "y $ineq2 {$m2_str}x + $b2";
$answer[0] = $eqn1;
$answer[1] = $eqn2;

// Display values (optional)
$disp1 = makexxpretty($eqn1);
$disp2 = makexxpretty($eqn2);

// Create JSXGraph board
$ops = array();
$ops['size'] = [500, 400];
$ops['bounds'] = [-10, 10, -10, 10];
$ops['axisLabel'] = ["`x`", "`y`"];
$ops['controls'] = ['no-pan'];
$ops['attributes'] = "{pan:false, zoom:false}";
$board = JSXG_createAxes("plot{$thisq}", $ops);

// Line 1
$ops = array();
$ops['rule'] = "$m1*x + $b1";
$ops['strokeColor'] = "red";
$ops['dash'] = ($ineq1 == "<" || $ineq1 == ">") ? 2 : 0;
$board = JSXG_addFunction($board, $ops);

// Line 2
$ops = array();
$ops['rule'] = "$m2*x + $b2";
$ops['strokeColor'] = "blue";
$ops['dash'] = ($ineq2 == "<" || $ineq2 == ">") ? 2 : 0;
$board = JSXG_addFunction($board, $ops);

// Shading polygons
$x1 = -12; $x2 = 12;
$y1a = $m1 * $x1 + $b1;
$y2a = $m1 * $x2 + $b1;
$y1b = $m2 * $x1 + $b2;
$y2b = $m2 * $x2 + $b2;
$offset = 0.2;

// Shade for line 1
if ($ineq1 == "<" || $ineq1 == "<=") {
  $poly1 = [[$x1, -10], [$x2, -10], [$x2, $y2a + $offset], [$x1, $y1a + $offset]];
} else {
  $poly1 = [[$x1, 10], [$x2, 10], [$x2, $y2a - $offset], [$x1, $y1a - $offset]];
}
$ops = array();
$ops['position'] = $poly1;
$ops['attributes'] = "{fillColor:'lightcoral', fillOpacity:0.5, borders:{visible:false}}";
$board = JSXG_addPolygon($board, $ops);

// Shade for line 2
if ($ineq2 == "<" || $ineq2 == "<=") {
  $poly2 = [[$x1, -10], [$x2, -10], [$x2, $y2b + $offset], [$x1, $y1b + $offset]];
} else {
  $poly2 = [[$x1, 10], [$x2, 10], [$x2, $y2b - $offset], [$x1, $y1b - $offset]];
}
$ops = array();
$ops['position'] = $poly2;
$ops['attributes'] = "{fillColor:'lightblue', fillOpacity:0.5, borders:{visible:false}}";
$board = JSXG_addPolygon($board, $ops);

// === QUESTION TEXT ===

<p>The graph below shows the solution region (darker overlap) for a system of two inequalities.</p>

$board

<p><b>Write the system of inequalities represented by the graph:</b></p>
<p>Red inequality: $answerbox[0]</p>
<p>Blue inequality: $answerbox[1]</p>
