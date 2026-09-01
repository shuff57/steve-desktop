// === NAME - DESCRIPTION: determine the midpoint ===
// === SET QUESTION TYPE TO: interval ===

// === COMMON CONTROL ===

// Generate distinct random points within bounds
for ($i = 1..10) {
  $x1 = rand(-10, 10);
  $y1 = rand(-10, 10);
  $x2 = rand(-10, 10);
  $y2 = rand(-10, 10);

  if ($x1 != $x2 || $y1 != $y2) {
    break;
  }
}

// Midpoint
$mx = ($x1 + $x2) / 2;
$my = ($y1 + $y2) / 2;

$answer = "($mx, $my)";

// === QUESTION TEXT ===

<p>Determine the midpoint of the segment with endpoints `($x1,$y1)` and `($x2,$y2)`.</p>
<p>Midpoint: $answerbox</p>
