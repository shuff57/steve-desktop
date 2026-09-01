// === NAME - DESCRIPTION: 1-49 identifying patterns and growth rate ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Define the L-shaped patterns with spaces in front of the tiles and consistent linear growth
$pattern_text_figure_1 = " □□□□\n			□□\n			□"; // Figure 1 with 7 tiles
$pattern_text_figure_2 = " □□□□□\n			□□□\n			□"; // Figure 2 with 9 tiles (2 more tiles added)
$pattern_text_figure_3 = " □□□□□□\n			□□□□\n			□"; // Figure 3 with 11 tiles (2 more tiles added)

// Calculate the number of tiles for Figure 4, Figure 10, and Figure 0
$tiles_figure_1 = 7; // Number of tiles in Figure 1
$tiles_figure_2 = 9; // Number of tiles in Figure 2
$tiles_figure_3 = 11; // Number of tiles in Figure 3

$growth_amount = $tiles_figure_2 - $tiles_figure_1; // Consistent growth of 2 tiles

$tiles_figure_4 = $tiles_figure_3 + $growth_amount; // 13 tiles in Figure 4
$tiles_figure_10 = $tiles_figure_3 + 7 * $growth_amount; // 25 tiles in Figure 10
$tiles_figure_0 = $tiles_figure_1 - $growth_amount; // 5 tiles in Figure 0

// Define the answer types
$anstypes = "numfunc,numfunc,numfunc";

// Assign the correct answers
$answer[0] = $tiles_figure_4; // Answer for Figure 4
$answer[1] = $tiles_figure_10; // Answer for Figure 10
$answer[2] = $tiles_figure_0; // Answer for Figure 0

// Display the correct answers for each box
$showanswer[0] = "$tiles_figure_4";
$showanswer[1] = "$tiles_figure_10";
$showanswer[2] = "$tiles_figure_0";

// === QUESTION TEXT ===

<p>Examine the tile pattern shown below. The pattern changes with each successive figure, and you are tasked with analyzing and predicting the continuation of this pattern.</p>

<pre style="font-size: 150%;">
<b>Figure 1:</b>
<span style="color: red;">$pattern_text_figure_1</span>

<b>Figure 2:</b>
<span style="color: blue;">$pattern_text_figure_2</span>

<b>Figure 3:</b>
<span style="color: green;">$pattern_text_figure_3</span>
</pre>

<p><b>Your Tasks:</b></p>
<ol>
  <li>Based on the pattern, how many tiles will be in Figure 4? Provide your answer below.</li>
  <p><b>Tiles in Figure 4:</b> $answerbox[0]</p>
  <li>If the pattern continues, how many tiles will be in Figure 10? Provide your answer below.</li>
  <p><b>Tiles in Figure 10:</b> $answerbox[1]</p>
  <li>Determine the number of tiles in Figure 0 (the figure before Figure 1). Provide your answer below.</li>
  <p><b>Tiles in Figure 0:</b> $answerbox[2]</p>
</ol>
