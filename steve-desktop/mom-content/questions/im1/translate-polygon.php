// === NAME - DESCRIPTION: Translate Polygon (local for Steven Huff) ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===
$answerformat = "twopoint,lineseg,dot";
$snaptogrid = 1;

$a = diffrands(-2, 2, 3);  // Random x-coordinates for the vertices
$b = diffrands(-2, 2, 3);  // Random y-coordinates for the vertices

$translation_x = nonzerorand(-2, 2);  // Random horizontal translation
$translation = abs($translation_x)
$direction_x = ($translation_x < 0) ? "left" : "right";


// Define the line equations for the original triangle and the translated triangle
$answer = array(
    // Original line segments represented as functions with domains, marked as optional
    "optional, " . (($b[1] - $b[0]) / ($a[1] - $a[0])) . "x+" . ($b[0] - ($a[0] * (($b[1] - $b[0]) / ($a[1] - $a[0])))) . "," . min($a[0], $a[1]) . "," . max($a[0], $a[1]),  // Line between original points 1 and 2
    "optional, " . (($b[2] - $b[1]) / ($a[2] - $a[1])) . "x+" . ($b[1] - ($a[1] * (($b[2] - $b[1]) / ($a[2] - $a[1])))) . "," . min($a[1], $a[2]) . "," . max($a[1], $a[2]),  // Line between original points 2 and 3
    "optional, " . (($b[0] - $b[2]) / ($a[0] - $a[2])) . "x+" . ($b[2] - ($a[2] * (($b[0] - $b[2]) / ($a[0] - $a[2])))) . "," . min($a[2], $a[0]) . "," . max($a[2], $a[0]),  // Line between original points 3 and 1

    // Translated line segments
    (($b[1] - $b[0]) / ($a[1] - $a[0])) . "x+" . ($b[0] - (($a[0] + $translation_x) * (($b[1] - $b[0]) / ($a[1] - $a[0])))) . "," . min($a[0] + $translation_x, $a[1] + $translation_x) . "," . max($a[0] + $translation_x, $a[1] + $translation_x),  // Translated line segment 1
    (($b[2] - $b[1]) / ($a[2] - $a[1])) . "x+" . ($b[1] - (($a[1] + $translation_x) * (($b[2] - $b[1]) / ($a[2] - $a[1])))) . "," . min($a[1] + $translation_x, $a[2] + $translation_x) . "," . max($a[1] + $translation_x, $a[2] + $translation_x),  // Translated line segment 2
    (($b[0] - $b[2]) / ($a[0] - $a[2])) . "x+" . ($b[2] - (($a[2] + $translation_x) * (($b[0] - $b[2]) / ($a[0] - $a[2])))) . "," . min($a[2] + $translation_x, $a[0] + $translation_x) . "," . max($a[2] + $translation_x, $a[0] + $translation_x),   // Translated line segment 3

    // Optional dots for original and translated triangle vertices
    "optional, $a[0],$b[0]",
    "optional, $a[1],$b[1]",
    "optional, $a[2],$b[2]",
    "optional, " . ($a[0] + $translation_x) . ",$b[0]",
    "optional, " . ($a[1] + $translation_x) . ",$b[1]",
    "optional, " . ($a[2] + $translation_x) . ",$b[2]"
);

// === QUESTION TEXT ===
<p>&nbsp;Graph a polygon&nbsp; with the following vertices: `A($a[0],$b[0]), B($a[1],$b[1]), C($a[2],$b[2]) `&nbsp;</p>
<p>Translate `triangle ABC`&nbsp; $translation units $direction_x to create `triangle A'B'C'`</p>
