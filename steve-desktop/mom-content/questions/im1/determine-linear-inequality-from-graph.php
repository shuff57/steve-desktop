// === NAME - DESCRIPTION: Determine linear inequality from graph ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("JSXG");

$anstypes = array("numfunc");
$answerformat = 'inequality';
$variables = 'x,y';

// Generate reduced rational slope
$num = 0;
$den = 1;
for ($i = 0..10) {
  $num = rand(-4, 4);
  $den = rand(1, 4);
  if ($num != 0) {
    break;
  }
}

// Reduce the fraction
$gcd = gcd(abs($num), $den);
$numr = $num / $gcd;
$denr = $den / $gcd;

// String version of slope for equation display
if ($denr == 1) {
  $m_str = "$numr";
} else {
  $m_str = " $numr/ $denr";
}

// Decimal version for plotting
$m = $numr / $denr;

// Random y-intercept
$b = rand(-4, 4);

// Random inequality
$ineqs = array("<", "<=", ">", ">=");
$ineq = $ineqs[rand(0, 3)];

// Display and answer
$eqn = "y $ineq {$m_str}x + $b";
$disp = makexxpretty($eqn);
$answer[0] = $eqn;

// JSXGraph setup
$ops = array();
$ops['size'] = [450, 450];
$ops['bounds'] = [-10, 10, -10, 10];
$ops['axisLabel'] = ["`x`", "`y`"];
$ops['controls'] = ['no-pan'];
$board = JSXG_createAxes("plot{$thisq}", $ops);

// Add boundary line
$ops = array();
$ops['rule'] = "$m*x + $b";  // decimal for graph
$ops['strokeColor'] = "black";
$ops['dash'] = ($ineq == "<" || $ineq == ">") ? 2 : 0;
$board = JSXG_addFunction($board, $ops);

// Determine shading
$x1 = -12;
$x2 = 12;
$y1 = $m * $x1 + $b;
$y2 = $m * $x2 + $b;

if ($ineq == "<" || $ineq == "<=") {
  $shade_poly = [[$x1, -12], [$x2, -12], [$x2, $y2], [$x1, $y1]];
} else {
  $shade_poly = [[$x1, 12], [$x2, 12], [$x2, $y2], [$x1, $y1]];
}

$ops = array();
$ops['position'] = $shade_poly;
$ops['controls'] = ['no-pan'];
$ops['attributes'] = "{fillColor:'lightblue', fillOpacity:0.3, borders:{visible:false}}";

$board = JSXG_addPolygon($board, $ops);

// === QUESTION TEXT ===

<p>The graph below shows the solution to a linear inequality.</p>

$board

<p><b>Write the inequality represented by the graph.</b></p>
<p>Answer: $answerbox[0]</p>
