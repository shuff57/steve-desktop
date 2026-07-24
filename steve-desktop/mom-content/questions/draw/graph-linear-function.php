// === NAME - DESCRIPTION: Graph a Linear Function - Graph y=mx+b on a coordinate grid using the two-point line tool ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===

loadlibrary("stats");

/* ---------- 1. Random Parameters ---------- */
// Integer slopes only (no fractions) for clean grid snapping
$m_opts = array(-3, -2, -1, 1, 2, 3);
$m = randfrom($m_opts);

// Nonzero y-intercepts so the line is never through the origin
$b_opts = array(-4, -3, -2, -1, 1, 2, 3, 4);
$b = randfrom($b_opts);

/* ---------- 2. Build $answers string for grading ---------- */
// Draw type expects a function of x: "2x+3", "-x-4", "3x-2"
// makepretty() cleans up sign issues like "x+-3" -> "x-3"

if ($m == 1) {
    $m_part = "";
} elseif ($m == -1) {
    $m_part = "-";
} else {
    $m_part = $m;
}

$answers = makepretty($m_part . "x+" . $b);

/* ---------- 3. Build LaTeX display equation ---------- */
// Separate treatment from $answers so LaTeX renders cleanly

if ($m == 1) {
    $m_latex = "";
} elseif ($m == -1) {
    $m_latex = "-";
} else {
    $m_latex = $m;
}

if ($b > 0) {
    $b_latex = " + " . $b;
} else {
    $b_latex = " - " . abs($b);
}

$latex_eq = $m_latex . "x" . $b_latex;

/* ---------- 4. Draw settings ---------- */
$answerformat = "twopoint";
$grid = "-6,6,-6,6,1,1,300,300";
$snaptogrid = 1;

/* ---------- 5. Two anchor points students should land on ---------- */
$x1 = 0
$y1 = $b
$x2 = 1
$y2 = $m + $b

/* ---------- 6. Solution guide (full step-by-step prose) ---------- */
$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em 1em; background:#fafafa; }
  .sol-body p { margin: 0.6em 0; }
  .sol-body .step { font-weight:700; color:#1865f2; margin-top:1em; }
  .sol-body .calc { margin: 0.4em 0 0.4em 1.5em; font-size:17px; }
  .sol-body .answer-box { margin: 1em 0 0 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>To graph a linear function in slope-intercept form `y = mx + b`, we only need two points on the line, because two points determine a unique line. The cleanest pair of points to plot are usually the y-intercept and one other point we can find using the slope.</p>

      <p><b>Equation:</b> `y = ' . $latex_eq . '` &nbsp;&rarr;&nbsp; slope `m = ' . $m . '`, y-intercept `b = ' . $b . '`.</p>

      <p class="step">Step 1. Plot the y-intercept.</p>
      <p>The y-intercept is where the line crosses the y-axis, i.e. where `x = 0`. Setting `x = 0` in the equation gives `y = ' . $b . '`. So plot the point <b>(0, ' . $b . ')</b> on the y-axis.</p>

      <p class="step">Step 2. Use the slope to find a second point.</p>
      <p>The slope `m = ' . $m . '` means "rise over run." From any point on the line, going 1 unit right (run = 1) means going ' . $m . ' units up (rise = ' . $m . '). Starting at the y-intercept `(0, ' . $b . ')` and moving right 1 and up ' . $m . ' lands at:</p>
      <p class="calc">(0 + 1, ' . $b . ' + ' . $m . ') = (' . $x2 . ', ' . $y2 . ')</p>
      <p>Plot that second point as well.</p>

      <p class="step">Step 3. Draw the line through both points.</p>
      <p>Using the two-point line tool, click on `(0, ' . $b . ')` and `(' . $x2 . ', ' . $y2 . ')`. The line will extend automatically in both directions across the grid.</p>

      <p class="step">Step 4. (Optional) verify with a third point.</p>
      <p>Plug another easy `x` value into the equation. For example, with `x = 2`: `y = ' . $m . '(2) + ' . $b . ' = ' . (2*$m + $b) . '`. The point (2, ' . (2*$m + $b) . ') should land on your line.</p>

      <div class="answer-box">
        Two points on the line: <b>(0, ' . $b . ')</b> and <b>(' . $x2 . ', ' . $y2 . ')</b>.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>Graph the linear function `y = $latex_eq` on the coordinate grid below.</p>
  <p style="font-size:small; color:#555;">
    Use the two-point line tool: click two points on the grid that lie exactly on the line.
    The line will extend automatically once both points are placed.
  </p>
</div>

$answerbox


// === ANSWER ===

$solutionguide

