// === NAME - DESCRIPTION: Slope From Two Points - Find slope, y-intercept, and equation of a line given two points ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Parts: (a) slope m [number], (b) y-intercept b [number], (c) equation y=mx+b [choices select]
$anstypes = array("number", "number", "choices")
$displayformat[2] = "select"
$noshuffle[2] = "all"

/* ---------- 1. Randomize via parallel arrays ---------- */
// Each row: [x1, y1, x2, y2, m_num, m_den, b, eq_index]
// m = (y2-y1)/(x2-x1); keep m as a clean integer or half-integer.
// eq_index: 0 = correct equation in the $eqs array for that row.
// Precomputed by hand; m stored as decimal for MOM answer comparison.
// Equation strings are assembled below using the correct m and b.

// Scenarios: integer slopes
// row 0: (0,1),(2,5)  m=(5-1)/2=2  b=1   y=2x+1
// row 1: (0,-2),(3,4)  m=6/3=2  b=-2  y=2x-2
// row 2: (1,3),(4,9)  m=6/3=2  b=1   -- duplicate slope, keep distinct b
// row 3: (0,3),(2,-1)  m=-4/2=-2  b=3  y=-2x+3
// row 4: (1,5),(3,11)  m=6/2=3  b=2   y=3x+2
// row 5: (0,4),(2,0)   m=-4/2=-2  b=4  y=-2x+4
// row 6: (1,-1),(3,5)  m=6/2=3  b=-4  y=3x-4
// row 7: (0,2),(4,6)   m=4/4=1  b=2   y=x+2
// row 8: (2,1),(4,5)   m=4/2=2  b=-3  y=2x-3
// row 9: (-1,3),(1,-1) m=-4/2=-2  b=1  y=-2x+1

$x1s = array(0,  0,  1,  0,  1,  0,  1,  0,  2, -1)
$y1s = array(1, -2,  3,  3,  5,  4, -1,  2,  1,  3)
$x2s = array(2,  3,  4,  2,  3,  2,  3,  4,  4,  1)
$y2s = array(5,  4,  9, -1, 11,  0,  5,  6,  5, -1)
$ms  = array(2,  2,  2, -2,  3, -2,  3,  1,  2, -2)
$bs  = array(1, -2,  1,  3,  2,  4, -4,  2, -3,  1)

// Correct equations (index 0 in each row's choices)
$eq0s = array("y = 2x + 1", "y = 2x - 2", "y = 2x + 1", "y = -2x + 3", "y = 3x + 2", "y = -2x + 4", "y = 3x - 4", "y = x + 2", "y = 2x - 3", "y = -2x + 1")
// Three distractors per scenario (wrong slope or wrong intercept)
$eq1s = array("y = 2x - 1", "y = 2x + 2", "y = 2x - 1", "y = -2x - 3", "y = 3x - 2", "y = -2x - 4", "y = 3x + 4", "y = x - 2", "y = 2x + 3", "y = -2x - 1")
$eq2s = array("y = x + 1",  "y = x - 2",  "y = x + 1",  "y = 2x + 3",  "y = -3x + 2","y = 2x + 4",  "y = -3x - 4","y = 2x + 2","y = x - 3",  "y = 2x + 1")
$eq3s = array("y = -2x + 1","y = -2x - 2","y = -2x + 1","y = 2x - 3",  "y = 3x + 5", "y = 2x - 4",  "y = -3x + 4","y = -x + 2","y = -2x - 3","y = 2x - 1")

$picked = jointrandfrom($x1s, $y1s, $x2s, $y2s, $ms, $bs, $eq0s, $eq1s, $eq2s, $eq3s)
$x1 = $picked[0]
$y1 = $picked[1]
$x2 = $picked[2]
$y2 = $picked[3]
$m  = $picked[4]
$b  = $picked[5]
$eq_correct = $picked[6]
$eq_d1 = $picked[7]
$eq_d2 = $picked[8]
$eq_d3 = $picked[9]

$answer[0] = $m
$answer[1] = $b
// correct equation is index 0 in choices[2]
$answer[2] = 0
$choices[2] = array($eq_correct, $eq_d1, $eq_d2, $eq_d3)

/* ---------- 2. Display strings ---------- */
// slope fraction display (all slopes are integers here)
$m_disp = $m
$b_abs = abs($b)
$b_sign_word = "positive"
if ($b < 0) { $b_sign_word = "negative" }

// y-intercept display in equation form
$b_str = "+ " . $b
if ($b < 0) { $b_str = "- " . $b_abs }
if ($b == 0) { $b_str = "" }

/* ---------- 3. Solution guide ---------- */
$dy = $y2 - $y1
$dx = $x2 - $x1
// b = y1 - m*x1
$mx1 = $m * $x1

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>Points: ('.$x1.', '.$y1.') and ('.$x2.', '.$y2.')</p>
      <p><b>(a) Slope:</b><br>
        m = (y<sub>2</sub> &minus; y<sub>1</sub>) / (x<sub>2</sub> &minus; x<sub>1</sub>)
        = ('.$y2.' &minus; '.$y1.') / ('.$x2.' &minus; '.$x1.')
        = '.$dy.' / '.$dx.'
        = <b>'.$m.'</b></p>
      <p><b>(b) Y-intercept:</b><br>
        Use y = mx + b with point ('.$x1.', '.$y1.'):<br>
        '.$y1.' = '.$m.' &middot; '.$x1.' + b<br>
        '.$y1.' = '.$mx1.' + b<br>
        b = '.$y1.' &minus; ('.$mx1.') = <b>'.$b.'</b></p>
      <p><b>(c) Equation:</b><br>
        y = '.$m.'x '.$b_str.' &nbsp;&rArr;&nbsp; <b>'.$eq_correct.'</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) m = '.$m.' &nbsp;&bull;&nbsp; (b) b = '.$b.' &nbsp;&bull;&nbsp; (c) '.$eq_correct.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 4px 0;">A line passes through the points <b>($x1, $y1)</b> and <b>($x2, $y2)</b>.</p>
<p style="margin:0; font-size:14px; color:#555;">Enter exact values for (a) and (b). Choose the equation for (c).</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>slope</b> m.<span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>y-intercept</b> b.<span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Select the <b>equation</b> of the line.<span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
