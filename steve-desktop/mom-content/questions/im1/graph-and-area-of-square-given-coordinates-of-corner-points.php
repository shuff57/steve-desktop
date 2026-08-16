// === NAME - DESCRIPTION: Graph and Area of square given coordinates of corner points (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "draw,number";

$answerformat[0] = "twopoint,lineseg,dot";  // Enable dot and line segment tools
$snaptogrid[0] = 1;
$grid[0] = "-10,10,-10,10,5:1,5:1,400,400";  // 4-quadrant grid

// Random bottom-left corner and square side length
$a = rand(-8, 4);   // x of bottom-left
$b = rand(-8, 4);   // y of bottom-left
$s = rand(2, 5);    // side length (equal width & height)

// Square corners
$xbl = $a;
$ybl = $b;
$xtl = $a;
$ytl = $b + $s;
$xtr = $a + $s;
$ytr = $b + $s;
$xbr = $a + $s;
$ybr = $b;

// Required drawing: dots + 4 segments
$answer[0] = array(
  "$xbl,$ybl",  // dots
  "$xtl,$ytl",
  "$xtr,$ytr",
  "$xbr,$ybr",

  "$ybl,$xbl,$xbr",  // bottom
  "$ytl,$xtl,$xtr",  // top
  "x=$xbl,$ybl,$ytl",  // left
  "x=$xbr,$ybr,$ytr"   // right
);

// Area of square = side²
$area = $s * $s;
$answer[1] = $area;
$showanswer[1] = "$s × $s = $area";

$answerbox[0] = "[DRAW]";

// === QUESTION TEXT ===
<p>Use the graphing tools to construct a square by plotting and connecting the following four points:</p>
<p>Plot the following four points, then connect them with line segments to form a rectangle:</p>
<ul style="list-style-type:none; padding-left:0;">
  <li><b>A</b> ` ({$xbl}, {$ybl})`</li>
  <li><b>B</b> ` ({$xtl}, {$ytl})`</li>
  <li><b>C</b> ` ({$xtr}, {$ytr})`</li>
  <li><b>D</b> ` ({$xbr}, {$ybr})`</li>
</ul>
<p>You may start with any point. Use the <b>dot</b> tool to plot the points, and the <b>line segment</b> tool to connect them.</p>
<p>{$answerbox[0]}</p>
<p>What is the area of the square?</p>
<p>$answerbox[1]</p>
