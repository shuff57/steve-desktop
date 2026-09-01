// === NAME - DESCRIPTION: Translation of a polygon given a description #2 ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===

$i = rand(2, 3); // Number of points to generate
$l = $i + 1;
$letters = array("A", "B", "C", "D", "E");
$p, $q = nonzerorands(-5, 5, 2); // Random transformation offsets

$used_points = array(); // To store unique points
$lines = array(); // To store line equations

for ($j = 0..$i) {
  $unique = 0;

  // Generate unique points within the grid limits
  for ($attempt = 1..50) { // Limit attempts to ensure uniqueness
    $x[$j], $y[$j] = nonzerorands(-5, 5, 2);
    $unique = 1;

    // Check against all previously stored points
    for ($k = 0..($j-1)) {
      if ($x[$j] == $x[$k] && $y[$j] == $y[$k]) {
        $unique = 0; // Not unique, regenerate
        break;
      }
    }

    // Check if the point lies on any existing line
    foreach ($lines as $line_index=>$line_values) {
      $a = $line_values[0];
      $b = $line_values[1];
      $c = $line_values[2];

      if (abs($a * $x[$j] + $b * $y[$j] + $c) < 0.001) { // Close to the line
        $unique = 0; // Point lies on the line, regenerate
        break;
      }
    }

    // Ensure transformation stays within bounds
    if ($unique == 1) {
      if (abs($x[$j] + $p) > 5 || abs($y[$j] + $q) > 5) {
        $unique = 0; // Transformation out of bounds, regenerate
      }
    }

    // If unique and within bounds, store and exit the loop
    if ($unique == 1) {
      break;
    }
  }

  // If no unique point is found after 50 attempts, adjust manually
  if ($unique == 0) {
    $x[$j] = rand(-5, 5 - abs($p)); // Adjust to ensure within bounds
    $y[$j] = rand(-5, 5 - abs($q)); // Adjust to ensure within bounds
  }

  // Generate line equations (for j > 0)
  if ($j != 0) {
    $k = $j - 1;
    $a = $y[$k] - $y[$j];
    $b = $x[$j] - $x[$k];
    $c = $x[$k] * $y[$j] - $x[$j] * $y[$k];
    $lines[] = array($a, $b, $c);
  }

  $object = "$letters[$j]($x[$j],$y[$j])" if $j == 0;
  $object = "$object, $letters[$j]($x[$j],$y[$j])" if $j != 0;
  $image = "$letters[$j]'" if $j == 0;
  $image = "$image, $letters[$j]'" if $j != 0;
  $ximage = $x[$j] + $p;
  $yimage = $y[$j] + $q;
  $answers[$j] = "$ximage,$yimage";

  if ($j != 0) {
    $k = $j - 1;
    if ($x[$k] - $x[$j] != 0) {
      $line[$j] = "($y[$k]-$y[$j])/($x[$k]-$x[$j])(x-$x[$j])+$y[$j],red,$x[$j],$x[$k]";
    } else {
      $line[$j] = "[$x[$j],t],red,$y[$j],$y[$k]";
    }
  }

  $coordinates[$j] = "{$y[$j]},red,{$x[$j]},{$x[$j]},closed";
}

if ($x[$i] - $x[0] != 0) {
  $line[0] = "($y[$i]-$y[0])/($x[$i]-$x[0])(x-$x[$i])+$y[$i],red,$x[$i],$x[0]";
} else {
  $line[0] = "[$x[0],t],red,$y[0],$y[$i]";
}

// Add the closing line to lines array
$a = $y[$i] - $y[0];
$b = $x[0] - $x[$i];
$c = $x[$i] * $y[0] - $x[0] * $y[$i];
$lines[] = array($a, $b, $c);

$answers[$l] = $answers[0];

$ap = abs($p);
$aq = abs($q);

$lr = "right";
$lr = "left" if $p < 0;

$ud = "up";
$ud = "down" if $q < 0;

$bg = mergearrays($line, $coordinates);

// Set the grid bounds to -5 to 5 explicitly
$graph = showplot($bg, -5, 5, -5, 5); 

$background = $bg;
$background = addlabel($background, $x[0], $y[0], "A");
$answerformat = "polygon,dot";
$snaptogrid = 1;

// === QUESTION TEXT ===

<h3>Consider the diagram below and p<span style="font-size: 1.17em;">lot </span>`$image` <span style="font-size: 1.17em;">, the image of `$object` if it is translated $ap units $lr and $aq units $ud.</span></h3>
