// === NAME - DESCRIPTION: Given similar quadrilaterals and some of the sides, find one side on second and perimeter of second and area of second given those on first (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("calculated", "calculated", "calculated");

$ab = rand(10, 15);         // base
$bc = rand(4, 8);           // height
$cd = rand(8, 12);          // top slant
$mult = rrand(1.5, 3, .5);    // similarity scale factor

$ef = $ab * $mult;
$fg = $bc * $mult;

$perimeter1 = $ab + $bc + $cd + rand(5, 10);
$perimeter2 = $perimeter1 * $mult;

$area1 = rand(40, 60);
$area2 = $area1 * $mult * $mult;

$answer[0] = $fg;
$answer[1] = $perimeter2;
$answer[2] = $area2;

// Coordinates for ABCD
$xa = 0; $ya = 0;
$xb = $ab; $yb = 0;
$xc = $ab - 2; $yc = $bc;
$xd = 2; $yd = $bc;

// Coordinates for EFGH (scaled)
$xshift = 10 + $ab;
$xe = $xshift; $ye = 0;
$xf = $xe + $ab * $mult; $yf = 0;
$xg = $xf - 2 * $mult; $yg = $bc * $mult;
$xh = $xe + 2 * $mult; $yh = $bc * $mult;

// Midpoint above CD for label
$cdx = ($xc + $xd) / 2;
$cdy = ($yc + $yd) / 2;
$cdy_label = $cdy + 0.5;

// Dynamically expand the plot window
$xmax = ceil(max($xa, $xb, $xc, $xd, $xe, $xf, $xg, $xh)) + 2;
$ymax = ceil(max($ya, $yb, $yc, $yd, $ye, $yf, $yg, $yh)) + 2;

// Generate the ASCII SVG diagram
$pic = showasciisvg("
  initPicture(-7,$xmax + 5,-2,$ymax);

  // Shape ABCD
  path([[$xa,$ya],[$xb,$yb],[$xc,$yc],[$xd,$yd],[$xa,$ya]]);
  text([$xa - 0.5,$ya - 0.5],'A');
  text([$xb + 0.5,$yb - 0.5],'B');
  text([$xc + 0.5,$yc + 0.5],'C');
  text([$xd - 0.5,$yd + 0.5],'D');

  // Side labels for ABCD (numbers only)
  text([" . ($xa + $ab/2) . ",-0.8],'$ab');
  text([" . ($xb + 0.5) . "," . ($yb + $bc/2) . "],'$bc');
  text([$cdx, $cdy_label],'$cd');

  // Shape EFGH (scaled version)
  path([[$xe,$ye],[$xf,$yf],[$xg,$yg],[$xh,$yh],[$xe,$ye]]);
  text([$xe - 0.5,$ye - 0.5],'E');
  text([$xf + 0.5,$yf - 0.5],'F');
  text([$xg + 0.5,$yg + 0.5],'G');
  text([$xh - 0.5,$yh + 0.5],'H');

  // Side labels for EFGH (numbers only)
  text([" . ($xe + $ef/2) . ",-0.8],'$ef');
  text([" . ($xf) . "," . ($yf + $fg/2) . "],'x');
");

// === QUESTION TEXT ===
<p><b>Quadrilateral `ABCD \sim EFGH`.</b></p>

$pic
<p><span style="color:blue">Click the magnifiying glass in the bottom right corner</span></p>
<p>The following side lengths are known:</p>
  <li>`AB = {$ab}`</li>
  <li>`BC = {$bc}`</li>
  <li>`CD = {$cd}`</li>
  <li>`EF = {$ef}`</li>

<p>What is the length of side `FG`?</p>
<p>$answerbox[0]</p>

<p>If the perimeter of `ABCD` is `{$perimeter1}`, what is the perimeter of `EFGH`?</p>
<p>$answerbox[1]</p>

<p>If the area of `ABCD` is `{$area1}`, what is the area of `EFGH`?</p>
<p>$answerbox[2]</p>
