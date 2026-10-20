// === NAME - DESCRIPTION: Graph a Horizontal or Vertical Line - Draw y = c or x = c on a grid and say what the slope is: 0 for a horizontal line, undefined for a vertical line ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.3 (2.3.2, Definitions 2.3.2 and 2.3.3, Problem 12). Each draw is either a horizontal line y = c
// or a vertical line x = c, chosen at random, with c a nonzero integer from -9 to 9 on a -10 to 10 grid.
// The horizontal answer is the function 0*x + c. The vertical answer is x = c, the same form the draw tool
// uses for a vertical segment. Every lattice point on the line inside the window is an optional dot.
// The slope choices are 0, undefined, c itself and a decoy. When c is 1 the decoy is -1 so no two
// choices are the same. The key is the choice index: 0 for a horizontal line, 1 for a vertical line.
$anstypes = array("choices", "draw")

$orient = rand(0, 1)
$c = randfrom(array(-9, -8, -7, -6, -5, -4, -3, -2, -1, 1, 2, 3, 4, 5, 6, 7, 8, 9))
$alt = 1
if ($c == 1) {
  $alt = -1
}

$ans = array("0 * x + $c")
$eq = "y = $c"
$lineWord = "horizontal"
$keyIdx = 0
if ($orient == 1) {
  $ans = array("x=$c")
  $eq = "x = $c"
  $lineWord = "vertical"
  $keyIdx = 1
}
$k = 1
for ($i = 0..20) {
  $t = $i - 10
  if ($orient == 0) {
    $ans[$k] = "optional,$t,$c"
  } else {
    $ans[$k] = "optional,$c,$t"
  }
  $k = $k + 1
}

$questions[0] = array("The slope is 0.", "The slope is undefined.", "The slope is $c.", "The slope is $alt.")
$answer[0] = $keyIdx
$displayformat[0] = "select"

$answers[1] = $ans
$answerformat[1] = "twopoint,dot,line"
$reltolerance[1] = 0.2
$grid[1] = "-10,10,-10,10,1,1,450,450"
$snaptogrid[1] = 1

$why = "Every point on a horizontal line has the same y-value, so the rise is 0 however far you run. The slope is 0."
$howDraw = "Every point on the line has y = " . $c . ", so the line goes straight across through `(0, " . $c . ")`. Plot two points with y = " . $c . ", such as `(-3, " . $c . ")` and `(4, " . $c . ")`, and draw the line through them."
if ($orient == 1) {
  $why = "Every point on a vertical line has the same x-value, so the run is 0 however far you rise. A slope of rise over 0 cannot be computed, so the slope is undefined. A vertical line is also not a function."
  $howDraw = "Every point on the line has x = " . $c . ", so the line goes straight up and down through `(" . $c . ", 0)`. Plot two points with x = " . $c . ", such as `(" . $c . ", -3)` and `(" . $c . ", 4)`, and draw the line through them."
}

$sol = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">Which kind of line.</span> The equation `' . $eq . '` is the equation of the ' . $lineWord . ' line.</p>
      <p><span class="term-label">(a) The slope.</span> ' . $why . '</p>
      <p><span class="term-label">(b) The graph.</span> ' . $howDraw . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A line has the equation `$eq`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the slope of the line? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Graph the line `$eq`.
    <p style="margin:10px 0 0 0;">$answerbox[1]</p>
  </div>
</div>

// === ANSWER ===

$sol
