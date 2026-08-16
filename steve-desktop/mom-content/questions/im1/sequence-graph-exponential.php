// === NAME - DESCRIPTION: Sequence - Graph Exponential (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
// Randomize the parameters
$randtype = rand(1,4);
$variables = "f,x";

##########################################################
// m = Random slope
// b = Random y-intercept
    $m = nonzerorand(-3, 3);
    $b = rrand(-5, 5,.5);  

// Pre-calculate the y-values for the dots
$y0 = $m * 0 + $b;  // Value at x = 0
$y1 = $m * 1 + $b;  // Value at x = 1
$y2 = $m * 2 + $b;  // Value at x = 2
$y3 = $m * 3 + $b;  // Value at x = 3
$y4 = $m * 4 + $b;  // Value at x = 4

// Pre-calculate the graph window
$xmin = -1;
$xmax = 5;
$ymin = min($y0, $y1, $y2, $y3, $y4) - 3; // Accommodate lowest y-value
$ymax = max($y0, $y1, $y2, $y3, $y4) + 3; // Accommodate highest y-value

if($b>=4 || $b<=-4){
  $ymin = min($y0, $y1, $y2, $y3, $y4) - 6; // Accommodate lowest y-value
	$ymax = max($y0, $y1, $y2, $y3, $y4) + 6; // Accommodate highest y-value
}

$points = arraystodoteqns([0,1,2,3,4],[$y0,$y1,$y2,$y3,$y4]);
$equ = "$m*x + $b";

$plot1 = showplot($points, $xmin, $xmax, $ymin, $ymax, 1, 1, 500, 800);
$plot2 = showplot($equ, $xmin, $xmax, $ymin, $ymax, 1, 1, 500, 800);
$plot = mergeplots($plot1, $plot2);

$answerformat = "equation";
$answer = "f(x)=$m x + $b";

// === QUESTION TEXT ===
What equation of the <b>function</b> that represents the relationship between `x and y` in the graph below ?  $answerbox

$plot
