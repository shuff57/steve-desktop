// === NAME - DESCRIPTION: Sequence - Graph Exponential (local for Steven Huff) ===
// === SET QUESTION TYPE TO: numfunc ===

// === COMMON CONTROL ===
// Randomize the parameters
$randtype = rand(1,4)
$variables = "f,x,a"

##########################################################
// a = Random y-int
// b = Random base 
if ($randtype == 1 ){
  $a = nonzerorand(-3, 3);  
  $b = 2; 
} elseif ($randtype == 2){
  $a = nonzerorand(-2, 2);  
  $b = 3;  
}elseif ($randtype == 3){
  $a = nonzerorand(-3, 3);  
  $b = .5;  
}elseif ($randtype == 4){
  $a = nonzerorand(-3, 3);  
  $b = 1/3;  
}

// Pre-calculate the y-values for the dots
$y0 = $a * ($b^0);  // Value at x = 0
$y1 = $a * ($b^1);  // Value at x = 1
$y2 = $a * ($b^2);  // Value at x = 2
$y3 = $a * ($b^3);  // Value at x = 3
$y4 = $a * ($b^4);  // Value at x = 4

// Pre-calculate the graph window
if ($randtype == 2){
  $xmin = -1;
  $xmax = 5;
  $ymin = -3;
  $ymax = $y3 + 3; // Ensure the graph accommodates the highest y-value

  $ymax = $y0+3 if ($a<0)
  $ymin = $y3-3 if ($a<0)
}else {
  $xmin = -1;
  $xmax = 5;
  $ymin = -3;
  $ymax = $y4 + 3; // Ensure the graph accommodates the highest y-value

  $ymax = $y0+3 if ($a<0)
  $ymin = $y4-3 if ($a<0)
}

$points = arraystodoteqns([0,1,2,3,4],[$y0,$y1,$y2,$y3,$y4])
$equ = "$a*($b^x)"
if($randtype == 3){
  $plot1 = showplot($points,$xmin,$xmax,$ymin,$ymax,1,.5,500,800)
  $plot2 = showplot($equ,$xmin,$xmax,$ymin,$ymax,1,.5,500,800)
  $plot = mergeplots($plot1,$plot2)
}elseif($randtype == 4){
  $plot1 = showplot($points,$xmin,$xmax,$ymin,$ymax,1,1/3,500,800)
  $plot2 = showplot($equ,$xmin,$xmax,$ymin,$ymax,1,1/3,500,800)
  $plot = mergeplots($plot1,$plot2)
}else{
  $plot1 = showplot($points,$xmin,$xmax,$ymin,$ymax,1,1,500,800)
  $plot2 = showplot($equ,$xmin,$xmax,$ymin,$ymax,1,1,500,800)
  $plot = mergeplots($plot1,$plot2)
}


$answerformat= "equation"
$answer= "f(x)=$a*$b^x"

// === QUESTION TEXT ===
What equation of the <b>function</b> that represents the relationship between `x` and `y` in the graph below? $answerbox

$plot
