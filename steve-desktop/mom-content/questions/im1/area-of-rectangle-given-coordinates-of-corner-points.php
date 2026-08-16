// === NAME - DESCRIPTION: Area of rectangle given coordinates of corner points (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = "draw,number";

$answerformat[0] = "twopoint,lineseg,dot"; // dot and line segment tools
$snaptogrid[0] = 1;
$scoremethod = "ignoreoverlap";
$grid[0] = "-10,10,-10,10,5:1,5:1,400,400"; // 4-quadrant grid

// Allow negative coordinates
$a = rand(-8, 4);   // x of bottom-left corner
$b = rand(-8, 4);   // y of bottom-left corner
$da = rand(2, 5);   // width
$db = rand(2, 5);   // height

// Rectangle corners
$xbl = $a;
$ybl = $b;
$xtl = $a;
$ytl = $b + $db;
$xtr = $a + $da;
$ytr = $b + $db;
$xbr = $a + $da;
$ybr = $b;

// Required answer: 4 dots and 4 segments (horizontal/vertical)

$leftx = min($xbl, $xbr);
$rightx = max($xbl, $xbr);
$topy = max($ytl, $ytr);
$bottomy = min($ytl, $ytr);

$answers[0] = array(
  "$xbl,$ybl",             // dots
  "$xtl,$ytl",
  "$xtr,$ytr",
  "$xbr,$ybr",

  $ybl . "," . $leftx . "," . $rightx,   // bottom
  $ytl . "," . $leftx . "," . $rightx,   // top
  "x=" . $xbl . "," . $ybl . "," . $ytl,        // left
  "x=" . $xbr . "," . $ybr . "," . $ytr         // right
);


// Area
$area = $da * $db;
$answer[1] = $area;
$showanswer[1] = "$da × $db = $area";

// Drawing box injection
$answerbox[0] = "[DRAW]";

// === QUESTION TEXT ===
<p>Use the graphing tool to construct a rectangle using the given points.</p>
<p>Plot the following four points, then connect them with line segments to form a rectangle:</p>
<ul style="list-style-type:none; padding-left:0;">
  <li><b>A</b> ` ({$xbl}, {$ybl})`</li>
  <li><b>B</b> ` ({$xtl}, {$ytl})`</li>
  <li><b>C</b> ` ({$xtr}, {$ytr})`</li>
  <li><b>D</b> ` ({$xbr}, {$ybr})`</li>
</ul>
<p>You may start with any point. Use the <b>dot</b> tool to plot the points, and the <b>line segment</b> tool to connect them.</p>
<p>{$answerbox[0]}</p>

<p>What is the area of the rectangle?</p>
<p>$answerbox[1]</p>
