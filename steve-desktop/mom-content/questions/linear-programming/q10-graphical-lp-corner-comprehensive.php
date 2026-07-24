// === NAME - DESCRIPTION: Graphical LP Comprehensive - Furniture maker maximizes profit; identify objective coefficients, read constraint, find optimal corner (x,y,Z) via graphical method ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Scenario: WoodCraft Furniture makes two products each week:
//   x = chairs produced per week
//   y = tables produced per week
//
// Three pre-solved scenario tuples (constraint coefficients, RHS, optimal corner):
//
//   Tuple 0: Wood 3x+5y≤30, Finishing x+2y≤11
//     Solve: x=11-2y → 3(11-2y)+5y=30 → 33-y=30 → y=3, x=5.  Corner (5,3).
//
//   Tuple 1: Wood 4x+6y≤40, Finishing x+2y≤12
//     Solve: x=12-2y → 4(12-2y)+6y=40 → 48-2y=40 → y=4, x=4.  Corner (4,4).
//
//   Tuple 2: Wood 2x+5y≤26, Finishing x+2y≤11
//     Solve: x=11-2y → 2(11-2y)+5y=26 → 22+y=26 → y=4, x=3.  Corner (3,4).
//
// Profit coefficients are randomized per-tuple (ranges keep Z a clean integer):
//   px in {8,10,12}   (profit per chair)
//   py in {10,12,15}  (profit per table)

$scenario = rand(0, 2)

// --- Constraint coefficients and RHS ---
$w_x_arr = array(3, 4, 2)
$w_y_arr = array(5, 6, 5)
$W_arr   = array(30, 40, 26)

$f_x_arr = array(1, 1, 1)
$f_y_arr = array(2, 2, 2)
$F_arr   = array(11, 12, 11)

// --- Optimal corner (pre-verified integers) ---
$xopt_arr = array(5, 4, 3)
$yopt_arr = array(3, 4, 4)

// --- Axis intercepts for corner-point table ---
// The feasible-region corner A (on x-axis) is the smaller x-intercept (binding constraint).
// The feasible-region corner C (on y-axis) is the smaller y-intercept (binding constraint).
//
//   Tuple 0: A=(10,0) wood binding, C=(0,5) finishing binding
//   Tuple 1: A=(10,0) wood binding, C=(0,6) finishing binding
//   Tuple 2: A=(11,0) finishing binding, C=(0,5) finishing binding
$A_x_arr = array(10, 10, 11)   // x-intercept of feasible region on x-axis
$C_y_arr = array(5,  6,  5)    // y-intercept of feasible region on y-axis

// Pick live values for this instance
$w_x = $w_x_arr[$scenario]
$w_y = $w_y_arr[$scenario]
$W   = $W_arr[$scenario]
$f_x = $f_x_arr[$scenario]
$f_y = $f_y_arr[$scenario]
$F   = $F_arr[$scenario]
$xopt = $xopt_arr[$scenario]
$yopt = $yopt_arr[$scenario]

// Corner point table support values
$A_x = $A_x_arr[$scenario]
$C_y = $C_y_arr[$scenario]

// Profit coefficients
$px = randfrom(array(8, 10, 12))
$py = randfrom(array(10, 12, 15))

// Precompute all corner Z values
$Z_origin = 0
$Z_A      = $px * $A_x   // corner A: feasible x-intercept
$Z_B      = $px * $xopt + $py * $yopt   // interior optimal corner
$Z_C      = $py * $C_y   // corner C: feasible y-intercept

$Zopt = $Z_B

// === Answer setup ===
// (a) coefficient of x in objective function → number (px)
// (b) coefficient of y in objective function → number (py)
// (c) optimal x value at maximizing corner   → number
// (d) optimal y value at maximizing corner   → number
// (e) maximum Z at optimal corner            → number

$anstypes = array("number", "number", "number", "number", "number")
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answerformat[2] = "integer"
$answerformat[3] = "integer"
$answerformat[4] = "integer"

$answer[0] = $px
$answer[1] = $py
$answer[2] = $xopt
$answer[3] = $yopt
$answer[4] = $Zopt

// === SOLUTION GUIDE ===

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .corner-table { border-collapse:collapse; width:100%; margin:8px 0; font-size:14px; }
  .corner-table th { background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:6px 12px; text-align:center; }
  .corner-table td { padding:6px 12px; border:1px solid #dee1e3; text-align:center; }
  .corner-opt { background:#e8f5e9; font-weight:700; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Step 1 — LP Formulation:</b></p>
      <p>Let x = chairs/week, y = tables/week.</p>
      <p>Maximize &nbsp;Z = ' . $px . 'x + ' . $py . 'y</p>
      <p>Subject to:<br>
        &nbsp;&nbsp;' . $w_x . 'x + ' . $w_y . 'y &le; ' . $W . ' &nbsp;(wood, board-ft/week)<br>
        &nbsp;&nbsp;' . $f_x . 'x + ' . $f_y . 'y &le; ' . $F . ' &nbsp;(finishing, hrs/week)<br>
        &nbsp;&nbsp;x, y &ge; 0
      </p>
      <p><b>Step 2 — Identify all corner points of the feasible region:</b></p>
      <table class="corner-table">
        <tr>
          <th>Corner</th>
          <th>x (chairs)</th>
          <th>y (tables)</th>
          <th>Z = ' . $px . 'x + ' . $py . 'y</th>
        </tr>
        <tr>
          <td>O &mdash; origin</td>
          <td>0</td><td>0</td><td>0</td>
        </tr>
        <tr>
          <td>A &mdash; x-axis intercept (y = 0)</td>
          <td>' . $A_x . '</td><td>0</td><td>' . $Z_A . '</td>
        </tr>
        <tr class="corner-opt">
          <td>B &mdash; both constraints active &#9733;</td>
          <td>' . $xopt . '</td><td>' . $yopt . '</td><td>' . $Zopt . '</td>
        </tr>
        <tr>
          <td>C &mdash; y-axis intercept (x = 0)</td>
          <td>0</td><td>' . $C_y . '</td><td>' . $Z_C . '</td>
        </tr>
      </table>
      <p><b>Step 3 — Solve for corner B (both constraints binding):</b></p>
      <p>
        Set ' . $w_x . 'x + ' . $w_y . 'y = ' . $W . ' and ' . $f_x . 'x + ' . $f_y . 'y = ' . $F . '.<br>
        From the finishing constraint: x = ' . $F . ' &minus; ' . $f_y . 'y.<br>
        Substitute into wood constraint:<br>
        &nbsp;&nbsp;' . $w_x . '(' . $F . ' &minus; ' . $f_y . 'y) + ' . $w_y . 'y = ' . $W . '<br>
        &nbsp;&nbsp;' . ($w_x*$F) . ' &minus; ' . ($w_x*$f_y) . 'y + ' . $w_y . 'y = ' . $W . '<br>
        &nbsp;&nbsp;' . ($w_y - $w_x*$f_y) . 'y = ' . ($W - $w_x*$F) . '<br>
        &nbsp;&nbsp;y = ' . $yopt . ', &nbsp; x = ' . $F . ' &minus; ' . $f_y . '(' . $yopt . ') = ' . $xopt . '
      </p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Optimal:</b> x = ' . $xopt . ' chairs, y = ' . $yopt . ' tables.<br>
        Maximum profit Z = $' . $px . '(' . $xopt . ') + $' . $py . '(' . $yopt . ') = <b>$' . $Zopt . '</b> per week.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 6px 0;"><b>WoodCraft Furniture</b> produces two items each week: <b>chairs</b> (x) and <b>tables</b> (y). Each chair earns <b>$$px profit</b>; each table earns <b>$$py profit</b>.</p>
<p style="margin:0 0 4px 0;">Two resources limit weekly production:</p>
<table style="border-collapse:collapse; width:100%; margin:4px 0;">
<tr>
  <th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:left;">Resource</th>
  <th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Chair (x)</th>
  <th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Table (y)</th>
  <th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Available</th>
</tr>
<tr>
  <td style="padding:6px 12px; border:1px solid #dee1e3;">Wood (board-ft/week)</td>
  <td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$w_x</td>
  <td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$w_y</td>
  <td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$W</td>
</tr>
<tr>
  <td style="padding:6px 12px; border:1px solid #dee1e3;">Finishing (hrs/week)</td>
  <td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$f_x</td>
  <td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$f_y</td>
  <td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$F</td>
</tr>
</table>
<p style="margin:6px 0 0 0;">Graph the feasible region, identify all corner points, and evaluate Z = px&#183;x + py&#183;y at each corner to find the maximum profit.</p>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a</span>What is the coefficient of x (chairs) in the objective function? <span style="margin-left:8px;">$answerbox[0]</span>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b</span>What is the coefficient of y (tables) in the objective function? <span style="margin-left:8px;">$answerbox[1]</span>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c</span>At the optimal corner, what is the value of x (chairs per week)? <span style="margin-left:8px;">$answerbox[2]</span>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d</span>At the optimal corner, what is the value of y (tables per week)? <span style="margin-left:8px;">$answerbox[3]</span>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e</span>What is the maximum value of Z (profit in $) at the optimal corner? <span style="margin-left:8px;">$answerbox[4]</span>
</div>

</div>

// === ANSWER ===

$solutionguide
