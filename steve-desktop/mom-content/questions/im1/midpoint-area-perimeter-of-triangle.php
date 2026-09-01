// === NAME - DESCRIPTION: midpoint, area, perimeter of triangle ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("JSXG");

$anstypes = array("string", "string", "number", "number", "number", "number");

// Randomize A on the x-axis
$Ax = rand(-1, 3);
$A = [$Ax, 0];
$B = [2, 6];
$C = [7, 0];

// Midpoints
$Dx = ($A[0] + $B[0]) / 2;
$Dy = ($A[1] + $B[1]) / 2;

$Ex = ($B[0] + $C[0]) / 2;
$Ey = ($B[1] + $C[1]) / 2;

// Side lengths of ABC
$AB = sqrt( ($A[0] - $B[0])^2 + ($A[1] - $B[1])^2 );
$BC = sqrt( ($B[0] - $C[0])^2 + ($B[1] - $C[1])^2 );
$AC = sqrt( ($A[0] - $C[0])^2 + ($A[1] - $C[1])^2 );
$perimABC = round($AB + $BC + $AC, 2);

// Side lengths of DBE
$DB = sqrt( ($Dx - $B[0])^2 + ($Dy - $B[1])^2 );
$BE = sqrt( ($B[0] - $Ex)^2 + ($B[1] - $Ey)^2 );
$DE = sqrt( ($Dx - $Ex)^2 + ($Dy - $Ey)^2 );
$perimDBE = round($DB + $BE + $DE, 2);

// Areas using determinant method
$areaABC = abs($A[0]*($B[1]-$C[1]) + $B[0]*($C[1]-$A[1]) + $C[0]*($A[1]-$B[1])) / 2;
$areaDBE = abs($Dx*($B[1]-$Ey) + $B[0]*($Ey - $Dy) + $Ex*($Dy - $B[1])) / 2;

// Round final values
$answer[0] = "(".round($Dx, 2) . "," . round($Dy, 2).")";     // midpoint D
$answer[1] = "(".round($Ex, 2) . "," . round($Ey, 2).")";     // midpoint E
$answer[2] = $perimABC;
$answer[3] = $perimDBE;
$answer[4] = round($areaABC, 2);
$answer[5] = round($areaDBE, 2);

// JSXGraph: plot board
$ops = array();
$ops['size'] = [500, 400];
$ops['bounds'] = [-1, 8, -1, 8];
$ops['showNavigation'] = true;
$ops['axisLabel'] = ["`x`", "`y`"];
$ops['controls'] = ['zoom'];
$board = JSXG_createAxes("plot{$thisq}", $ops);

// Add points
$board = JSXG_addPoint($board, array("position" => $A, "attributes" => "{name:'A', fixed:true, draggable:false}"), "A");
$board = JSXG_addPoint($board, array("position" => $B, "attributes" => "{name:'B', fixed:true, draggable:false}"), "B");
$board = JSXG_addPoint($board, array("position" => $C, "attributes" => "{name:'C', fixed:true, draggable:false}"), "C");
$board = JSXG_addPoint($board, array("position" => [$Dx, $Dy], "attributes" => "{name:'D', color:'blue', fixed:true, draggable:false}"), "D");
$board = JSXG_addPoint($board, array("position" => [$Ex, $Ey], "attributes" => "{name:'E', color:'blue', fixed:true, draggable:false}"), "E");

// Add triangle ABC
$board = JSXG_addPolygon($board, array(
  "position" => ["p_plot{$thisq}_A", "p_plot{$thisq}_B", "p_plot{$thisq}_C"],
  "attributes" => "{borders:{strokeColor:'black'}}"
));

// Add triangle DBE
$board = JSXG_addPolygon($board, array(
  "position" => ["p_plot{$thisq}_D", "p_plot{$thisq}_B", "p_plot{$thisq}_E"],
  "attributes" => "{borders:{strokeColor:'blue', dash:2}}"
));

// === QUESTION TEXT ===

<p><b>Given:</b> Triangle ΔABC has vertices A(0,0), B(2,6), and C(7,0). Point D is the midpoint of AB and point E is the midpoint of BC.</p>

<p>The triangle ΔDBE is formed inside triangle ΔABC.</p>

<p><b>a)</b> Use the graph below to explore the triangle and its midpoints:</p>

$board

<hr>

<p><b>b)</b> Find the coordinates of points D and E (midpoints). Enter as <code>(x,y)</code>:</p>
<ul>
  <li><b>D:</b> $answerbox[0]</li>
  <li><b>E:</b> $answerbox[1]</li>
</ul>

<hr>

<p><b>c)</b> Find the perimeters of triangles:</p>
<ul>
  <li>Perimeter of ΔABC: $answerbox[2]</li>
  <li>Perimeter of ΔDBE: $answerbox[3]</li>
</ul>

<p><b>d)</b> Find the areas of triangles:</p>
<ul>
  <li>Area of ΔABC: $answerbox[4]</li>
  <li>Area of ΔDBE: $answerbox[5]</li>
</ul>
